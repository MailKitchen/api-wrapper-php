<?php

namespace Mailkitchen;

use Psr\Http\Message\ResponseInterface;

/**
 * Build an Http response
 */
class Response
{
    private $status;
    private $success;
    private $body;
    private $rawResponse;

    /**
     * Construct a Mailkitchen response
     * @param Request $request Mailkitchen actual request
     * @param ResponseInterface $response Guzzle response
     */
    public function __construct($request, $response)
    {
        $this->request = $request;
        if ($response) {
            $this->rawResponse = $response;
            $this->status = $response->getStatusCode();
            $this->body = json_decode($response->getBody(), true);
            $this->success = floor($this->status / 100) == 2 ? true : false;
        }
    }

    /**
     * Status Getter
     * return the http status code
     * @return int status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Status Getter
     * return the entire response array
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Data Getter
     * The data returned by the Mailkitchen call
     * @return null|array data
     */
    public function getData()
    {
        if (isset($this->body['data'])) {
            return $this->body['data'];
        }

        if (isset($this->body['errors'])) {
            return $this->body['errors'];
        }

        return $this->body;
    }

    /**
     * Count getter
     * return the resulting array size
     * @return null|int
     */
    public function getCount()
    {
        if (isset($this->body['Count'])) {
            return $this->body['Count'];
        }

        return null;
    }

    /**
     * Error Reason getter
     * return the resulting error message
     * @return null|string
     */
    public function getReasonPhrase()
    {
        return $this->rawResponse->getReasonPhrase();
    }

    /**
     * Total getter
     * return the total count of all results
     * @return int count
     */
    public function getTotal()
    {
        if (isset($this->body['Total'])) {
            return $this->body['Total'];
        }

        return null;
    }

    /**
     * Success getter
     * @return boolean true is return code is 2**
     */
    public function success()
    {
        return $this->success;
    }
}