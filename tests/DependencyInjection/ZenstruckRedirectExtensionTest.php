<?php

/*
 * This file is part of the zenstruck/redirect-bundle package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\RedirectBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Zenstruck\RedirectBundle\DependencyInjection\ZenstruckRedirectExtension;
use Zenstruck\RedirectBundle\Tests\Fixture\Entity\DummyNotFound;
use Zenstruck\RedirectBundle\Tests\Fixture\Entity\DummyRedirect;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ZenstruckRedirectExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function remove_not_founds_with_no_classes_set(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The "remove_not_founds" option requires "redirect_class" and "not_found_class" to be set.');

        $this->load(['remove_not_founds' => true]);
        $this->compile();
    }

    /**
     * @test
     */
    public function remove_not_founds_with_just_not_found_class(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The "remove_not_founds" option requires "redirect_class" and "not_found_class" to be set.');

        $this->load(['remove_not_founds' => true, 'not_found_class' => DummyNotFound::class]);
        $this->compile();
    }

    /**
     * @test
     */
    public function remove_not_founds_with_just_redirect_class(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The "remove_not_founds" option requires "redirect_class" and "not_found_class" to be set.');

        $this->load(['remove_not_founds' => true, 'redirect_class' => DummyRedirect::class]);
        $this->compile();
    }

    /**
     * @test
     */
    public function redirect_class(): void
    {
        $this->load(['redirect_class' => DummyRedirect::class]);
        $this->compile();

        $this->assertContainerBuilderHasService('.zenstruck_redirect.track_redirect_handler');
        $this->assertContainerBuilderHasService('.zenstruck_redirect.redirect_listener');
        $this->assertContainerBuilderHasService('.zenstruck_redirect.redirect.form.type');
    }

    /**
     * @test
     */
    public function not_found_class(): void
    {
        $this->load(['not_found_class' => DummyNotFound::class]);
        $this->compile();

        $this->assertContainerBuilderHasService('.zenstruck_redirect.track_not_found_handler');
        $this->assertContainerBuilderHasService('.zenstruck_redirect.not_found_listener');
    }

    /**
     * @test
     */
    public function remove_not_found_subscriber_auto_enabled_if_both_classes_configured(): void
    {
        $this->load([
            'not_found_class' => DummyNotFound::class,
            'redirect_class' => DummyRedirect::class,
        ]);
        $this->compile();

        $this->assertContainerBuilderHasService('.zenstruck_redirect.remove_not_found_subscriber');
    }

    /**
     * @test
     */
    public function disable_remove_not_found_subscriber(): void
    {
        $this->load([
            'not_found_class' => DummyNotFound::class,
            'redirect_class' => DummyRedirect::class,
            'remove_not_founds' => false,
        ]);
        $this->compile();

        $this->assertContainerBuilderNotHasService('.zenstruck_redirect.remove_not_found_subscriber');
    }

    /**
     * @test
     * @dataProvider invalidClassProvider
     */
    public function invalid_redirect_class($class): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->load(['redirect_class' => $class]);
    }

    /**
     * @test
     * @dataProvider invalidClassProvider
     */
    public function invalid_not_found_class($class): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->load(['not_found_class' => $class]);
    }

    public static function invalidClassProvider(): iterable
    {
        yield ['Foo\Bar'];
        yield [static::class];
    }

    protected function getContainerExtensions(): array
    {
        return [new ZenstruckRedirectExtension()];
    }
}
