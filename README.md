# Due Manager

A modern customer and invoice management application designed to help you track dues efficiently and manage your business finances.

## Overview

Due Manager is a powerful application that helps you:
- Manage customers and their details
- Generate and track invoices
- Monitor due payments and outstanding balances
- Stay on top of financial commitments

This application is developed using FilamentPHP.

## Features

- Customer management system
- Invoice generation and tracking
- Clean and modern interface
- User authentication

## Getting Started

### Prerequisites

Make sure you have the following installed:

- PHP (v8.0 or higher)
- Composer
- Laravel v12.x
- FilamentPHP v3.x
- Node.js (v14 or higher)
- npm or yarn
- MySQL database

### Installation

1. Clone the repository
```bash
git clone https://github.com/muhsiminShihab/due-manager.git
cd due-manager
```

2. Install dependencies
```bash
composer install
npm install
```

3. Set up environment variables
```bash
cp .env.example .env
```

4. Generate application key
```bash
php artisan key:generate
```

5. Configure your database in the `.env` file

6. Run database migrations
```bash
php artisan migrate
```

7. Seed the database (optional)
```bash
php artisan db:seed
```

8. Start the development server
```bash
php artisan serve
```

9. Run frontend build (if applicable)
```bash
npm run dev
```

## Usage

- Register/Login to access the dashboard
- Add and manage customers
- Create and manage invoices
- Track due payments and balances

## Deployment

For deployment, make sure to:

- Set up a production database and update `.env`
- Run `php artisan config:cache`
- Use a web server like Nginx or Apache
- Set up queue workers if needed (`php artisan queue:work`)
- Enable scheduled tasks (`php artisan schedule:run`)

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for discussion.

## License

This project is licensed under the MIT License.

## Contact

For any questions or suggestions, please reach out at [hostforshihab@gmail.com](mailto:hostforshihab@gmail.com).

