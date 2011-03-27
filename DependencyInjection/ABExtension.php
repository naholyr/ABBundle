<?php

namespace AB\ABBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class ABExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->process($configuration->getConfigTree(), $configs);

        // Validate persistence driver
        $db_driver = $config['db_driver'];
        if (!in_array($db_driver, array('odm', 'orm', 'custom'))) {
            throw new \InvalidArgumentException('Invalid DB driver for ABBundle, check "ab.db_driver" configuration.');
        }

        // Config = YAML files (yep, I hate XML and it won't change now :P)
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        // Load db_driver's configuration
        if ($db_driver != 'custom') {
            $loader->load(sprintf('ab-db_driver-%s.yml', $config['db_driver']));
        }

        // Eventually override some parameters from driver's config
        if (!empty($config['model_repository'])) {
            $container->setParameter('ab.model_repository', $config['model_repository']);
        }
        if (!empty($config['model_class'])) {
            $container->setParameter('ab.model_class', $config['model_class']);
        }

        // Alias the persistence service (declared as parameter by driver)
        $container->setAlias('ab.persistence_service', $container->getParameter('ab.persistence_service'));

        // Load service definition, based on previous parameters
        $loader->load('service.yml');
    }

}
