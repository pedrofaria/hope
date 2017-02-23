<?php
namespace Hope\Exceptions;

/**
 * InvalidDatabaseConnectorException
 *
 * @package Hope/Exceptions
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class InvalidDatabaseConnectorException extends \Exception
{
    protected $code = 1001;
    protected $message = "This Database Connector is not implemented!";
}
