<?php

declare(strict_types=1);


namespace noliktop\linkShortener\table;


use mysqli;

class LinksTable implements Table {

	public function getQueryForCreateIfNotExists(): string {
		return <<<QUERY
create table if not exists links (
    id int unsigned not null auto_increment primary key,
    short_url varchar(16) not null,
    destination_url varchar(255) not null,
    owner_id int unsigned not null,
    foreign key (owner_id) references users(id)
)
QUERY;
	}

	public function getQueryForDropIfExists(): string {
		return <<<QUERY
drop table if exists links
QUERY;
	}
}