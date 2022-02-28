<?php

declare(strict_types=1);


namespace noliktop\linkShortener\config;


class Config {

	protected $path;
	protected $contents;

	/**
	 * @throws ConfigException
	 */
	public function __construct(string $path) {
		$this->path = $path;

		$this->contents = $this->load();
	}

	public function get(string $field): array {
		return $this->contents[$field];
	}

	public function fillObject(string $field, object $obj): object {
		$data = $this->get($field);

		foreach ($data as $key => $value) {
			$obj->{$key} = $value;
		}

		return $obj;
	}

	/**
	 * @throws ConfigException
	 */
	protected function load(): array {
		$contents = file_get_contents($this->path);
		if ($contents === false) {
			throw new ConfigException("Couldn't read config file");
		}

		$json = json_decode($contents, true, 512); // я бы использовал JSON_THROW_ON_ERROR, но в тз указано php >= 7.2
		if (!isset($json)) {
			throw new ConfigException(json_last_error_msg());
		}

		return $json;
	}

}