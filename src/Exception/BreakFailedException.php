<?php


namespace Crawly\CaptchaBreaker\Exception;

use Throwable;

/**
 * Class BreakFailedException
 * @package Crawly\CaptchaBreaker\Exception
 */
class BreakFailedException extends \Exception
{
    public function __construct(?string $providerMessage = '')
    {
        parent::__construct($providerMessage ?? 'Could not break the provided captcha', 'CB003');
    }
}
