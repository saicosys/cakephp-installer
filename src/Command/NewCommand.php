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
namespace Saicosys\Installer\Command;

use Saicosys\Installer\Installer\CakePHPInstaller;
use Saicosys\Installer\Installer\StarterKitInstaller;
use Saicosys\Installer\Util\DirectoryHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * NewCommand class for CakePHP installer.
 *
 * @category  Plugin
 * @package   Saicosys/Installer
 * @author    Saicosys <info@saicosys.com>
 * @copyright Copyright (c) 2017-2025, Saicosys Technologies
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @link      https://www.saicosys.com
 * @since     1.0.0
 */
class NewCommand extends Command
{
    /**
     * Constructor to ensure command name and description are set for all Symfony Console versions.
     */
    public function __construct()
    {
        parent::__construct('new');
        $this->setDescription('Create a new CakePHP application');
    }

    /**
     * The default command name for the installer.
     *
     * @var string
     */
    protected static $defaultName = 'new';

    /**
     * The default command description for the installer.
     *
     * @var string
     */
    protected static $defaultDescription = 'Create a new CakePHP application';

    /**
     * Configure the command arguments, options, and help text.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the application'
            )
            ->addOption(
                'starter-kit',
                null,
                InputOption::VALUE_OPTIONAL,
                'Use a specific starter kit',
                null
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Overwrite existing directory'
            )
            ->setHelp(
                "This command creates a new CakePHP application.\n\n" .
                "Available options:\n" .
                "  --starter-kit=<kit>  Specify the starter kit to use (e.g., 'saas', 'simple').\n" .
                "  --force              Overwrite existing directory if it exists.\n" .
                "\nExample usage:\n" .
                "  php bin/cakephp new <name> --starter-kit=saas\n" .
                "  php bin/cakephp new <name> --force\n"
            );
    }

    /**
     * Execute the command to create a new CakePHP application.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface   $input  The input interface
     * @param  \Symfony\Component\Console\Output\OutputInterface $output The output interface
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $force = $input->getOption('force');
        $starterKit = $input->getOption('starter-kit');

        $io->title('CakePHP Application Installer');

        // Display CakePHP ASCII art logo
        $this->_displayLogo($io);
        
        $io->text("Creating new CakePHP application: <info>{$name}</info>");

        // Check if directory exists
        if (is_dir($name)) {
            if (!$force) {
                $io->error("Directory '{$name}' already exists. Use --force to overwrite.");

                return Command::FAILURE;
            } else {
                $io->warning("Directory '{$name}' already exists. Removing it...");
                // Remove the existing directory if --force is used
                DirectoryHelper::removeDirectory($name, $io);
                $io->text("Directory removed successfully.");
            }
        }

        // Ask user about installation method if not provided
        if ($starterKit === null) {
            $question = new ChoiceQuestion(
                'How would you like to create your new application?',
                ['manual' => 'Manual installation', 'starter-kit' => 'Use a starter kit'],
                'manual'
            );
            $choice = $io->askQuestion($question);
            
            if ($choice === 'starter-kit') {
                // Prompt user to select a starter kit
                $starterKit = $this->_selectStarterKit($io);
            }
        }

        try {
            if ($starterKit) {
                // Use the StarterKitInstaller if a starter kit is selected
                $installer = new StarterKitInstaller($io);
                $installer->install($name, $starterKit);
            } else {
                // Use the CakePHPInstaller for manual installation
                $installer = new CakePHPInstaller($io);
                $installer->install($name);
            }

            $io->success("CakePHP application '{$name}' created successfully!");
            $io->text(
                [
                '',
                'Next steps:',
                "  cd {$name}",
                '  bin/cake server',
                '',
                'Happy coding!'
                ]
            );

            return Command::SUCCESS;
        } catch (\Exception $e) {
            // Handle installation errors
            $io->error('Installation failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Prompt the user to select a starter kit from available options.
     *
     * @param  \Symfony\Component\Console\Style\SymfonyStyle $io The SymfonyStyle IO helper
     * @return string
     */
    private function _selectStarterKit(SymfonyStyle $io): string
    {
        $starterKits = [
            'simple' => 'CakePHP 5 + TailwindCSS simple starter kit with',
            'saas' => 'CakePHP 5 + SAAS + TailwindCSS + Alpinejs SAAS starter kit with',
            'react' => 'CakePHP 5 + React + TailwindCSS starter kit (Coming soon)',
            'next' => 'CakePHP 5 + Next + TailwindCSS starter kit (Coming soon)',
            'api' => 'CakePHP 5 + API starter kit (Coming soon)',
            'cms' => 'CakePHP 5 + TailwindCSS + CMS starter kit (Coming soon)'
        ];

        $question = new ChoiceQuestion(
            'Select a starter kit:',
            $starterKits,
            'saas'
        );

        // Ask the user to choose a starter kit
        return $io->askQuestion($question);
    }

    /**
     * Display the CakePHP ASCII art logo in the console output.
     *
     * @param  \Symfony\Component\Console\Style\SymfonyStyle $io The SymfonyStyle IO helper
     * @return void
     */
    private function _displayLogo(SymfonyStyle $io): void
    {
        $logo = <<<LOGO
        ____       _        ____   _   _ ____  
        / ___|  ___| | _____|  _  \| | | |  _ \ 
        | |     / _ \ |/ / _ \| |_) | |_| | |_) |
        | |___ |  __/   <  __/|  __/|  _  |  __/ 
        \____| \___|_|\_\___|_|    |_| |_| |  
        LOGO;

        // Output the logo in green color
        $io->writeln('<fg=green>' . $logo . '</>');
        $io->writeln('');
    }
} 
