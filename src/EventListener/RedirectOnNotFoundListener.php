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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Zenstruck\RedirectBundle\Message\TrackRedirect;
use Zenstruck\RedirectBundle\Model\Redirect;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @internal
 */
final class RedirectOnNotFoundListener extends NotFoundListener
{
    /**
     * @param class-string<Redirect> $class
     */
    public function __construct(private ContainerInterface $container, private string $class)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$this->isNotFoundException($event)) {
            return;
        }

        $path = $event->getRequest()->getPathInfo();
        $redirect = $this->container->get('doctrine')->getRepository($this->class)->findOneBy(['source' => $path]);

        if (!$redirect) {
            return;
        }

        $this->container->get('bus')->dispatch(new TrackRedirect($path, new \DateTimeImmutable('now')));

        /** @var Redirect $redirect */
        $event->setResponse(new RedirectResponse(
            $redirect->getDestination(),
            $redirect->isPermanent() ? 301 : 302
        ));
    }
}
