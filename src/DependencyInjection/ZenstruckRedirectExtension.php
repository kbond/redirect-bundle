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

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @internal
 */
final class ZenstruckRedirectExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void // @phpstan-ignore-line
    {
        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));

        if (null !== $mergedConfig['redirect_class']) {
            $loader->load('redirect.php');
            $container->getDefinition('.zenstruck_redirect.redirect_manager')
                ->setArgument(0, $mergedConfig['redirect_class'])
            ;
        }

        if (null !== $mergedConfig['not_found_class']) {
            $loader->load('not_found.php');
            $container->getDefinition('.zenstruck_redirect.not_found_manager')
                ->setArgument(0, $mergedConfig['not_found_class'])
            ;
        }

        if (null === $mergedConfig['remove_not_founds'] && $mergedConfig['redirect_class'] && $mergedConfig['not_found_class']) {
            $mergedConfig['remove_not_founds'] = true;
        }

        if (!$mergedConfig['remove_not_founds']) {
            return;
        }

        if (null === $mergedConfig['not_found_class'] || null === $mergedConfig['redirect_class']) {
            throw new InvalidConfigurationException('The "remove_not_founds" option requires "redirect_class" and "not_found_class" to be set.');
        }

        $loader->load('remove_not_found_subscriber.php');
    }
}
