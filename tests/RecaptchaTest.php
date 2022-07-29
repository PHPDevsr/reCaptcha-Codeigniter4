<?php

use CodeIgniter\Test\CIUnitTestCase;
use PHPDevsr\Recaptcha\Config\Recaptcha as RecaptchaConfig;
use PHPDevsr\Recaptcha\Recaptcha;

/**
 * @internal
 */
final class RecaptchaTest extends CIUnitTestCase
{
    public function testGetScriptTagDefault()
    {
        $expected = '<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?render=onload&hl=id" defer></script>';

        $config                     = new RecaptchaConfig();
        $config->recaptchaSiteKey   = 'justtestscripttag';
        $config->recaptchaSecretKey = 'justtestscripttag';
        $config->recaptchaLang      = 'id';

        $recaptcha = new Recaptcha($config);

        $this->assertSame($expected, $recaptcha->getScriptTag());
    }

    public function testGetWidgetTagDefault()
    {
        $config                     = new RecaptchaConfig();
        $config->recaptchaSiteKey   = 'justtestscripttag';
        $config->recaptchaSecretKey = 'justtestscripttag';
        $config->recaptchaLang      = 'id';

        $expected = '<div class="g-recaptcha" data-sitekey="' . $config->recaptchaSiteKey . '" data-theme="light" data-type="image" data-size="normal" loading="lazy"></div>';

        $recaptcha = new Recaptcha($config);

        $this->assertSame($expected, $recaptcha->getWidget());
    }
}
