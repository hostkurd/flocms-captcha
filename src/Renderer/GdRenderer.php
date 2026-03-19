<?php

namespace FloCMS\Captcha\Renderer;

use FloCMS\Captcha\Exceptions\CaptchaException;

class GdRenderer implements RendererInterface
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function render(string $text): void
    {
        if (!extension_loaded('gd')) {
            throw new CaptchaException('GD extension is not enabled.');
        }

        $width  = (int) $this->config['width'];
        $height = (int) $this->config['height'];

        $image = imagecreatetruecolor($width, $height);

        if ($image === false) {
            throw new CaptchaException('Unable to create captcha image.');
        }

        imageantialias($image, true);

        $background = imagecolorallocate($image, 245, 245, 245);
        $border     = imagecolorallocate($image, 220, 220, 220);
        $dark       = imagecolorallocate($image, 30, 30, 30);
        $gray       = imagecolorallocate($image, 90, 90, 90);
        $light      = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $background);
        imagerectangle($image, 0, 0, $width - 1, $height - 1, $border);

        for ($i = 0; $i < (int) $this->config['noise_lines']; $i++) {
            $color = ($i % 2 === 0) ? $gray : $light;
            imageline(
                $image,
                random_int(0, $width),
                random_int(0, $height),
                random_int(0, $width),
                random_int(0, $height),
                $color
            );
        }

        $fonts = $this->config['fonts'];
        if (empty($fonts)) {
            imagedestroy($image);
            throw new CaptchaException('No captcha fonts configured.');
        }

        $length = strlen($text);
        $slotWidth = (int) floor(($width - 20) / max($length, 1));
        $fontSize = (int) $this->config['font_size'];

        for ($i = 0; $i < $length; $i++) {
            $font = $fonts[array_rand($fonts)];

            if (!is_file($font)) {
                imagedestroy($image);
                throw new CaptchaException("Captcha font not found: {$font}");
            }

            $angle = random_int(
                (int) $this->config['angle_min'],
                (int) $this->config['angle_max']
            );

            $x = 10 + ($i * $slotWidth) + random_int(2, 8);
            $y = random_int((int) ($height * 0.65), (int) ($height * 0.85));
            $color = ($i % 2 === 0) ? $dark : $gray;

            imagettftext(
                $image,
                $fontSize,
                $angle,
                $x,
                $y,
                $color,
                $font,
                $text[$i]
            );
        }

        header('Content-Type: image/png');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        imagepng($image);
        imagedestroy($image);
    }
}