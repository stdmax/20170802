<?php

namespace Search\Service;

use Search\Exception\IException;

interface IClientService
{
	/**
	 * Return search service name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Return search result from first page
	 *
	 * @param string $query
	 *
	 * @throws IException
	 * @return array
	 */
	public function search($query);
}