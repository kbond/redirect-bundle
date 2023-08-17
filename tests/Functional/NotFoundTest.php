<?php

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
class NotFoundTest extends KernelTestCase
{
    use ResetDatabase, Factories, HasBrowser;

    /**
     * @test
     */
    public function not_found_created()
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
