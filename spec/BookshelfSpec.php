<?php

namespace spec\Scriptotek\GoogleBooks;

use Mockery as m;
use Scriptotek\GoogleBooks\Bookshelf;
use PhpSpec\ObjectBehavior;
use Scriptotek\GoogleBooks\GoogleBooks;
use Scriptotek\GoogleBooks\Volume;

class BookshelfSpec extends ObjectBehavior
{
    function init($shelf = 'bookshelf.json', $volumes = null, $volume = null)
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

        $this->beConstructedWith($books, json_decode(file_get_contents(__DIR__ . '/dummy/' . $shelf)));
    }

    function it_is_initializable()
    {
        $this->init();
        $this->shouldHaveType(Bookshelf::class);
    }

    public function it_should_have_title()
    {
        $this->init();
        $this->title->shouldBe('Photovoltaics');
    }

    public function it_should_have_volumes()
    {
        $this->init('bookshelf.json', 'volumes.json');
        $this->getVolumes()->shouldHaveType(\Generator::class);
        $this->getVolumes()->current()->shouldHaveType(Volume::class);
    }

    public function it_should_be_string_serializable()
    {
        $this->init();
        $this->__toString()->shouldStartWith('{');
    }
}
