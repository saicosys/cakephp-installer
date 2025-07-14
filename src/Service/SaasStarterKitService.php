<?php
declare(strict_types=1);

/**
* Copyright (c) 2017-present Saicosys Technologies (https://www.saicosys.com)
*
* Licensed under The MIT License
* For full copyright and license information, please see the LICENSE.md
* Redistributions of files must retain the above copyright notice.
*
* @copyright Copyright (c) 2015-present Saicosys Technologies
* @link https://www.saicosys.com
* @since 1.0.0
* @license MIT License (https://opensource.org/licenses/mit-license.php )
*/
namespace Saicosys\Installer\Service;

use Saicosys\Installer\Util\DirectoryHelper;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

/**
 * Service to install the SAAS CakePHP starter kit from a remote repository.
 *
 * Handles cloning, dependency installation, asset building, and setup automation.
 *
 * @package Saicosys\Installer
 */
class SaasStarterKitService implements StarterKitServiceInterface
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
     * Install the SAAS starter kit into the given directory.
     *
     * @param  string $name The target directory for the new project
     * @throws \RuntimeException If any step fails
     * @return void
     */
    public function install(string $name): void
    {
        $this->io->text('Installing SAAS starter kit from GitHub...');
        
        // Clone the SAAS starter kit repository from GitHub
        $process = new Process(
            [
            'git', 'clone', 'https://github.com/sandeep-kadyan/cakephp-starter-kit.git', $name
            ]
        );
        $process->setTimeout(300);
        // Run the git clone process and stream output
        $process->run(
            function ($type, $buffer) {
                $this->io->write($buffer);
            }
        );
        if (!$process->isSuccessful()) {
            // Throw if cloning fails
            throw new \RuntimeException('Failed to clone SAAS starter kit: ' . $process->getErrorOutput());
        }

        // Remove .git directory to make it a fresh project
        $gitDir = $name . '/.git';
        if (is_dir($gitDir)) {
            // Use DirectoryHelper to remove the .git directory
            DirectoryHelper::removeDirectory($gitDir, $this->io);
        }

        // Install Composer dependencies with no interaction
        $this->io->text('Installing Composer dependencies...');
        $process = new Process(['composer', 'install'], $name);
        $process->setTimeout(300);
        // Run the composer install process and stream output
        $process->run(
            function ($type, $buffer) {
                $this->io->write($buffer);
            }
        );
        if (!$process->isSuccessful()) {
            // Throw if Composer install fails
            throw new \RuntimeException('Failed to install Composer dependencies: ' . $process->getErrorOutput());
        }

        // Install NPM dependencies if package.json exists
        $packageJson = $name . '/package.json';
        if (file_exists($packageJson)) {
            $this->io->text('Installing NPM dependencies...');
            // Run npm install in the project directory
            $process = new Process(['npm', 'install'], $name);
            $process->setTimeout(300);
            $process->run(
                function ($type, $buffer) {
                    $this->io->write($buffer);
                }
            );
        }

        // Build assets if build script exists in package.json
        $packageJsonContent = file_exists($packageJson) ? file_get_contents($packageJson) : '';
        if (strpos($packageJsonContent, '"build"') !== false) {
            $this->io->text('Building assets...');
            // Run npm run build in the project directory
            $process = new Process(['npm', 'run', 'build'], $name);
            $process->setTimeout(300);
            $process->run(
                function ($type, $buffer) {
                    $this->io->write($buffer);
                }
            );
        }

        // Automate folder permissions prompt if needed
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $cakeCmd = $isWindows ? ['php', 'bin/cake'] : ['bin/cake'];
        // Suppress output for CakePHP setup
        $process = new Process(array_merge($cakeCmd, ['setup', '-n']), $name);
        $process->setInput("Y\n");
        $process->setTimeout(120);
        $process->run();
    }
}
