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

namespace PHPDevsr\Recaptcha\Config;

use CodeIgniter\Config\BaseService;
use PHPDevsr\Recaptcha\Recaptcha;

class Services extends BaseService
{
    public static function recaptcha(bool $getShared = true): Recaptcha
    {
        if ($getShared) {
            return self::getSharedInstance('recaptcha');
        }

        return new Recaptcha(config('Recaptcha'));
    }
}
