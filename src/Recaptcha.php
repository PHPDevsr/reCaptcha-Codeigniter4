<?php

namespace PHPDevsr\Recaptcha;

use Exception;
use PHPDevsr\Recaptcha\Config\Recaptcha as RecaptchaConfig;

/**
 * CodeIgniter 4 Recaptcha Library
 *
 * @see    https://github.com/PHPDevsr/reCaptcha-Codeigniter4
 * @see https://www.google.com/recaptcha/admin
 */
class Recaptcha
{
    protected const sign_up_url     = 'https://www.google.com/recaptcha/admin';
    protected const site_verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    protected const api_url         = 'https://www.google.com/recaptcha/api.js';

    protected RecaptchaConfig $config;

    public function __construct(?RecaptchaConfig $config = null)
    {
        // Set Config
        if (null !== $config) {
            $this->config = $config;
        }

        if (empty($this->config->recaptchaSiteKey) || empty($this->config->recaptchaSecretKey)) {
            throw new Exception('To use reCAPTCHA you must get an API key from ' . self::sign_up_url);
        }
    }

    /**
     * HTTP Builder
     *
     * @param array $data Data
     *
     * @return bool|string
     *
     * @codeCoverageIgnore
     */
    protected function _submitHTTPGet(array $data = [])
    {
        $url = $data !== [] ? self::site_verify_url . '?' . http_build_query($data) : self::site_verify_url;

        if (ini_get('allow_url_fopen')) {
            return file_get_contents($url);
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * Calls the reCAPTCHA siteverify API to verify whether the user passes
     * CAPTCHA test.
     *
     * @param string $response response string from Recaptcha Verification
     * @param string $remoteIp IP address of End User
     *
     * @return array
     */
    public function verifyResponse($response, $remoteIp = null)
    {
        $remoteIp = (! empty($remoteIp)) ? $remoteIp : $_SERVER['REMOTE_ADDR'];

        // Discard empty solution submissions
        if (empty($response)) {
            return [
                'success'     => false,
                'error-codes' => 'missing-input',
            ];
        }

        $getResponse = self::_submitHTTPGet(
            [
                'secret'   => $this->config->recaptchaSecretKey,
                'remoteip' => $remoteIp,
                'response' => $response,
            ]
        );

        // get reCAPTCHA server response
        $responses = json_decode($getResponse, true);

        if (isset($responses['success']) && $responses['success'] === true) {
            $status = true;
        } else {
            $status = false;
            $error  = $responses['error-codes'] ?? 'invalid-input-response';
        }

        return [
            'success'     => $status,
            'error-codes' => $error ?? null,
        ];
    }

    /**
     * Render Script Tag
     *
     * onload: Optional.
     * render: [explicit|onload] Optional.
     * hl: Optional.
     * see: https://developers.google.com/recaptcha/docs/display
     *
     * @return string
     */
    public function getScriptTag(array $parameters = [])
    {
        $default = [
            'render' => 'onload',
            'hl'     => $this->config->recaptchaLang,
        ];

        $result = $parameters !== [] ? array_merge($default, $parameters) : $default;

        return sprintf(
            '<script type="text/javascript" src="%s?%s" defer></script>',
            self::api_url,
            http_build_query($result)
        );
    }

    /**
     * Render reCAPTCHA Widget
     *
     * - data-theme: dark|light
     * - data-type: audio|image
     *
     * @return string
     */
    public function getWidget(array $parameters = [])
    {
        $default = [
            'data-sitekey' => $this->config->recaptchaSiteKey,
            'data-theme'   => 'light',
            'data-type'    => 'image',
            'data-size'    => 'normal',
        ];

        $result        = $parameters !== [] ? array_merge($default, $parameters) : $default;
        $countedResult = count($result);

        $html        = '';
        $indexResult = 1;

        foreach ($result as $key => $value) {
            if ($countedResult === $indexResult) {
                $html .= sprintf('%s="%s"', $key, $value);
            } else {
                $html .= sprintf('%s="%s" ', $key, $value);
            }

            $indexResult++;
        }

        return '<div class="g-recaptcha" ' . $html . ' loading="lazy"></div>';
    }
}
