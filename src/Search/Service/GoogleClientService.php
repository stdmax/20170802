<?php

namespace Search\Service;

use Search\Client\WebClient;
use Search\Exception\ClientServiceException;

class GoogleClientService extends AbstractClientService implements IClientService
{
	/**
	 * @var WebClient
	 */
	protected $webClient;

	/**
	 * GoogleClientService constructor.
	 *
	 * @param array $conf
	 */
	public function __construct(array $conf) {
		parent::__construct('Google');
		$this->webClient = new WebClient(WebClient::USER_AGENT_SEK800C, $conf['google_url']);
	}

	/**
	 * @param string $query
	 *
	 * @return array
	 * @throws ClientServiceException
	 */
	public function search($query) {
		if (0 == strlen($query)) {
			throw new ClientServiceException('Search string is empty');
		}

		$response = $this->webClient->request([
			'q' => $query,
		]);

		$pattern = '/"web_result"[^>*]*>.*href="([^"]+)"[^>]*>(.+)<\/a>.*<div[^>]*>(.*)<div[^>]*>/Us';
		$matched = 0 != preg_match_all($pattern, iconv('WINDOWS-1251', 'UTF-8', $response), $matches);
		if (!$matched) {
			throw new ClientServiceException('Search result page parse error');
		}

		array_walk($matches[1], function (&$value) {
			$value = str_replace('&amp;', '&', $value);

			// parse google formatted url by 2 steps
			foreach (['q', 'lite_url'] as $parameterName) {

				$query = parse_url($value, PHP_URL_QUERY);
				if (false === $query) {
					throw new ClientServiceException(sprintf('Parse url error: %s', $value));
				}

				// parse query string into array
				parse_str($query, $value);
				if (!isset($value[$parameterName])) {
					throw new ClientServiceException(sprintf('Parse url query error: %s, "%s" parameter not found', $query, $parameterName));
				}
				$value = $value[$parameterName];
			}
		});

		return $this->formatResult($matches[1], $matches[2], $matches[3]);
	}
}