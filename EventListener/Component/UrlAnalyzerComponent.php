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
	
	public function add($request, &$errors = array())
	{
		
		/**
		 * Purge of error messages
		 */
		$this->url_manager->reset();

		$id = null;
		$portMode			= isset($request['portMode']) ? $request['portMode'] : null;
		$classname			= isset($request['classname']) ? $request['classname'] : null;
		$requestUri			= isset($request['requestUri']) ? $request['requestUri'] : null;
		$encodeUrl			= isset($request['encodeUrl']) ? $request['encodeUrl'] : true;
		$useUrlWithoutQueryNorFragment	= isset($request['useUrlWithoutQueryNorFragment']) ? $request['useUrlWithoutQueryNorFragment'] : true;
		if((null !== $classname) && (null !== $portMode))
		{
			$this->url_manager->changePortMode($portMode);
			$entity = $this->url_manager->sanitize($classname,$requestUri,$encodeUrl);
			$entity->setUseUrlWithoutQueryNorFragment($useUrlWithoutQueryNorFragment);
			if(null !== $entity && !$this->url_manager->hasErrors())
			{
				$this->url_manager->send($entity);
				
				if(!$this->url_manager->hasErrors())
				{
					/**
					 * we said that the link can not be updated
					 */
					$entity->setAcceptUpdate(false);	
				}
				$this->url_storage_manager->push($entity);
				$id = $entity->getId();
			}
			elseif(null !== $entity)
			{
				$this->url_storage_manager->push($entity);
				$id = $entity->getId();
			}
			$errors = $this->url_manager->getErrors();
		}
		return $id;
	}

	public function removeAll($id, $classname, array &$errors = array())
	{
		$entity = $this->url_storage_manager->getEntityByIdAndClassname($id, $classname);
		if((null !== $entity) && (null !== $entity->getId()))
		{
			$entities = $this->url_storage_manager
			  ->getEntitiesCalculatedByRequestUriCalculatedAndClassname(
			    $entity->getRequestUriCalculated(), 
			    $classname
			  );

			foreach($entities as $entity)
			{
				$this->remove($entity->getId(), $classname, $errors);
			}
		}
	}

	public function remove($id, $classname,array &$errors = array())
	{
		$entity = $this->url_storage_manager->getEntityByIdAndClassname($id, $classname);
		if((null !== $entity) && (null !== $entity->getId()))
		{
			/**
			 * Removing response
			 *
			 */
			if($entity->getResponse() && $entity->getResponse()->getId())
			{
				$response = $this
					->url_storage_manager
					->getEntityByIdAndClassname(
						$entity->getResponse()->getId(),
						$entity->getResponseClass()
					);
				$this->url_storage_manager->remove($response);
				$entity->setResponse(null);		
			}
			$this->url_storage_manager->remove($entity);
		}
	}

	public function reload($id, $classname,array &$errors = array())
	{

		/**
		 * Purge of error messages
		 */
		$this->url_manager->reset();

		$entity = $this->url_storage_manager->getEntityByIdAndClassname($id, $classname);
		if(null !== $entity->getId())
		{
			$this->url_manager->changePortMode($entity->getPortMode());
			
			if($entity->getAcceptUpdate() === true)
			{			
				/**
				 * entity reloading process
				 *
				 */
				$entity = $this->url_manager->sanitize($classname,$entity->getRequestUri(),true);
				if(null !== $entity && !$this->url_manager->hasErrors())
				{
					$this->url_manager->send($entity);
					
					if(!$this->url_manager->hasErrors())
					{
					}
					$this->url_storage_manager->push($entity);
				}
				elseif(null !== $entity)
				{
					$this->url_storage_manager->push($entity);
				}
				else
				{
					 $this->url_manager->addCustomError('can\'t not match the request uri with any existing item',999);
				}
			}
			else
			{	
				$this->url_manager->addCustomError('accept update at false',999);		
			}
		}
		$errors = $this->url_manager->getErrors();
	}
}
