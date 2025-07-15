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
namespace Saicosys\Installer\Test;

use PHPUnit\Framework\TestCase;
use Saicosys\Installer\Command\NewCommand;
use Saicosys\Installer\Installer\CakePHPInstaller;

/**
 * Unit tests for the CakePHP Installer core classes.
 *
 * Ensures that key installer classes exist and are autoloadable.
 *
 * @category  Plugin
 * @package   Saicosys/Installer
 * @author    Saicosys <info@saicosys.com>
 * @copyright Copyright (c) 2017-2025, Saicosys Technologies
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @link      https://www.saicosys.com
 * @since     1.0.0
 */
class InstallerTest extends TestCase
{
    /**
     * Test that the CakePHPInstaller class exists and is autoloadable.
     *
     * @return void
     */
    public function testCakePHPInstallerExists(): void
    {
        // Assert that the CakePHPInstaller class can be found
        $this->assertTrue(class_exists(CakePHPInstaller::class));
    }

    /**
     * Test that the NewCommand class exists and is autoloadable.
     *
     * @return void
     */
    public function testNewCommandExists(): void
    {
        // Assert that the NewCommand class can be found
        $this->assertTrue(class_exists(NewCommand::class));
    }
} 
