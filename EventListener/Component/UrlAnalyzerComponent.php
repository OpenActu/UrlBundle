<?php
namespace OpenActu\UrlBundle\EventListener\Component;

use OpenActu\UrlBundle\Model\UrlManager;
use OpenActu\UrlBundle\Model\UrlStorageManager;
class UrlAnalyzerComponent
{
	/**
	 * @var open-actu.url_storage.manager Service
	 *
	 */
	private $url_storage_manager;

	/**
	 * @var open-actu.url.manager Service
	 *
	 */
	private $url_manager;
	
	public function __construct(UrlManager $url_manager,UrlStorageManager $url_storage_manager)
	{
		$this->url_storage_manager 	= $url_storage_manager;
		$this->url_manager 		= $url_manager;
	}		
	
	public function reload($id, $classname)
	{
		$entity = $this->url_storage_manager->getEntityByIdAndClassname($id, $classname);
		if(null !== $entity->getId())
		{
			$this->url_manager->changePortMode($entity->getPortMode());
			if($entity->getAcceptUpdate())
			{			
				/**
				 * entity reloading process
				 *
				 */
				$entity = $this->url_manager->sanitize($classname,$entity->getRequestUri(),true);
				$this->url_manager->send($entity);
				$this->url_storage_manager->push($entity);
			}
			else
			{
				echo 'todo:'.__CLASS__.'@'.__LINE__;
				die;
			}
		}
	}
}
