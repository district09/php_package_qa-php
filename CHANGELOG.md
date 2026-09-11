# Changelog

All Notable changes to the **Quality Assurance - PHP** package.

## [3.0.0]

### Changed

- Require PHP 8.3 or later.
- Support PHPUnit 11.5 and 12.5 only.
- Refresh the bundled QA tool dependencies and replace Travis CI with GitHub
  Actions. PHPCompatibility 9 requires PHPCS 3, which in turn limits Slevomat
  to 8.22. PHPCPD 8 and 9 are supported so Composer can select the compatible
  `phpunit/php-timer` dependency for PHPUnit 11 or 12.

### Fixed

- Generate a PHPUnit-version-specific `phpunit.qa-php.xml` configuration with
  a current XML schema and code-coverage filter.

## [2.2.1]

### Added

- Add phpstan-deprecation-rules 2.0 support.

## [2.2.0]

### Added

- Add phpunit 10, 11 and 12 support.
- Add phpstan 2.x support.
- Add systemsdk/phpcpd.

### Updated

- Updated grumphp config.

### Removed

- Removed sebiastian/phpcpd.

## [2.1.0]

### Added

- Add Unused use statement rule to PHPCS checks.
- 'Revert' is now a valid start of a commit message.

## [2.0.0]

### Added

- Add support for .dist files in the project.

### Changed

- Changed minimal PHP version to 8.1.

### Fixed

- Fix merging configs when both have same string keys.

### Updated

- Update GrumPHP to 2.x.
- Update security-checker package to 2.x.

## [1.1.0]

### Added

- Add support for release/ branches.
- Add support for hotfix/ branches.
- Add support for Merge commit messages.
- Add support for RELEASE commit messages.

## [1.0.1]

### Added

- Add php compatibility checker.

## [1.0.0]

### Added

Initial setup of the qa-php package:

- Default config files and checks for a PHP project (package).
- Add support for PHP 7.3+
- Add support for PHP 8.0+

[3.0.0]: https://github.com/district09/php_package_qa-php/compare/2.2.1...3.0.0
[2.2.1]: https://github.com/district09/php_package_qa-php/compare/2.2.0...2.2.1
[2.2.0]: https://github.com/district09/php_package_qa-php/compare/2.1.0...2.2.0
[2.1.0]: https://github.com/district09/php_package_qa-php/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/district09/php_package_qa-php/compare/1.1.0...2.0.0
[1.1.0]: https://github.com/district09/php_package_qa-php/compare/1.0.1...1.1.0
[1.0.1]: https://github.com/district09/php_package_qa-php/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/district09/php_package_qa-php/releases/tag/1.0.0
[Unreleased]: https://github.com/district09/php_package_qa-php/compare/main...develop
