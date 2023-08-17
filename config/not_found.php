<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Zenstruck\RedirectBundle\EventListener\TrackNotFoundListener;
use Zenstruck\RedirectBundle\Message\TrackNotFoundHandler;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('.zenstruck_redirect.track_not_found_handler', TrackNotFoundHandler::class)
            ->args([
                abstract_arg('not_found_class'),
                service('doctrine'),
            ])
            ->tag('messenger.message_handler')

        ->set('.zenstruck_redirect.not_found_listener', TrackNotFoundListener::class)
            ->args([
                service_locator([
                    'bus' => service('.zenstruck_redirect.message_bus_bridge'),
                ]),
            ])
            ->tag('kernel.event_listener', ['event' => 'kernel.exception'])
    ;
};
