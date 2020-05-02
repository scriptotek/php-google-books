<?php

namespace Scriptotek\GoogleBooks\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public static function keyNotSpecified()
    {
        return new static('No Google Books API key configured.');
    }
}
