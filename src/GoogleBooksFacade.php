<?php

namespace Scriptotek\GoogleBooks;

use Illuminate\Support\Facades\Facade;

class GoogleBooksFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'googlebooks';
    }
}
