<?php

declare(strict_types=1);


namespace noliktop\linkShortener\table;


class UsersTable implements Table {

	public function getQueryForCreateIfNotExists(): string {
		// password_hash 64 байта, так как sha256
		return <<<QUERY
create table if not exists users (
    id int unsigned not null auto_increment primary key,
    login varchar(32) not null unique,
    password_hash binary(32) not null
)
QUERY;
	}

	public function getQueryForDropIfExists(): string {
		return <<<QUERY
drop table if exists users
QUERY;
	}
}