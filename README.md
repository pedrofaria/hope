# Hope

Hope is a very lightweight PHP microframework.

Check a [example of Hope application](https://github.com/pedrofaria/hope-test-app/tree/develop)

## Installation

TODO!

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

### Routes

All routes are defined with the method `setRoute`. You can pass a closure (above) or a file name with the route definition.

Map all your routes to your actions.

```php
$route->add(METHOD, URI, ACTION);
```

If you want to map more than one method to the same action, use:

```php
$route->add([METHOD1, METHOD2], URI, ACTION);
```

You can define parameters on URI using braces. Example:

	/recipes/{id}

Now the parameter id will be passed to your controller action. You can also specify an regular expression to your parameter to have a better controls of your URIs, example:

    /recipes/{id:\d+}

Your actions should be defined as string, array or closures. example:

```php
$route->add('GET', '/recipes', 'App\Controller\RecipesController::index');
$route->add('GET', '/recipes', ['App\Controller\RecipesController', 'index']);
$route->add('GET', '/ping', function () {
	return 'pong';
};
```

Additionally, you can specify routes inside of a group. All routes defined inside a group will have a common prefix.

For example, defining your routes as:

```php
$route->addGroup('/admin', function (Hope\Router\RouteCollector $route) {
    $route->add('GET', '/do-something', 'handler');
    $route->add('GET', '/do-another-thing', 'handler');
    $route->add('GET', '/do-something-else', 'handler');
});
```

Will have the same result as:

```php
$route->add('GET', '/admin/do-something', 'handler');
$route->add('GET', '/admin/do-another-thing', 'handler');
$route->add('GET', '/admin/do-something-else', 'handler');
```

Nested groups are also supported, in which case the prefixes of all the nested groups are combined.

For more informations, access https://github.com/pedrofaria/router 

### Providers

You can extends or replace some funcionality of Hope using the Dependency Injection system. Just two steps and every thing is running.

1) Create your own Provider.

```php
<?php
namespace App\Providers;

class MyRequestProvider implements Hope\Contracts\ProviderInterface
{
    public static function register(Hope\Application $app)
    {
        $app->bind('Hope\Http\Request', function() {
            return new App\Http\MyRequest();
        });
    }
}
```

2) Add it with the method `addExternalProviders`.

```php
$app->addExternalProviders([
    App\Providers\YourProviderClassProvider::class,
]);

```

CAUTION: This method must stay before `bootstrap()`.

## Running Tests

Codeception was chosen on this project to support all tests. To run, use the command below:

`$ vendor/bin/codecept run unit`

If you want run with code coverage, use the follow command and the HTML report will be available at `tests/_output/coverage`.

`$ vendor/bin/codecept run unit --coverage --coverage-html`
