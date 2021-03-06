<?php
namespace OpenActu\UrlBundle\Exceptions;

use OpenActu\UrlBundle\Exceptions\InvalidUrlExceptionInterface;
class InvalidUrlException extends \Exception implements InvalidUrlExceptionInterface
{
	public function __construct($message,$code,array $params=array())
	{	
		if(0 !== count($params))
		{
			foreach($params as $param => $value)
			{
				$message = str_replace("%".$param."%",$value,$message);
			}
		}		
		parent::__construct($message,$code);
	}
}
