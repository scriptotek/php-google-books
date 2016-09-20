<?php

namespace spec\Scriptotek\GoogleBooks;

use Scriptotek\GoogleBooks\Bookshelves;
use Scriptotek\GoogleBooks\GoogleBooks;
use PhpSpec\ObjectBehavior;
use Scriptotek\GoogleBooks\Volumes;

class GoogleBooksSpec extends ObjectBehavior
{

    public function it_is_initializable()
    {
        $this->shouldHaveType(GoogleBooks::class);
    }

    public function it_should_have_volumes()
    {
        $this->volumes->shouldHaveType(Volumes::class);
    }

    public function it_should_have_bookshelves()
    {
        $this->bookshelves->shouldHaveType(Bookshelves::class);
    }
}
