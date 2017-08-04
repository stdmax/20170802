<?php

namespace Search\Client;

use Search\Exception\IException;

interface IWebClient
{
	/**
	 * @param array $getData
	 *
	 * @throws IException
	 * @return string
	 */
	public function request(array $getData);
}