<?php 

namespace Scriptotek\GoogleBooks;

use GuzzleHttp\Client;
use Scriptotek\GoogleBooks\GoogleBooks;

class LibraryBuilder
{
    protected $data;
    protected $client;
    protected $chunk;
    protected $query;
    protected $limit;

    /**
     * Accepts a GoogleBooks dependency and sets it as a client property
     *
     * @param GoogleBooks $GoogleBooks
     */
    public function __construct(GoogleBooks $GoogleBooks)
    {
        $this->client = $GoogleBooks;
    }

    /**
     * Limits the results. May be an integer between 1 and 40
     *
     * @param int $limit
     * @return self
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Sets the query property
     *
     * @param string $query
     * @return self
     */
    public function search($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Fetches results from the Google API
     *
     * @param string $id
     * @return array
     */
    private function fetch($id = false)
    {
        if ($this->limit <= 40) {
            if ($id !== false) {
                return $this->client->getItem("volumes/$id");
            }
            return $this->client->raw('volumes', $this->getParams());
        }
        
        return $this->fetchAll();
    }

    /**
     * Performs multiple searches in order to overcome Googles max of 40 results per request
     *
     * @return array
     */
    private function fetchAll()
    {
        return $this->data = $this->client->listItems('volumes', ['q' => $this->query]);
    }

    /**
     * Finds and returns one result. Should accept a book ID (found on API responses)
     *
     * @param string $id
     * @return Scriptotek\GoogleBooks\Volume
     */
    public function find($id)
    {
        return $this->fetch($id);
    }

    /**
     * Retrieves the first result
     *
     * @return Scriptotek\GoogleBooks\Volume
     */
    public function first()
    {
        if ($this->fetch()->totalItems) {
            return $this->fetch()->items[0];
        }

        return null;
    }

    /**
     * Gets all the results from the query. Possibly chunks the results
     *
     * @return array
     * @see chunk()
     */
    public function all()
    {
        if ($this->chunk) {
            return $this->data = array_chunk($this->fetch()->items, $this->chunk);
        }
        
        return $this->data = $this->fetch();
    }

    /**
     * Sets the chunk property. Used as an alias for array chunking. Primarily for views
     *
     * @param int $chunk
     * @return self
     */
    public function chunk($chunk)
    {
        $this->chunk = $chunk;
        return $this;
    }

    /**
     * Gets the paramters to be passed to the API request.
     *
     * @return array
     */
    private function getParams()
    {
        $params = [];
        $params['q'] = $this->query;
        $params['maxResults'] = $this->limit ?: 40;

        return $params;
    }
}
