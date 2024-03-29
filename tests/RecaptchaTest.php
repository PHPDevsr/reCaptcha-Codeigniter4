<?php

/**
 * This file is part of PHPDevsr/recaptcha-codeigniter4.
 *
 * (c) 2023 Denny Septian Panggabean <xamidimura@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use Exception;
use PHPDevsr\Recaptcha\Config\Recaptcha as RecaptchaConfig;
use PHPDevsr\Recaptcha\Recaptcha;

/**
 * @internal
 */
final class RecaptchaTest extends CIUnitTestCase
{
    public function testExceptionAPIKey()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('To use reCAPTCHA you must get an API key from https://www.google.com/recaptcha/admin');

        $config                     = new RecaptchaConfig();
        $config->recaptchaSiteKey   = '';
        $config->recaptchaSecretKey = '';
        $config->recaptchaLang      = 'id';

        $recaptcha = new Recaptcha($config);
        $recaptcha->verifyResponse('justtestverify');
    }

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

    public function testGetScriptTagServiceDefault()
    {
        $expected = '<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?render=onload&hl=en" defer></script>';

        $recaptcha = service('recaptcha');

        $this->assertSame($expected, $recaptcha->getScriptTag());
    }

    public function testGetWidgetTagServiceDefault()
    {
        $expected = '<div class="g-recaptcha" data-sitekey="" data-theme="light" data-type="image" data-size="normal" loading="lazy"></div>';

        $recaptcha = service('recaptcha');

        $this->assertSame($expected, $recaptcha->getWidget());
    }

    public function testGetScriptTagHelperDefault()
    {
        $expected = '<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?render=onload&hl=en" defer></script>';

        helper('recaptcha');

        $this->assertSame($expected, getScriptTag());
    }

    public function testGetWidgetTagHelperDefault()
    {
        $expected = '<div class="g-recaptcha" data-sitekey="" data-theme="light" data-type="image" data-size="normal" loading="lazy"></div>';

        helper('recaptcha');

        $this->assertSame($expected, getWidget());
    }
}
