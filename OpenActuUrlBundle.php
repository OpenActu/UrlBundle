<?php

namespace OpenActu\UrlBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use OpenActu\UrlBundle\DependencyInjection\OpenActuUrlExtension;
use Doctrine\DBAL\Types\Type;
class OpenActuUrlBundle extends Bundle
{
	public function getContainerExtension()
	{
		return new OpenActuUrlExtension();
	}

        public function boot()
	{
		if(!Type::hasType('enumUrlAnalyzerStatus'))
		{
			Type::addType('enumUrlAnalyzerStatus', 'OpenActu\UrlBundle\DBAL\EnumUrlAnalyzerStatusType');
			Type::addType('enumUrlAnalyzerPortMode', 'OpenActu\UrlBundle\DBAL\EnumUrlAnalyzerPortModeType');
		}	
	}

	/**
         * {@inheritdoc}
         */
    	public function build(ContainerBuilder $container)
    	{
	       parent::build($container);
	}
}
