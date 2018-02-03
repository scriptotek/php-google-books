[![Build Status](https://img.shields.io/travis/scriptotek/php-google-books.svg)](https://travis-ci.org/scriptotek/php-google-books)
[![Scrutinizer code quality](https://scrutinizer-ci.com/g/scriptotek/php-google-books/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/scriptotek/php-google-books/?branch=master)
[![Packagist](https://img.shields.io/packagist/v/scriptotek/google-books.svg)](https://packagist.org/packages/scriptotek/google-books)
[![Gitter](https://badges.gitter.im/scriptotek/php-google-books.svg)](https://gitter.im/scriptotek/php-google-books?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

# php-google-books

Simple PHP package for working with the [Google Books API](https://developers.google.com/books/docs/v1/reference/). With Laravel5 integration.
Doesn't yet support authentication, so it only works with public data.
PRs are welcome.

### Install using Composer

Make sure you have [Composer](https://getcomposer.org) installed, then run

```bash
composer require scriptotek/google-books
```

in your project directory to get the latest stable version of the package.

## Usage

Start by creating a new client:

```php
require_once('vendor/autoload.php');
use Scriptotek\GoogleBooks\GoogleBooks;

$books = new GoogleBooks(['key' => 'YOUR_API_KEY_HERE']);
```

Note that you can also use the API without specifying an API key,
but you will then get a lower request quota. A `UsageLimitExceeded`
exception is thrown when you reach the quota.

### Working with volumes

Getting a single volume by id:

```php
$volume = $books->volumes->get('kdwPAQAAMAAJ');
```

or by ISBN:

```php
$volume = $books->volumes->byIsbn('0521339057');
```

Search:

```php
foreach ($books->volumes->search('Hello world') as $vol) {
    echo $vol->title . "\n";
}
```

Note that the `search()` method returns a generator
that automatically fetches more results until the result
list is depleted. If there are thousands of results this will of course take a *long*
time to fetch, so you probably want to define a limit. Limits  can be defined as an option: `['maxResults' => 10]` inside the `GoogleBooks` class.

### Working with bookshelves

Getting a single bookshelf by user id and shelf id:

```php
$shelf = $books->bookshelves->get('113555231101190020526', '1002');
```

List the public bookshelves of a user, and their volumes:

```php
foreach ($books->bookshelves->byUser('113555231101190020526') as $shelf) {
    echo "<h2>$shelf->title</h2>\n";
    echo "<ul>\n";
    foreach ($shelf->getVolumes() as $vol) {
        echo "  <li>$vol->title</li>\n";
    }
    echo "</ul>\n";
}
```

## Laravel 5 integration

This project ships with a service provider that you can add to the
`$providers` array in your `config/app.php`:

    Scriptotek\GoogleBooks\GoogleBooksServiceProvider::class,

There's also a facade you can add to the `$aliases` array if you like:

    'GoogleBooks' => Scriptotek\GoogleBooks\GoogleBooksFacade::class,

Run

    $ php artisan vendor:publish --provider="Scriptotek\GoogleBooks\GoogleBooksServiceProvider"

to create the `config/googlebooks.php` configuration file.

## Troubleshooting

If you get 403 Forbidden with

```json
{
 "error": {
  "errors": [
   {
    "domain": "global",
    "reason": "unknownLocation",
    "message": "Cannot determine user location for geographically restricted operation."
   }
  ],
  "code": 403,
  "message": "Cannot determine user location for geographically restricted operation."
 }
}
```

it means the Books API failed to locate you based on your ip address. Fix this
by specifying the 2 letter ISO639 country code manually using the `country`
option to the constructor:

```php
$books = new GoogleBooks(['country' => 'NO']);
```
