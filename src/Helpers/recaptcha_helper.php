<?php

declare(strict_types=1);

/**
 * This file is part of PHPDevsr/recaptcha-codeigniter4.
 *
 * (c) 2023 Denny Septian Panggabean <xamidimura@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

if (! function_exists('getWidget')) {
    /**
     * Render reCAPTCHA Widget
     *
     * @param array $parameters Attribute
     */
    function getWidget(array $parameters = []): string
    {
        $recaptcha = service('recaptcha');

        return $recaptcha->getWidget($parameters);
    }
}

if (! function_exists('getScriptTag')) {
    /**
     * Render Script Tag
     *
     * @param array $parameters Attribute
     */
    function getScriptTag(array $parameters = []): string
    {
        $recaptcha = service('recaptcha');

        return $recaptcha->getScriptTag($parameters);
    }
}

if (! function_exists('verifyResponse')) {
    /**
     * Verify Response
     *
     * @param string $response response string from Recaptcha Verification
     */
    function verifyResponse($response): array
    {
        $recaptcha = service('recaptcha');

        return $recaptcha->verifyResponse($response);
    }
}
