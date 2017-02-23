<?php
namespace Hope\Exceptions;

use Hope\Contracts\HttpExceptionInterface;
use Hope\Exceptions\HttpException;

/**
 * UnauthorizedException
 *
 * @package Hope/Exceptions
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class UnauthorizedException extends HttpException implements HttpExceptionInterface
{
    protected $code = 401;
    protected $message = "Unauthorized";
}
