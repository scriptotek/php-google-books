<?php

namespace Scriptotek\GoogleBooks;

class Bookshelf
{
    protected $data;
    protected $uid;
    protected $client;

    public function __construct(GoogleBooks $client, $data)
    {
        $this->client = $client;
        $this->data = $data;
        $selfLinkParts = explode('/', $data->selfLink);
        $this->uid = $selfLinkParts[count($selfLinkParts) - 3];
    }

    public function getVolumes()
    {
        foreach ($this->client->listItems("users/$this->uid/bookshelves/$this->id/volumes") as $item) {
            yield new Volume($this->client, $item);
        }
    }

    public function __get($key)
    {
        if (isset($this->data->{$key})) {
            return $this->data->{$key};
        }
    }
    
    public function __isset($key)
    {
        return isset($this->data->{$key});
    }

    public function __toString()
    {
        return json_encode($this->data);
    }
}
