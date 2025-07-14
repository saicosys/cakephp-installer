@echo off

REM CakePHP Installer Setup Script for Windows

echo Setting up CakePHP Installer...

REM Install dependencies
composer install

echo CakePHP Installer setup complete!
echo.
echo You can now use the installer:
echo   php bin/cakephp new my-app
echo.
echo Or install globally with:
echo   composer global require cakephp/installer 