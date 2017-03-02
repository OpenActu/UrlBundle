<?php
namespace OpenActu\UrlBundle\Model;

/**
 * Value object representing a URI.
 *
 * This class is meant to represent URIs according to RFC 3986 and to
 * provide methods for most common operations. Additional functionality for
 * working with URIs can be provided on top of the interface or externally.
 * Its primary use is for HTTP requests, but may also be used in other
 * contexts.
 *
 * Instances of this interface are considered immutable; all methods that
 * might change state MUST be implemented such that they retain the internal
 * state of the current instance and return an instance that contains the
 * changed state.
 *
 * Typically the Host header will be also be present in the request message.
 * For server-side requests, the scheme will typically be discoverable in the
 * server parameters.
 *
 * @link http://tools.ietf.org/html/rfc3986 (the URI specification)
 */
use Psr\Http\Message\UriInterface;
use OpenActu\UrlBundle\Exceptions\InvalidArgumentException;
use OpenActu\UrlBundle\Exceptions\InvalidUrlException;

class Url implements UriInterface
{
    /**
     *
     */
    const REGEXP_SCHEME_PROTOCOL	= "(?<scheme>[a-z0-9*]*)";
    const REGEXP_SCHEME_PUNCT  		= "[:]{0,1}";
    const REGEXP_SCHEME_PATH_ABEMPTY 	= "\/\/";

    /**
     * Scheme component of the URI.
     *
     * @var string|null Scheme component
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     * @return string The URI scheme.
     */
    private $scheme;
      
    /** 
     * @todo 
     */    
    private $folder;
    
    /** 
     * @todo 
     */
    private $query;
    
    /**
     * @todo
     */
    private $subdomain;
    
    /**
     * @todo
     */
    private $domain;
    
    /**
     * @todo
     */
    private $domainExtension;
    
    /**
     * @todo
     */
    private $filename;
    
    /**
     * @todo
     */
    private $filenameExtension;
    
    /**
     * @todo
     */
    private $port;
    
    /**
     * @todo
     */
    private $fragment;
    
    /**
     * Array of active schemes (must be referenced in "open_actu_url.url.schemes" section in config.yml)
     */
    private static $active_schemes = array();

    //////////////////////////////////////////////////////////////////////////////////////
    //											//
    //				CONSTRUCTOR						//
    //											//
    //////////////////////////////////////////////////////////////////////////////////////

    public function __construct($active_schemes)
    {
	self::$active_schemes = $active_schemes;
    }

    /**
     *  set the scheme component of the URI.
     *
     * If no scheme is present, this attribute must be an empty string.
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.1.
     *
     * The trailing ":" character is not part of the scheme and MUST NOT be
     * added.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     * @return 
     */
    public function setScheme($scheme)
    {
	$regexp = $this->getSchemeRegexp('scheme_only');
	if(!preg_match($regexp,$scheme))
	{
		throw new InvalidUrlException(
			InvalidUrlException::INVALID_SCHEME_FORMAT_MESSAGE,
			InvalidUrlException::INVALID_SCHEME_FORMAT_CODE,
			array('name' => $scheme)
		);
	}
    }
    
    //////////////////////////////////////////////////////////////////////////////////////
    //											//
    //				SPECIFIC METHODS					//
    //											//
    //////////////////////////////////////////////////////////////////////////////////////
    
    /**
     * @param  string $str regexp without delimiters
     *
     * @return string the regexp with delimiters
     */
    private static function formatRegexp($str)
    {
        return "/^".$str."$/i";
    }

    /**
     * @param  enum $option mode of regexp scheme building 
     *         - full 		: regexp with scheme protocol, punct and path 
     *         - scheme_only 	: regexp with scheme protocol
     *
     * @return string the regexp scheme validation among option selected
     */
    private function getSchemeRegexp($option = "full")
    {
	$scheme_regexp = null;
	switch($option)
	{
		case 'scheme_only':
			$scheme_regexp = self::REGEXP_SCHEME_PROTOCOL;
			break;
		case 'full':
		default:
			$scheme_regexp = 
				self::REGEXP_SCHEME_PROTOCOL.
				self::REGEXP_SCHEME_PUNCT.
				self::REGEXP_SCHEME_PATH_ABEMPTY;

	}
	return self::formatRegexp($scheme_regexp);
    }
    ///////////////////////////////// refactoring ////////////////////////////////////////
    const SCHEME_DEFAULT		= "http";
    const PORT_DEFAULT			= 80;
    const AUTHORITY_PATH_ABEMPTY	= "//";
    const PATH_ABSOLUTE			= "/";
    const POST_SCHEME_TAG		= ":";
    const POST_FILENAME_TAG		= '.';
    const PRE_QUERY_TAG			= '?';
    const PRE_FRAGMENT			= '#';
    const URL_SCHEME			= "/^(?<scheme>[a-z0-9*]*)[:]{0,1}\/\//i";
    const PATH_PATTERN			= "/(\/(((?<folder>((([^\/]*)\/)*)([^\/]{1,100}))\/){0,1})(?<filename>[^?.\/]+)([.\/]{0,1})(?<filenameExtension>[^?]*)|\/|)/i";
    const REGEXP_HOST			= "((?<subdomain>(([^\/.]+[.])*)[^\/.]+[.]){0,1})(?<domain>[^.\/]{3,})([.](?<domainExtension>(([^\/.]{2,3}[.])*)[^\/.]{2,3}))?";
    const REGEXP_PORT			= "([:](?<port>\d+))?";
    const REGEXP_PATH			= "(\/(((?<folder>((([^\/]*)\/)*)([^\/]{1,100}))\/){0,1})(?<filename>[^?.\/]+)([.\/]{0,1})(?<filenameExtension>[a-z0-9\.]*)|\/|)";
    const REGEXP_QUERY			= "([?](?<query>[^#]*))?";
    const REGEXP_FRAGMENT		= "([#](?<fragment>.*))?";
    const REGEXP_REST			= "(?<rest>.*)";

    
    private $encodeUrl = true;
    
    /**
     * Define if url query parameters must be encoded or not
     */
    public function setEncodeUrl($option)
    {
	$this->encodeUrl = $option;
    }
    
    private static function getCompleteRegexp()
    {
	return "/^".
		self::REGEXP_SCHEME.
		self::REGEXP_HOST.
		self::REGEXP_PORT.
		self::REGEXP_PATH.
		self::REGEXP_QUERY.
		self::REGEXP_FRAGMENT.
		self::REGEXP_REST.
		"$/i";
		
    }

    
    /**
     * Init the current object
     */
    public function init()
    {
	$this->scheme		= null;
	$this->query		= null;
	$this->fragment		= null;
	$this->subdomain	= null;
	$this->domain		= null;
	$this->domainExtension	= null;
	$this->port		= null;
	$this->initPath();
    }

    /**
     * init path
     */
    public function initPath()
    {
	$this->filename		= null;
	$this->filenameExtension= null;
	$this->folder		= null;
    }
    /**
     * Has scheme ?
     */
    public static function hasScheme($url)
    {
	return preg_match(self::getSchemeRegexp(),$url);
    }
    
    /**
     * Append default scheme if nothing is present
     */
    private static function appendDefaultScheme($url)
    {
	return self::SCHEME_DEFAULT.self::POST_SCHEME_TAG.self::AUTHORITY_PATH_ABEMPTY.$url;
    }

    /**
     * Path builder
     */
    private function buildPath($folder,$filename,$filenameExtension)
    {
	$this->initPath();

	if(!empty($folder)){
		$folder	      = strtolower($folder);
		$folder	      = preg_replace("/\/(\/+)([^\/]|$)/iU","/$2", $folder);				
		$this->folder = $folder;
	}

	if(!empty($filename))
		$this->filename = strtolower($filename);

	if(!empty($filenameExtension))
		$this->filenameExtension = strtolower($filenameExtension);
    }

    /**
     * Load the current object with new URL
     */
    public function load($url=null)
    {
		
		$this->init();

		if(null === $url)
		{
			return false;
		}
		
		if(!self::hasScheme($url))
		{
			$url = self::appendDefaultScheme($url);		
		}
		$url = trim($url);

		if(preg_match(self::getCompleteRegexp(),$url,$matches)){
				
			if(!empty($matches['scheme']))
				$this->scheme = strtolower($matches['scheme']);

			if(!empty($matches['subdomain']))
				$this->subdomain = strtolower(substr($matches['subdomain'],0,-1));

			if(!empty($matches['domain']))
				$this->domain = strtolower($matches['domain']);

			if(!empty($matches['domainExtension']))
				$this->domainExtension = strtolower($matches['domainExtension']);
			
			$folder  		= !empty($matches['folder']) ? $matches['folder'] : null;
			$filename		= !empty($matches['filename']) ? $matches['filename'] : null;
			$filenameExtension 	= !empty($matches['filenameExtension']) ? $matches['filenameExtension'] : null;
			
			$this->buildPath($folder, $filename, $filenameExtension);
			
			if(!empty($matches['port']))
				$this->port = (int)$matches['port'];			
  			
			if(!empty($matches['query']))
				$this->query = (string)$matches['query'];
			
			if(!empty($matches['fragment']))
				$this->fragment = (string)$matches['fragment'];

			return true;
		}
		
		throw new InvalidArgumentException('you attempt to load an url with bad format (please consult the RFC3986 recommandations). Given : '.$url);

	}    
	
    /**
     * Retrieve the scheme component of the URI.
     *
     * If no scheme is present, this method MUST return an empty string.
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.1.
     *
     * The trailing ":" character is not part of the scheme and MUST NOT be
     * added.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     * @return string The URI scheme.
     */
    public function getScheme()
    {
	return $this->scheme;
    }

    /**
     * Retrieve the authority component of the URI.
     *
     * If no authority information is present, this method MUST return an empty
     * string.
     *
     * The authority syntax of the URI is:
     *
     * <pre>
     * [user-info@]host[:port]
     * </pre>
     *
     * If the port component is not set or is the standard port for the current
     * scheme, it SHOULD NOT be included.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @return string The URI authority, in "[user-info@]host[:port]" format.
     */
    public function getAuthority()
    {
	$authority = $this->getHost();

	if(null !== $this->port)
		$authority.=self::POST_SCHEME_TAG.(string)$this->port;

	return $authority;
    }

    /**
     * Retrieve the user information component of the URI.
     *
     * If no user information is present, this method MUST return an empty
     * string.
     *
     * If a user is present in the URI, this will return that value;
     * additionally, if the password is also present, it will be appended to the
     * user value, with a colon (":") separating the values.
     *
     * The trailing "@" character is not part of the user information and MUST
     * NOT be added.
     *
     * @return string The URI user information, in "username[:password]" format.
     */
    public function getUserInfo()
    {
    }

    /**
     * Retrieve the host component of the URI.
     *
     * If no host is present, this method MUST return an empty string.
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.2.2.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     * @return string The URI host.
     */
    public function getHost()
    {
	$host = null;

	if(null !== $this->subdomain)
		$host = $this->subdomain.self::POST_FILENAME_TAG;
	
	$host.=$this->domain;

	if(null !== $this->domainExtension)
		$host.=self::POST_FILENAME_TAG.$this->domainExtension;
        
        return $host;
    }

    /**
     * Retrieve the port component of the URI.
     *
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it as an integer. If the port is the standard port
     * used with the current scheme, this method SHOULD return null.
     *
     * If no port is present, and no scheme is present, this method MUST return
     * a null value.
     *
     * If no port is present, but a scheme is present, this method MAY return
     * the standard port for that scheme, but SHOULD return null.
     *
     * @return null|int The URI port.
     */
    public function getPort()
    {
	return $this->port;
    }

    /**
     * Retrieve the path component of the URL.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * Normally, the empty path "" and absolute path "/" are considered equal as
     * defined in RFC 7230 Section 2.7.3. But this method MUST NOT automatically
     * do this normalization because in contexts with a trimmed base path, e.g.
     * the front controller, this difference becomes significant. It's the task
     * of the user to handle both "" and "/".
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.3.
     *
     * As an example, if the value should include a slash ("/") not intended as
     * delimiter between path segments, that value MUST be passed in encoded
     * form (e.g., "%2F") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @return string The URL path.
     */
    public function getPath()
    {
	$path = $this->folder;
	
    	if( (null !== $this->folder) && (null !== $this->filename) )
		$path.=self::PATH_ABSOLUTE;
       
	if(null !== $this->filename)
		$path.=$this->filename;

	if(null !== $this->filenameExtension)
		$path.=self::POST_FILENAME_TAG.$this->filenameExtension;
    	
	return $path;
    }

    /**
     * Retrieve the query string of the URI.
     *
     * If no query string is present, this method MUST return an empty string.
     *
     * The leading "?" character is not part of the query and MUST NOT be
     * added.
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.4.
     *
     * As an example, if a value in a key/value pair of the query string should
     * include an ampersand ("&") not intended as a delimiter between values,
     * that value MUST be passed in encoded form (e.g., "%26") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     * @return string The URI query string.
     */
    public function getQuery()
    {
	return $this->query;
    }

    /**
     * Retrieve the fragment component of the URI.
     *
     * If no fragment is present, this method MUST return an empty string.
     *
     * The leading "#" character is not part of the fragment and MUST NOT be
     * added.
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.5.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     * @return string The URI fragment.
     */
    public function getFragment()
    {
	return $this->fragment;
    }

    /**
     * Return an instance with the specified scheme.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified scheme.
     *
     * Implementations MUST support the schemes "http" and "https" case
     * insensitively, and MAY accommodate other schemes if required.
     *
     * An empty scheme is equivalent to removing the scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     * @return static A new instance with the specified scheme.
     * @throws \InvalidArgumentException for invalid or unsupported schemes.
     */
    public function withScheme($scheme)
    {
	if(true)
	{
	}
	else
	{
		throw new InvalidArgumentException('invalid scheme given',502);
	}
    }

    /**
     * Return an instance with the specified user information.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified user information.
     *
     * Password is optional, but the user information MUST include the
     * user; an empty string for the user is equivalent to removing user
     * information.
     *
     * @param string $user The user name to use for authority.
     * @param null|string $password The password associated with $user.
     * @return static A new instance with the specified user information.
     */
    public function withUserInfo($user, $password = null)
    {
    }

    /**
     * Return an instance with the specified host.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified host.
     *
     * An empty host value is equivalent to removing the host.
     *
     * @param string $host The hostname to use with the new instance.
     * @return static A new instance with the specified host.
     * @throws \InvalidArgumentException for invalid hostnames.
     */
    public function withHost($host)
    {
	if(true)
	{
	}
	else
	{
		throw new InvalidArgumentException('invalid host given',504);
	}
    }

    /**
     * Return an instance with the specified port.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified port.
     *
     * Implementations MUST raise an exception for ports outside the
     * established TCP and UDP port ranges.
     *
     * A null value provided for the port is equivalent to removing the port
     * information.
     *
     * @param null|int $port The port to use with the new instance; a null value
     *     removes the port information.
     * @return static A new instance with the specified port.
     * @throws \InvalidArgumentException for invalid ports.
     */
    public function withPort($port)
    {
	$url = clone $this;
	$url->port = $port;
	return $url;
    }

    /**
     * Return an instance with the specified path.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified path.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * If the path is intended to be domain-relative rather than path relative then
     * it must begin with a slash ("/"). Paths not starting with a slash ("/")
     * are assumed to be relative to some base path known to the application or
     * consumer.
     *
     * Users can provide both encoded and decoded path characters.
     * Implementations ensure the correct encoding as outlined in getPath().
     *
     * @param string $path The path to use with the new instance.
     * @return static A new instance with the specified path.
     * @throws \InvalidArgumentException for invalid paths.
     */
    public function withPath($path)
    {
	$url = clone $this;
	
	if(preg_match(self::PATH_PATTERN,$path,$matches))
	{

		$folder 		= !empty($matches['folder']) ? $matches['folder'] : null;
		$filename		= !empty($matches['filename']) ? $matches['filename'] : null;
		$filenameExtension	= !empty($matches['filenameExtension']) ? $matches['filenameExtension'] : null; 
		
		$url->buildPath($folder,$filename,$filenameExtension);		
	}
	else
		throw new InvalidArgumentException('invalid path given',500);
	return $url;
    }

    private function buildQuery($strQuery)
    {
	$complete_tab = array();

	$tmp_tab = explode("&",$strQuery);
	foreach($tmp_tab as $subQuery)
	{
		$data = explode("=",$subQuery);
		
		if(count($data) == 2)
		{
			$complete_tab[$data[0]]=$data[1];
		}
		elseif(count($data) == 1)
		{
			$complete_tab[$data[0]]=null;
		}
	}

        $str = null;
	
	foreach($complete_tab as $key => $parameter){
		if($this->encodeUrl)
			$str.='&'.urlencode($key).'='.urlencode($parameter);
		else
			$str.='&'.$key.'='.$parameter;
	}
	
	$this->query = substr($str,1);
    }

    /**
     * Return an instance with the specified query string.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified query string.
     *
     * Users can provide both encoded and decoded query characters.
     * Implementations ensure the correct encoding as outlined in getQuery().
     *
     * An empty query string value is equivalent to removing the query string.
     *
     * @param string $query The query string to use with the new instance.
     * @return static A new instance with the specified query string.
     * @throws \InvalidArgumentException for invalid query strings.
     */
    public function withQuery($query)
    {
	$url = clone $this;
	if(empty($query))
	{
		$url->query = null;
	}
	else
	{
		$url->buildQuery($query);
	}
	
	return $url;
    }

    /**
     * Return an instance with the specified URI fragment.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified URI fragment.
     *
     * Users can provide both encoded and decoded fragment characters.
     * Implementations ensure the correct encoding as outlined in getFragment().
     *
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     * @return static A new instance with the specified fragment.
     */
    public function withFragment($fragment)
    {
    }

    /**
     * Return the string representation as a URI reference.
     *
     * Depending on which components of the URI are present, the resulting
     * string is either a full URI or relative reference according to RFC 3986,
     * Section 4.1. The method concatenates the various components of the URI,
     * using the appropriate delimiters:
     *
     * - If a scheme is present, it MUST be suffixed by ":".
     * - If an authority is present, it MUST be prefixed by "//".
     * - The path can be concatenated without delimiters. But there are two
     *   cases where the path has to be adjusted to make the URI reference
     *   valid as PHP does not allow to throw an exception in __toString():
     *     - If the path is rootless and an authority is present, the path MUST
     *       be prefixed by "/".
     *     - If the path is starting with more than one "/" and no authority is
     *       present, the starting slashes MUST be reduced to one.
     * - If a query is present, it MUST be prefixed by "?".
     * - If a fragment is present, it MUST be prefixed by "#".
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.1
     * @return string
     */
    public function __toString()
    {

	$url = $this->getScheme().self::POST_SCHEME_TAG;
	
	if(null !== $this->getAuthority())
		$url.= self::AUTHORITY_PATH_ABEMPTY . $this->getAuthority();
	
	if(null !== $this->getPath())
		$url.= self::PATH_ABSOLUTE.$this->getPath();
	elseif(null !== $this->getAuthority())
		$url.= self::PATH_ABSOLUTE;
	
	if(null !== $this->getQuery())
		$url.= self::PRE_QUERY_TAG.$this->getQuery();
	
        if(null !== $this->getFragment())
		$url.= self::PRE_FRAGMENT.$this->getFragment();
	
	return $url;
    }

}

