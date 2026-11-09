# Helpers compatibility fork: PHP 8.5 / Laravel 13

The `laravel-13-php-85` branch retains the Composer package name, namespace and tools of the existing Processor admin extension.

- Updates Symfony Console output signatures.
- Adapts migration scaffold signatures, class placeholders, duplicate detection and post-create hooks to Laravel 13.
- Keeps `laravel/framework: ^13.0` in `require-dev` for the tested development environment. Runtime Laravel is supplied by the mandatory `encore/laravel-admin` dependency.
- Regression tests under `tests/Compatibility` do not start a server or execute generated migrations.

Verified on PHP 8.5.12 / Laravel 13.39 / PHPUnit 12: 2 tests, 7 assertions. Other Laravel versions are not verified by this migration. Actual terminal/admin integration and migration execution still require an isolated environment.

Upstream history, Composer identity, namespace and license are retained.

Related Processor migration: https://github.com/MineFinanceGroup/processor/pull/559.
