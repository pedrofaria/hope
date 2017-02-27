<?php
namespace Hope;

use Closure;
use Hope\ApplicationProvider;
use Hope\Contracts\HttpExceptionInterface;
use Hope\Contracts\ProviderInterface;
use Hope\DIContainer;
use Hope\Exceptions\InvalidProviderException;
use Hope\Http\RequestProvider;
use Hope\Outputer\OutputerProvider;
use Hope\Router\Router;
use Hope\Router\Dispatcher;
use Hope\Router\RouteCollector;

/**
 * Hope Application base class
 *
 * @package Hope
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class Application extends DIContainer
{
    /**
     * List of Hope Providers
     *
     * @var array
     */
    private $providers = [
        ApplicationProvider::class,
        RequestProvider::class,
        OutputerProvider::class
    ];

    /** @var string Route file definition */
    private $routes;

    /**
     * Initialization
     */
    public function __construct()
    {
        $this->initContainer();
        $this->registerBaseBindings();
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);

        $this->bind('app', $this);

        $this->bind('Hope\DIContainer', $this);
    }

    /**
     * Define externals providers
     *
     * @param array $providers Providers list
     *
     * @return void
     */
    public function addExternalProviders(array $providers)
    {
        $this->providers += $providers;
    }

    /**
     * Registration of Providers
     *
     * @return void
     *
     * @throws InvalidProviderException Invalid provider.
     */
    private function registerProviders()
    {
        foreach ($this->providers as $provider) {
            if (!in_array(ProviderInterface::class, class_implements($provider))) {
                throw new InvalidProviderException(
                    "Class '{$provider}' SHOULD implement ProviderInterface!"
                );
            }
            $provider::register($this);
        }
    }

    /**
     * Define routes of Hope
     *
     * @param string|Closure $routes Pass file name or a Closure of the Routes definitions
     *
     * @return void
     */
    public function setRoute($routes)
    {
        if (is_string($routes)) {
            if (!file_exists($routes)) {
                throw new \Exception("Route definition file don't exist", 1);
            }

            $routes = function(RouteCollector $route) use ($routes)
            {
                include $routes;
            };
        }
        else if (!is_callable($routes)) {
            throw new \Exception("You should pass a filename string or a Closure with routes definitions", 1);
        }

        $this->routes = $routes;
    }

    /**
     * Registration of Routes
     *
     * @return void
     */
    private function registerRoutes()
    {
        $this->call([Router::class, 'register'], [$this->routes]);
    }

    /**
     * Bootstrap application
     *
     * @return void
     */
    public function bootstrap()
    {
        $this->registerProviders();
        $this->buildContainer();
        $this->registerRoutes();
    }

    /**
     * Run application
     *
     * @return void
     */
    public function run()
    {
        $this->call([Application::class, 'performResponse']);
    }

    /**
     * Call Dispatcher and perform response
     *
     * @param Dispatcher        $dispatcher Router Dispatcher.
     * @param OutputerInterface $outputer   Injection of Outputer.
     *
     * @return void
     */
    public function performResponse(Dispatcher $dispatcher)
    {
        try {
            $outputer = $dispatcher->dispatch();
            $outputer->output($responseData);
        } catch (HttpExceptionInterface $e) {
            $outputer->outputHttpError($e);
        } catch (\Exception $e) {
            $outputer->outputException($e);
        }
    }
}
