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
     * Send output to client
     *
     * @param mixed $responseData Data to response.
     *
     * @return void
     */
    public function output($responseData);

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
