# Saicosys CakePHP Installer

A global installer for CakePHP 5 applications and starter kits. This tool allows you to quickly create new CakePHP applications with optional starter kits that include pre-configured database settings, email configurations, and migrations.

## Installation

Install globally via Composer:

```bash
composer global require saicosys/cakephp-installer
```

Make sure your global Composer bin directory is in your PATH. You can find it by running:

```bash
composer global config bin-dir --absolute
```

## Usage

### Basic Installation

Create a new CakePHP application:

```bash
cakephp new my-app
```

This will create a basic CakePHP 5 application in the `my-app` directory.

### Interactive Installation

When you run the command without specifying a starter kit, you'll be prompted to choose:

```bash
cakephp new my-app
```

You'll see options like:

- Manual installation (basic CakePHP)
- Use a starter kit

### Starter Kit Installation

You can directly specify a starter kit:

```bash
cakephp new my-app --starter-kit=blog
```

Available starter kits:

- `simple` - CakePHP 5 + TailwindCSS starter kit
- `saas` - CakePHP 5 + SAAS + TailwindCSS + Alpinejs
- `react` - CakePHP 5 + React + TailwindCSS starter kit (Coming soon)
- `next` - CakePHP 5 + Next + TailwindCSS starter kit (Coming soon)
- `api` - CakePHP 5 + API starter kit (Coming soon)
- `cms` - CakePHP 5 + TailwindCSS + CMS starter kit (Coming soon)

### Force Installation

To overwrite an existing directory:

```bash
cakephp new my-app --force
```

## Starter Kits

### Simple Starter Kit

A clean and simple CakePHP 5 starter kit with TailwindCSS for building modern web applications.

**Features:**

- CakePHP 5 with modern PHP features
- TailwindCSS for rapid UI development
- User authentication and authorization
- Clean, responsive design
- Mobile-first approach

### SAAS Starter Kit

A comprehensive starter kit for building Software-as-a-Service applications.

**Features:**

- CakePHP 5 with robust backend features
- TailwindCSS + Alpine.js for modern frontend
- Multi-tenant architecture ready
- Authentication and authorization
- CRUD operations with friendsofcake/crud
- Professional SAAS interface
- Subscription and billing models

### React Starter Kit (Coming Soon)

A modern starter kit combining CakePHP 5 backend with React frontend.

**Features:**

- CakePHP 5 API backend
- React frontend with TailwindCSS
- Modern SPA architecture
- API-first design
- Component-based UI

### Next.js Starter Kit (Coming Soon)

A full-stack starter kit with CakePHP 5 and Next.js.

**Features:**

- CakePHP 5 backend API
- Next.js frontend with TailwindCSS
- Server-side rendering
- API routes integration
- Modern React patterns

### API Starter Kit (Coming Soon)

A dedicated API starter kit for building RESTful services.

**Features:**

- CakePHP 5 API structure
- JWT authentication
- RESTful design patterns
- API documentation
- Rate limiting and security

### CMS Starter Kit (Coming Soon)

A content management system starter kit.

**Features:**

- CakePHP 5 with content management
- TailwindCSS for admin interface
- Content editing and publishing
- Media management
- SEO optimization tools

## Configuration

When using a starter kit, the installer will guide you through:

1. **Database Configuration**
   - Database host, name, username, and password
   - Automatic configuration file generation

2. **Email Configuration**
   - SMTP settings for notifications
   - Email transport configuration

3. **Migrations**
   - Automatic database table creation
   - Initial data seeding

## Development

### Requirements

- PHP 8.1 or higher
- Composer
- Symfony Console components

### Local Development

1. Clone the repository
2. Install dependencies: `composer install`
3. Make the binary executable: `chmod +x bin/cakephp`
4. Run locally: `php bin/cakephp new test`

### Testing

```bash
composer test
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## Support

For support and questions:

- Create an issue on GitHub
- Check the CakePHP documentation
- Join the community

## Maintainer

[Sandeep Kadyan](https://github.com/sandeep-kadyan)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
