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
namespace Saicosys\Installer\Service;

use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Service to install the Simple CakePHP starter kit (CakePHP + TailwindCSS).
 *
 * This is a placeholder for the simple starter kit installation logic.
 *
 * @category  Plugin
 * @package   Saicosys/Installer
 * @author    Saicosys <info@saicosys.com>
 * @copyright Copyright (c) 2017-2025, Saicosys Technologies
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @link      https://www.saicosys.com
 * @since     1.0.0
 */
class SimpleStarterKitService implements StarterKitServiceInterface
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
     * Install the Simple starter kit into the given directory.
     *
     * @param  string $name The target directory for the new project
     * @return void
     */
    public function install(string $name): void
    {
        // Output installation start message
        $this->io->text('Installing Simple Starter Kit (default CakePHP + TailwindCSS)...');
        // Here you would implement the logic for the simple starter kit
        // For now, just a placeholder
        $this->io->success('Simple Starter Kit installed (placeholder).');
    }
} 
