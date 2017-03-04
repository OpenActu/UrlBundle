<?php
namespace OpenActu\UrlBundle\Model;

use OpenActu\UrlBundle\Exceptions\InvalidUrlException;
use OpenActu\UrlBundle\Model\UrlManager;
class Request
{
	private static $method;
	private static $timeout;

	public function __construct($method,$timeout)
	{
		self::$method = $method;
		
		self::$timeout= $timeout;

		// check if curl is installed
		if(!is_callable('curl_init'))
			throw new InvalidUrlException(
				InvalidUrlException::CURL_NOT_ACTIVATED_MESSAGE,
				InvalidUrlException::CURL_NOT_ACTIVATED_CODE
			);
	}

	public function send($url,array $parameters)
	{
		$ch = \curl_init($url);

		\curl_setopt($ch, CURLOPT_HEADER, 0);
		\curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		\curl_setopt($ch, CURLOPT_TIMEOUT, self::$timeout);

		if(self::$method == UrlManager::METHOD_POST)
		{
			\curl_setopt($ch, CURLOPT_HEADER, false);
			\curl_setopt($ch, CURLOPT_POST, count($parameters));
			\curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
		}
		
		$return 	= \curl_exec($ch);
		$curl_err 	= \curl_error($ch);
		$infos		= \curl_getinfo($ch);

		\curl_close($ch);
		print_r($return);
		if($curl_err)
		{
			throw new InvalidUrlException(
			InvalidUrlException::UNKNOWN_RESPONSE_MESSAGE,
			InvalidUrlException::UNKNOWN_RESPONSE_CODE,
			array('name' => $curl_err));
		}
		elseif($infos['http_code'] < 200 || $infos['http_code'] > 299)
		{
			throw new InvalidUrlException(
			InvalidUrlException::BAD_RESPONSE_MESSAGE,
			InvalidUrlException::BAD_RESPONSE_CODE,
			array('http_code' => $infos['http_code']));
		}
		else
		{
			/**
			 * @todo
			 */
		}
	}
}
