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
			if($is_url_analyzer && isset($subtab['reload']))
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
					$this->component->reload($id,$classname);
				}
			}			
		}
	}
}
