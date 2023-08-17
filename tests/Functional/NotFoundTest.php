<?php

/*
 * This file is part of the zenstruck/redirect-bundle package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\RedirectBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use Zenstruck\RedirectBundle\Tests\Fixture\Entity\DummyNotFound;

use function Zenstruck\Foundry\repository;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class NotFoundTest extends KernelTestCase
{
    use Factories, HasBrowser, ResetDatabase;

    /**
     * @test
     */
    public function not_found_created(): void
    {
        $repo = repository(DummyNotFound::class);

        $repo->assert()->count(0);

        $this->browser()->visit('/not-found?foo=bar');

        $repo->assert()->count(1);

        $notFound = $repo->first();

        $this->assertSame('/not-found', $notFound->getPath());
        $this->assertSame('http://localhost/not-found?foo=bar', $notFound->getFullUrl());
        $this->assertNull($notFound->getReferer());
    }
}
