<?php
namespace Hope\Exceptions;

use Hope\Contracts\HttpExceptionInterface;
use Hope\Exceptions\HttpException;

/**
 * NotFoundException
 *
 * @package Hope/Exceptions
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class NotFoundException extends HttpException implements HttpExceptionInterface
{
    protected $code = 404;
    protected $message = "Page not Found";
}
