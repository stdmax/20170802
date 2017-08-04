<?php

namespace Search\Service;

abstract class AbstractClientService
{
	/**
	 * Service name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * AbstractClientService constructor.
	 *
	 * @param string $name
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param array $urls
	 * @param array $texts
	 * @param array $descriptions
	 *
	 * @return array
	 */
	protected function formatResult($urls, $texts, $descriptions) {
		return array_map(function ($url, $text, $description) {
			return [
				'url' => trim($url),
				'text' => trim($text),
				'description' => trim($description),
			];
		}, $urls, $texts, $descriptions);
	}
}