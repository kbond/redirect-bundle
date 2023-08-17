<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Zenstruck\RedirectBundle\EventListener\Doctrine\RemoveNotFoundSubscriber;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('.zenstruck_redirect.remove_not_found_subscriber', RemoveNotFoundSubscriber::class)
            ->args([
                service_locator([
                    'manager' => service('.zenstruck_redirect.not_found_manager'),
                ]),
            ])
            ->tag('doctrine.event_listener', ['event' => 'postPersist'])
            ->tag('doctrine.event_listener', ['event' => 'postUpdate'])
    ;
};
