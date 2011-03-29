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

        // Service class names
        foreach ($configuration->getDefaultServiceClasses() as $name => $value) {
            if (!$container->hasParameter($name)) {
                $container->setParameter($name, $value);
            }
        }

        // Config = YAML files (yep, I hate XML and it won't change now :P)
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        // Load db_driver's configuration
        if ($db_driver != 'custom') {
            $loader->load(sprintf('ab-db_driver-%s.yml', $config['db_driver']));
        }

        // Eventually override some parameters from driver's config
        foreach ($configuration->getParameterNames() as $name) {
            if (isset($config[$name]) && !empty($config[$name])) {
                $container->setParameter('ab.'.$name, $config[$name]);
            }
        }

        // Persistence service
        if ($container->hasParameter('ab.persistence_service')) {
            $container->setAlias('ab.persistence_service', $container->getParameter('ab.persistence_service'));
        }
        if ($db_driver != 'custom') {
            $loader->load('manager.yml');
        }

        // Session service
        $loader->load('session.yml');

        // A/B service
        $loader->load('service.yml');
        
        // Twig extension
        if (is_null($config['load_twig_extension']) || $config['load_twig_extension']) {
            $loader->load('twig.yml');
        }
    }

}
