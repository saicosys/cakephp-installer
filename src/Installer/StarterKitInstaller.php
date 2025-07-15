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

namespace Saicosys\Installer\Installer;

use Saicosys\Installer\Service\SaasStarterKitService;
use Saicosys\Installer\Service\SimpleStarterKitService;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

/**
 * Installs a new CakePHP application with a selected starter kit.
 *
 * This class handles the process of creating a new CakePHP project using a starter kit service,
 * and configures database, email, and runs migrations as part of the setup.
 *
 * Local Usage:
 *   php bin/cakephp new <name> --starter-kit=<kit>
 *
 * Global Usage:
 *   cakephp new <name> --starter-kit=<kit>
 *
 * @package Saicosys\Installer
 */
class StarterKitInstaller
{
    /**
     * SymfonyStyle IO helper for console output.
     *
     * @var SymfonyStyle
     */
    private SymfonyStyle $io;

    /**
     * Map of starter kit names to their service classes
     *
     * @var array<string, class-string>
     */
    private array $starterKitServices;

    /**
     * Constructor.
     *
     * @param SymfonyStyle $io IO helper for user interaction and output
     */
    public function __construct(SymfonyStyle $io)
    {
        $this->io = $io;
        $this->starterKitServices = [
            'saas' => SaasStarterKitService::class,
            'simple' => SimpleStarterKitService::class,
            // Add more kits here
        ];
    }

    /**
     * Install a new CakePHP application using the selected starter kit.
     *
     * @param  string $name       The name of the application (directory to create)
     * @param  string $starterKit The starter kit key
     * @return void
     */
    public function install(string $name, string $starterKit): void
    {
        $this->io->section("Installing {$starterKit} starter kit");

        // If the starter kit is supported, use its service
        if (isset($this->starterKitServices[$starterKit])) {
            // Get the service class for the selected starter kit
            $serviceClass = $this->starterKitServices[$starterKit];

            // Instantiate the starter kit service with IO helper
            $service = new $serviceClass($this->io);

            // Run the install method of the starter kit service
            $service->install($name);

            // Set bin/cake as executable on Unix systems
            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
            $cakePath = $name . '/bin/cake';
            if (!$isWindows && file_exists($cakePath)) {
                // Make the cake script executable
                @chmod($cakePath, 0755);
            }

            // After install, configure DB, email, migrations
            $this->_configureDatabase($name);
            $this->_updateSecurityAndDebug($name);
            $this->_configureEmail($name);
            $this->_runMigrations($name);

            $this->io->success(
                sprintf('%s starter kit installed successfully!', $starterKit)
            );

            return;
        }

        // Fallback: basic CakePHP install if starter kit is not found
        // Instantiate the basic installer
        $basicInstaller = new CakePHPInstaller($this->io);

        // Run the install method
        $basicInstaller->install($name);

        // Set bin/cake as executable on Unix systems
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $cakePath = $name . '/bin/cake';
        if (!$isWindows && file_exists($cakePath)) {
            // Make the cake script executable
            @chmod($cakePath, 0755);
        }
        $this->io->success('Basic CakePHP application installed.');
    }

    /**
     * Prompt the user for database configuration and update the .env file.
     *
     * @param  string $name The project directory name
     * @return void
     */
    private function _configureDatabase(string $name): void
    {
        $this->io->section('Database Configuration');

        // Prompt for DB connection details
        $dbHost = $this->io->ask('Database host', 'localhost');
        $dbPort = $this->io->ask('Database port', '3306');
        $dbName = $this->io->ask('Database name', 'cakephp_app');
        $dbUser = $this->io->ask('Database username', 'root');
        $dbPass = $this->io->askHidden('Database password') ?? '';

        // Update .env file with DB credentials
        $this->_updateEnvFile(
            $name,
            [
                'export DB_HOST' => $dbHost,
                'export DB_PORT' => $dbPort,
                'export DB_DATABASE' => $dbName,
                'export DB_USERNAME' => $dbUser,
                'export DB_PASSWORD' => $dbPass,
            ]
        );

        $this->io->success('Database configuration saved to .env!');

        // Try to create the database if it does not exist (MySQL/MariaDB)
        try {
            $dsn = "mysql:host=$dbHost;port=$dbPort;charset=utf8mb4";
            // Create PDO connection
            $pdo = new \PDO(
                $dsn,
                $dbUser,
                $dbPass,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]
            );
            // Check if the database already exists
            $result = $pdo->query("SHOW DATABASES LIKE " . $pdo->quote($dbName));
            if ($result->rowCount() === 0) {
                // Create the database if it does not exist
                $pdo->exec("CREATE DATABASE `" . str_replace('`', '``', $dbName) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $this->io->success("Database '$dbName' created successfully!");
            } else {
                $this->io->text("Database '$dbName' already exists.");
            }
        } catch (\PDOException $e) {
            // Warn if unable to create or check the database
            $this->io->warning("Could not check or create database: " . $e->getMessage());
        }
    }

    /**
     * Update the security salt and debug value in the .env file.
     *
     * @param  string $projectPath Path to the project directory
     * @return void
     */
    private function _updateSecurityAndDebug(string $projectPath): void
    {
        // Generate a random 32-character salt
        $salt = bin2hex(random_bytes(32));

        // Prompt user for debug value, default to 'false'
        $debug = $this->io->ask('Set debug mode? (true/false)', 'false');

        $this->_updateEnvFile(
            $projectPath,
            [
                'export SECURITY_SALT' => $salt,
                'export DEBUG' => $debug,
            ]
        );
        $this->io->success('Security salt and debug value updated in .env!');
    }

    /**
     * Prompt the user for email configuration and update the .env file.
     *
     * @param  string $name The project directory name
     * @return void
     */
    private function _configureEmail(string $name): void
    {
        $this->io->section('Email Configuration');

        // Prompt for SMTP details
        $smtpHost = $this->io->ask('SMTP Host', 'smtp.gmail.com');
        $smtpPort = $this->io->ask('SMTP Port', '587');
        $smtpUser = $this->io->ask('SMTP Username');
        $smtpPass = $this->io->askHidden('SMTP Password');
        $smtpClient = $this->io->ask('SMTP Client', null);

        // Update .env file with SMTP credentials
        $this->_updateEnvFile(
            $name,
            [
                'export EMAIL_TRANSPORT_HOST' => $smtpHost,
                'export EMAIL_TRANSPORT_PORT' => $smtpPort,
                'export EMAIL_TRANSPORT_USERNAME' => $smtpUser,
                'export EMAIL_TRANSPORT_PASSWORD' => $smtpPass,
                'export EMAIL_TRANSPORT_CLIENT' => $smtpClient,
                'export EMAIL_FROM' => $smtpUser,
                // Optionally add EMAIL_FROM, EMAIL_TRANSPORT, etc.
            ]
        );
        $this->io->success('Email configuration saved to .env!');
    }

    /**
     * Run CakePHP database migrations in the new project.
     *
     * @param  string $name The project directory name
     * @return void
     */
    private function _runMigrations(string $name): void
    {
        $this->io->section('Running Database Migrations');

        $this->io->text('Running migrations...');

        // Run the migrations command in the project directory
        $process = new Process(['bin/cake', 'migrations', 'migrate'], $name);
        $process->setTimeout(120);
        $process->run(
            function ($type, $buffer) {
                // Output migration progress to the console
                $this->io->write($buffer);
            }
        );

        if ($process->isSuccessful()) {
            $this->io->success('Migrations completed successfully!');
        } else {
            $this->io->warning('Migrations failed: ' . $process->getErrorOutput());
        }
    }

    /**
     * Update the .env file in the project with the given environment variables.
     *
     * @param  string $projectPath Path to the project directory
     * @param  array  $envVars     Key-value pairs to update in the .env file
     * @return void
     */
    private function _updateEnvFile(string $projectPath, array $envVars): void
    {
        $envFile = $projectPath . '/config/.env';

        // Prefer config/.env.example, fallback to .env.example in root
        $envExample = file_exists($projectPath . '/config/.env.example')
            ? $projectPath . '/config/.env.example'
            : $projectPath . '/.env.example';

        // If .env does not exist, copy from example
        if (!file_exists($envFile) && file_exists($envExample)) {
            copy($envExample, $envFile);
        }

        $lines = [];
        if (file_exists($envFile)) {
            // Read all lines from the .env file
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

        $envMap = [];
        // Parse existing .env lines into key-value pairs
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && $line[0] !== '#') {
                [$k, $v] = explode('=', $line, 2);
                $envMap[$k] = $v;
            }
        }

        // Overwrite or add new env vars
        foreach ($envVars as $k => $v) {
            $envMap[$k] = $v;
        }

        $newLines = [];
        // Rebuild the .env file content
        foreach ($envMap as $k => $v) {
            $newLines[] = $k . '=' . $v;
        }

        $envContent = implode(PHP_EOL, $newLines) . PHP_EOL;

        // Write the new content to the .env file
        $result = @file_put_contents($envFile, $envContent);
        if ($result === false) {
            $this->io->warning(
                "Could not write to $envFile. 
                The file may be locked or in use by another process. 
                Please close any editors or Composer processes and try again."
            );
        }
    }
}
