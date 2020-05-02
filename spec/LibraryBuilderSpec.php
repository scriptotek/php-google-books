<?php

namespace spec\Scriptotek\GoogleBooks;

use Scriptotek\GoogleBooks\LibraryBuilder;
use Scriptotek\GoogleBooks\GoogleBooks;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LibraryBuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new GoogleBooks);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LibraryBuilder::class);
    }
}
