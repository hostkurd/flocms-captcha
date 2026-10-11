<?php
declare(strict_types=1);

namespace FloCMS\Captcha\Tests;

use FloCMS\Captcha\Captcha;
use PHPUnit\Framework\TestCase;

final class CaptchaTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    public function testTheRightCodePasses(): void
    {
        $captcha = new Captcha();
        $captcha->store('K7MPQ2');

        self::assertTrue($captcha->verify('K7MPQ2'));
    }

    public function testCaseAndSurroundingSpacesAreIgnoredByDefault(): void
    {
        $captcha = new Captcha();
        $captcha->store('K7MPQ2');

        self::assertTrue($captcha->verify('  k7mpq2 '));
    }

    public function testAWrongAnswerUsesUpTheCode(): void
    {
        $captcha = new Captcha();
        $captcha->store('K7MPQ2');

        self::assertFalse($captcha->verify('WRONG1'));
        self::assertNull($captcha->getStoredText());
        self::assertFalse($captcha->verify('K7MPQ2'), 'a second guess must not be checked against the old code');
    }

    public function testARightAnswerCanOnlyBeUsedOnce(): void
    {
        $captcha = new Captcha();
        $captcha->store('K7MPQ2');

        self::assertTrue($captcha->verify('K7MPQ2'));
        self::assertFalse($captcha->verify('K7MPQ2'), 'a replayed form must not pass again');
    }

    public function testEmptyAnswersFailAndStillUseUpTheCode(): void
    {
        $captcha = new Captcha();
        $captcha->store('K7MPQ2');

        self::assertFalse($captcha->verify(''));
        self::assertNull($captcha->getStoredText());
    }

    public function testSingleUseCanBeTurnedOff(): void
    {
        $captcha = new Captcha(['single_use' => false]);
        $captcha->store('K7MPQ2');

        self::assertFalse($captcha->verify('WRONG1'));
        self::assertTrue($captcha->verify('K7MPQ2'));
        self::assertSame('K7MPQ2', $captcha->getStoredText());
    }

    public function testCaseSensitiveComparison(): void
    {
        $captcha = new Captcha(['case_sensitive' => true]);
        $captcha->store('K7MPQ2');

        self::assertFalse($captcha->verify('k7mpq2'));
    }
}
