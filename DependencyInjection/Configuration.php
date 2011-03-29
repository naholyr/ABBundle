<?php

namespace AB\ABBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration
{

    public function getConfigTree()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('ab');

        $root->children()
        ->scalarNode('db_driver')->isRequired()->cannotBeEmpty()->end()
        ->scalarNode('model_class')->defaultNull()->end()
        ->scalarNode('model_repository')->defaultNull()->end()
        ->scalarNode('load_twig_extension')->defaultValue(true)->treatNullLike(true)->end();

        return $builder->buildTree();
    }

    public function getDefaultServiceClasses()
    {
        return array(
            'ab.manager_class' => 'AB\\ABBundle\\Base\\DoctrineManager',
            'ab.session_class' => 'AB\\ABBundle\\Base\\HttpSession',
            'ab.service_class' => 'AB\\ABBundle\\Service\\Service',
        );
    }

    public function getParameterNames()
    {
        return array('model_class', 'model_repository') + array_keys($this->getDefaultServiceClasses());
    }

}
