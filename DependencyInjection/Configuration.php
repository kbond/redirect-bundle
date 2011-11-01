<?php

namespace Zenstruck\Bundle\RedirectBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('zenstruck_redirect');

        $rootNode
            ->children()
                ->scalarNode('redirect_class')->isRequired()->end()
                ->scalarNode('template')->defaultValue('ZenstruckRedirectBundle:Redirect:redirect.html.twig')->end()
                ->scalarNode('not_found_url')->defaultValue('/not_found')->end()
                ->booleanNode('log_statistics')->defaultFalse()->end()
                ->booleanNode('log_404_errors')->defaultFalse()->end()
            ->end()
        ;

        return $treeBuilder;
    }

}
