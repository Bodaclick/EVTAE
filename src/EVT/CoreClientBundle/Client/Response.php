<?php

namespace EVT\CoreClientBundle\Client;

 /**
 * Response
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class Response
{
    private $statusCode;
    private $body;

    public function __construct($statusCode, $body)
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
