#!/bin/bash

# CakePHP Installer Setup Script

echo "Setting up CakePHP Installer..."

# Make the binary executable
chmod +x bin/cakephp

# Install dependencies
composer install

echo "CakePHP Installer setup complete!"
echo ""
echo "You can now use the installer:"
echo "  php bin/cakephp new my-app"
echo ""
echo "Or install globally with:"
echo "  composer global require cakephp/installer" 