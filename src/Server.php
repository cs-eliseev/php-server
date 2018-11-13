<?php

namespace PHPServer;

class Server
{
    /**
     * @var null|string
     */
    protected $host = null;
    /**
     * @var null|string
     */
    protected $port = null;
    /**
     * @var null|resource
     */
    protected $socket = null;

    /**
     * @var int
     */
    protected $maxRead = 1024;

    /**
     * Server constructor.
     * @param string $host
     * @param string $port
     */
    public function __construct(string $host, string $port)
    {
        $this->host = $host;
        $this->port = $port;

        $this->socket = socket_create(AF_INET, SOCK_STREAM, 0);
        socket_bind($this->socket, $this->host, $this->port);
    }

    /**
     * @param $callback
     */
    public function listen($callback): void
    {
        while(true) {
            socket_listen($this->socket);
            $client = socket_accept($this->socket);

            if(!$client) {
                socket_close($client);
                continue;
            }

            $text = '';
            while($line = socket_read($client, $this->maxRead)) $text .= $line;

            $request = Request::createRequest($text);

            $response = call_user_func($callback, $request);
            $responseText = (string) $response;

            socket_write($client, $responseText, strlen($responseText));
            socket_close($client);
        }
    }
}