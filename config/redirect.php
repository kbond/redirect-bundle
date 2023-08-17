<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Zenstruck\RedirectBundle\EventListener\RedirectOnNotFoundListener;
use Zenstruck\RedirectBundle\Form\Type\RedirectType;
use Zenstruck\RedirectBundle\Service\RedirectManager;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('.zenstruck_redirect.redirect_manager', RedirectManager::class)
            ->args([
                abstract_arg('redirect_class'),
                service('doctrine'),
            ])

        ->set('.zenstruck_redirect.redirect_listener', RedirectOnNotFoundListener::class)
            ->args([
                service_locator([
                    'manager' => service('.zenstruck_redirect.redirect_manager'),
                ]),
            ])
            ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'priority' => 10])

        ->set('.zenstruck_redirect.redirect.form.type', RedirectType::class)
            ->args([
                abstract_arg('redirect_class'),
            ])
            ->tag('form.type', ['alias' => 'zenstruck_redirect'])
    ;
};
