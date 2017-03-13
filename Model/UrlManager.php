<?php
namespace OpenActu\UrlBundle\Model;

use Symfony\Component\DependencyInjection\Container;
use OpenActu\UrlBundle\Model\Url;
use OpenActu\UrlBundle\Model\Request;
use OpenActu\UrlBundle\Exceptions\InvalidUrlException;
use OpenActu\UrlBundle\Model\Exception\UrlExceptions;
use OpenActu\UrlBundle\Entity\UrlAnalyzer;
use OpenActu\UrlBundle\DBAL\EnumUrlAnalyzerStatusType;

class UrlManager
{

	const 	LEVEL_EXCEPTION_INFO	= "INFO";
	const 	LEVEL_EXCEPTION_ERROR	= "ERROR";

        const   PORT_MODE_NORMAL	= "normal";
	const 	PORT_MODE_FORCED	= "forced";
	const 	PORT_MODE_NONE		= "none";

   	const 	SCHEME_DEFAULT		= "http";

	const   METHOD_GET		= "get";
	const	METHOD_POST		= "post";
	
	const 	REQUEST_TIMEOUT		= 10;

	const 	STATUS_CODE_SUCCESS	= 200;
	const 	STATUS_CODE_FAILED	= 404;

	private $url;
	private $request;
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
         * Retrieve current folder
	 *
	 * The folder is part of Path
         * @return string|null Folder
	 */
        public function getFolder()
        {
	 	return $this->url->getFolder();
	}

        /**
         * Retrieve current filename
	 *
	 * The filename is part of Path
         * @return string|null Filename
	 */
        public function getFilename()
        {
	 	return $this->url->getFilename();
	}

        /**
         * Retrieve current filename extension
	 *
	 * The filename extension is part of Path
         * @return string|null Filename extension
	 */
        public function getFilenameExtension()
        {
	 	return $this->url->getFilenameExtension();
	}

        /**
         * Retrieve current subdomain
	 *
	 * The subdomain is part of Host
         * @return string|null Subdomain
	 */
        public function getSubdomain()
        {
	 	return $this->url->getSubdomain();
	}

        /**
         * Retrieve current domain
	 *
	 * The domain is part of Host
         * @return string Domain
	 */
        public function getDomain()
        {
	 	return $this->url->getDomain();
	}


        /**
         * Retrieve current top level domain
	 *
	 * The top level domain is part of Host
         * @return string Top level domain
	 */
        public function getTopLevelDomain()
        {
	 	return $this->url->getTopLevelDomain();
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
	 * @param bool $decodeURL (OPTIONAL) Decoding to URL recommandation
         * @return string Fragment
 	 */
	public function getFragment($decodeURL=false)
	{
		return $this->url->getFragment($decodeURL);
	}

	/**
	 * set fragment
	 *
	 * @param string $fragment fragment component
	 * @param bool $encodeURL (OPTIONAL) Encoding to URL recommandation
	 */
	public function setFragment($fragment,$encodeURL=false)
	{
		$this->url->setFragment($fragment,$encodeURL);
	}

	/**
	 *
	 * @return string
	 */
	public function getUrlWithoutQueryNorFragment()
	{
		return $this->url->getUrlWithoutQueryNorFragment();
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
	 * @param bool $encodeURL (OPTIONAL) Encoding to URL recommandation
     	 */
        public function setQuery($query,$encodeURL=false)
        {
		$this->url->setQuery($query,$encodeURL);
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
		$scheme_default		= $container->getParameter('open_actu_url.url.scheme_default');
		$this->url 		= new Url($active_schemes,$default_ports,$port_mode,$scheme_default);
		
		$level_exception	= $container->getParameter('open_actu_url.url.level_exception');
		$this->errors		= new UrlExceptions($level_exception);
		
		$method			= $container->getParameter('open_actu_url.url.protocol.method');
		$this->request		= new Request($method,10);
	}

	/**
	 * send request and return response
         *
         * @param \OpenActu\UrlBundle\Entity\UrlAnalyzer $object
         * @param bool $urlEncode
	 * @param array $options
	 * @return string|null Response 
         */	
	public function send(UrlAnalyzer &$object,$urlEncode=true, array $parameters = array())
	{
		try
		{
			$parameters['query'] = $this->url->getQuery($urlEncode);
			$parameters['fragment'] = $this->url->getFragment($urlEncode);
			$this->request->sendRequest($object,$this->url,$parameters);
		}
		catch(InvalidUrlException $e)
		{
			$object->setRequestErrorMessage($e->getMessage());
			$this->errors->add($e->getMessage(),$e->getCode());
		}
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
 
        /**
         * Check if the url is valid 
         *
	 * An URL is valid only if a scheme is given
         *
	 * @return bool Response of the current url has errors or not 
         */
        public function isValid()
	{
		if($this->hasErrors())
		{
			return false;
		}

		$validator = $this->container->get('validator');
		$listErrors= $validator->validate($this->url);
		if(count($listErrors) > 0)
		{
			foreach($listErrors as $listError)
			{
				$this->errors->add($listError->getMessage(),InvalidUrlException::INVALID_VALIDATION_CODE);
			}
			return false;
		}
		return true;
	}

	/**
	 * Sanitize an url
         *
         * The object is to validate the URL construct and optionnaly return the normalized well-formed URL
	 *
         * @param string $classname the classname object returned
	 * @param string $url Url to sanitize
         * @param bool $encodeURL Option to protect the query and fragment area (DEFAULT=false)
         * @return bool The url validation result
         */
	public function sanitize($classname,$url,$encodeURL=false)
	{
	
		$object = null;	
		if(null !== $classname)
			$object = new $classname();
		
		try
		{
			$this->url->sanitize($url,$encodeURL);
			if(null !== $object)
			{			
				$object->setRequestQuery($this->url->getQuery());
				$object->setRequestFragment($this->url->getFragment());
				$object->setRequestUri((string)$this->url);		
				$object->setRequestScheme($this->url->getScheme());
				$object->setRequestSubdomain($this->url->getSubdomain());
				$object->setRequestDomain($this->url->getDomain());
				$object->setRequestTopLevelDomain($this->url->getTopLevelDomain());
				$object->setRequestPath($this->url->getPath());
				$object->setRequestFolder($this->url->getFolder());
				$object->setRequestFilename($this->url->getFilename());
				$object->setRequestFilenameExtension($this->url->getFilenameExtension());
				$object->setRequestHost($this->url->getHost());
				$object->setStatus(EnumUrlAnalyzerStatusType::STATUS_SANITIZED);
				$object->setResponse(null);
			}
			return $object;
		}
		catch(InvalidUrlException $e)
		{
			$this->errors->add($e->getMessage(),$e->getCode());
		}
		return null;
	}
}
