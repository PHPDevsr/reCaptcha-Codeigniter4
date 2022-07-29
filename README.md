# reCAPTCHA Codeigniter 4
Free to Use Library reCAPTCHA v2 for Codeigniter 4.

# What is reCAPTCHA?

reCAPTCHA is a free service that protects your site from spam and abuse. It uses advanced risk analysis engine to tell humans and bots apart. With the new API, a significant number of your valid human users will pass the reCAPTCHA challenge without having to solve a CAPTCHA (See blog for more details). reCAPTCHA comes in the form of a widget that you can easily add to your blog, forum, registration form, etc.

See [the details][1].

# Sign up for an API key pair

To use reCAPTCHA, you need to [sign up for an API key pair][4] for your site. The key pair consists of a site key and secret. The site key is used to display the widget on your site. The secret authorizes communication between your application backend and the reCAPTCHA server to verify the user's response. The secret needs to be kept safe for security purposes.

# Installation

install with composer
```bash
$ composer require phpdevsr/recaptcha-codeigniter4
```

# Usage

## Initialization

```php
<?php

require 'vendor/autoload.php';

use PHPDevsr\Recaptcha\Config\Recaptcha as RecaptchaConfig;
use PHPDevsr\Recaptcha\Recaptcha;

class Example
{
    /**
     * Config Recaptcha
     * 
     * @var RecaptchaConfig $config
     */
    protected RecaptchaConfig $config;

    /**
     * Recaptcha
     * 
     * @var Recaptcha $recaptcha
     */
    protected Recaptcha $recaptcha;

    public function __construct()
    {
        // Set Config
        $this->config = new RecaptchaConfig();

        $this->config->recaptchaSiteKey   = 'your-site-key';
        $this->config->recaptchaSecretKey = 'your-secret-key';
        $this->config->recaptchaLang      = 'id';

        $this->recaptcha = new Recaptcha($this->config);
    }
}
```

## Render reCAPTCHA Widget

- Default
```php
echo $this->recaptcha->getWidget();

// Output
<div class="g-recaptcha" data-sitekey="xxxxx" data-theme="light" data-type="image" data-size="normal" loading="lazy"></div>
```

- Theme
```php
echo $this->recaptcha->getWidget(array('data-theme' => 'dark'));

// Output
<div class="g-recaptcha" data-sitekey="xxxxx" data-theme="dark" data-type="image" data-size="normal" loading="lazy"></div>
```

- Type
```php
echo $this->recaptcha->getWidget(array('data-theme' => 'dark', 'data-type' => 'audio'));

// Output
<div class="g-recaptcha" data-sitekey="xxxxx" data-theme="dark" data-type="audio" data-size="normal" loading="lazy"></div>
```

## Render Script Tag

- Default
```php
echo $this->recaptcha->getScriptTag();

// Output
<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?render=onload&hl=en" defer></script>
```

- Render
```php
echo $this->recaptcha->getScriptTag(array('render' => 'explicit'));

// Output
<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?render=explicit&hl=en" defer></script>
```

- Language
```php
echo $this->recaptcha->getScriptTag(array('hl' => 'id'));

// Output
<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?render=onload
&hl=id" defer></script>
```

## Verify Response

Calls the reCAPTCHA siteverify API to verify whether the user passes `g-recaptcha-response` POST parameter.

```php
$captcha = $this->request->getPost('g-recaptcha-response');
$response = $this->recaptcha->verifyResponse($captcha);

if (isset($response['success']) and $response['success'] === true) {
    echo "You got it!";
}
```

# Contributor

<a href="https://github.com/PHPDevsr/reCaptcha-Codeigniter4/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=PHPDevsr/reCaptcha-Codeigniter4" />
</a>

Made with [contrib.rocks](https://contrib.rocks).

[1]: https://www.google.com/recaptcha/intro/index.html
[2]: http://www.codeigniter.com/
[3]: https://developers.google.com/recaptcha/
[4]: http://www.google.com/recaptcha/admin