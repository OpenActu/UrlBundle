<?php
namespace OpenActu\UrlBundle\Model\ProtocolSender;

use OpenActu\UrlBundle\Model\UrlManager;
use OpenActu\UrlBundle\Entity\UrlAnalyzer;
use OpenActu\UrlBundle\Model\Url;
use OpenActu\UrlBundle\Exceptions\InvalidUrlException;
use OpenActu\UrlBundle\Model\ProtocolSender\ProtocolSender;
class HTTPSender extends ProtocolSender implements ProtocolSenderInterface
{
	const DEFAULT_METHOD = 'get';

	public function __construct($timeout)
	{
		parent::__construct($timeout);

		// check if curl is installed
		if(!is_callable('curl_init'))
		{
			throw new InvalidUrlException(
				InvalidUrlException::CURL_NOT_ACTIVATED_MESSAGE,
				InvalidUrlException::CURL_NOT_ACTIVATED_CODE
			);
		}
	}

	/**
	 * Get children from a current content
 	 *
	 * It's the default search based on HTML analysis
	 * @param string $content Content HTML
	 * @return array of URL found
	 */
	private function getChildren(UrlAnalyzer &$object,Url &$url)
	{
		$output = array();
		$content= "";
		$prefix = $url->getUrlWithoutPath();
		
		if($object->getResponse() && ($content = $object->getResponse()->getContent()))
		{
			preg_match_all("/href=\"(?<url>[^\"]*)/",$content,$matches);

			foreach($matches['url'] as $s_url)
			{
				if(substr($s_url,0,1) == '/')
					$s_url = $prefix.$s_url;
				
				/**
				 * filter on prefix
				 */
				$pos = strpos($s_url,$prefix);
				if($pos === 0)
					$output[] = $s_url;
			}
			$output = array_unique($output,SORT_STRING);
		}
		return $output;
	}

	public function sendRequest(UrlAnalyzer &$object,Url &$url,array $parameters,array $config=array())
	{
		$s_url 	= $url->getUrlWithoutQueryNorFragment();
		$method = !empty($config['method']) ? $config['method'] : UrlManager::METHOD_GET;
		$ch = \curl_init($s_url);

		\curl_setopt($ch, CURLOPT_HEADER, 0);
		\curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		\curl_setopt($ch, CURLOPT_TIMEOUT, $this->getTimeout());

		if($method == UrlManager::METHOD_POST)
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
				array('name' => $curl_err)
			);
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
			if(null === $response)
			{
				$classname = $object->getResponseClass();
				$response = new $classname();
				$object->setResponse($response);
			}
			$response->setContent($return);
			
			/**
			 * By default we search the children in the current URL
			 * based on the HTML detection
		 	 */	
			if(strlen($return))
			{
				$children = $this->getChildren($object, $url);
				$object->setChildren($children);
			}

		}
	}
}
