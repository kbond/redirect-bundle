<?php

/*
 * This file is part of the zenstruck/redirect-bundle package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\RedirectBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Zenstruck\RedirectBundle\Model\NotFound;
use Zenstruck\RedirectBundle\Model\Redirect;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @internal
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('zenstruck_redirect');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('redirect_class')
                    ->defaultNull()
                    ->validate()
                        ->ifTrue(fn($value) => !\is_subclass_of($value, Redirect::class))
                        ->thenInvalid(\sprintf('"redirect_class" must be an instance of "%s"', Redirect::class))
                    ->end()
                ->end()
                ->scalarNode('not_found_class')
                    ->defaultNull()
                    ->validate()
                        ->ifTrue(fn($value) => !\is_subclass_of($value, NotFound::class))
                        ->thenInvalid(\sprintf('"not_found_class" must be an instance of "%s"', NotFound::class))
                    ->end()
                ->end()
                ->booleanNode('remove_not_founds')
                    ->info('When enabled, when a redirect is updated or created, the NotFound entites with a matching path are removed.')
                    ->defaultNull()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
