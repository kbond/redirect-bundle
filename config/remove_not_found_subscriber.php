<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Zenstruck\RedirectBundle\EventListener\Doctrine\RemoveNotFoundSubscriber;
use Zenstruck\RedirectBundle\Message\RemoveNotFoundsHandler;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('.zenstruck_redirect.remove_not_founds_handler', RemoveNotFoundsHandler::class)
            ->args([
                abstract_arg('not_found_class'),
                service('doctrine'),
            ])
            ->tag('messenger.message_handler')

        ->set('.zenstruck_redirect.remove_not_found_subscriber', RemoveNotFoundSubscriber::class)
            ->args([
                service_locator([
                    'bus' => service('.zenstruck_redirect.message_bus_bridge'),
                ]),
            ])
            ->tag('doctrine.event_listener', ['event' => 'postPersist'])
            ->tag('doctrine.event_listener', ['event' => 'postUpdate'])
    ;
};
