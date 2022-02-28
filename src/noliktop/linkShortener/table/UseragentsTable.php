<?php

declare(strict_types=1);


namespace noliktop\linkShortener\table;


use mysqli;

class UseragentsTable implements Table {

	public function getQueryForCreateIfNotExists(): string {
		return <<<QUERY
create table if not exists useragents (
    id int unsigned not null auto_increment primary key,
    useragent text not null
)
QUERY;
	}

	public function getQueryForDropIfExists(): string {
		return <<<QUERY
drop table if exists useragents
QUERY;
	}
}