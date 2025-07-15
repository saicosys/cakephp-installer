<?php
declare(strict_types=1);

/**
 * Saicosys Technologies Private Limited
 * Copyright (c) 2017-2025, Saicosys Technologies
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.md
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Saicosys <info@saicosys.com>
 * @copyright Copyright (c) 2017-2025, Saicosys Technologies
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @link      https://www.saicosys.com
 * @since     1.0.0
 */
namespace Saicosys\Installer\Installer;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

/**
 * Installs a new CakePHP 5 application using Composer.
 *
 * This class handles the process of creating a new CakePHP project by invoking Composer.
 *
 * Local Usage:
 *   php bin/cakephp new <name>
 *
 * Global Usage:
 *   cakephp new <name>
 *
 * @category  Plugin
 * @package   Saicosys/Installer
 * @author    Saicosys <info@saicosys.com>
 * @copyright Copyright (c) 2017-2025, Saicosys Technologies
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @link      https://www.saicosys.com
 * @since     1.0.0
 */
class CakePHPInstaller
{
    /**
     * SymfonyStyle IO helper for console output.
     *
     * @var SymfonyStyle
     */
    private SymfonyStyle $io;

    /**
     * Constructor.
     *
     * @param SymfonyStyle $io IO helper for user interaction and output
     */
    public function __construct(SymfonyStyle $io)
    {
        $this->io = $io;
    }

    /**
     * Install a new CakePHP application using Composer.
     *
     * @param  string $name The name of the application (directory to create)
     * @throws \RuntimeException If the Composer process fails
     * @return void
     */
    public function install(string $name): void
    {
        $this->io->section('Installing CakePHP 5 application');

        // Prepare the Composer create-project command
        $process = new Process(
            [
            'composer', 'create-project', '--prefer-dist', 'cakephp/app', $name
            ]
        );
        
        $process->setTimeout(300);

        // Run the process and stream output to the console
        $process->run(
            function ($type, $buffer) {
                // Composer writes progress info to stderr, so we don't treat it as error
                $this->io->write($buffer);
            }
        );

        // Check if the process was successful
        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Failed to create CakePHP project: ' . $process->getErrorOutput());
        }
    }
} 
