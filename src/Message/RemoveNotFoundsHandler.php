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
 */
final class RemoveNotFoundsHandler
{
    /**
     * @param class-string<NotFound> $class
     */
    public function __construct(private string $class, private ManagerRegistry $doctrine)
    {
    }

    public function __invoke(RemoveNotFounds $message): void
    {
        $om = $this->doctrine->getManagerForClass($this->class) ?? throw new \LogicException(\sprintf('No manager found for class "%s".', $this->class));

        $notFounds = $om->getRepository($this->class)->findBy(['path' => $message->source]);

        foreach ($notFounds as $notFound) {
            $om->remove($notFound);
        }

        $om->flush();
    }
}
