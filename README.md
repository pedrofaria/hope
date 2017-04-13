# Hope

Hope is a very lightweight PHP microframework.

Check a [example of Hope application](https://github.com/pedrofaria/hope-test-app/tree/develop)

## Installation

`$ composer require pedrofaria/hope`

***

## Usage

Very simple usage of Hope microframework.

```php
$app = new Hope\Application;

$app->setRoute(function(Hope\Router\RouteCollector $route) {
    $route->add('GET', '/ping', function() {
        return ['data' => 'pong'];
    });
});

$app->bootstrap();
$app->run();
```

And that's it!

## Documentation

Check the [Wiki with all Hope documentation](https://github.com/pedrofaria/hope/wiki).

## Running Tests

Codeception was chosen on this project to support all tests. To run, use the command below:

`$ vendor/bin/codecept run unit`

If you want run with code coverage, use the follow command and the HTML report will be available at `tests/_output/coverage`.

`$ vendor/bin/codecept run unit --coverage --coverage-html`
