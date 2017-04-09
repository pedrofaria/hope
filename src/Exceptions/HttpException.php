<?php
namespace Hope\Exceptions;

use Hope\Contracts\HttpExceptionInterface;

/**
 * HttpException
 *
 * @package Hope/Exceptions
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
abstract class HttpException extends \Exception
{
    protected $attributes = ['code', 'message'];

    /**
     * Get data of attributes fields.
     *
     * @return array Data of attributes.
     */
    public function getData()
    {
        $data = [];

        foreach ($this->attributes as $attr) {
            $data[$attr] = $this->$attr;
        }

        return $data;
    }
}
