<?php

namespace Bilyiv\RequestDataBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public const DEFAULT_PREFIX = 'App\\RequestData';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('request_data');

        $rootNode->children()
            ->scalarNode('prefix')
            ->defaultValue(self::DEFAULT_PREFIX)
            ->end();

        return $treeBuilder;
    }
}