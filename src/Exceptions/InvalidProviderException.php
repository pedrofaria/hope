<?php
namespace Hope\Exceptions;

/**
 * InvalidProviderException
 *
 * @package Hope/Exceptions
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class InvalidProviderException extends \Exception
{
    protected $code = 1000;
    protected $message = "Your provider SHOULD implement ProviderInterface!";
}
