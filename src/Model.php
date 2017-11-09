<?php

namespace Scriptotek\GoogleBooks;

abstract class Model implements \IteratorAggregate
{
    protected $client;
    private $data;

    public function __construct(GoogleBooks $client, \stdClass $data)
    {
        $this->client = $client;
        $this->data = $data;
    }

    /**
     * Returns true if the model is created from a search result response
     * (and thus do not contain all the data of the full record).
     *
     * @return bool
     */
    public function isSearchResult()
    {
        return $this->has('searchInfo');
    }

    /**
     * Expand a search result response object to a full record.
     */
    public function expandToFullRecord()
    {
        $url = $this->client->removeBaseUrl($this->selfLink);
        $this->data = $this->client->getItem($url);
    }

    /**
     * Special method that allows the object to be iterated over, for example
     * with a foreach statement.
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $current = $this->data;
        foreach (explode('.', $key) as $segment) {
            if (!isset($current->{$segment})) {
                return $default;
            }
            $current = $current->{$segment};
        }
        return $current;
    }

    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param  string  $key
     * @return mixed
     */
    public function has($key)
    {
        $current = $this->data;
        foreach (explode('.', $key) as $segment) {
            if (!isset($current->{$segment})) {
                return false;
            }
            $current = $current->{$segment};
        }
        return true;
    }

    /**
     * Get a string representation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }

    /**
     * Provide object-like access to the data.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }
}
