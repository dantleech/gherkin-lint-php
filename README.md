Gherkin Lint for PHP
====================

This package provides a Gherkin liter for PHP heavily inspired by
[vsiakka/gherkin-lint](https://github.com/vsiakka/gherkin-lint) and using the
[PHP Gherkin Parser](https://packagist.org/packages/cucumber/gherkin).

![image](https://user-images.githubusercontent.com/530801/175784302-398ca341-ae67-4b63-8b8d-b7e705286ab3.png)

Usage
-----

Install with Composer:

```
$ composer require --dev dantleech/gherkin-lint
```

Lint your feature files:

```
$ ./vendor/bin/gherkinlint features/
```

Configuration
-------------

By default no rules are enabled, create a configuration file called
`gherkinlint.json`:

```
{
    "rules": {
        "no-duplicate-tags": {},
        "no-empty-file": {},
        "allowed-tags": {
            "allow": null
        },
        "filename": {
            "style": "snake_case"
        },
        "indentation": {
            "width": 4
        },
        "keyword-order": {
            "tolerateThenBeforeWhen": true
        }
    }
}
```

Contributing
------------

Make a pull request!
