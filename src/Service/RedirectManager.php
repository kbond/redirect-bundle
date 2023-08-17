<?php

/*
 * This file is part of the zenstruck/redirect-bundle package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\RedirectBundle\Service;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\RedirectBundle\Model\Redirect;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RedirectManager
{
    /**
     * @param class-string<Redirect> $class
     */
    public function __construct(private string $class, private ManagerRegistry $doctrine)
    {
    }

    public function findAndUpdate(string $source): ?Redirect
    {
        $om = $this->om();

        if (!$redirect = $om->getRepository($this->class)->findOneBy(['source' => $source])) {
            return null;
        }

        /** @var Redirect|null $redirect */
        $redirect->increaseCount();
        $redirect->updateLastAccessed();
        $om->flush();

        return $redirect;
    }

    private function om(): ObjectManager
    {
        return $this->doctrine->getManagerForClass($this->class) ?? throw new \LogicException(\sprintf('No manager found for class "%s".', $this->class));
    }
}
