<?php

namespace FloCMS\Captcha;

class Captcha
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $defaults = require __DIR__ . '/../config/captcha.php';
        $this->config = array_replace_recursive($defaults, $config);
    }

    public function generateText(): string
    {
        $characters = (string) $this->config['characters'];
        $length = (int) $this->config['length'];
        $maxIndex = strlen($characters) - 1;

        $text = '';

        for ($i = 0; $i < $length; $i++) {
            $text .= $characters[random_int(0, $maxIndex)];
        }

        return $text;
    }

    public function store(string $text): void
    {
        $this->startSessionIfNeeded();
        $_SESSION[$this->config['session_key']] = $text;
    }

    public function getStoredText(): ?string
    {
        $this->startSessionIfNeeded();
        return $_SESSION[$this->config['session_key']] ?? null;
    }

    public function verify(?string $input): bool
    {
        $stored = $this->getStoredText();

        if ($stored === null || $input === null || trim($input) === '') {
            return false;
        }

        $input = trim($input);

        if (!(bool) $this->config['case_sensitive']) {
            $stored = strtoupper($stored);
            $input  = strtoupper($input);
        }

        return hash_equals($stored, $input);
    }

    public function clear(): void
    {
        $this->startSessionIfNeeded();
        unset($_SESSION[$this->config['session_key']]);
    }

    public function refresh(): string
    {
        $text = $this->generateText();
        $this->store($text);
        return $text;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    protected function startSessionIfNeeded(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}