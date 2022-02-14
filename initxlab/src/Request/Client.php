<?php


namespace Initxlab\Request;


use CurlHandle;

/**
 * Light client to deal with http request to Ngd-API Server. Subject to changes
 * Class Client
 * @package Initxlab\Request
 */
class Client
{
    /**
     * @var string|null
     */
    private ?string $response = null;
    /**
     *
     */
    private const RESPONSE_SUCCESS_STATUSES = [200];

    // the endpoint
    private string $host;
    private string $route;
    private ?string $method = null;
    public function __construct(string $host, string $route, string $method=null)
    {
        $this->host = $host;
        $this->route = $route;
        $this->method = $method;
    }


    /**
     * @param array|null $cOpts
     * @return bool|CurlHandle
     */
    public function createRequest(null|array $cOpts = null): bool|CurlHandle
    {
        $initUrl = curl_init($this->host.$this->route);
        // IF FOUND, INJECT CURL OPTIONS
        if(null !== $cOpts) {
            curl_setopt_array($initUrl, $cOpts);
        }
        return $initUrl;
    }

    /**
     * @param CurlHandle $requestConfig
     */
    public function sendRequest(CurlHandle $requestConfig): void
    {
        if(!$requestConfig instanceof CurlHandle) { return; }

        if( !curl_errno( $requestConfig )){
            $this->response = curl_exec( $requestConfig );
        }

        curl_close($requestConfig);
    }

    /**
     * @return string|null
     */
    public function getResponse(): null|string
    {
        return $this->response;
    }
}