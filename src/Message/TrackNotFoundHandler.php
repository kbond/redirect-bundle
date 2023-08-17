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
use Zenstruck\RedirectBundle\Model\NotFound;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @internal
 */
final class TrackNotFoundHandler
{
    /**
     * @param class-string<NotFound> $class
     */
    public function __construct(private string $class, private ManagerRegistry $doctrine)
    {
    }

    public function __invoke(TrackNotFound $message): void
    {
        $notFound = new $this->class(
            $message->path,
            $message->fullUrl,
            $message->referrer,
            $message->timestamp,
        );

        $om = $this->doctrine->getManagerForClass($this->class) ?? throw new \LogicException(\sprintf('No manager found for class "%s".', $this->class));

        $om->persist($notFound);
        $om->flush();
    }
}
