<?php
namespace OpenActu\UrlBundle\Model;

use Symfony\Component\DependencyInjection\Container;
use OpenActu\UrlBundle\Model\Url;
use OpenActu\UrlBundle\Exceptions\InvalidUrlException;
use OpenActu\UrlBundle\Model\Exception\UrlExceptions;
class UrlManager
{

	const 	LEVEL_EXCEPTION_INFO	= "INFO";
	const 	LEVEL_EXCEPTION_ERROR	= "ERROR";

	private $url;
	private $container;
	private $errors;
	
	/**
	 * has errors
         * @return boolean
         */
	public function hasErrors()
	{
		return $this->errors->hasErrors();
	}

	/**
	 * get Errors
         * @return UrlException[] 
	 */
        public function getErrors()
	{
		return $this->errors->getErrors();
	}
	/**
	 * set current scheme
	 * @param string $scheme Scheme
	 * @return
	 */
	public function setScheme($scheme)
        {
		try
		{
			$this->url->setScheme($scheme);
		}
		catch(InvalidUrlException $e)
		{
			$this->errors->add($e->getMessage(),$e->getCode());
		}
        }
	
	
	/**
	 * construct declaration
         */
	public function __construct(Container $container)
	{
		$this->container 	= $container;
		$active_schemes		= $container->getParameter('open_actu_url.url.schemes');
		$this->url 		= new Url($active_schemes);
		$level_exception	= $container->getParameter('open_actu_url.url.level_exception');
		$this->errors		= new UrlExceptions($level_exception);
	}
	
	/**
         * @var string
         * 
         * @return string
         * sanitize url and return the well-formed string in case of problem
         */  
        public function sanitizeUrl($url)
        {
		/**
		 * @todo
 		 */
        }
}
