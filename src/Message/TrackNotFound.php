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

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class TrackNotFound
{
    public function __construct(
        /** @readonly */
        public string $path,

        /** @readonly */
        public string $fullUrl,

        /** @readonly */
        public ?string $referrer,

        /** @readonly */
        public \DateTimeImmutable $timestamp,
    ) {
    }
}
