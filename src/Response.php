<?php

namespace PHPServer;

class Response
{
    const STATUS_CODE_OK = 200;
    const STATUS_CODE_NOT_FOUND = 404;

    /**
     * @var string
     */
    protected $body = '';

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var int|null
     */
    protected $status = self::STATUS_CODE_OK;

    /**
     * @var array
     */
    protected $statusCodes = [
        self::STATUS_CODE_OK => 'OK',
        self::STATUS_CODE_NOT_FOUND => 'Not found'
    ];

    /**
     * Response constructor.
     * @param string $body
     * @param int|null $status
     */
    public function __construct(string $body, ?int $status = null)
    {
        if (!is_null($status)) $this->status = $status;

        $this->body = $body;

        $this->header('Date:', gmdate('D , d M Y H:i:s T'))
             ->header('Content-Type:', 'text/html; charset=utf8')
             ->header('Server', 'PHPServer');
    }

    /**
     * @param string $key
     * @param string $value
     * @return Response
     */
    public function header(string $key, string $value): Response
    {
        $this->headers[ucfirst($key)] = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function buildHeader(): string
    {
        $lines = [];
        $lines[] = 'HTTP/1.1 ' . $this->status . ' ' . $this->statusCodes[$this->status];

        foreach ($this->headers as $key => $value) {
            $lines[] = $key . ': ' . $value;
        }

        return implode("\r\n", $lines) . "\r\n\r\n";
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->buildHeader() . $this->body;
    }


}