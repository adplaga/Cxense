<?php


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Cxense {

    const BASE_URI = 'https://api.cxense.com';
    const AUTH_HEADER = 'x-cXense-Authentication';

    /**
     * Cxense API Username
     * @var string
     */
    private $username;

    /**
     * Cxense API secret key
     * @var string
     */
    private $apiKey;

    /**
     * Cxense sites ID
     * @var string
     */
    private $siteId;

    /**
     * Cxense API end point
     * @var string
     */
    private $requestPath;

    /**
     * GuzzleHttp client
     * @var GuzzleHttp\Client
     */
    private $client;



    public function __construct($username, $apiKey, $siteId, $requestPath) {

        $this->username = $username;
        $this->apiKey = $apiKey;
        $this->siteIds = $siteId;
        $this->requestPath = $requestPath;
    }

    /**
     * Wrapper around the runRequest method, to fetch the Cxense data.
     * @param  array  $options Request Options
     * @return array
     */
    public function getData($options = [])
    {
        return $this->runRequest($options);
    }


    /**
     * Create the authentication header value needed when sending
     * requests to the Cxense Insight API     *
     * @return string
     */
    private function generateAuthHeaderValue()
    {
        $date = date('Y-m-d\TH:i:s.000O');
        $signature = hash_hmac('sha256', $date, $this->apiKey);

        return "username=$this->username date=$date hmac-sha256-hex=$signature";
    }


    /**
     * Returns the client. Client is created if it is not yet set.
     *
     * @return GuzzleHttp\Client
     */
    private function getClient()
    {
        if (!$this->client) {
            $this->client = new Client(['base_uri' => self::BASE_URI]);
        }

        return $this->client;
    }


    /**
     * Run the request with the given options
     * @param  array
     * @return array
     * @throws
     */
    protected function runRequest($options = [])
    {
        if ($this->requestPath == '') {
            throw new \Exception('requestPath must be specified.');
        }

        $response = $this->getClient()->post(
            $this->requestPath,
            [
                'body' => json_encode($options),
                'headers' => [
                    self::AUTH_HEADER => $this->generateAuthHeaderValue()
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }




}
