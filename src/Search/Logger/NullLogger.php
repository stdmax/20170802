<?php

namespace Search\Logger;

class NullLogger extends AbstractLogger implements ILogger
{
	/**
	 * {@inheritdoc}
	 */
	public function message($status, $message) {
		return false;
	}
}