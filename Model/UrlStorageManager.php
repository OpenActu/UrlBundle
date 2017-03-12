<?php
namespace OpenActu\UrlBundle\Model;

use Symfony\Component\DependencyInjection\Container;
use OpenActu\UrlBundle\Entity\UrlAnalyzer;
class UrlStorageManager
{
	public $container;
	
	public function __construct(Container $container)
	{
		$this->container = $container;
	}
	
	/**
	 *  Push 
         *
	 * @param UrlAnalyzer $object Url Entity
	 */
	public function push(UrlAnalyzer $object)
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		$repository = $em->getRepository(get_class($object));
		
		if( (null !== $object->getRequestUri()) )
		{
			$entity = $repository->findOneBy(
				array(
					'requestUri'   => $object->getRequestUri(),
					'acceptUpdate' => true
				)
			);

			if(null === $entity)
			{
				/**
				 * creating Entity
				 */
				$em->persist($object);
			}
			else
			{
				/**
				 * updating Entity
				 */
				// Remove old response if existing
				if((null !== ($response = $entity->getResponse())) && (null !== $response->getId())){
					$r_entity   = $em->getRepository($object->getResponseClass())->find($response->getId());
					$em->remove($r_entity);
				}		
				$entity->setResponse($object->getResponse());	
				$entity->setContentType($object->getContentType());
				$entity->setHttpCode($object->getHttpCode());
				$entity->setHeaderSize($object->getHeaderSize());				
				$entity->setRequestSize($object->getRequestSize());
				$entity->setFiletime($object->getFiletime());		
				$entity->setSslVerifyResult($object->getSslVerifyResult());
				$entity->setResponseUrl($object->getResponseUrl());
				$entity->setRedirectCount($object->getRedirectCount());
				$entity->setTotalTime($object->getTotalTime());
				$entity->setNameLookupTime($object->getNameLookupTime());
				$entity->setConnectTime($object->getConnectTime());		
				$entity->setPretransferTime($object->getPretransferTime());
				$entity->setSizeUpload($object->getSizeUpload());			
				$entity->setSizeDownload($object->getSizeDownload());
				$entity->setSpeedDownload($object->getSpeedDownload());
				$entity->setSpeedUpload($object->getSpeedUpload());
				$entity->setDownloadContentLength($object->getDownloadContentLength());
				$entity->setUploadContentLength($object->getUploadContentLength());
				$entity->setStarttransferTime($object->getStarttransferTime());
				$entity->setRedirectTime($object->getRedirectTime());
				$entity->setRedirectUrl($object->getRedirectUrl());
				$entity->setPrimaryIp($object->getPrimaryIp());
				$entity->setCertinfo($object->getCertinfo());
				$entity->setPrimaryPort($object->getPrimaryPort());
				$entity->setLocalIp($object->getLocalIp());
				$entity->setLocalPort($object->getLocalPort());
				$entity->setStatus($object->getStatus());
				$entity->setAcceptUpdate($object->getAcceptUpdate());
			}			
			$em->flush();		
		}
	}
}
