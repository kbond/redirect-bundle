<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Zenstruck\RedirectBundle\EventListener\CreateNotFoundListener;
use Zenstruck\RedirectBundle\Service\NotFoundManager;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('.zenstruck_redirect.not_found_manager', NotFoundManager::class)
            ->args([
                abstract_arg('not_found_class'),
                service('doctrine'),
            ])

        ->set('.zenstruck_redirect.not_found_listener', CreateNotFoundListener::class)
            ->args([
                service_locator([
                    'manager' => service('.zenstruck_redirect.not_found_manager'),
                ]),
            ])
            ->tag('kernel.event_listener', ['event' => 'kernel.exception'])
    ;
};
