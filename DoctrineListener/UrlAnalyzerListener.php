<?php
namespace OpenActu\UrlBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use OpenActu\UrlBundle\Entity\UrlAnalyzer;
class UrlAnalyzerListener
{
	private $container;

	public function __construct($container)
	{
		$this->container = $container;
	}
	
	private function updateResponseUriWithoutRequestNorFragment(UrlAnalyzer $entity)
	{
		
		$um	= $this->container->get('open-actu.url.manager');
		$usm 	= $this->container->get('open-actu.url_storage.manager');
		$um->sanitize(null,$entity->getRequestUri());
		
		/**
		 * update the response_uri_without_request_nor_fragment
		 */
		$entity->setRequestUriWithoutQueryNorFragment($um->getUrlWithoutQueryNorFragment());
	}

	public function preUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof UrlAnalyzer){
			$this->updateResponseUriWithoutRequestNorFragment($entity);
		}
	}

	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof UrlAnalyzer){
			$this->updateResponseUriWithoutRequestNorFragment($entity);
		}
	}
}
