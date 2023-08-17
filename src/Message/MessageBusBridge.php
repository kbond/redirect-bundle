<?php

/*
 * This file is part of the zenstruck/redirect-bundle package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\RedirectBundle\Message;

use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @internal
 */
final class MessageBusBridge
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function dispatch(object $message): void
    {
        if ($this->container->has('bus')) {
            $this->container->get('bus')->dispatch($message, [new DispatchAfterCurrentBusStamp()]);

            return;
        }

        $this->container->get($message::class)($message);
    }
}
