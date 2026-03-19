<?php

namespace FloCMS\Captcha;

use FloCMS\Captcha\Renderer\GdRenderer;
use FloCMS\Captcha\Renderer\RendererInterface;

class CaptchaManager
{
    protected Captcha $captcha;
    protected RendererInterface $renderer;

    public function __construct(array $config = [], ?RendererInterface $renderer = null)
    {
        $this->captcha = new Captcha($config);
        $this->renderer = $renderer ?? new GdRenderer($this->captcha->getConfig());
    }

    public function output(): void
    {
        $text = $this->captcha->refresh();
        $this->renderer->render($text);
    }

    public function verify(?string $input): bool
    {
        return $this->captcha->verify($input);
    }

    public function clear(): void
    {
        $this->captcha->clear();
    }

    public function refresh(): string
    {
        return $this->captcha->refresh();
    }

    public function getCaptcha(): Captcha
    {
        return $this->captcha;
    }
}