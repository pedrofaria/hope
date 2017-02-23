<?php
namespace Hope\Http;

use Symfony\Component\HttpFoundation\Response as FoundationResponse;

/**
 * Response
 *
 * @package Hope\Http
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class Response extends FoundationResponse
{
    /**
     * Easy way to set content with status code
     *
     * @param string|object $content     Content.
     * @param integer|null  $status_code HTTP Code.
     *
     * @return Response Response object
     */
    public function easyResponse($content = null, int $status_code = null)
    {
        if (!empty($content)) {
            if (is_array($content) || is_object($content)) {
                $content = serialize($content);
            }

            $this->setContent($content);
        }

        if (!empty($status_code)) {
            $this->setStatusCode($status_code);
        }

        return $this;
    }

    /**
     * Check if a string is serialized
     *
     * @param string $string String to check.
     *
     * @return boolean
     */
    public static function isSerialized(string $string)
    {
        return (@unserialize($string) !== false || $string == 'b:0;');
    }

    /**
     * Get response content
     *
     * @return mixed Content
     */
    public function getContent()
    {
        $content = parent::getContent();

        if ($this->isSerialized($content)) {
            $content = unserialize($content);
        }

        return $content;
    }
}
