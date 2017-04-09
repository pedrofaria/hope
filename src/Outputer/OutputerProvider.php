<?php
namespace Hope\Outputer;

use Hope\Application;
use Hope\Contracts\ProviderInterface;
use Hope\Http\Request;
use Hope\Http\Response;
use Hope\Outputer\OutputerJson;

/**
 * Outputer Provider
 *
 * @package Hope\Outputer
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class OutputerProvider implements ProviderInterface
{
    /**
     * Registering Outputer Class
     *
     * @param Application $app Hope Application.
     *
     * @return void
     */
    public static function register(Application $app)
    {
        $app->bind(
            'Hope\Contracts\OutputerInterface',
            function (Request $request, Response $response) use ($app) {
                return new OutputerJson($app, $request, $response);
            }
        );
    }
}
