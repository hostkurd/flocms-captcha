# Changelog

## Unreleased

### Security
- Captcha codes are single-use. `verify()` now removes the stored code on
  every check, right or wrong. Before, a wrong answer left the code in the
  session, so a script could keep guessing it, and a correct form could be
  submitted again until the code was cleared. The new `single_use` setting
  (default `true`) restores the old behavior when set to `false`.

### Added
- PHPUnit tests (`composer test`).

## 1.0.0

- First release.
