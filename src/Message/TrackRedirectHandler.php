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

use Doctrine\Persistence\ManagerRegistry;
use Zenstruck\RedirectBundle\Model\Redirect;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @internal
 */
final class TrackRedirectHandler
{
    /**
     * @param class-string<Redirect> $class
     */
    public function __construct(private string $class, private ManagerRegistry $doctrine)
    {
    }

    public function __invoke(TrackRedirect $message): void
    {
        $om = $this->doctrine->getManagerForClass($this->class) ?? throw new \LogicException(\sprintf('No manager found for class "%s".', $this->class));

        if (!$redirect = $om->getRepository($this->class)->findOneBy(['source' => $message->source])) {
            return;
        }

        /** @var Redirect $redirect */
        $redirect->increaseCount();
        $redirect->updateLastAccessed($message->timestamp);
        $om->flush();
    }
}
