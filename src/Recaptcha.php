<?php

/**
 * This file is part of PHPDevsr/recaptcha-codeigniter4.
 *
 * (c) 2023 Denny Septian Panggabean <xamidimura@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace PHPDevsr\Recaptcha;

use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\CURLRequest;
use Exception;
use stdClass;

/**
 * CodeIgniter 4 Recaptcha Library
 *
 * @see https://github.com/PHPDevsr/reCaptcha-Codeigniter4
 * @see https://www.google.com/recaptcha/admin
 */
class Recaptcha
{
    protected const sign_up_url     = 'https://www.google.com/recaptcha/admin';
    protected const site_verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    protected const api_url         = 'https://www.google.com/recaptcha/api.js';

    protected object $config;
    protected CURLRequest $curl;

    /**
     * Setup Params from Config
     *
     * @param object $config
     */
    public function __construct($config)
    {
        $this->config = new stdClass();

        // Set Config
        $this->config->recaptchaSiteKey   = env('recaptcha.recaptchaSiteKey') ?? $config->recaptchaSiteKey;
        $this->config->recaptchaSecretKey = env('recaptcha.recaptchaSecretKey') ?? $config->recaptchaSecretKey;
        $this->config->recaptchaLang      = env('recaptcha.recaptchaLang') ?? $config->recaptchaLang;

        $this->curl = Services::curlrequest([
            'timeout' => 5,
            'headers' => [
                'User-Agent' => 'PHPDevsr',
            ],
            'http_errors'     => false,
            'allow_redirects' => true,
            'verify'          => true,
            'version'         => 2.0,
        ]);
    }

    /**
     * HTTP Builder
     *
     * @param array $data Data
     *
     * @codeCoverageIgnore
     */
    protected function _submitHTTPGet(array $data = [])
    {
        $query       = $data !== [] ? ['query' => $data] : [];
        $responseObj = $this->curl->get(self::site_verify_url, $query);

        return $responseObj->getBody();
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
        if ($this->config->recaptchaSiteKey === '' || $this->config->recaptchaSecretKey === '') {
            throw new Exception('To use reCAPTCHA you must get an API key from ' . self::sign_up_url);
        }

        $remoteIp = (empty($remoteIp)) ? $_SERVER['REMOTE_ADDR'] : $remoteIp;

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

        if (array_key_exists('success', $responses) && $responses['success'] === true) {
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
            $html .= $countedResult === $indexResult ? sprintf('%s="%s"', $key, $value) : sprintf('%s="%s" ', $key, $value);

            $indexResult++;
        }

        return '<div class="g-recaptcha" ' . $html . ' loading="lazy"></div>';
    }
}
