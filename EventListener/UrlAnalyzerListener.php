<?php
namespace OpenActu\UrlBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use OpenActu\UrlBundle\EventListener\Component\UrlAnalyzerComponent;
use OpenActu\UrlBundle\Entity\UrlAnalyzer;
class UrlAnalyzerListener
{
	/**
	 * @var $component
	 */
	private $component;

	public function __construct(UrlAnalyzerComponent $component)
	{
		$this->component = $component;
	}

	public function processReload(FilterControllerEvent $event)
	{

		$kernel		= $event->getKernel();
		$request 	= $event->getRequest();
		$tab	 	= $request->request->all();
		$is_url_analyzer= false;
		$parameters	= array();
		$session 	= $request->getSession();

		foreach($tab as $key => $subtab)
		{
			/**
			 * check if the source is from UrlAnalyzer ?
			 *
			 */
			$classname = !empty($subtab['classname']) ? $subtab['classname'] : null;
			if(null !== $classname)
			{
				$entity = new $classname();
				$parent_classname = get_parent_class($entity);	
				
				$is_url_analyzer = ( $parent_classname === UrlAnalyzer::class );
							
			}
			/**
			 * check if the call is to reload item
			 *
			 */
			if($is_url_analyzer && isset($subtab['__reload']))
			{
				/**
				 * getting the id of the current URL item
				 *
				 * this works only if the "id" is given as the route parameter
				 */
				$route_params 	= $event->getRequest()->attributes->get('_route_params');
				$id		= !empty($route_params['id']) ? $route_params['id'] : null;
				
				if(null !== $id)
				{
					/**
					 * reload the current Url item
					 *
					 */
					$errors = array();
					$this->component->reload($id,$classname,$errors);
					
					if(count($errors) > 0)
					{
						foreach($errors as $error)
						{
							$session->getFlashBag()->add('url_analyzer_errors', $error->getMessage());
						}
					}
					elseif(count($errors) === 0)
					{
						$session->getFlashBag()->add('url_analyzer_reload_success', true);
					}				
				}
			}			
		}
	}
        
        public function processRemove(FilterControllerEvent $event)
	{
		$id 		= null;
		$classname	= null;
		$errors		= array();

		if($this->checkOrigin($event, '__remove', $id, $classname))
		{
			$this->component->remove($id, $classname, $errors);
		}
	}

        private function checkOrigin(FilterControllerEvent $event, $target, &$id, &$classname)
	{
		$kernel		= $event->getKernel();
		$request 	= $event->getRequest();
		$tab	 	= $request->request->all();
		$is_url_analyzer= false;
		$parameters	= array();
		
		$id 		= null;
		$classname	= null;

		foreach($tab as $key => $subtab)
		{
			/**
			 * check if the source is from UrlAnalyzer ?
			 *
			 */
			$classname = !empty($subtab['classname']) ? $subtab['classname'] : null;
			if(null !== $classname)
			{
				$entity = new $classname();
				$parent_classname = get_parent_class($entity);	
				
				$is_url_analyzer = ( $parent_classname === UrlAnalyzer::class );
							
			}
			/**
			 * check if the call is searched
			 *
			 */
			if($is_url_analyzer && isset($subtab[$target]))
			{
				$route_params 	= $event->getRequest()->attributes->get('_route_params');
				$id		= !empty($route_params['id']) ? $route_params['id'] : null;
				return true;
			}
		}
		
		return false;		
	}

        public function processAdd(FilterControllerEvent $event)
	{
		$kernel		= $event->getKernel();
		$request 	= $event->getRequest();
		$tab	 	= $request->request->all();
		$is_url_analyzer= false;
		$parameters	= array();
		
		foreach($tab as $key => $subtab)
		{
			/**
			 * check if the source is from UrlAnalyzer ?
			 *
			 */
			$classname = !empty($subtab['classname']) ? $subtab['classname'] : null;
			if(null !== $classname)
			{
				$entity = new $classname();
				$parent_classname = get_parent_class($entity);	
				
				$is_url_analyzer = ( $parent_classname === UrlAnalyzer::class );
							
			}
			/**
			 * check if the call is to add item
			 *
			 */
			if($is_url_analyzer && isset($subtab['__add']))
			{
				/**
				 * adding new item
				 *
				 * if id is returned, we have no error
				 */
				$errors = array();
				$id = $this->component->add($subtab, $errors);
				$session = $request->getSession();

				if(null !== $id)
				{
					$request->request->set('id', $id);
					$session->getFlashBag()->add('url_analyzer_add_success', true);
				}
				if(count($errors) > 0)
				{
					foreach($errors as $error)
					{
						$session->getFlashBag()->add('url_analyzer_errors', $error->getMessage());
					}
				}
			}			
		}
	}
}
