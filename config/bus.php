<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Zenstruck\RedirectBundle\Message\MessageBusBridge;
use Zenstruck\RedirectBundle\Message\RemoveNotFounds;
use Zenstruck\RedirectBundle\Message\TrackNotFound;
use Zenstruck\RedirectBundle\Message\TrackRedirect;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('.zenstruck_redirect.message_bus_bridge', MessageBusBridge::class)
            ->args([
                service_locator([
                    'bus' => service('messenger.bus.default')->ignoreOnInvalid(),
                    TrackNotFound::class => service('.zenstruck_redirect.track_not_found_handler')->ignoreOnInvalid(),
                    TrackRedirect::class => service('.zenstruck_redirect.track_redirect_handler')->ignoreOnInvalid(),
                    RemoveNotFounds::class => service('.zenstruck_redirect.remove_not_founds_handler')->ignoreOnInvalid(),
                ])
            ])
    ;
};
