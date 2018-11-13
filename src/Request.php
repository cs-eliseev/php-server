<?php

namespace PHPServer;

class Request
{
    /**
     * @var string
     */
    protected $method = '';

    /**
     * @var string
     */
    protected $uri = '';

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * Request constructor.
     * @param string $method
     * @param string $uri
     * @param array $headers
     */
    public function __construct(string $method, string $uri, array $headers)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;

        list($this->uri, $params) = explode('?', $uri);
        parse_str($params, $this->params);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getParamsByKey(string $key)
    {
        return $this->params[$key];
    }

    /**
     * @param string $header
     * @return Request
     */
    public static function createRequest(string $header): Request
    {
        $lines = explode("\r", $header);
        list($method, $uri) = explode(' ', array_shift($lines));
        $headers = [];

        foreach ($lines as $line) {

            $line = trim($line);

            if(strpos($line, ':') !== false) {

                list($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }

        return new static($method, $uri, $headers);
    }
}