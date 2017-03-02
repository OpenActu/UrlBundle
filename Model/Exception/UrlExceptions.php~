<?php
namespace OpenActu\UrlBundle\Model\Exception;

use OpenActu\UrlBundle\Model\Exception\UrlException;
use OpenActu\UrlBundle\Model\UrlManager;
use OpenActu\UrlBundle\Exceptions\InvalidUrlException;
class UrlExceptions
{
	/**
 	 * @var array Array of UrlException items
         */
	private $errors = array();
	
	/**
	 * @var string Level exception (list of level exception listed on UrlManager consts)
         */
	private $level_exception;

	public function __construct($level_exception)
	{
		$this->level_exception=$level_exception;
	}
	public function hasErrors()
	{
		return (0 !== count($this->errors));
	}
	public function getErrors()
	{
		return $this->errors;
	}
	public function add($errmsg, $errno)
	{
		if(UrlManager::LEVEL_EXCEPTION_INFO === $this->level_exception)
			$this->errors[] = new UrlException($errmsg,$errno);
		elseif(UrlManager::LEVEL_EXCEPTION_ERROR === $this->level_exception)
			throw new InvalidUrlException($errmsg,$errno);
	}
}
