<?php

namespace Scriptotek\GoogleBooks;


class Volumes
{
    protected $client;

    public function __construct(GoogleBooks $client)
    {
        $this->client = $client;
    }

    /**
     * @param $query
     * @return \Generator|Volume
     */
    public function search($query)
    {
        foreach ($this->client->listItems('volumes', ['q' => $query]) as $item) {
            yield new Volume($this->client, $item);
        }
    }

    public function firstOrNull($query)
    {
        $res = $this->search($query)->current();
        return $res;
    }

    public function get($id)
    {
        return new Volume($this->client, $this->client->getItem("volumes/$id"), true);
    }

    public function byIsbn($isbn)
    {
        $isbn = preg_replace('/[^0-9Xx]/', '', $isbn);

        return $this->firstOrNull('isbn:' . $isbn);
    }
}
