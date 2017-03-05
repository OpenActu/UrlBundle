<?php
namespace OpenActu\UrlBundle\Model;

use OpenActu\UrlBundle\Exceptions\InvalidUrlException;
use OpenActu\UrlBundle\Model\UrlManager;
use OpenActu\UrlBundle\Entity\UrlAnalyzer;
use OpenActu\UrlBundle\Model\Url;
use OpenActu\UrlBundle\DBAL\EnumUrlAnalyzerStatusType;
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
	
	/**
	 * Send request and response management
         */
	public function sendRequest(UrlAnalyzer &$object,Url &$url,array $parameters)
	{
		$scheme = $url->getScheme();
		$method = 'send'.strtoupper($scheme).'Request';
		
		$object->setRequestQuery($url->getQuery());
		$object->setRequestFragment($url->getFragment());
		$object->setRequestUri((string)$url);		
		$object->setRequestScheme($url->getScheme());
		$object->setRequestSubdomain($url->getSubdomain());
		$object->setRequestDomain($url->getDomain());
		$object->setRequestTopLevelDomain($url->getTopLevelDomain());
		$object->setRequestPath($url->getPath());
		$object->setRequestFolder($url->getFolder());
		$object->setRequestFilename($url->getFilename());
		$object->setRequestFilenameExtension($url->getFilenameExtension());
		$object->setRequestHost($url->getHost());
		$object->setStatus(EnumUrlAnalyzerStatusType::STATUS_SENT);
		if(method_exists($this,$method))
		{
			return $this->$method($object,$url,$parameters);
		}
		else
		{
			throw new InvalidUrlException(
			InvalidUrlException::INVALID_SEND_METHOD_MESSAGE,
			InvalidUrlException::INVALID_SEND_METHOD_CODE,
			array('scheme' => $scheme,'method' => $method));
		}
	}

	public function sendHTTPRequest(UrlAnalyzer &$object,Url &$url,array $parameters)
	{
		$s_url 	= $url->getUrlWithoutQueryNorFragment();

		$ch = \curl_init($s_url);

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
		
		// Retrieve information from CURL calling
		if(!empty($infos))
		{
			$object->setContentType($infos['content_type']);
			$object->setHttpCode($infos['http_code']);
			$object->setHeaderSize($infos['header_size']);
			$object->setRequestSize($infos['request_size']);
			$object->setFiletime($infos['filetime']);
			$object->setSslVerifyResult($infos['ssl_verify_result']);
			$object->setResponseUrl($infos['url']);
			$object->setRedirectCount($infos['redirect_count']);
			$object->setTotalTime($infos['total_time']);
			$object->setNameLookupTime($infos['namelookup_time']);
			$object->setConnectTime($infos['connect_time']);
			$object->setPretransferTime($infos['pretransfer_time']);
			$object->setSizeUpload($infos['size_upload']);
			$object->setSizeDownload($infos['size_download']);
			$object->setSpeedDownload($infos['speed_download']);
			$object->setSpeedUpload($infos['speed_upload']);
			$object->setDownloadContentLength($infos['download_content_length']);
			$object->setUploadContentLength($infos['upload_content_length']);
			$object->setStarttransferTime($infos['starttransfer_time']);
			$object->setRedirectTime($infos['redirect_time']);
			$object->setRedirectUrl($infos['redirect_url']);
			$object->setPrimaryIp($infos['primary_ip']);
			$object->setCertinfo($infos['certinfo']);
			$object->setPrimaryPort($infos['primary_port']);
			$object->setLocalIp($infos['local_ip']);
			$object->setLocalPort($infos['local_port']);
		}
		if($curl_err)
		{
			$object->setResponse(null);
			$object->setRequestErrorMessage($curl_err);
			throw new InvalidUrlException(
			InvalidUrlException::UNKNOWN_RESPONSE_MESSAGE,
			InvalidUrlException::UNKNOWN_RESPONSE_CODE,
			array('name' => $curl_err));
		}
		elseif($infos['http_code'] < 200 || $infos['http_code'] > 299)
		{
			$object->setResponse(null);
			throw new InvalidUrlException(
			InvalidUrlException::BAD_RESPONSE_MESSAGE,
			InvalidUrlException::BAD_RESPONSE_CODE,
			array('http_code' => $infos['http_code']));
		}
		else
		{
			$response = $object->getResponse();
			$response->setContent($return);
		}
	}
}
