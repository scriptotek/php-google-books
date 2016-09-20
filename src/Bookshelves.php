<?php

namespace Scriptotek\GoogleBooks;

class Bookshelves
{
    protected $client;

    public function __construct(GoogleBooks $client)
    {
        $this->client = $client;
    }

    public function byUser($uid)
    {
        foreach ($this->client->listItems("users/$uid/bookshelves") as $item) {
            yield new Bookshelf($this->client, $item);
        }
    }

    public function get($uid, $id)
    {
        return new Bookshelf($this->client, $this->client->getItem("users/$uid/bookshelves/$id"));
    }

}
