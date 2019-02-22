<?php

namespace Bilyiv\RequestDataBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * The DEFAULT_PREFIX defines the default request data namespace prefix.
     */
    public const DEFAULT_PREFIX = 'App\\RequestData';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('request_data');
        $rootNode = \method_exists(TreeBuilder::class, 'getRootNode') ? $treeBuilder->getRootNode() : $treeBuilder->root('request_data');

        $rootNode->children()
            ->scalarNode('prefix')
            ->defaultValue(self::DEFAULT_PREFIX)
            ->end();

        return $treeBuilder;
    }
}
