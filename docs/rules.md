Rules
=====

no-duplicate-tags
-----------------

Disallow duplicate tags

**Good**

```gherkin
# example.feature
@foo @bar
Feature: Some feature
```
**Bad**

```gherkin
# example.feature
@foo @foo
Feature: Some feature
```
no-empty-file
-------------

Disallow empty files

**Good**

```json
{
    "no-empty-file": {
        "tolerateThenBeforeWhen": true
    }
}
```

```gherkin
# example.feature
Feature: Foobar
```
**Bad**

```json
{
    "no-empty-file": {
        "tolerateThenBeforeWhen": true
    }
}
```

```gherkin
# example.feature
   
```
allowed-tags
------------

Only permit specified tags

**Good**

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
**Bad**

```json
{
    "allowed-tags": {
        "allow": []
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

**Good**

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
**Good**

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
**Good**

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
**Good**

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

**Good**

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
**Bad**

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

**Good**

```gherkin
# example.feature
Feature: Foobar
    Scenario: This is a scenario
        Given this is a scenario
        And the indentation is incorrect
        When I run the linter
        Then things will not be good
```
**Bad**

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
**Bad**

```gherkin
# example.feature
Feature: Foobar
    Scenario: This is a scenario
        Then things will not be good
```
**Bad**

```gherkin
# example.feature
Feature: Foobar
    Scenario: This is a scenario
        And things will not be good
```
**Good**

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

