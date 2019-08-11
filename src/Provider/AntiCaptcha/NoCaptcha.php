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
class NoCaptcha extends Anticaptcha implements ProviderInterface
{
    protected $websiteUrl;
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
            "type" => "NoCaptchaTask",
            "websiteURL" => $this->websiteUrl,
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
            throw new TaskCreationFailed($this->getErrorMessage());
        }

        if (!$this->waitForResult()) {
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
            'websiteUrl',
            'websiteKey',
            'websiteToken',
            'userAgent'
        ];

        $allowedKeys = [
            'proxyType',
            'proxyHost',
            'proxyPort',
            'proxyLogin',
            'proxyPassword',
            'cookies'
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