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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('request_data');

        $rootNode->children()
            ->scalarNode('prefix')
            ->end();

        return $treeBuilder;
    }
}
