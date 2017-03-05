<?php
namespace OpenActu\UrlBundle\Model;

use OpenActu\UrlBundle\Exceptions\InvalidUrlException;
use OpenActu\UrlBundle\Model\UrlManager;
use OpenActu\UrlBundle\Entity\UrlAnalyzer;
use OpenActu\UrlBundle\Model\Url;
use OpenActu\UrlBundle\DBAL\EnumUrlAnalyzerStatusType;
use OpenActu\UrlBundle\Model\ProtocolSender\HTTPSender;
use OpenActu\UrlBundle\Model\ProtocolSender\FILESender;
class Request
{
	private static $method;
	private static $timeout;

	public function __construct($method,$timeout)
	{
		self::$method = $method;
		
		self::$timeout= $timeout;
	}
	
	/**
	 * Send request and response management
         */
	public function sendRequest(UrlAnalyzer &$object,Url &$url,array $parameters)
	{
		$scheme 	= $url->getScheme();
		$sclass 	= strtoupper($scheme).'Sender';
		$protocolSender = 'OpenActu\UrlBundle\Model\ProtocolSender\\'.$sclass;	

		$object->setStatus(EnumUrlAnalyzerStatusType::STATUS_SENT);
		
		if(!class_exists($protocolSender))
		{
			throw new InvalidUrlException(
			InvalidUrlException::INVALID_SEND_METHOD_MESSAGE,
			InvalidUrlException::INVALID_SEND_METHOD_CODE,
			array('scheme' => $scheme,'sender' => $protocolSender));
		}
		else
		{
			$config['method'] = self::$method;
			$sender = new $protocolSender(self::$timeout);
			$sender->sendRequest($object,$url,$parameters,$config);			
		}
		
	}
}
