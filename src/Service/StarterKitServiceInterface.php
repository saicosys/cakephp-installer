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

/**
 * Interface for CakePHP starter kit service installers.
 *
 * Any starter kit service must implement this interface to provide an install method.
 *
 * @category  Plugin
 * @package   Saicosys/Installer
 * @author    Saicosys <info@saicosys.com>
 * @copyright Copyright (c) 2017-2025, Saicosys Technologies
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @link      https://www.saicosys.com
 * @since     1.0.0
 */
interface StarterKitServiceInterface
{
    /**
     * Install the starter kit into the given directory.
     *
     * @param  string $name The target directory for the new project
     * @return void
     */
    public function install(string $name): void;
} 
