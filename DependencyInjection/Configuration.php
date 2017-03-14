<?php

namespace OpenActu\UrlBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use OpenActu\UrlBundle\Model\UrlManager;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('open_actu_url');
	$rootNode
          ->children()
            ->arrayNode('url')
              ->children()			
                ->arrayNode('schemes')
		  ->isRequired()
                  ->prototype('scalar')->end()
                ->end()
                ->scalarNode('scheme_default')
                  ->defaultValue(UrlManager::SCHEME_DEFAULT)
                ->end()
                ->enumNode('level_exception')
                  ->values(array(UrlManager::LEVEL_EXCEPTION_INFO,UrlManager::LEVEL_EXCEPTION_ERROR))
                  ->defaultValue(UrlManager::LEVEL_EXCEPTION_INFO)
                ->end()
                ->arrayNode('port')
                  ->children()
		    ->arrayNode('defaults')
		      ->prototype('array')
			->children()
			  ->scalarNode('scheme')->end()
			  ->scalarNode('port')->end()
			->end()
		      ->end()
		    ->end()
		    ->enumNode('mode')
                      ->values(array(UrlManager::PORT_MODE_NORMAL,UrlManager::PORT_MODE_FORCED,UrlManager::PORT_MODE_NONE))
		      ->defaultValue(UrlManager::PORT_MODE_NORMAL)
		    ->end()
		  ->end()
                ->end()
                ->arrayNode('protocol')
		  ->children()
                    ->enumNode('method')
		      ->values(array(UrlManager::METHOD_GET,UrlManager::METHOD_POST))
                      ->defaultValue(UrlManager::METHOD_GET)
                    ->end()
                    ->integerNode('timeout')
		      ->defaultValue(UrlManager::REQUEST_TIMEOUT)
		    ->end()
		  ->end()
                ->end()
		->arrayNode('response')
		  ->children()
		    ->arrayNode('purge')
		      ->children()
                        ->integerNode('delay')
			->end()
			->enumNode('unit')
			  ->values(array(
				UrlManager::PURGE_UNIT_SECOND,
				UrlManager::PURGE_UNIT_MINUTE,
				UrlManager::PURGE_UNIT_HOUR,
				UrlManager::PURGE_UNIT_MONTH,
				UrlManager::PURGE_UNIT_DAY
				)
			)
			->end()
		      ->end()
		    ->end()
		  ->end()
		->end()
              ->end()
            ->end()
          ->end();
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
