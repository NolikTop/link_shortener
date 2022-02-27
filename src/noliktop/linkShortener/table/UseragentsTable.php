<?php

declare(strict_types=1);


namespace noliktop\linkShortener\table;


use mysqli;

class UseragentsTable implements Table {

	public function createIfNotExists(mysqli $db): void {
		$db->query(<<<QUERY
create table `useragents` (
    id int unsigned not null auto_increment primary key,
    useragent text not null
)
QUERY
);
	}

	public function drop(mysqli $db): void {
		$db->query(<<<QUERY
drop table `useragents`
QUERY
);
	}
}