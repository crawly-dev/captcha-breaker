<?php


namespace Crawly\CaptchaBreaker\Provider;


use Crawly\CaptchaBreaker\Exception\SetupFailedException;
use Crawly\CaptchaBreaker\Exception\TaskCreationFailed;

interface ProviderInterface
{
    /**
     * @return string
     * @throws TaskCreationFailed If the captcha breaker provider throws a task creation exception.
     */
    public function solve(): string;

    /**
     * @param array $data
     * @throws SetupFailedException If provider setup fails
     */
    public function setup(array $data): void;
}