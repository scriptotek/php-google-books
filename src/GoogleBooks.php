<?php

namespace Scriptotek\GoogleBooks;

use GuzzleHttp\Client;

class GoogleBooks
{
    /**
     * @var string
     */
    protected $baseUri = 'https://www.googleapis.com/books/v1/';

    /**
     * @var integer (Number of results to retrieve per batch, between 1 and 40)
     */
    protected $batchSize = 40;

    /**
     * @var Client
     */
    protected $http;

    /**
     * @var key string API key
     */
    protected $key;

    /**
     * @var country string 2 letter ISO 639 country code.
     *
     * The Books API must honor copyright laws from various countries, and have
     * country-specific rights from publishers. It uses the IP address of the
     * client to geo-locate the user, but if this fails for some reason, it will
     * return 403 Forbidden with reason "unknownLocation". To avoid this, we can
     * manually set the country code.
     */
    protected $country;

    /**
     * @var Volumes
     */
    public $volumes;

    /**
     * @var Bookshelves
     */
    public $bookshelves;
    
    /**
     * @var maxResults
     */
    public $maxResults;

    public function __construct($options = [])
    {
        $this->http = new Client([
            'base_uri' => $this->baseUri,
            'handler' => isset($options['handler']) ? $options['handler'] : null,
        ]);

        $this->key = isset($options['key']) ? $options['key'] : null;
        $this->country = isset($options['country']) ? $options['country'] : null;

        $this->volumes = new Volumes($this);
        $this->bookshelves = new Bookshelves($this);

        $this->batchSize = isset($options['batchSize']) ? $options['batchSize'] : 40;

        $this->maxResults = isset($options['maxResults']) ? $options['maxResults'] : null;
    }

    protected function raw($endpoint, $params = [], $method='GET')
    {
        if (!is_null($this->key)) {
            $params['key'] = $this->key;
        }
        if (!is_null($this->country)) {
            $params['country'] = $this->country;
        }

        if (!is_null($this->maxResults)) {
            $params['maxResults'] = $this->maxResults;
        }

        try {
            $response = $this->http->request($method, $endpoint, [
                'query' => $params,
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // 400 level errors
            if ($e->getResponse()->getStatusCode() == 403) {
                $json = json_decode($e->getResponse()->getBody());

                $domain = $json->error->errors[0]->domain;
                $reason = $json->error->errors[0]->reason;
                $message = $json->error->errors[0]->message;

                if ($domain == 'usageLimits') {
                    throw new Exceptions\UsageLimitExceeded($message, $reason);
                }
            }

            throw $e;

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // networking error (connection timeout, DNS errors, etc.)

            // TODO: sleep and retry

            throw $e;
        }

        return json_decode($response->getBody());
    }

    public function getItem($path)
    {
        return $this->raw($path);
    }

    public function listItems($endpoint, $params = [])
    {
        $params['maxResults'] = $this->batchSize;

        $i = 0;
        while (true) {
            $n = $i % $this->batchSize;
            if ($n == 0) {
                $params['startIndex'] = $i;
                $response = $this->raw($endpoint, $params);
            }
            if (isset($response->totalItems) && $i >= $response->totalItems) {
                return;
            }
            if (!isset($response->items[$n])) {
                return;
            }
            yield $response->items[$n];
            $i++;
        }
    }

}
