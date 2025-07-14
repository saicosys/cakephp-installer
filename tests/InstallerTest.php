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
namespace Saicosys\Installer\Test;

use PHPUnit\Framework\TestCase;
use Saicosys\Installer\Command\NewCommand;
use Saicosys\Installer\Installer\CakePHPInstaller;

/**
 * Unit tests for the CakePHP Installer core classes.
 *
 * Ensures that key installer classes exist and are autoloadable.
 *
 * @package Saicosys\Installer
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
