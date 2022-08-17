# Quality Assurance - PHP

This package provides a set of Quality Assurance tools and configuration files
for PHP projects and packages (libraries).

## Requirements

* [Composer](https://getcomposer.org)

## Installation

Add the `grumphp` entry to the `extra` section of your `composer.json`.

```json
"grumphp": {
    "config-default-path": "vendor/district09/qa-php/configs/grumphp.yml"
}
```

Add the qa-php package as dev requirement:

```bash
composer require --dev district09/qa-php:^1.0
```

## Configuration

### General

If required you can extend or override the provided configuration file of a
task. Simply create the matching configuration file in the root of your project.

For example, to override the provided `phpcs.xml` file you can either create a
`phpcs.xml` or `phpcs.local.xml` file.

Note that the `.local.` files should only be used for changes that shouldn't be
committed. Exclude them in `.gitignore`:

```gitignore
/*.local.*
```

Yaml and Neon files will extend (merged into) the provided configuration file by
default. Create a `.env` or `.env.local` file and add following contents to
change this behaviour:

```
[FILENAME]_SKIP_[TYPE]=1
```

Wherein `[FILENAME]` matches the configuration filename and `[TYPE]` is either:

- `LOCAL` to skip for example your `phpstan.local.neon` file.
- `PROJECT` to skip for example your `phpstan.neon` file.
- `GLOBAL` to skip for example the by qa-php provided `phpstan.neon` file.

Other file types cannot be merged and will just override all other less specific
files.

### PHPStan in deprecations only mode

Create a `phpstan.neon` file and add following contents to ignore everything
except deprecations:

```
parameters:
  customRulesetUsed: true
  ignoreErrors:
    - '#^(?:(?!deprecated).)*$#'
```

### Ignore automatically created config files

Some GrumPHP tasks require a config file. These are automatically created, from
the examples within vendor/qa-php/config or by the project specific files within
your project or package root directory. The generated files are also stored in
the same project/package root. You can recognize these files by the `.qa-php.`
suffix.

**These files should not be committed!** Add them to the `.gitignore` file:

```gitignore
/*.qa-php.*
```

### Ignore PHPUnit build files

When the PHPUnit task runs, coverage report files are stored into the `build`
directory located in the root of your project. Add this file to the `.gitignore`
file:

```gitignore
/build
/.phpunit.result.cache
```

### Run PHPUnit locally without coverage

Running PHPUnit with coverage report is time consuming. You can locally speed up
PHPUnit by copying the generated `phpunit.qa-php.xml` file to
`phpunit.local.xml` and remove the `<coverage>` section from it.

## Run GrumPHP

GrumPHP will automatically run all tasks on the changed code on git commit and
push.

You can run all tasks at once:

```bash
vendor/bin/grumphp
```

Or you can run one or more specific tasks manually by running:

```bash
vendor/bin/grumphp --tasks phpcs,phpmd
vendor/bin/grumphp --tasks phpunit
```

## PHPStorm

PHPStorm requires config files for PHP_CodeSniffer, PHP Mess Detector & PhpUnit.
Run the grumphp command at least once (successfully) to generate these files.

The files will be created as:

- `phpcs.qa-php.xml` : PHP_CodeSniffer config file.
- `phpmd.qa-php.xml` : PHP Mess Detector config file.
- `phpunit.qa-php.xml` : PHPUnit config file.

Configure the paths to these files in PHPStorm:

* Editor > Inspections > PHP > Quality tools > PHP Mess Detector validation
  Add `phpmd.qa-php.xml` to the "Custom rulesets".
* Editor > Inspections > PHP > Quality tools > PHP_CodeSniffer validation
  Set "Coding Standard" to "Custom" and set the path to `phpcs.qa-php.xml`.
* Languages & Frameworks > PHP > Test Frameworks > Test Runner
  Set "Default configuration file" to `phpunit.qa-php.xml`.

### PHP compatibility

In order to check php compatibility you can use the phpcs `PHPCompatibility` sniff:

```bash
php vendor/bin/phpcs -p --ignore="*/vendor/*" --extensions=php,inc,module,install,theme --runtime-set testVersion 8.1 --standard=PHPCompatibility ./web/modules/contrib
```
