<?php

namespace Crawly\CaptchaBreaker;

use Crawly\CaptchaBreaker\Exception\InvalidProviderException;
use Crawly\CaptchaBreaker\Provider\AntiCaptcha\NoCaptcha;
use Crawly\CaptchaBreaker\Provider\AntiCaptcha\ReCaptchaV3;
use Crawly\CaptchaBreaker\Provider\ProviderInterface;

class CaptchaBreakerFactory
{
    public const PROVIDER_ANTICAPTCHA_NOCAPTCHA = 'anticaptcha/nocaptcha';
    public const PROVIDER_ANTICAPTCHA_RECAPTCHA_V3 = 'anticaptcha/recaptchav3';

    private $providers;

    public function __construct(array $providers = []) {
        if (empty($providers)) {
            $this->setDefaultProviders();
        }
    }

    private function setDefaultProviders()
    {
        $this->providers =  [
            self::PROVIDER_ANTICAPTCHA_NOCAPTCHA => NoCaptcha::class,
            self::PROVIDER_ANTICAPTCHA_RECAPTCHA_V3 => ReCaptchaV3::class,
        ];
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

        /** @var ProviderInterface $provider */
        $providerObj = new $this->providers[$provider];

        if (!empty($providerData)) {
            $providerObj->setup($providerData);
        }

        return $providerObj;
    }
}