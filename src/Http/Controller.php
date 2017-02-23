<?php
namespace Hope\Http;

/**
 * Controller
 *
 * @package Hope\Http
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
abstract Controller
{
    protected $app;
    protected $request;
    protected $response;
    
    /**
     * Constructor
     *
     * @param \Hope\Application $app      Application.
     * @param Request           $request  Request.
     * @param Response          $response Response.
     */
    public function __construct(
        \Hope\Application $app,
        Request $request,
        Response $response
    ) {
        $this->app = $app;
        $this->request = $request;
        $this->response = $response->prepare($request);
    }

    /**
     * Alias for Response::easyResponse
     *
     * @param string|object $content     Content to response.
     * @param integer       $status_code HTTP Code.
     *
     * @return Response Response object
     */
    public function response($content = null, int $status_code = null)
    {
        return $this->response->easyResponse($content, $status_code);
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
            return $this->request->request->all();
        }

        return $this->request->request->get($key) ?: $default;
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
            return $this->request->query->all();
        }

        return $this->request->query->get($key) ?: $default;
    }

    /**
     * Define response cache headers
     *
     * @param array $data Data for cache header.
     *
     * @return void
     */
    public function setCache(array $data)
    {
        $this->response->setCache($data);
    }
}
