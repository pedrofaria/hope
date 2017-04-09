<?php
namespace Hope;

use DI\ContainerBuilder;
use Hope\Contracts\ContainerContract;

/**
 * Container manager trait
 *
 * @package Hope
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class DIContainer implements ContainerContract
{
    /**
     * The current globally available container (if any).
     *
     * @var static
     */
    protected static $instance;

    private $containerBuilder;
    private $container;

    /**
     * Set the globally available instance of the container.
     *
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Set the shared instance of the container.
     *
     * @param  \Hope\Contracts\Container|null  $container
     * @return static
     */
    public static function setInstance(ContainerContract $container = null)
    {
        return static::$instance = $container;
    }

    /**
     * Initialize container builder class
     *
     * @return void
     */
    public function initContainer()
    {
        $this->containerBuilder = new ContainerBuilder;
        $this->containerBuilder->useAnnotations(false);
    }

    /**
     * Bind a thing to a container
     *
     * Example:
     *
     *     $app->bind('Hope\Http\Response', function() {
     *         return new App\Http\Response;
     *     });
     *
     * @param string   $class          Name of Container.
     * @param callable $factoryClosure Closure for container creation.
     *
     * @return void
     */
    public function bind(string $class, $factoryClosure)
    {
        $this->containerBuilder->addDefinitions(
            [
                $class => \DI\factory($factoryClosure)
            ]
        );
    }

    /**
     * Register an existing instance as shared in the container.
     *
     * @param string $abstract
     * @param mixed $instance
     *
     * @return void
     */
    public function instance(string $abstract, $instance)
    {
        $this->containerBuilder->set($abstract, $instance);
    }

    /**
     * Get container already initialized
     *
     * @param string $class Name of container.
     *
     * @return mixed Container created
     */
    public function get(string $class)
    {
        return $this->container->get($class);
    }

    /**
     * Call the container
     *
     * Example:
     *
     *     $content = $app->call(
     *         'App\Controllers\RecipesController::show',
     *         ['id' => 1]
     *     );
     *     $content = $app->call(['App\Controllers\RecipesController', 'index']);
     *
     * @param mixed $class      Name of the container.
     * @param array $parameters Parameters passed to function/method.
     *
     * @return mixed Returned data of the call
     */
    public function call($class, array $parameters = [])
    {
        return $this->container->call($class, $parameters);
    }

    /**
     * Build the container
     *
     * @return void
     */
    public function buildContainer()
    {
        $this->container = $this->containerBuilder->build();
    }
}
