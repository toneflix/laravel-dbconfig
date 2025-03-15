# Changelog

All notable changes to `laravel-dbconfig` will be documented in this file

## 1.0.11 - 2025-02-09

### What's Changed

* build(deps): Bump codecov/codecov-action from 5.1.2 to 5.3.1 by @dependabot in https://github.com/toneflix/laravel-dbconfig/pull/2
* build(deps): Bump dependabot/fetch-metadata from 2.2.0 to 2.3.0 by @dependabot in https://github.com/toneflix/laravel-dbconfig/pull/3
* build(deps): Bump aglipanci/laravel-pint-action from 2.4 to 2.5 by @dependabot in https://github.com/toneflix/laravel-dbconfig/pull/4
* fix: Fix migration bugs.

**Full Changelog**: https://github.com/toneflix/laravel-dbconfig/compare/1.0.10...1.0.11

## 1.0.10 - 2025-01-25

- [feat: Use upsert method for the ConfigurationSeede](https://github.com/toneflix/laravel-dbconfig/commit/fc150633fac097e143003e85383db501ff89f8ed)
- [feat: Allow secret data to be displayed when running the dbconfig:show artisan command.](https://github.com/toneflix/laravel-dbconfig/commit/c104822126cb96b416b429506766ae0fb10d5c49)

**Full Changelog**: https://github.com/toneflix/laravel-dbconfig/compare/1.0.7...1.0.10

## 1.0.9 - 2025-01-23

[fix: Fix validation for boolean values when updating config with dbconfig:set](https://github.com/toneflix/laravel-dbconfig/commit/7728715d982496af7b638b435c46d06fe30db020)

**Full Changelog**: https://github.com/toneflix/laravel-dbconfig/compare/1.0.8...1.0.9

## 1.0.8 - 2025-01-23

- [feat: Covert Configuration model casts to property instead of method to support older versions of laravel.](https://github.com/toneflix/laravel-dbconfig/commit/efb6db3995b6f916e6bea3d03d7926b2d94db086)
- [feat: Improve and optimize configuration loading and caching.](https://github.com/toneflix/laravel-dbconfig/commit/1fac3b1a2fe88da6342348622b39eb48b79a00f6)
- [fix: Fix bug causing float and boolean types to return integers.](https://github.com/toneflix/laravel-dbconfig/commit/d106f8bba1942a468c809281ebade48014ce8dcb)
- [feat: Add validation for config type when creating with dbconfig:create.](https://github.com/toneflix/laravel-dbconfig/commit/a9cf4affa3594bb8187dbd37942600cca3ea45e4)
- [chore: Set deault cache config to false.](https://github.com/toneflix/laravel-dbconfig/commit/c769d9895d89d294a1ea484f1bc373febd3ddc77)

**Full Changelog**: https://github.com/toneflix/laravel-dbconfig/compare/1.0.7...1.0.8

## 1.0.7 - 2025-01-23

- [feat: Add the dbconfig:purge artisan command.](https://github.com/toneflix/laravel-dbconfig/commit/34f898471cb2ef3c1929d1459ff672e434b6bdc5)
- [feat: Add test for dbconfig:purge command.](https://github.com/toneflix/laravel-dbconfig/commit/ac2d034c2ad076200303d75c1b279db0fff506b4)

**Full Changelog**: https://github.com/toneflix/laravel-dbconfig/compare/1.0.6...1.0.7

## 1.0.6 - 2025-01-23

- [fix: Fix the about command cached info.](https://github.com/toneflix/laravel-dbconfig/commit/5f26b0db465bcdefe4a4cdbfb577b18a838728ec)
- [feat!: Simplify command names and move to dbconfig: namespace.](https://github.com/toneflix/laravel-dbconfig/commit/f2999d589628a83c1836bbacca525c1e5ab282c0)
- [feat!: Create the dbconfig:show command.](https://github.com/toneflix/laravel-dbconfig/commit/f2999d589628a83c1836bbacca525c1e5ab282c0)
- [chore: Update docs and test to match lastest features.](https://github.com/toneflix/laravel-dbconfig/commit/10861f703ff831def1513d7de4803c2217644eed)

**Full Changelog**: https://github.com/toneflix/laravel-dbconfig/compare/1.0.5...1.0.6

## 1.0.5 - 2025-01-23

[feat: Format about command output.](https://github.com/toneflix/laravel-dbconfig/commit/8348967e3a416a0fd656c521680ac6cdf1206648)

**Full Changelog**: https://github.com/toneflix/laravel-dbconfig/compare/1.0.4...1.0.5

## 1.0.4 - 2025-01-23

- [feat: Ask for a different key when creating config with dbconfig:create if the key already exists.](https://github.com/toneflix/laravel-dbconfig/commit/46df9ca706fa689733a381a1a7394f2d4682077e)
- [feat: Add cache status to about command.](https://github.com/toneflix/laravel-dbconfig/commit/3831b9b8caeb4fc3c4cccbf7175f3ebf6f199464)
- [feat: Add count of created configs to about commnad.](https://github.com/toneflix/laravel-dbconfig/commit/e3316ab791998d8924d84202835c8072948acd3a)
- [chore: Remove uselsess coments.](https://github.com/toneflix/laravel-dbconfig/commit/12b90bb48ac9e9e977aba76e76ba24bd2529ced6)

**Full Changelog**: https://github.com/toneflix/laravel-dbconfig/compare/1.0.2...1.0.4

## 1.0.3 - 2025-01-23

- [Reorder the the argument request when creating configurations.](https://github.com/toneflix/laravel-dbconfig/commit/bdee2ee7592db05b64dd45a9bc01221519682409)

## 1.0.2 - 2025-01-23

- Lower restriction on fileable dependency.
- Document config publishing
- Fix missing requirement imports.

**Full Changelog**: https://github.com/toneflix/laravel-dbconfig/compare/1.0.1...1.0.2

## 1.0.1 - 2025-01-20

- fix: use previously missing Symfony\Component\Console\Attribute\AsCommand

**Full Changelog**: https://github.com/toneflix/laravel-dbconfig/compare/1.0.0...1.0.1

## 1.0.0 - 201X-XX-XX

- initial release
