<?php

namespace Search\Controller;

use Search\Logger\ILogger;

abstract class AbstractController
{
	/**
	 * @var ILogger
	 */
	protected $logger;

	/**
	 * @var array
	 */
	protected $conf;

	/**
	 * AbstractController constructor.
	 *
	 * @param ILogger $logger
	 * @param array $conf
	 */
	public function __construct(ILogger $logger, array $conf) {
		$this->logger = $logger;
		$this->conf = $conf;
	}

	/**
	 * @param array $request
	 *
	 * @return boolean
	 */
	abstract public function mainAction(array $request);

	/**
	 * @param array $request
	 * @param string $name
	 * @param string $defaultValue
	 *
	 * @return string
	 */
	protected function getTextFromRequest(array $request, $name, $defaultValue = '') {
		if (!array_key_exists($name, $request)
			|| !is_string($text = strval($request[$name]))) {
			$text = $defaultValue;
		}

		return $text;
	}

	/**
	 * @param string $templateName
	 * @param array $data
	 *
	 * @return boolean
	 */
	protected function renderHtml($templateName, array $data = []) {
		$replace = [];
		foreach ($data + [
			'title' => $this->conf['title'],
		] as $name => $value) {
			$replace['{{' . $name . '}}'] = strval($value);
		}

		$fileName = $this->conf['path'] . 'src/Search/Resources/templates/' . $templateName . '.html';
		$contents = strtr(file_get_contents($fileName), $replace);

		header('Content-type: text/html; charset=utf-8');
		echo $contents;

		return true;
	}

	/**
	 * @param array $data
	 *
	 * @return boolean
	 */
	protected function renderJson($data) {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);

		return true;
	}

	/**
	 * @param string $url
	 *
	 * @return boolean
	 */
	protected function redirect($url) {
		header('Location: ' . $url);
		echo '<!DOCTYPE html><html><body><a href="' . $url . '">' . htmlspecialchars($url) . '</a></body></html>';

		return true;
	}
}
