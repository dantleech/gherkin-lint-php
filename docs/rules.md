Rules
=====

- [no-duplicate-tags](#no-duplicate-tags): Disallow duplicate tags
- [no-empty-file](#no-empty-file): Disallow empty files
- [allowed-tags](#allowed-tags): Only permit specified tags
- [filename](#filename): Filenames must conform to the specified stype
- [indentation](#indentation): Ensure consistent indentation
- [keyword-order](#keyword-order): Ensure that keywords are in the correct order
- [scenarios-per-file](#scenarios-per-file): Set a maximum (and/or minimum) number of scenarios allowed per file

no-duplicate-tags
-----------------

Disallow duplicate tags

**Good**: No duplicate tags

```gherkin
# example.feature
@foo @bar
Feature: Some feature
```
**Bad**: Duplicated tags

```gherkin
# example.feature
@foo @foo
Feature: Some feature
```
no-empty-file
-------------

Disallow empty files

**Good**: Non-empty file

```gherkin
# example.feature
Feature: Foobar
```
**Bad**: Empty file

```gherkin
# example.feature
   
```
allowed-tags
------------

Only permit specified tags

**Good**: Feature has allowed tags

```json
{
    "allowed-tags": {
        "allow": [
            "@foo",
            "@bar"
        ]
    }
}
```

```gherkin
# example.feature
@foo @bar
Feature: Some feature
```
**Bad**: Feature has not allowed tags

```json
{
    "allowed-tags": {
        "allow": [
            "@baz"
        ]
    }
}
```

```gherkin
# example.feature
@this-is-not-allowed
Feature: Some feature
```
filename
--------

Filenames must conform to the specified stype

**Good**: Snake case

```json
{
    "filename": {
        "style": "snake_case"
    }
}
```

```gherkin
# this_is_fine.feature
Feature: Some feature
```
**Good**: Pascal case

```json
{
    "filename": {
        "style": "PascalCase"
    }
}
```

```gherkin
# ThisIsFine.feature
Feature: Some feature
```
**Good**: Kebab Case

```json
{
    "filename": {
        "style": "kebab-case"
    }
}
```

```gherkin
# this-is-fine.feature
Feature: Some feature
```
**Good**: Camel case

```json
{
    "filename": {
        "style": "camelCase"
    }
}
```

```gherkin
# thisIsFine.feature
Feature: Some feature
```
indentation
-----------

Ensure consistent indentation

**Good**: Valid indentation

```json
{
    "indentation": {
        "width": 4,
        "feature": 0,
        "rule": 1,
        "backgroud": 1,
        "scenario": 1,
        "step": 2,
        "table": 3,
        "literalBlock": 2,
        "examples": 2,
        "examplesTable": 3
    }
}
```

```gherkin
# example.feature
Feature: Foobar
    Scenario: This is a scenario
        Given this is a scenario
        And the indentation is correct
        When I run the linter
        Then it should be fine
```
**Bad**: Invalid indentation

```json
{
    "indentation": {
        "width": 4,
        "feature": 0,
        "rule": 1,
        "backgroud": 1,
        "scenario": 1,
        "step": 2,
        "table": 3,
        "literalBlock": 2,
        "examples": 2,
        "examplesTable": 3
    }
}
```

```gherkin
# example.feature
 Feature: Foobar
   Scenario: This is a scenario
       Given this is a scenario
       And the indentation is incorrect
        When I run the linter
       Then things will not be good
```
keyword-order
-------------

Ensure that keywords are in the correct order

**Good**: Keywords in correct order

```gherkin
# example.feature
Feature: Foobar
    Scenario: This is a scenario
        Given this is a scenario
        And the indentation is incorrect
        When I run the linter
        Then things will not be good
```
**Bad**: Extra when is not allowed

```gherkin
# example.feature
Feature: Foobar
    Scenario: This is a scenario
        Given this is a scenario
        And the indentation is incorrect
        When I run the linter
        Then things will not be good
        When I do something else
```
**Bad**: Scenarios cannot start with Then

```gherkin
# example.feature
Feature: Foobar
    Scenario: This is a scenario
        Then things will not be good
```
**Bad**: Scenarios cannot start with And

```gherkin
# example.feature
Feature: Foobar
    Scenario: This is a scenario
        And things will not be good
```
**Good**: Tolerate then before when with config option

```json
{
    "keyword-order": {
        "tolerateThenBeforeWhen": true
    }
}
```

```gherkin
# example.feature
Feature: Foobar
    Scenario: This is a scenario
        Given something
        Then an exception should be thrown
        When I do this
```
scenarios-per-file
------------------

Set a maximum (and/or minimum) number of scenarios allowed per file

**Good**: Valid quantity of scenarios

```json
{
    "scenarios-per-file": {
        "min": 1,
        "max": 3
    }
}
```

```gherkin
# example.feature
Feature: One
    Scenario: One
    Scenario: Two
    Scenario: Three
```
**Bad**: Too many scenarios

```json
{
    "scenarios-per-file": {
        "min": 0,
        "max": 1
    }
}
```

```gherkin
# example.feature
Feature: One
    Scenario: First scenario
    Scenario: Two
```
**Bad**: Not enough scenarios

```json
{
    "scenarios-per-file": {
        "min": 5,
        "max": 10
    }
}
```

```gherkin
# example.feature
Feature: One
    Scenario: One
```

