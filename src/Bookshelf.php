<?php

namespace Scriptotek\GoogleBooks;

class Bookshelf extends Model
{
    /**
     * Get the user id for this bookshelf.
     *
     * @return string
     */
    public function getUid()
    {
        $selfLinkParts = explode('/', $this->selfLink);
        return $selfLinkParts[count($selfLinkParts) - 3];
    }

    /**
     * Get the volumes contained in this bookshelf.
     *
     * @return Volume[]
     */
    public function getVolumes()
    {
        $endpoint = sprintf('users/%s/bookshelves/%s/volumes', $this->getUid(), $this->id);
        $volumes = [];
        foreach ($this->client->listItems($endpoint) as $item) {
            $volumes[] = new Volume($this->client, $item);
        }
        return $volumes;
    }
}
