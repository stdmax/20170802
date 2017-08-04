<?php

namespace Search\Client;

use Search\Exception\WebClientException;

class WebClient implements IWebClient
{
	const USER_AGENT_SEK800C = 'SonyEricssonK800c/R8BF Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1';

	/**
	 * @var string
	 */
	protected $userAgent;

	/**
	 * @var string
	 */
	protected $baseUrl;

	/**
	 * @var array
	 */
	protected $baseGetData;

	/**
	 * WebClient constructor.
	 *
	 * @param string $userAgent
	 * @param string $baseUrl
	 * @param array $baseGetData
	 */
	public function __construct($userAgent, $baseUrl, array $baseGetData = []) {
		$this->userAgent = $userAgent;
		$this->baseUrl = $baseUrl;
		$this->baseGetData = $baseGetData;
	}

	/**
	 * {@inheritdoc}
	 */
	public function request(array $getData) {
		$url = $this->baseUrl . '?' . http_build_query($getData + $this->baseGetData);
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_HEADER => false,
			CURLOPT_USERAGENT => $this->userAgent,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
		];

		$curl = curl_init();
		curl_setopt_array($curl, $options);

		$response = curl_exec($curl);
		$error = curl_errno($curl);
		if (0 != $error) {
			throw new WebClientException(sprintf('Curl error: %d, url: %s', $error, $url));
		} else {
			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ('200' != $status) {
				throw new WebClientException(sprintf('HTTP error. HTTP status: %s, url: %s', $status, $url));
			}
		}

		return $response;
	}
}