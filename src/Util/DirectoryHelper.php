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
* @link      https://www.saicosys.com
* @since     1.0.0
* @license   MIT License (https://opensource.org/licenses/mit-license.php )
*/
namespace Saicosys\Installer\Util;

use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Utility class for directory operations such as recursive removal.
 *
 * Provides static methods to safely remove directories and their contents, with optional CLI feedback.
 *
 * @package Saicosys\Installer
 */
class DirectoryHelper
{
    /**
     * Recursively remove a directory and its contents.
     *
     * @param  string            $path The directory path to remove
     * @param  SymfonyStyle|null $io   (optional) for CLI warnings
     * @return void
     */
    public static function removeDirectory(string $path, ?SymfonyStyle $io = null): void
    {
        // If the path is not a directory, nothing to do
        if (!is_dir($path)) {
            return;
        }

        // Get all files and directories except . and ..
        $files = array_diff(scandir($path), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $path . DIRECTORY_SEPARATOR . $file;
            if (is_dir($filePath)) {
                // Recursively remove subdirectories
                self::removeDirectory($filePath, $io);
            } else {
                // Try to set permissions and delete the file
                @chmod($filePath, 0666);
                if (!@unlink($filePath)) {
                    $msg = "Warning: Could not delete file: $filePath. Please check permissions.";
                    // Output warning via SymfonyStyle if available, else print
                    $io ? $io->warning($msg) : print($msg . PHP_EOL);
                }
            }
        }
        // Set permissions and try to remove the now-empty directory
        @chmod($path, 0777);
        if (!@rmdir($path)) {
            $msg = "Warning: Could not remove directory: $path. Please check permissions.";
            // Output warning via SymfonyStyle if available, else print
            $io ? $io->warning($msg) : print($msg . PHP_EOL);
        }
    }
} 
