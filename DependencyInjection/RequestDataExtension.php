<?php

namespace Bilyiv\RequestDataBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class RequestDataExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('request_data.normalizer.request_data');
        $definition->replaceArgument(3, $config['prefix']);

        $definition = $container->getDefinition('request_data.controller_listener');
        $definition->replaceArgument(3, $config['prefix']);
    }
}
