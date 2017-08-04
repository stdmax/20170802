<?php

namespace Search\Logger;

interface ILogger
{
	/**
	 * ILogger constructor.
	 *
	 * @param array $conf
	 */
	public function __construct(array $conf);

	/**
	 * @param string $message
	 *
	 * @return boolean
	 */
	public function info($message);

	/**
	 * @param string $message
	 *
	 * @return boolean
	 */
	public function error($message);
}