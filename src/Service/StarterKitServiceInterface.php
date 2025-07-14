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

/**
 * Interface for CakePHP starter kit service installers.
 *
 * Any starter kit service must implement this interface to provide an install method.
 *
 * @package Saicosys\Installer
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
