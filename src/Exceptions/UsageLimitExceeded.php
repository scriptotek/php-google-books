<?php

namespace Scriptotek\GoogleBooks\Exceptions;

class UsageLimitExceeded extends GoogleApiException
{
    /** @var string Reason can be any of:
       - 'dailyLimitExceeded' : The Courtesy API limit for your project has been reached.
       - 'userRateLimitExceeded': The per-user limit from the Developer Console has been reached.
       - 'userRateLimitExceededUnreg': Limit when unregistered
       - More?
    */
    private $reason;

    public function __construct($message, $reason)
    {
        parent::__construct($message);
        $this->reason = $reason;
    }

    public function getReason()
    {
        return $this->reason;
    }
}
