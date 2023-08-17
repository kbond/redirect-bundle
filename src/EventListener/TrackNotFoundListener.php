<?php

/*
 * This file is part of the zenstruck/redirect-bundle package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\RedirectBundle\EventListener;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Zenstruck\RedirectBundle\Message\TrackNotFound;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @internal
 */
final class TrackNotFoundListener extends NotFoundListener
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$this->isNotFoundException($event)) {
            return;
        }

        $request = $event->getRequest();

        $this->container->get('bus')->dispatch(new TrackNotFound(
            $request->getPathInfo(),
            $request->getUri(),
            $request->server->get('HTTP_REFERER'),
            new \DateTimeImmutable('now'),
        ));
    }
}
