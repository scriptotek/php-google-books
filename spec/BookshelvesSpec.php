<?php

namespace spec\Scriptotek\GoogleBooks;

use Mockery as m;
use Scriptotek\GoogleBooks\Bookshelf;
use Scriptotek\GoogleBooks\Bookshelves;
use PhpSpec\ObjectBehavior;
use Scriptotek\GoogleBooks\GoogleBooks;

class BookshelvesSpec extends ObjectBehavior
{

    function init($bookshelves = 'bookshelves.json', $bookshelf = null)
    {
        $books = m::mock(GoogleBooks::class);

        if (!is_null($bookshelves)) {
            $json = json_decode(file_get_contents(__DIR__ . '/dummy/' . $bookshelves));
            $books->shouldReceive('listItems')->andReturn(isset($json->items) ? $json->items : []);
        }
        if (!is_null($bookshelf)) {
            $json = json_decode(file_get_contents(__DIR__ . '/dummy/' . $bookshelf));
            $books->shouldReceive('getItem')->andReturn($json);
        }

        $this->beConstructedWith($books);
    }

    function it_is_initializable()
    {
        $this->init();
        $this->shouldHaveType(Bookshelves::class);
    }

    function it_should_list_the_bookshelves_of_a_user()
    {
        $this->init();
        $res = $this->byUser('123');
        $res->shouldHaveType(\Generator::class);
    }

    function it_should_provide_bookshelf_by_id()
    {
        $this->init(null, 'bookshelf.json');
        $res = $this->get('123', '4');
        $res->shouldHaveType(Bookshelf::class);
    }
}
