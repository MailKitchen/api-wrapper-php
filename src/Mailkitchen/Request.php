<?php

namespace Mailkitchen;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

/**
 * Build an Http request
 */
class Request extends GuzzleClient
{

    private $method;
    private $url;
    private $filters;
    private $body;
    private $auth;
    private $type = 'application/json';
    private $requestOptions = [];

    /**
     * Build a new Http request
     * @param string $auth token
     * @param string $method http method
     * @param string $url call url
     * @param array $query query url
     * @param array $body body url
     * @param string $type Request Content-type
     */
    public function __construct(
        $auth,
        $method,
        $url,
        $query = [],
        $body = [],
        $type = 'application/json',
        array $requestOptions = []
    ) {
        parent::__construct();
        $this->type = $type;
        $this->auth = $auth;
        $this->method = $method;
        $this->url = Config::API_URL . $url;
        $this->query = $query;
        $this->body = $body;
        $this->requestOptions = $requestOptions;
    }

    /**
     * Trigger the actual call
     * @param $call
     * @return Response the call response
     */
    public function call($call = true)
    {
        $payload = [];

        if (!empty($this->body)) {
            $payload[($this->type === 'application/json' ? 'json' : 'body')] = $this->body;
        }

        if (!empty($this->query)) {
            $payload['query'] = $this->query;
        }

        $headers = [
            'content-type' => $this->type
        ];

        if (!empty($this->auth)) {
            $headers['Authorization'] = 'Bearer ' . $this->auth;
        }

        $payload['headers'] = $headers;

        if ((!empty($this->requestOptions)) && (is_array($this->requestOptions))) {
            $payload = array_merge_recursive($payload, $this->requestOptions);
        }

        $response = null;
        if ($call) {
            try {
                $response = call_user_func_array(
                    [$this, strtolower($this->method)], [$this->url, $payload]
                );
            } catch (ClientException $e) {
                $response = $e->getResponse();
            } catch (ServerException $e) {
                $response = $e->getResponse();
            }
        }

        return new Response($this, $response);
    }

    /**
     * Filters getters
     * @return array Request filters
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Http method getter
     * @return string Request method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Call Url getter
     * @return string Request Url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Request body getter
     * @return array request body
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Auth getter. to discuss
     * @return string Request auth
     */
    public function getAuth()
    {
        return $this->auth;
    }

}