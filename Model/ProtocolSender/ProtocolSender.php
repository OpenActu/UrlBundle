<?php
namespace OpenActu\UrlBundle\Model\ProtocolSender;

class ProtocolSender
{
	protected static $timeout;
	
	public function __construct($timeout)
	{
		$this->setTimeout($timeout);
	}

	public function setTimeout($timeout)
	{
		self::$timeout = $timeout;
	}
	
	public function getTimeout()
	{
		return self::$timeout;
	}
}
