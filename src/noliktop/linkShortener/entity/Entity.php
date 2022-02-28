<?php

declare(strict_types=1);


namespace noliktop\linkShortener\entity;


use mysqli;
use mysqli_stmt;

abstract class Entity {

	/** @var int */
	protected $id;

	public function getId(): int{
		return $this->id;
	}

	public function setId(int $id): void{
		$this->id = $id;
	}

	abstract protected function loadFromRow(array $row): void;

	/**
	 * @throws EntityException
	 */
	public function loadById(int $id, mysqli $db): void{
		$this->id = $id;

		$q = $this->prepareLoadById($db);

		if (!$q->execute()) {
			throw new EntityException("Cant load: $db->error");
		}

		$result = $q->get_result();

		if ($result->num_rows === 0) {
			throw new EntityException("No entity with id $this->id");
		}

		$this->loadFromRow($result->fetch_assoc());
	}

	abstract public function prepareLoadById(mysqli $db): mysqli_stmt;

	/**
	 * @throws EntityException
	 */
	public function insert(mysqli $db): void{
		$q = $this->prepareInsert($db);

		if (!$q->execute()) {
			throw new EntityException($db->error);
		}

		$this->setId($db->insert_id);
	}

	abstract protected function prepareInsert(mysqli $db): mysqli_stmt;

	/**
	 * @throws EntityException
	 */
	public function update(mysqli $db): void{
		$q = $this->prepareUpdate($db);

		if (!$q->execute()) {
			throw new EntityException($db->error);
		}
	}

	abstract protected function prepareUpdate(mysqli $db): mysqli_stmt;

	/**
	 * @throws EntityException
	 */
	public function delete(mysqli $db): void{
		$q = $this->prepareDelete($db);

		if (!$q->execute()) {
			throw new EntityException($db->error);
		}
	}

	abstract protected function prepareDelete(mysqli $db): mysqli_stmt;

}