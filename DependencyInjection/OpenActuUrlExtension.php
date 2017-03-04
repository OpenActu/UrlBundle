<?php

namespace OpenActu\UrlBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use OpenActu\UrlBundle\Model\UrlManager;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class OpenActuUrlExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
	$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
	

	$container->setParameter('open_actu_url.url.schemes', $config['url']['schemes']);
	$container->setParameter('open_actu_url.url.scheme_default', $config['url']['scheme_default']);		
	$container->setParameter('open_actu_url.url.level_exception', $config['url']['level_exception']);
	
	$container->setParameter('open_actu_url.url.port.defaults', array());
	if(!empty($config['url']) && !empty($config['url']['port']) && !empty($config['url']['port']['defaults']))
		$container->setParameter('open_actu_url.url.port.defaults', $config['url']['port']['defaults']);
	
	$container->setParameter('open_actu_url.url.port.mode', UrlManager::PORT_MODE_NONE);
	if(!empty($config['url']) && !empty($config['url']['port']) && !empty($config['url']['port']['mode']))
		$container->setParameter('open_actu_url.url.port.mode', $config['url']['port']['mode']);
	
	$container->setParameter('open_actu_url.url.protocol.method', $config['url']['protocol']['method']);
	$container->setParameter('open_actu_url.url.protocol.timeout', $config['url']['protocol']['timeout']);
    }
}
