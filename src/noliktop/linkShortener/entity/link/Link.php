<?php

declare(strict_types=1);


namespace noliktop\linkShortener\entity\link;


use Exception;
use mysqli;
use mysqli_stmt;
use noliktop\linkShortener\entity\Entity;
use noliktop\linkShortener\entity\EntityException;
use noliktop\linkShortener\entity\user\User;
use noliktop\linkShortener\entity\visit\Visit;
use noliktop\linkShortener\table\TableException;
use noliktop\linkShortener\tip\Tip;
use noliktop\linkShortener\utils\Url;
use Throwable;

class Link extends Entity {

	/** @var string */
	protected $shortLink;

	/** @var string */
	protected $destinationUrl;

	/** @var int */
	protected $ownerId;

	public static function tryCreate(string $url, int $shortLinkLength, User $owner, string $failureUrl, mysqli $db): Link {
		try {
			return self::create($url, $shortLinkLength, $owner, $db);
		} catch (Throwable $e) {
			Tip::error($e->getMessage());
			header("Location: $failureUrl");
			die;
		}
	}

	/**
	 * @throws LinkException
	 */
	public static function create(string $url, int $shortLinkLength, User $owner, mysqli $db): Link {
		$link = new Link();
		$link->setDestinationUrl($url);
		$link->setOwnerId($owner->getId());

		$attemptsToInsert = 10;
		$lastExceptionMessage = "";
		for ($i = 0; $i < $attemptsToInsert; ++$i) {
			$shortLink = ShortLinkGenerator::newShortLink($shortLinkLength);
			$link->setShortLink($shortLink);

			try {
				$link->insert($db);
				break;
			} catch (Throwable $e) {
				$lastExceptionMessage = $e->getMessage();
			}
		}
		if ($i === $attemptsToInsert) {
			throw new LinkException("Couldn't insert short link $link->shortLink: $lastExceptionMessage");
		}

		return $link;
	}

	/**
	 * todo отрефачить
	 * @throws Exception
	 */
	public static function fromShortLink(string $shortLink, mysqli $db): ?self {
		$q = $db->prepare("select * from links where short_link = ?");

		$q->bind_param("s", $shortLink);

		if (!$q->execute()) {
			throw new Exception("db err: " . $db->error);
		}

		$r = $q->get_result();
		$t = $r->fetch_assoc();
		if ($t === null) {
			return null;
		}

		$link = new Link();
		$link->loadFromRow($t);

		return $link;
	}

	protected function loadFromRow(array $row): void {
		$this->id = (int)$row["id"];
		$this->shortLink = $row["short_link"];
		$this->destinationUrl = $row["destination_url"];
	}

	public function prepareFetch(mysqli $db): mysqli_stmt {
		$q = $db->prepare(<<<QUERY
select * from links where id = ? 
QUERY
		);
		$q->bind_param("i", $this->id);

		return $q;
	}

	protected function prepareInsert(mysqli $db): mysqli_stmt {
		$q = $db->prepare(<<<QUERY
insert into links (short_link, destination_url, owner_id) values (?, ?, ?)
QUERY
		);

		$q->bind_param("ssi", $this->shortLink, $this->destinationUrl, $this->ownerId);

		return $q;
	}

	protected function prepareUpdate(mysqli $db): mysqli_stmt {
		$q = $db->prepare(<<<QUERY
update links set short_link = ?, destination_url = ?, owner_id = ? where id = ?
QUERY
		);

		$q->bind_param("ssii", $this->shortLink, $this->destinationUrl, $this->ownerId, $this->id);

		return $q;
	}

	protected function prepareDelete(mysqli $db): mysqli_stmt {
		$q = $db->prepare("delete from links where id = ?");

		$q->bind_param("i", $this->id);

		return $q;
	}

	/**
	 * @return string
	 */
	public function getShortLink(): string {
		return $this->shortLink;
	}

	public function getFullShortLink(): string {
		return Url::get() . $this->getShortLink();
	}

	/**
	 * @param string $shortLink
	 */
	public function setShortLink(string $shortLink): void {
		//todo validate

		$this->shortLink = $shortLink;
	}

	/**
	 * @return string
	 */
	public function getDestinationUrl(): string {
		return $this->destinationUrl;
	}

	/**
	 * @param string $destinationUrl
	 * @throws LinkException
	 */
	public function setDestinationUrl(string $destinationUrl): void {
		if (!filter_var($destinationUrl, FILTER_VALIDATE_URL)) {
			throw new LinkException("Wrong url");
		}

		$this->destinationUrl = $destinationUrl;
	}

	/**
	 * @return Visit[]
	 * @throws LinkException
	 */
	public function getVisits(mysqli $db): array {
		$q = $db->prepare(<<<QUERY
select v.* from visits v 
    inner join links l on v.link_id = l.id
where l.id = ? 
QUERY
		);
		$q->bind_param("i", $this->id);
		if (!$q->execute()) {
			throw new LinkException("Db error: $db->error");
		}

		$result = $q->get_result();

		$visits = [];
		/** @noinspection PhpAssignmentInConditionInspection */
		while ($t = $result->fetch_assoc()) {
			$visits[] = $v = new Visit();
			$v->loadFromRow($t);
		}

		return $visits;
	}

	/**
	 * @throws EntityException
	 */
	public function addVisit(string $ip, string $useragent, mysqli $db): void {
		Visit::create($ip, $this->getId(), $useragent, $db);
	}

	/**
	 * @return int
	 */
	public function getOwnerId(): int {
		return $this->ownerId;
	}

	/**
	 * @param int $ownerId
	 */
	public function setOwnerId(int $ownerId): void {
		$this->ownerId = $ownerId;
	}
}