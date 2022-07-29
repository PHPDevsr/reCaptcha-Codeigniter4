<?php

namespace PHPDevsr\Recaptcha\Config;

use CodeIgniter\Config\BaseConfig;

class Recaptcha extends BaseConfig
{
    /**
     * Site Key
     *
     * @see http://www.google.com/recaptcha/admin
     */
    public string $recaptchaSiteKey = '';

    /**
     * Secret Key
     *
     * @see http://www.google.com/recaptcha/admin
     */
    public string $recaptchaSecretKey = '';

    /**
     * Language
     *
     * @see http://www.google.com/recaptcha/admin
     */
    public string $recaptchaLang = 'en';
}
