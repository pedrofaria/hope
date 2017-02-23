<?php
namespace Hope\Exceptions;

use Hope\Contracts\HttpExceptionInterface;
use Hope\Exceptions\HttpException;

/**
 * UnprocessableEntityException
 *
 * @package Hope/Exceptions
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class UnprocessableEntityException extends HttpException implements HttpExceptionInterface
{
    protected $code = 422;
    protected $message = "Unprocessable Entity";
    protected $errors = [];
    protected $attributes = ['code', 'message', 'errors'];

    /**
     * Constructor
     * @param array $errors Errors of input data.
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }
}
