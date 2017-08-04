<?php

namespace Search\Logger;

abstract class AbstractLogger
{
	const STATUS_INFO = 1;
	const STATUS_ERROR = 2;

	/**
	 * @var array
	 */
	protected $conf;

	/**
	 * NullLogger constructor.
	 *
	 * @param array $conf
	 */
	public function __construct(array $conf) {
		$this->conf = $conf;
	}

	/**
	 * {@inheritdoc}
	 */
	public function info($message) {
		return $this->message(self::STATUS_INFO, $message);
	}

	/**
	 * {@inheritdoc}
	 */
	public function error($message) {
		return $this->message(self::STATUS_ERROR, $message);
	}

	/**
	 * @param integer $status
	 * @param string $message
	 *
	 * @return boolean
	 */
	abstract public function message($status, $message);
}