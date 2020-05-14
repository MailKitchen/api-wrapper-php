<?php

namespace Mailkitchen;

/**
 * The Mailkitchen API Client
 */
class Client
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var array
     */
    private $config;

    /**
     * Client constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = array_merge(
            [
                "api_key" => null,
                "api_secret_key" => null,
                "jwt" => null
            ],
            $config
        );

        if (!empty($config['api_key']) && !empty($config['api_secret_key'])) {
            $this->setLoginAuth($config['api_key'], $config['api_secret_key']);
        }

        if (!empty($config['jwt'])) {
            $this->token = $config['jwt'];
        }
    }

    /**
     * Magic method to call a mailkitchen resource
     *
     * @param $name
     * @param $arguments
     * @return array|mixed|null
     * @throws \ReflectionException
     */
    public function __call($name, $arguments)
    {
        if (substr($name, 0, 3) === 'get' && ctype_upper($name[3])) {
            $resource = strtolower(substr($name, 3));

            $class = new \ReflectionClass('Mailkitchen\Resources');
            $value = $class->getStaticPropertyValue($resource, false);
            if ($value !== false) {
                $id = (!empty($arguments[0])) ? $arguments[0] : null;
                $resourceParams = [
                    'resource' => $value[0],
                ];
                if (!empty($value[1])) {
                    $resourceParams['relationship'] = $value[1];
                }
                return $this->get($resourceParams, $id);
            }
        }
        return call_user_func_array($name, $arguments);
    }

    /**
     * Trigger a GET request
     * @param $resource
     * @param null $resourceId
     * @param null $module
     * @param null $action
     * @param array $query
     * @return array
     */
    public function get($resource, $resourceId = null, $module = null, $action = null, $query = [])
    {
        $resourcePath = "/$resource";

        $resourceIdPath = (isset($resourceId)) ? "/$resourceId" : "";

        $relationshipsPath = "";

        if (!empty($module)) {
            $relationshipsPath = "/$module";
        }

        if (!empty($module) && !empty($action)) {
            $relationshipsPath = "/$module/$action";
        }

        $queryPath = "";
        if (!empty($query)) {
            $queryPath = "?" . http_build_query($query);
        }

        $uri = "{$resourcePath}{$resourceIdPath}{$relationshipsPath}{$queryPath}";
        $res = $this->request("GET", $uri);
        return $res->getBody();
    }

    /**
     * Trigger a POST request
     * @param $resource
     * @param null $resourceId
     * @param array $data
     * @param null $module
     * @param null $action
     * @return array
     */
    public function post($resource, $resourceId = null, $data = [], $module = null, $action = null)
    {
        $resourcePath = "/$resource";

        $resourceIdPath = (isset($resourceId)) ? "/$resourceId" : "";

        $relationshipsPath = "";
        if (!empty($module) && !empty($action)) {
            $relationshipsPath = "/$module/$action";
        }

        $uri = "{$resourcePath}{$resourceIdPath}{$relationshipsPath}";
        $res = $this->request("POST", $uri, [], $data);
        return $res->getBody();
    }

    /**
     * Trigger a PUT request
     * @param $resource
     * @param null $resourceId
     * @param array $data
     * @return array
     */
    public function put($resource, $resourceId = null, $data = [])
    {
        $resourcePath = "/$resource";

        $resourceIdPath = (isset($resourceId)) ? "/$resourceId" : "";

        $relationshipsPath = "";
        if (isset($resource['relationship'])) {
            $relationshipsPath = "/relationships/{$resource['relationship']}";
        }

        $uri = "{$resourcePath}{$resourceIdPath}{$relationshipsPath}";
        $res = $this->request("PUT", $uri, [], $data);
        return $res->getBody();
    }

    /**
     * Trigger a DELETE request
     * @param $resource
     * @param null $resourceId
     * @return array
     */
    public function delete($resource, $resourceId = null)
    {
        $resourcePath = "/$resource";

        $resourceIdPath = (isset($resourceId)) ? "/$resourceId" : "";

        $relationshipsPath = "";
        if (isset($resource['relationship'])) {
            $relationshipsPath = "/relationships/{$resource['relationship']}";
        }

        $uri = "{$resourcePath}{$resourceIdPath}{$relationshipsPath}";
        $res = $this->request("DELETE", $uri);
        return $res->getBody();
    }

    /**
     * Set auth with a token
     *
     * @param $token
     */
    public function setTokenAuth($token)
    {
        $this->token = $token;
    }

    /**
     * Set auth with a login and a password
     *
     * @param $api_key
     * @param $api_secret_key
     * @return array|null
     */
    public function setLoginAuth($api_key, $api_secret_key)
    {
        $request = new Request(
            null, 'POST', '/login', [], ['api_key' => $api_key, 'api_secret_key' => $api_secret_key],
            'application/json', array('curl' => array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false))
        );

        $res = $request->call();
        if (isset($res->getData()['token'])) {
            $this->token = $res->getData()['token'];
        }
        return $res->getData();
    }

    /**
     * @param $method
     * @param $uri
     * @param array $query
     * @param array $body
     * @return Response
     */
    private function request($method, $uri, $query = [], $body = [])
    {
        $request = new Request(
            $this->token, $method, $uri, $query, $body
        );
        return $request->call();
    }


    /**
     * Get Token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

}