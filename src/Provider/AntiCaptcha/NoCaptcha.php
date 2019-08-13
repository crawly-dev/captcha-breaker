<?php


namespace Crawly\CaptchaBreaker\Provider\AntiCaptcha;

use Crawly\CaptchaBreaker\Exception\BreakFailedException;
use Crawly\CaptchaBreaker\Exception\SetupFailedException;
use Crawly\CaptchaBreaker\Exception\TaskCreationFailed;
use Crawly\CaptchaBreaker\Provider\ProviderInterface;

/**
 * Class NoCaptcha
 * @package Crawly\CaptchaBreaker\Provider\AntiCaptcha
 *
 * Class that manages all NoCaptcha challenges.
 */
class NoCaptcha extends AntiCaptcha implements ProviderInterface
{
    protected $websiteURL;
    protected $websiteKey;
    protected $websiteSToken;
    protected $proxyType = "http";
    protected $proxyAddress;
    protected $proxyPort;
    protected $proxyLogin;
    protected $proxyPassword;
    protected $userAgent = "";
    protected $cookies = "";

    protected function getPostData()
    {
        return array(
            "type" => empty($this->proxyAddress) ? "NoCaptchaTaskProxyless" : "NoCaptchaTask",
            "websiteURL" => $this->websiteURL,
            "websiteKey" => $this->websiteKey,
            "websiteSToken" => $this->websiteSToken,
            "proxyType" => $this->proxyType,
            "proxyAddress" => $this->proxyAddress,
            "proxyPort" => $this->proxyPort,
            "proxyLogin" => $this->proxyLogin,
            "proxyPassword" => $this->proxyPassword,
            "userAgent" => $this->userAgent,
            "cookies" => $this->cookies
        );
    }

    protected function getTaskSolution()
    {
        return $this->taskInfo->solution->gRecaptchaResponse;
    }

    /**
     * {@inheritDoc}
     */
    public function solve(): string
    {
        if (!$this->createTask()) {
            dump($this->getErrorMessage());
            throw new TaskCreationFailed($this->getErrorMessage());
        }

        if (!$this->waitForResult()) {
            dump($this->getErrorMessage());
            throw new BreakFailedException($this->getErrorMessage());
        }

        return $this->getTaskSolution();
    }

    /**
     * {@inheritDoc}
     */
    public function setup(array $data): void
    {
        $requiredKeys = [
            'websiteURL',
            'websiteKey',
            'userAgent'
        ];

        $allowedKeys = [
            'websiteToken',
            'proxyType',
            'proxyHost',
            'proxyPort',
            'proxyLogin',
            'proxyPassword',
            'cookies',
            'invisible'
        ];

        $illegalKeys = array_intersect($requiredKeys, $allowedKeys, array_keys($data));
        if (!empty($illegalKeys)) {
            throw new SetupFailedException('Invalid keys: ' . implode(', ', $illegalKeys));
        }

        $missingKeys = array_diff($requiredKeys, array_keys($data));
        if (!empty($missingKeys)) {
            throw new SetupFailedException('Missing required keys: ' . implode(', ', $missingKeys));
        }

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
}