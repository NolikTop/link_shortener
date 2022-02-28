<?php

declare(strict_types=1);


namespace noliktop\linkShortener\table;


class VisitsTable implements Table {

	public function getQueryForCreateIfNotExists(): string {
		/*
		 * Поле ip длиной 15, потому что длина ipv4 15 символов.
		 * Можно было бы конечно в инте его хранить, но для удобочитаемости я сделал так.
		 *
		 * Также, конечно, возможен расклад, когда у нас ipv6, однако в условии про это
		 * ничего не сказано, а в выходные особо не спросишь.
		 * В конце концов, поправить этот момент несложно
		 */
		return <<<QUERY
create table if not exists visits (
 	id int unsigned not null primary key auto_increment,
 	ip varchar(15) not null,
 	created_at timestamp not null default current_timestamp,
 	useragent_id int unsigned not null,
 	foreign key (useragent_id) references useragents(id)
)
QUERY;
	}

	public function getQueryForDropIfExists(): string {
		return <<<QUERY
drop table if exists visits
QUERY;

	}
}