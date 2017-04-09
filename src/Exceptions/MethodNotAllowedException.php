<?php
namespace Hope\Exceptions;

use Hope\Contracts\HttpExceptionInterface;
use Hope\Exceptions\HttpException;

/**
 * MethodNotAllowedException
 *
 * @package Hope/Exceptions
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class MethodNotAllowedException extends HttpException implements HttpExceptionInterface
{
    protected $code = 405;
    protected $message = "Method Not Allowed";
}
