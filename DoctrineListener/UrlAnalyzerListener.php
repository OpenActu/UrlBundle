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
		 * update the response_uri_without_request_and_fragment
		 */
		$entity->setRequestUriWithoutQueryAndFragment($um->getUrlWithoutQueryNorFragment());
		$usm->push($entity);
	}

	public function postUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof UrlAnalyzer){
			$this->updateResponseUriWithoutRequestNorFragment($entity);
		}
	}

	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof UrlAnalyzer){
			$this->updateResponseUriWithoutRequestNorFragment($entity);
		}
	}
}
