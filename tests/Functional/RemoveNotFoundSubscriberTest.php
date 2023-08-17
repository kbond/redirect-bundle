<?php

namespace Zenstruck\RedirectBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use Zenstruck\RedirectBundle\Tests\Fixture\Entity\DummyNotFound;
use Zenstruck\RedirectBundle\Tests\Fixture\Entity\DummyRedirect;
use function Zenstruck\Foundry\create;
use function Zenstruck\Foundry\repository;

class RemoveNotFoundSubscriberTest extends KernelTestCase
{
    use ResetDatabase, Factories;

    protected function setUp(): void
    {
        create(DummyNotFound::class, ['path' => '/foo', 'fullUrl' => 'http://example.com/foo']);
        create(DummyNotFound::class, ['path' => '/foo', 'fullUrl' => 'http://example.com/foo?bar=foo']);
        create(DummyNotFound::class, ['path' => '/bar', 'fullUrl' => 'http://example.com/bar']);
    }

    /**
     * @test
     */
    public function delete_not_found_on_create_redirect(): void
    {
        repository(DummyNotFound::class)->assert()->count(3);

        create(DummyRedirect::class, ['source' => '/foo', 'destination' => '/bar']);

        repository(DummyNotFound::class)->assert()->count(1);
    }

    /**
     * @test
     */
    public function delete_not_found_on_update_redirect()
    {
        $redirect = create(DummyRedirect::class, ['source' => '/original', 'destination' => '/bar']);

        repository(DummyNotFound::class)->assert()->count(3);

        $redirect->setSource('/foo');
        $redirect->save();

        repository(DummyNotFound::class)->assert()->count(1);
    }
}
