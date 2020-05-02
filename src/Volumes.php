<?php

namespace Scriptotek\GoogleBooks;

use Scriptotek\GoogleBooks\LibraryBuilder;

class Volumes
{
    protected $client;

    public function __construct(GoogleBooks $client)
    {
        $this->client = $client;
    }

    /**
     * @param $query
     * @return stdclass | array
     */
    public function search($query)
    {
        return (new LibraryBuilder($this->client))->search($query);
    }

    /**
     * @param $query
     * @return array
     */
    public function fetchSearch($query)
    {
        $return = [];
        foreach ($this->client->listItems('volumes', ['q' => $query]) as $item) {
            $return[] = new Volume($this->client, $item);
        }

        return $return;
    }

    public function firstOrNull($query)
    {
        return $this->search($query)->first();
    }

    public function find($id)
    {
        return (new LibraryBuilder($this->client))->find($id);
    }

    public function byIsbn($isbn)
    {
        $isbn = preg_replace('/[^0-9Xx]/', '', $isbn);
        return $this->search('isbn:' . $isbn)->first();
    }
}
