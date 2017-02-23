<?php
namespace Hope;

use Hope\Contracts\HttpExceptionInterface;
use Hope\Contracts\OutputerInterface;
use Hope\Contracts\ProviderInterface;
use Hope\DIContainer;
use Hope\Database\DatabaseProvider;
use Hope\Exceptions\InvalidProviderException;
use Hope\Http\RequestProvider;
use Hope\Outputer\OutputerProvider;
use Hope\Providers\ApplicationProvider;
use Hope\Router\Dispatcher;

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

    /**
     * Initialization
     */
    public function __construct(string $basePath = null)
    {
        if (isset($basePath)) {
            $this->setBasePath($basePath);
        }

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
     * Set the base path for the application.
     *
     * @param  string  $basePath
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');

        return $this;
    }

    /**
     * Get the base path of the Laravel installation.
     *
     * @return string
     */
    public function basePath()
    {
        return $this->basePath;
    }

    /**
     * Get configuration data
     *
     * @param string $key Config item.
     *
     * @return mixed Config value
     */
    public static function config(string $key = null)
    {
        static $config;

        if (is_null($config)) {
            $config = require $this->basePath() . '/config/config.php';
        }

        if ($key) {
            return isset($config[$key]) ? $config[$key] : null;
        }

        return $config;
    }

    /**
     * Get all providers
     *
     * @return array List of providers
     */
    private function loadProvidersList()
    {
        $app_providers = include $this->basePath() . '/config/providers.php';
        return $this->providers + $app_providers;
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
        foreach ($this->loadProvidersList() as $provider) {
            if (!in_array(ProviderInterface::class, class_implements($provider))) {
                throw new InvalidProviderException(
                    "Class '{$provider}' SHOULD implement ProviderInterface!"
                );
            }
            $provider::register($this);
        }
    }

    /**
     * Registration of Routes
     *
     * @return void
     */
    private function registerRoutes()
    {
        $this->call([Router::class, 'register']);
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
    public function performResponse(
        Dispatcher $dispatcher,
        OutputerInterface $outputer
    ) {
        try {
            $responseData = $dispatcher->dispatch();
            $outputer->output($responseData);
        } catch (HttpExceptionInterface $e) {
            $outputer->outputHttpError($e);
        } catch (\Exception $e) {
            $outputer->outputException($e);
        }
    }
}
