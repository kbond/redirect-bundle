<?php

/*
 * This file is part of the zenstruck/redirect-bundle package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\RedirectBundle\EventListener\Doctrine;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Container\ContainerInterface;
use Zenstruck\RedirectBundle\Model\Redirect;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @internal
 */
final class RemoveNotFoundSubscriber
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->remoteNotFoundForRedirect($args);
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->remoteNotFoundForRedirect($args);
    }

    private function remoteNotFoundForRedirect(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Redirect) {
            return;
        }

        $this->container->get('manager')->removeForRedirect($entity);
    }
}
