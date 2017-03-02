<?php

namespace OpenActu\UrlBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use OpenActu\UrlBundle\DependencyInjection\OpenActuUrlExtension;
class OpenActuUrlBundle extends Bundle
{
	public function getContainerExtension()
	{
		return new OpenActuUrlExtension();
	}

	/**
         * {@inheritdoc}
         */
    	public function build(ContainerBuilder $container)
    	{
 	       parent::build($container);
	}
}
