<?php

declare(strict_types=1);

namespace District09\QA\PHP\Tests\GrumPHP;

use District09\QA\PHP\GrumPHP\PhpunitConfigResolver;
use LogicException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(PhpunitConfigResolver::class)]
final class PhpunitConfigResolverTest extends TestCase
{
    /**
     * Tests resolving the bundled configuration for supported PHPUnit versions.
     */
    #[DataProvider('supportedPhpunitVersions')]
    public function testResolve(int $majorVersion, string $filename): void
    {
        self::assertSame($filename, PhpunitConfigResolver::resolve($majorVersion));
    }

    /**
     * Provides supported PHPUnit major versions.
     *
     * @return array<string, array{int, string}>
     */
    public static function supportedPhpunitVersions(): array
    {
        return [
            'PHPUnit 11' => [11, 'phpunit-11.xml'],
            'PHPUnit 12' => [12, 'phpunit.xml'],
        ];
    }

    /**
     * Tests resolving an unsupported PHPUnit version.
     */
    public function testResolveRejectsUnsupportedPhpunitVersion(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Unsupported PHPUnit major version 13.');

        PhpunitConfigResolver::resolve(13);
    }
}
