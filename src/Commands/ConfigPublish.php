<?php

/**
 * This file is part of PHPDevsr/recaptcha-codeigniter4.
 *
 * (c) 2023 Denny Septian Panggabean <xamidimura@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace PHPDevsr\Recaptcha\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Autoload;
use Exception;

class ConfigPublish extends BaseCommand
{
    protected $group       = 'Recaptcha CI4';
    protected $name        = 'config:publish';
    protected $description = 'Publish config file to Config folder';

    /**
     * The path to PHPDevsr\Recaptcha\src directory.
     *
     * @var string
     */
    protected $sourcePath;

    // --------------------------------------------------------------------

    /**
     * Copy config file
     */
    public function run(array $params)
    {
        $this->determineSourcePath();
        $this->publishConfig();

        CLI::write('[+] Config file was successfully generated.', 'green');
    }

    // --------------------------------------------------------------------

    /**
     * Determines the current source path from which all other files are located.
     */
    protected function determineSourcePath()
    {
        $this->sourcePath = realpath(__DIR__ . '/../');

        if (in_array($this->sourcePath, ['/', ''], true) || $this->sourcePath === false) {
            CLI::error('Unable to determine the correct source directory. Bailing.');

            exit();
        }
    }

    // --------------------------------------------------------------------

    /**
     * Publish config file.
     */
    protected function publishConfig()
    {
        $path = "{$this->sourcePath}/Config/Recaptcha.php";

        $content = file_get_contents($path);
        $content = str_replace('namespace PHPDevsr\Recaptcha\Config', 'namespace Config', $content);

        $this->writeFile('Config/Recaptcha.php', $content);
    }

    // --------------------------------------------------------------------

    /**
     * Write a file, catching any exceptions and showing a nicely formatted error.
     */
    protected function writeFile(string $path, string $content)
    {
        $config  = new Autoload();
        $appPath = $config->psr4[APP_NAMESPACE];

        $directory = dirname($appPath . $path);

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        if (file_exists($appPath . $path) && CLI::prompt('Config file already exists, do you want to replace it?', ['y', 'n']) === 'n') {
            CLI::error('Cancelled');

            exit();
        }

        try {
            write_file($appPath . $path, $content);
        } catch (Exception $e) {
            $this->showError($e);

            exit();
        }

        $path = str_replace($appPath, '', $path);

        CLI::write(CLI::color('[+] Created: ', 'yellow') . $path);
    }

    // --------------------------------------------------------------------
}
