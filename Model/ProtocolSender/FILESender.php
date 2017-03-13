<?php
namespace OpenActu\UrlBundle\Model\ProtocolSender;

use OpenActu\UrlBundle\Model\UrlManager;
use OpenActu\UrlBundle\Entity\UrlAnalyzer;
use OpenActu\UrlBundle\Model\Url;
use OpenActu\UrlBundle\Exceptions\InvalidUrlException;
use OpenActu\UrlBundle\Model\ProtocolSender\ProtocolSender;
class FILESender implements ProtocolSenderInterface
{
	public function __construct($timeout)
	{
	}
	
	public function sendRequest(UrlAnalyzer &$object,Url &$url,array $parameters,array $config=array())
	{
		$s_url 	= $url->getUrlWithoutQueryNorFragment();
		
		if(file_exists($s_url))
		{
			$object->setPermissions(substr(sprintf('%o', fileperms($s_url)), -4));		
			$object->setResponseUrl($s_url);
			
			if(is_dir($s_url))
			{
				/**
				 * directory management
				 *
				 */
				$object->setIsDir(true);
				$object->setContentType('inode/directory');
				
				/**
				 * If the URL is a directory, then the filename part is include into the folder
                                 *
 				 */
				$object->setRequestFolder($url->getPath());
				$object->setRequestFilename(null);
				$object->setRequestFilenameExtension(null);
				
				/**
				 * children detection
				 *
				 */
				$children = array();
        			if ($handle = opendir($s_url)) 
				{
            				while (false !== ($file = readdir($handle))) 
					{
				                if ($file != "." && $file != "..")
							$children[] = $s_url.'/'.$file;
                        
		                	}
		            	}
        			closedir($handle);
    				$object->setChildren($children);
				
				$object->setHttpCode(UrlManager::STATUS_CODE_SUCCESS);				
			}
			else
			{
				/**
				 * file management
				 *
				 */
				$object->setIsDir(false);
			 	$content = file_get_contents($s_url);
				$infos   = lstat($s_url);
				
				$object->setHttpCode(UrlManager::STATUS_CODE_SUCCESS);
				$object->setContentType(mime_content_type($s_url));		
				$object->setSizeDownload($infos['size']);
				$object->setDownloadContentLength($infos['size']);
				
				if($content)
				{
					$response = $object->getResponse();
					if(null === $response)
					{
						$classname = $object->getResponseClass();
						$response = new $classname();
						$object->setResponse($response);
					}
					$response->setContent($content);
				}
			}
		
		}
		else
		{
			/**
			 * file doesn't exist
			 *
			 */
			$object->setResponse(null);
			throw new InvalidUrlException(
			InvalidUrlException::BAD_RESPONSE_MESSAGE,
			InvalidUrlException::BAD_RESPONSE_CODE,
			array('http_code' => UrlManager::STATUS_CODE_FAILED));
		}
		/**
		// Retrieve information from CURL calling
		if(!empty($infos))
		{
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
		}
		*/
	}
}
