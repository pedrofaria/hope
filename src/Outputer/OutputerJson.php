<?php
namespace Hope\Outputer;

use Hope\Application;
use Hope\Contracts\HttpExceptionInterface;
use Hope\Contracts\OutputerInterface;
use Hope\Http\Request;
use Hope\Http\Response;

/**
 * Outputer for Json
 *
 * @package Hope\Outputer
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class OutputerJson implements OutputerInterface
{
    private $app;
    private $request;
    private $response;

    /**
     * Constructor
     *
     * @param Application $app      Hope Aplication.
     * @param Request     $request  Hope Request.
     * @param Response    $response Hope Response.
     */
    public function __construct(
        Application $app,
        Request $request,
        Response $response
    ) {
        $this->app = $app;
        $this->request = $request;
        $this->response = $response;

        $this->response->headers->set('Content-Type', 'application/json');
    }

    /**
     * Transform any kind of data to JSON
     *
     * @param mixed $responseData Response data.
     *
     * @return string JSON
     */
    private function toJson($responseData)
    {
        if ($responseData instanceof Response) {
            $responseData = $responseData->getContent();
        }

        if (is_array($responseData) || is_object($responseData)) {
            $responseData = json_encode($responseData);
        }

        return $responseData;
    }

    /**
     * Send output to client
     *
     * @param mixed $responseData Data to response.
     *
     * @return void
     */
    public function output($responseData)
    {
        $responseData = $this->toJson($responseData);

        $this->response->setContent($responseData);

        $this->response->send();
    }

    /**
     * Parse HTTP Error and output to client
     *
     * @param HttpExceptionInterface $exception Exception HTTP Error.
     *
     * @return void
     */
    public function outputHttpError(HttpExceptionInterface $exception)
    {
        $this->response->setStatusCode($exception->getCode());
        
        $this->response->setContent($this->toJson($exception->getData()));

        $this->response->send();
    }
    
    /**
     * Parse Exception Error and output to client
     *
     * @param \Exception $exception Exception error.
     *
     * @return void
     */
    public function outputException(\Exception $exception)
    {
        $this->response->setStatusCode(500);

        $data = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage()
        ];

        $this->response->setContent($this->toJson($data));

        $this->response->send();
    }
}
