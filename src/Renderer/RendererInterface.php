<?php

namespace FloCMS\Captcha\Renderer;

interface RendererInterface
{
    public function render(string $text): void;
}