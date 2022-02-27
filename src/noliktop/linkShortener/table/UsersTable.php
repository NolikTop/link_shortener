<?php

declare(strict_types=1);


namespace noliktop\linkShortener\table;


use mysqli;

class UsersTable implements Table {

	public function createIfNotExists(mysqli $db): void {
		$db->query(<<<QUERY
create table if not exists users (
    id int unsigned not null auto_increment primary key,
    login varchar(32) not null,
    password_hash binary(256) not null
)
QUERY
		);
	}

	public function drop(mysqli $db): void {
		$db->query(<<<QUERY
drop table users
QUERY
		);
	}
}