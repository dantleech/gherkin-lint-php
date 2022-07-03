Gherkin Lint for PHP
====================

This package provides a Gherkin linter for PHP heavily inspired by
[vsiakka/gherkin-lint](https://github.com/vsiakka/gherkin-lint) and using the
[PHP Gherkin Parser](https://packagist.org/packages/cucumber/gherkin).

Contents
--------

- [Usage](#usage)
- [Configuration](#configuration)
- [Rules](docs/rules.md)
- [Disabling Rules](#disabling-rules)

Usage
-----

> **NOTE**: Gherkin Lint requires PHP 8.1

Install with Composer:

```
$ composer require --dev dantleech/gherkin-lint
```

Lint your feature files:

```
$ ./vendor/bin/gherkinlint lint features/
```

![image](https://user-images.githubusercontent.com/530801/175784302-398ca341-ae67-4b63-8b8d-b7e705286ab3.png)


To see the available and enabled rules run:

```
$ ./vendor/bin/gherkinlint rules
```

![image](https://user-images.githubusercontent.com/530801/175804779-0fe10523-c410-4545-b564-c23e896b2133.png)

Configuration
-------------

By default all rules are enabled. In order to customise or disable them create
a config file `gherkinlint.json`:

```
{
    "rules": {
        "allowed-tags": {
            "allow": ["@my-special-tag", "@my-other-tag"]
        },
        "filename": {
            "enabled": false
        }
    }
}
```

Use the `rules` command to see which rules are enabled.

Disabling Rules
---------------

Rules can be disabled by adding a comment before the Feature declaration:

```gherkin
# @gherkinlint-disable-rule keyword-order
Feature: My feature with strange keyword orders
```

Disable multiple rules with comma separation:

```gherkin
# @gherkinlint-disable-rule keyword-order, someother-rule
Feature: My feature with strange keyword orders
```

Contributing
------------

Make a pull request!
