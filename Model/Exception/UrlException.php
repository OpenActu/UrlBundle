<?php
namespace OpenActu\UrlBundle\Model\Exception;

use OpenActu\UrlBundle\Exceptions\InvalidUrlException;
class UrlException
{
	private $errmsg;
	private $errno;
	
	/**
	 * Build the Url Exception
	 *
	 * @author	yanroussel
	 * @var string 	$errmsg 	Error message
  	 * @var string	$errno		Error number
	 * @throws 	InvalidUrlException if the url given not responding with RFC3986
   	 * @link http://tools.ietf.org/html/rfc3986 (the URI specification)
 	 */
	public function __construct($errmsg,$errno)
	{
		$this->errmsg 	= $errmsg;
		$this->errno	= $errno;
	}
	
	/**
	 * @return string
         */
 	public function getMessage()
	{
		return $this->errmsg;
	}
	/**
	 * @return int
	 */
	public function getCode()
	{
		return $this->errno;
	}
}
