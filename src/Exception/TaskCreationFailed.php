<?php

namespace Crawly\CaptchaBreaker\Exception;

/**
 * Class TaskCreationFailed
 * @package Crawly\CaptchaBreaker\Exception
 */
class TaskCreationFailed extends \Exception
{
    /** @var string */
    protected $providerdata;

    /**
     * TaskCreationFailed constructor.
     * @param string $providerdata
     */
    public function __construct(string $providerdata)
    {
        parent::__construct('CaptchaBreaker was unable to communicate with the captcha provider.', 'CB001');
        $this->providerdata = $providerdata;
    }

    /**
     * ProviderData Getter
     * @return string Returns the provider error message
     */
    public function getProviderData(): string
    {
        return $this->providerdata;
    }
}