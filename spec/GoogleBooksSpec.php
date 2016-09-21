<?php

namespace spec\Scriptotek\GoogleBooks;

use Scriptotek\GoogleBooks\Bookshelves;
use Scriptotek\GoogleBooks\GoogleBooks;
use Scriptotek\GoogleBooks\Exceptions\UsageLimitExceeded;

use PhpSpec\ObjectBehavior;
use Scriptotek\GoogleBooks\Volumes;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

class GoogleBooksSpec extends ObjectBehavior
{
    public function initHttpMock403()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            ClientException::create(
                new Request('GET', 'test'),
                new Response(403, [], '{
                     "error": {
                      "errors": [
                       {
                        "domain": "usageLimits",
                        "reason": "userRateLimitExceededUnreg",
                        "message": "User Rate Limit Exceeded. Please sign up",
                        "extendedHelp": "https://code.google.com/apis/console"
                       }
                      ],
                      "code": 403,
                      "message": "User Rate Limit Exceeded. Please sign up"
                     }
                    }')
            ),
        ]);

        $handler = HandlerStack::create($mock);
        $this->beConstructedWith(['handler' => $handler]);
    }

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

    public function it_throws_exception_on_unregistered_usage_rate_exceeded()
    {
        $this->initHttpMock403();
        $this->shouldThrow(UsageLimitExceeded::class)->duringGetItem('documents/1');
    }
}
