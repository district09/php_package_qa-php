<?php

declare(strict_types=1);

namespace District09\QA\PHP\GrumPHP;

use LogicException;

/**
 * Resolves the bundled configuration file for the installed PHPUnit major.
 *
 * @internal
 */
final class PhpunitConfigResolver
{
    /**
     * Gets the bundled PHPUnit configuration filename.
     */
    public static function resolve(int $majorVersion): string
    {
        return match ($majorVersion) {
            11 => 'phpunit-11.xml',
            12 => 'phpunit.xml',
            default => throw new LogicException(sprintf(
                'Unsupported PHPUnit major version %d. Supported versions are 11 and 12.',
                $majorVersion,
            )),
        };
    }
}
