<?php

namespace Zenstruck\RedirectBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use Zenstruck\RedirectBundle\Tests\Fixture\Entity\DummyRedirect;

use function Zenstruck\Foundry\create;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RedirectTest extends KernelTestCase
{
    use ResetDatabase, Factories, HasBrowser;

    /**
     * @test
     */
    public function redirect_301()
    {
        $redirect = create(DummyRedirect::class, [
            'source' => '/301-redirect',
            'destination' => 'https://symfony.com',
        ]);

        $this->assertSame(0, $redirect->getCount());
        $this->assertNull($redirect->getLastAccessed());

        $browser = $this->browser()
            ->interceptRedirects()
            ->visit('/301-redirect')
            ->assertStatus(301)
            ->assertRedirectedTo('https://symfony.com')
        ;

        $this->assertSame(1, $redirect->getCount());
        $this->assertNotNull($redirect->getLastAccessed());

        $browser
            ->visit('/301-redirect?foo=bar')
            ->assertRedirectedTo('https://symfony.com')
        ;

        $this->assertSame(2, $redirect->getCount());
        $this->assertNotNull($redirect->getLastAccessed());
    }

    /**
     * @test
     */
    public function test302_redirect()
    {
        $redirect = create(DummyRedirect::class, [
            'source' => '/302-redirect',
            'destination' => 'https://symfony.com',
            'permanent' => false,
        ]);

        $this->assertSame(0, $redirect->getCount());
        $this->assertNull($redirect->getLastAccessed());

        $this->browser()
            ->interceptRedirects()
            ->visit('/302-redirect')
            ->assertStatus(302)
            ->assertRedirectedTo('https://symfony.com')
        ;

        $this->assertSame(1, $redirect->getCount());
        $this->assertNotNull($redirect->getLastAccessed());
    }
}
