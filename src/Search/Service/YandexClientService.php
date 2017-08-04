<?php

namespace Search\Service;

use Search\Client\WebClient;
use Search\Exception\CaptchaException;
use Search\Exception\ClientServiceException;

class YandexClientService extends AbstractClientService implements IClientService
{
	/**
	 * @var WebClient
	 */
	protected $webClient;

	/**
	 * YandexClientService constructor.
	 *
	 * @param array $conf
	 */
	public function __construct(array $conf) {
		parent::__construct('Yandex');
		$this->webClient = new WebClient(WebClient::USER_AGENT_SEK800C, $conf['yandex_url']);
	}

	/**
	 * @param string $query
	 *
	 * @return array
	 * @throws CaptchaException
	 * @throws ClientServiceException
	 */
	public function search($query) {
		if (0 == strlen($query)) {
			throw new ClientServiceException('Search string is empty');
		}

		$response = $this->webClient->request([
			'query' => $query,
		]);

		// Yandex may give are captcha check page
		$pattern = '/"\/checkcaptcha"/';
		$matched = 0 != preg_match($pattern, $response);
		if ($matched) {
			throw new CaptchaException('Captcha detected');
		}

		$pattern = '/(?<=<\/li|<ul)[^>]*>[^<]*<li[^>]*>[^<]*<[^>]*href="([^"]+)"[^>]*>(.+)<\/a>.*"b\-results__text"[^>]*>(.*)<\/span>[^<]*<(?:p|div).*"www"[^>]*>/Us';
		$matched = 0 != preg_match_all($pattern, $response, $matches);
		if (!$matched) {
			throw new ClientServiceException('Search result page parse error');
		}

		return $this->formatResult($matches[1], $matches[2], $matches[3]);
	}
}