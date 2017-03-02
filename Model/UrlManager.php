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

        const   PORT_MODE_NORMAL	= "normal";
	const 	PORT_MODE_FORCED	= "forced";
	const 	PORT_MODE_NONE		= "none";

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
	 * Retrieve current
         * @return string Scheme
	 * @throws InvalidUrlException if the scheme is null (only with level_exception at "ERROR")
         */
	public function getScheme()
	{
		try
		{
			return $this->url->getScheme();
		}
		catch(InvalidUrlException $e)
		{
			$this->errors->add($e->getMessage(),$e->getCode());
		}
	}	

 	/**
	 * set current scheme
	 * @param string $scheme Scheme
	 * @throws InvalidUrlException if the scheme is invalid (only with level_exception at "ERROR")
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
	 * Retrieve current
         * @return string Host
	 * @throws InvalidUrlException if the host is null (only with level_exception at "ERROR")
         */
	public function getHost()
	{
		try
		{
			return $this->url->getHost();
		}
		catch(InvalidUrlException $e)
		{
			$this->errors->add($e->getMessage(),$e->getCode());
		}
	}	
	
	/**
	 * set current host
	 * @param string $host Host
 	 * @throws InvalidUrlException if the host is invalid (only with level_exception at "ERROR")
	 */
	public function setHost($host)
        {
		try
		{
			$this->url->setHost($host);
		}
		catch(InvalidUrlException $e)
		{
			$this->errors->add($e->getMessage(),$e->getCode());
		}
        }

	/**
	 * Retrieve current Port
         * @return int|null Port
         */
	public function getPort()
	{
		return $this->url->getPort();
	}	

	/**
	 * set current port
	 * @param int $port Port
 	 * @throws InvalidUrlException if the port is invalid (only with level_exception at "ERROR")
	 */
	public function setPort($port)
        {
		try
		{
			$this->url->setPort($port);
		}
		catch(InvalidUrlException $e)
		{
			$this->errors->add($e->getMessage(),$e->getCode());
		}
        }

	/**
	 * Retrieve current Path
         * @return string|null path
         */
	
	public function getPath()
	{
		return $this->url->getPath();
	}

        /**
         * set current path
         * @param string $path Path
         * @throws InvalidUrlException if the path is invalid (only with level_exception at "ERROR")
         */
        public function setPath($path)
	{
		try
		{
			$this->url->setPath($path);
		}
		catch(InvalidUrlException $e)
		{
			$this->errors->add($e->getMessage(),$e->getCode());
		}

	}
	
	/**
  	 * get fragment
         * 
         * @return string Fragment
 	 */
	public function getFragment()
	{
		return $this->url->getFragment();
	}

	/**
	 * set fragment
	 *
	 * @param string $fragment fragment component
	 */
	public function setFragment($fragment)
	{
		$this->url->setFragment($fragment);
	}

	/**
	 * get query
	 *
         * @return string Query
         */
        public function getQuery()
	{
		return $this->url->getQuery();
	}

	/**
	 * set query
	 * 
	 * The query is a set of key/value separated by "&" and associated by "=" between them. In case of we
	 * have a part of keys without value, we consider that theses keys exist and are equals to "".
         *
	 * For example, the queries "now=here&right=left" and "now=&right=left" are valid.
	 * 
         * @param string $query
     	 */
        public function setQuery($query)
        {
		$this->url->setQuery($query);
        }
	/**
	 * construct declaration
         */
	public function __construct(Container $container)
	{
		$this->container 	= $container;
		
		$active_schemes		= $container->getParameter('open_actu_url.url.schemes');
		$default_ports		= $container->getParameter('open_actu_url.url.port.defaults');
		$port_mode		= $container->getParameter('open_actu_url.url.port.mode');
		$this->url 		= new Url($active_schemes,$default_ports,$port_mode);
		
		$level_exception	= $container->getParameter('open_actu_url.url.level_exception');
		$this->errors		= new UrlExceptions($level_exception);
		
	}
	
	/**
	 * reset url parameters and errors bag
   	 *
	 * @return
	 */
	public function reset()
	{
		$this->url->reset();
		$this->errors->reset();
	}
	
	/**
	 * Change the port mode
         *
         * three modes are availabled: "normal", "forced" and "none"
         * - "normal" 		:(RECOMMANDED) If the port is the standard port used with the current scheme, the port will
         *                          be omitted.
         * - "forced"		: force the port information. If port is not given, the port takes the default port
         *                         relative to the current scheme
         * - "none"		: use port only if the information is done
         * @param string $port_mode	Port mode
         * @throws InvalidUrlException if the port mode is not referenced
	 */
	public function changePortMode($port_mode)
	{
		try
		{
			$this->url->changePortMode($port_mode);
		}
		catch(InvalidUrlException $e)
		{
			$this->errors->add($e->getMessage(),$e->getCode());
		}
	}

	/**
	 * Check if the url given is equivalent to the target url
 	 *
         * @param string $url Url to compare with current instance 
         * @return boolean
         */  
        public function isEquals($url)
        {
		return $this->url->isEquals($url);
        }
	
	public function __toString()
	{
		return (string)$this->url;
	}
}
