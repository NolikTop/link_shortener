<?php

declare(strict_types=1);


namespace noliktop\linkShortener\table;


class LinksTable implements Table {

	public function getQueryForCreateIfNotExists(): string {
		//todo повесить index на short link
		return <<<QUERY
create table if not exists links (
    id int unsigned not null auto_increment primary key,
    short_link varchar(16) not null unique,
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