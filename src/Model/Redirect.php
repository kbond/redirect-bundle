<?php

/*
 * This file is part of the zenstruck/redirect-bundle package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\RedirectBundle\Model;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class Redirect
{
    protected ?string $source;

    protected ?string $destination;

    protected bool $permanent;

    protected int $count = 0;

    protected ?\DateTimeImmutable $lastAccessed = null;

    public function __construct(?string $source, ?string $destination, bool $permanent = true)
    {
        $this->setSource($source);
        $this->setDestination($destination);
        $this->setPermanent($permanent);
    }

    public static function createFromNotFound(NotFound $notFound, string $destination, bool $permanent = true): static
    {
        return new static($notFound->getPath(), $destination, $permanent);
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): void
    {
        $source = \trim($source);
        $source = !empty($source) ? $source : null;

        if (null !== $source) {
            $source = $this->createAbsoluteUri($source);
        }

        $this->source = $source;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): void
    {
        $destination = \trim($destination);
        $destination = !empty($destination) ? $destination : null;

        if (null !== $destination && null === \parse_url($destination, \PHP_URL_SCHEME)) {
            $destination = $this->createAbsoluteUri($destination, true);
        }

        $this->destination = $destination;
    }

    public function isPermanent(): bool
    {
        return $this->permanent;
    }

    public function setPermanent(bool $permanent): void
    {
        $this->permanent = $permanent;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function increaseCount(int $amount = 1): void
    {
        $this->count += $amount;
    }

    public function getLastAccessed(): ?\DateTimeImmutable
    {
        return $this->lastAccessed;
    }

    public function updateLastAccessed(?\DateTimeImmutable $time = null): void
    {
        $this->lastAccessed = $time ?? new \DateTimeImmutable('now');
    }

    protected function createAbsoluteUri(string $path, bool $allowQueryString = false): string
    {
        $parse_url = \parse_url($path, \PHP_URL_PATH);

        if (null != $parse_url) {
            $value = '/'.\ltrim($parse_url, '/');
        } else {
            $value = '/';
        }

        if ($allowQueryString && $query = \parse_url($path, \PHP_URL_QUERY)) {
            $value .= '?'.$query;
        }

        return $value;
    }
}
