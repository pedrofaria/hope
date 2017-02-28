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

    /**
     * Get $_POST data. Ommit $key to get all data.
     *
     * @param string|null $key     Name of parameter.
     * @param mixed       $default Default value.
     *
     * @return mixed
     */
    public function postData($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->request->all();
        }

        return $this->request->get($key) ?: $default;
    }

    /**
     * Get $_GET data. Ommit $key to get all data.
     *
     * @param string|null $key     Name of parameter.
     * @param mixed       $default Default value.
     *
     * @return mixed
     */
    public function getData($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->query->all();
        }

        return $this->query->get($key) ?: $default;
    }
}
