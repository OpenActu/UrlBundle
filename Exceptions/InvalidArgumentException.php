<?php
namespace OpenActu\UrlBundle\Exceptions;

class InvalidArgumentException extends \Exception implements OpenActuUrlBundleExceptionInterface
{
	const DEFAULT_CODE = 404;

	public function __construct($message,$code=self::DEFAULT_CODE)
	{
		parent::__construct($message,$code);
	}
}
