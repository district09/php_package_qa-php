<?php

declare(strict_types=1);

namespace District09\QA\PHP\GrumPHP;

use Composer\InstalledVersions;
use LogicException;

/**
 * Resolves the bundled configuration file for the installed PHPUnit major.
 *
 * @internal
 */
final class PhpunitConfigResolver
{
    /**
     * Gets the PHPUnit major installed in the consumer project.
     */
    public static function installedMajorVersion(): int
    {
        $version = InstalledVersions::getVersion('phpunit/phpunit');
        if ($version === null) {
            throw new LogicException('Unable to determine the installed PHPUnit version.');
        }

        if (!preg_match('/^(?:v)?(?<major>\d+)/', $version, $matches)) {
            throw new LogicException(sprintf(
                'Unable to determine the PHPUnit major version from "%s".',
                $version,
            ));
        }

        return (int) $matches['major'];
    }

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

    /**
     * Gets the bundled configuration for the consumer project's PHPUnit.
     */
    public static function resolveInstalled(): string
    {
        return self::resolve(self::installedMajorVersion());
    }
}
