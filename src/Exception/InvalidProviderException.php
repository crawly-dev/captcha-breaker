<?php

namespace Crawly\CaptchaBreaker\Exception;

/**
 * Class InvalidProviderException
 * @package Crawly\CaptchaBreaker\Exception
 */
class InvalidProviderException extends \Exception
{
    public function __construct(string $provider)
    {
        parent::__construct('Invalid provider: ' . $provider, 'CB002');
    }
}