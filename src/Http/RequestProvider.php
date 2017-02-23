<?php
namespace Hope\Http;

use Hope\Contracts\ProviderInterface;

/**
 * Request Provider
 *
 * @package Hope\Http
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class RequestProvider implements ProviderInterface
{

    /**
     * Register the provider container
     *
     * @param \Hope\Application $app Application object.
     *
     * @return void
     */
    public static function register(\Hope\Application $app)
    {
        $app->bind(
            Request::class,
            function () {
                $request = Request::createFromGlobals();

                $contentType = $request->headers->get('Content-Type');
                if (0 === strpos($contentType, 'application/json')) {
                    $data = json_decode($request->getContent(), true);
                    $request->request->replace(is_array($data) ? $data : array());
                }

                return $request;
            }
        );
    }
}
