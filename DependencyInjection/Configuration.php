<?php

namespace AB\ABBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration
{

    public function getConfigTree()
    {
        $builder = new TreeBuilder();
        $root = $builder->root('ab');
        $root->children()->scalarNode('db_driver')->isRequired()->cannotBeEmpty()->end();

        return $builder->buildTree();
    }

}