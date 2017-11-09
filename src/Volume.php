<?php

namespace Scriptotek\GoogleBooks;

class Volume extends Model
{
    protected static $coverSizes = [
        'extraLarge',
        'large',
        'medium',
        'small',
        'thumbnail',
        'smallThumbnail',
    ];

    /**
     * Provide a shortcut to the data in 'volumeInfo', so we can do e.g.
     * `$volume->title` instead of `$volume->volumeInfo->title`.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get("volumeInfo.$key") ?: $this->get($key);
    }

    /**
     * Get the URL to the largest available cover, but no larger than $preferredSize.
     *
     * @param string $preferredSize
     * @return string|null
     */
    public function getCover($preferredSize = 'extraLarge')
    {
        $url = null;

        if (!$this->has('volumeInfo.imageLinks')) {
            return null;
        }

        if ($this->isSearchResult() && !in_array($preferredSize, ['thumbnail', 'smallThumbnail'])) {
            // The brief record we get from search results only contains the sizes 'smallThumbnail'
            // and 'thumbnail'. To get larger sizes, we need the full record.
            $this->expandToFullRecord();
        }

        $idx = array_search($preferredSize, self::$coverSizes);
        if ($idx === false) {
            throw new \InvalidArgumentException('Invalid size: ' . $preferredSize);
        }

        foreach (self::$coverSizes as $n => $size) {
            if ($n >= $idx && $this->has("volumeInfo.imageLinks.{$size}")) {
                $url = $this->get("volumeInfo.imageLinks.{$size}");
                break;
            }
        }

        return $this->removeCoverEdge($url);
    }

    /**
     * Modify the cover URL to remove the cover edge.
     *
     * @param string $url
     * @return string
     */
    protected function removeCoverEdge($url)
    {
        if (is_null($url)) {
            return null;
        }
        return str_replace('&edge=curl', '&edge=none', $url);
    }
}
