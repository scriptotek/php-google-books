<?php

namespace Scriptotek\GoogleBooks;


class Volumes
{
    protected $client;
    protected $chunk = false;

    public function __construct(GoogleBooks $client)
    {
        $this->client = $client;
    }

    /**
     * @param $query
     * @return \Generator|Volume || array
     */
    public function search($query)
    {
        if ($this->chunk) {
            return $this->client->chunk($this->fetchSearch($query), $this->chunk);
        }
        return $this->fetchSearch($query);
    }

    /**
     * @param $query
     * @return \Generator|Volume
     */
    private function fetchSearch($query)
    {
        foreach ($this->client->listItems('volumes', ['q' => $query]) as $item) {
            yield new Volume($this->client, $item);
        }
    }

    /**
     * @param $chunk
     * @return Volumes
     */
    public function chunk(int $chunk)
    {
        $this->chunk = $chunk;
        return $this;
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
