<?php

namespace spec\Scriptotek\GoogleBooks;

use Mockery as m;
use Scriptotek\GoogleBooks\GoogleBooks;
use Scriptotek\GoogleBooks\Volume;
use Scriptotek\GoogleBooks\Volumes;
use PhpSpec\ObjectBehavior;

class VolumesSpec extends ObjectBehavior
{
    function init($volumes = 'volumes.json', $volume = null)
    {
        $books = m::mock(GoogleBooks::class);

        if (!is_null($volumes)) {
            $json = json_decode(file_get_contents(__DIR__ . '/dummy/' . $volumes));
            $books->shouldReceive('listItems')->andReturn(isset($json->items) ? $json->items : []);
        }
        if (!is_null($volume)) {
            $json = json_decode(file_get_contents(__DIR__ . '/dummy/' . $volume));
            $books->shouldReceive('getItem')->andReturn($json);
        }

        $this->beConstructedWith($books);
    }

    function it_is_initializable()
    {
        $this->init();
        $this->shouldHaveType(Volumes::class);
    }

    function it_should_provide_search()
    {
        $this->init();
        $res = $this->search('isbn:0253324009');
        $res->shouldHaveType(\Generator::class);
    }

    function it_should_provide_search_based_lookup()
    {
        $this->init('volumes.json', 'volume.json');
        $res = $this->firstOrNull('isbn:0253324009');
        $res->shouldHaveType(Volume::class);
    }

    function it_should_handle_zero_result_responses()
    {
        $this->init('volumes_zero.json');
        $res = $this->firstOrNull('isbn:0253324009');
        $res->shouldBe(null);
    }

    function it_should_provide_lookup_by_isbn()
    {
        $this->init('volumes.json', 'volume.json');
        $res = $this->byIsbn('0253324009');
        $res->shouldHaveType(Volume::class);
    }

    function it_should_provide_lookup_by_id()
    {
        $this->init(null, 'volume.json');
        $res = $this->get('kdwPAQAAMAAJ');
        $res->shouldHaveType(Volume::class);
    }
}
