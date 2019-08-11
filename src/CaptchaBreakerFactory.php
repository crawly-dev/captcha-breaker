<?php

namespace Crawly\CaptchaBreaker;

use Crawly\CaptchaBreaker\Exception\InvalidProviderException;
use Crawly\CaptchaBreaker\Provider\AntiCaptcha\NoCaptcha;
use Crawly\CaptchaBreaker\Provider\ProviderInterface;

class CaptchaBreakerFactory
{
    public const PROVIDER_ANTICAPTCHA_NOCAPTCHA = 'anticaptcha/nocaptcha';

    private $providers;

    public function __construct(array $providers = []) {
        if (empty($providers)) {
            $this->setDefaultProviders();
        }
    }

    private function setDefaultProviders()
    {
        $this->providers =  [self::PROVIDER_ANTICAPTCHA_NOCAPTCHA => NoCaptcha::class];
    }

    /**
     * Creates a new provider class
     *
     * @param string $provider The provider name
     * @param array $providerData OPTIONAL - Sets up the provider with the data it needs in order to create a captcha breaking task.
     * @return ProviderInterface
     * @throws InvalidProviderException If the user specifies an invalid
     */
    public function create(string $provider, array $providerData = []): ProviderInterface
    {
        if (!isset($this->providers[$provider])) {
            throw new InvalidProviderException($provider);
        }
    }
}