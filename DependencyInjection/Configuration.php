<?php
namespace Kwc\ContentApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements \Symfony\Component\Config\Definition\ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kwc_contentapi');
        $rootNode
            ->children()
                ->arrayNode('export_components')
                    ->prototype('scalar')->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
