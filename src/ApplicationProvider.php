<?php

namespace Hope;

use Hope\Application;
use Hope\Contracts\ProviderInterface;

/**
 * Hope Application base class
 *
 * @package Hope\Providers
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class ApplicationProvider implements ProviderInterface
{
    /**
     * Callable static method used to bind a container
     *
     * @param Application $app Application object.
     *
     * @return void
     */
    public static function register(Application $app)
    {
        // Register it self.
        $app->bind(
            Application::class,
            function () use ($app) {
                return $app;
            }
        );
    }
}
