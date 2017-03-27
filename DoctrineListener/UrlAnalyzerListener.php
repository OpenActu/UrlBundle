<?php
namespace OpenActu\UrlBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use OpenActu\UrlBundle\Entity\UrlAnalyzer;
use OpenActu\UrlBundle\Entity\UrlCoreAnalyzer;
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
		$um->changePortMode($entity->getPortMode());
		$usm 	= $this->container->get('open-actu.url_storage.manager');
		$um->sanitize(null,$entity->getRequestUri());
		
		/**
		 * update the response_uri_without_request_nor_fragment
		 */
		$entity->setRequestUriWithoutQueryNorFragment($um->getUrlWithoutQueryNorFragment());

		/**
		 * update the response_uri_calculated
		 */
		if($entity->getUseUrlWithoutQueryNorFragment() === true)
			$entity->setRequestUriCalculated($um->getUrlWithoutQueryNorFragment());
		else
			$entity->setRequestUriCalculated($entity->getRequestUri());

		/**
		 * build core
		 */
		$em = $this->container->get('doctrine.orm.entity_manager');
		$core = $em->getRepository('OpenActuUrlBundle:UrlCoreAnalyzer')
		  ->findOneBy(
		    array(
			'uriCalculated' => $entity->getRequestUriCalculated(), 
			'classname' => get_class($entity)
		    )
		  );
		
		if(null === $core)
		{
			$core = new UrlCoreAnalyzer();			
			$core->setUriCalculated($entity->getRequestUriCalculated());
			$core->setClassname(get_class($entity));
			$core->setUri($entity->getRequestUri());
			$core->setUriWithoutQueryNorFragment($entity->getRequestUriWithoutQueryNorFragment());
			$core->setScheme($entity->getRequestScheme());
			$core->setHost($entity->getRequestHost());
			$core->setSubdomain($entity->getRequestSubdomain());
			$core->setDomain($entity->getRequestDomain());
			$core->setTopLevelDomain($entity->getRequestTopLevelDomain());
			$core->setFolder($entity->getRequestFolder());
			$core->setFilename($entity->getRequestFilename());
			$core->setFilenameExtension($entity->getRequestFilenameExtension());
			$core->setPath($entity->getRequestPath());
			$core->setQuery($entity->getRequestQuery());
			$core->setFragment($entity->getRequestFragment());
		}
		$entity->setCore($core);
	}

	private function loadRequestDatas(UrlAnalyzer $entity)
	{
		$core = $entity->getCore();
		$entity->setRequestUriCalculated($core->getUriCalculated());
		$entity->setRequestUri($core->getUri());
		$entity->setRequestUriWithoutQueryNorFragment($core->getUriWithoutQueryNorFragment());
		$entity->setRequestScheme($core->getScheme());
		$entity->setRequestHost($core->getHost());
		$entity->setRequestSubdomain($core->getSubdomain());
		$entity->setRequestDomain($core->getDomain());
		$entity->setRequestTopLevelDomain($core->getTopLevelDomain());
		$entity->setRequestFolder($core->getFolder());
		$entity->setRequestFilename($core->getFilename());
		$entity->setRequestFilenameExtension($core->getFilenameExtension());
		$entity->setRequestPath($core->getPath());
		$entity->setRequestQuery($core->getQuery());
		$entity->setRequestFragment($core->getFragment());
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
        
        public function postLoad(LifecycleEventArgs $args)
        {
		$entity = $args->getObject();
		if($entity instanceof UrlAnalyzer){
			$this->loadRequestDatas($entity);
		}
	}
}
