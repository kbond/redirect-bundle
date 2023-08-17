<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Zenstruck\RedirectBundle\EventListener\RedirectOnNotFoundListener;
use Zenstruck\RedirectBundle\Form\Type\RedirectType;
use Zenstruck\RedirectBundle\Message\TrackRedirectHandler;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('.zenstruck_redirect.track_redirect_handler', TrackRedirectHandler::class)
            ->args([
                abstract_arg('redirect_class'),
                service('doctrine'),
            ])
            ->tag('messenger.message_handler')

        ->set('.zenstruck_redirect.redirect_listener', RedirectOnNotFoundListener::class)
            ->args([
                service_locator([
                    'doctrine' => service('doctrine'),
                    'bus' => service('.zenstruck_redirect.message_bus_bridge'),
                ]),
                abstract_arg('redirect_class'),
            ])
            ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'priority' => 10])

        ->set('.zenstruck_redirect.redirect.form.type', RedirectType::class)
            ->args([
                abstract_arg('redirect_class'),
            ])
            ->tag('form.type', ['alias' => 'zenstruck_redirect'])
    ;
};
