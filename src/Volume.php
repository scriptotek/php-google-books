<?php

namespace Scriptotek\GoogleBooks;

class Volume
{
    protected $client;
    protected $data;
    protected $full = false;

    protected static $coverSizes = [
        "extraLarge",
        "large",
        "medium",
        "small",
        "thumbnail",
        "smallThumbnail",
    ];

    public function __construct($client, $data, $full=false)
    {
        $this->client = $client;
        $this->data = $data;
    }

    /**
     * Returns cover of size $preferredSize or smaller if a cover of size $preferredSize does not exist.
     *
     * @param string $preferredSize
     * @return mixed|null
     */
    public function getCover($preferredSize='extraLarge')
    {
        $url = null;

        if (!isset($this->data->volumeInfo) || !isset($this->data->volumeInfo->imageLinks)) {
            return null;
        }

        if (!$this->full && !in_array($preferredSize, ['thumbnail', 'smallThumbnail'])) {
            // Need to fetch the full record
            $this->data = $this->client->getItem('volumes/' . $this->id);
        }

        $idx = array_search($preferredSize, self::$coverSizes);
        if ($idx === false) {
            throw new \InvalidArgumentException('Invalid size: ' . $preferredSize);
        }

        foreach (self::$coverSizes as $n => $size) {
            if ($n >= $idx && isset($this->data->volumeInfo->imageLinks->{$size})) {
                $url = $this->data->volumeInfo->imageLinks->{$size};
                break;
            }
        }

        return $this->removeCoverEdge($url);
    }

    protected function removeCoverEdge($url) {
        // Remove cover edge
        if (is_null($url)) {
            return null;
        }
        return str_replace('&edge=curl', '&edge=none', $url);
    }

    public function __get($key)
    {
        if (isset($this->data->volumeInfo->{$key})) {
            return $this->data->volumeInfo->{$key};
        } else if (isset($this->data->{$key})) {
            return $this->data->{$key};
        }
    }
    
    public function __isset($key)
    {
        return isset($this->data->volumeInfo->{$key}) || isset($this->data->{$key});
    }

    public function __toString()
    {
        return json_encode($this->data);
    }
}
