<?php
namespace Hope\Http;

use Symfony\Component\HttpFoundation\Request as FoundationRequest;

/**
 * Request
 *
 * @package Hope\Http
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class Request extends FoundationRequest
{

    /**
     * Remove Query String from URI for Router dispatcher
     *
     * @return string URI without Query String
     */
    public function getUriForRouter()
    {
        $uri = $this->getRequestUri();

        // Strip query string (?foo=bar) and decode URI.
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        return $uri;
    }
}
