<?php


namespace Crawly\CaptchaBreaker\Provider\AntiCaptcha;

use Crawly\CaptchaBreaker\Exception\BreakFailedException;
use Crawly\CaptchaBreaker\Exception\SetupFailedException;
use Crawly\CaptchaBreaker\Exception\TaskCreationFailed;
use Crawly\CaptchaBreaker\Provider\ProviderInterface;

class ReCaptchaV3 extends AntiCaptcha implements ProviderInterface
{
    private $websiteURL;
    private $websiteKey;
    private $pageAction;
    private $minScore;

    protected function getPostData()
    {
        return [
            "type" => "RecaptchaV3TaskProxyless",
            "websiteURL" => $this->websiteURL,
            "websiteKey" => $this->websiteKey,
            "minScore" => $this->minScore,
            "pageAction" => $this->pageAction
        ];
    }

    private function getTaskSolution()
    {
        return $this->taskInfo->solution->gRecaptchaResponse;
    }

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

    public function setup(array $data): void
    {
        dump($data);
        $requiredKeys = [
            'websiteURL',
            'websiteKey',
            'pageAction',
            'minScore',
            'clientKey'
        ];

        $illegalKeys = array_diff($requiredKeys, array_keys($data));
        if (!empty($illegalKeys)) {
            throw new SetupFailedException('Invalid keys: ' . implode(', ', $illegalKeys));
        }

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
}