<?php

declare(strict_types=1);


namespace noliktop\linkShortener\config;


use JsonException;

class Config {

	protected $path;
	protected $contents;

	/**
	 * @throws ConfigException
	 */
	public function __construct(string $path){
		$this->path = $path;

		$this->contents = $this->load();
	}

	/**
	 * @throws ConfigException
	 */
	protected function load(): array {
		$contents = file_get_contents($this->path);
		if ($contents === false) {
			throw new ConfigException("Couldn't read config file");
		}

		try {
			return json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
		} catch (JsonException $e) {
			throw new ConfigException("Couldn't parse config file", 0, $e);
		}
	}

}