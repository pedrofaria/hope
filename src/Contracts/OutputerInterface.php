<?php
namespace Hope\Contracts;

use Hope\Application;
use Hope\Contracts\HttpExceptionInterface;
use Hope\Http\Request;
use Hope\Http\Response;

/**
 * Outputer Contract
 *
 * @package Hope\Contracts
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
interface OutputerInterface
{
    /**
     * Build Response object
     *
     * @param mixed $responseData Data to response.
     *
     * @return \Hope\Http\Response
     */
    public function buildResponse($responseData);

    /**
     * Sent Response to client
     *
     * @return void
     */
    public function output();

    /**
     * Parse HTTP Error and output to client
     *
     * @param HttpExceptionInterface $exception Exception HTTP Error.
     *
     * @return void
     */
    public function outputHttpError(HttpExceptionInterface $exception);
    
    /**
     * Parse Exception Error and output to client
     *
     * @param \Exception $exception Exception error.
     *
     * @return void
     */
    public function outputException(\Exception $exception);
}
