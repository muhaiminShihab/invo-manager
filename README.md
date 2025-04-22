<div align="center">

# Invoice Manager

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-v12.x-FF2D20.svg)](https://laravel.com)
[![FilamentPHP](https://img.shields.io/badge/FilamentPHP-v3.x-7952B3.svg)](https://filamentphp.com)

A modern invoice management application designed to help you track dues efficiently and manage your business finances.

[Key Features](#features) • [Installation](#installation) • [Documentation](#usage) • [Contributing](#contributing)

</div>

## 🎯 Overview
![Invoice Manager Overview](/public/overview.png)
Invoice Manager is a powerful application built with Laravel and FilamentPHP that helps you:
- 📊 Manage customers and their details
- 📝 Generate and track invoices
- 💰 Monitor due payments and outstanding balances
- ⏰ Stay on top of financial commitments

## ✨ Features

-   👥 **Customer Management System**

    -   Comprehensive customer database
    -   Contact information tracking
    -   Customer history and analytics

-   📄 **Invoice Management**

    -   Professional invoice generation
    -   Real-time tracking and updates
    -   Payment history monitoring

-   🎨 **Modern Interface**

    -   Clean and intuitive design
    -   Responsive dashboard
    -   User-friendly controls

-   🔐 **Security**
    -   Robust user authentication
    -   Role-based access control
    -   Secure data handling

## 🚀 Getting Started

### Prerequisites

Ensure you have the following installed:

-   PHP (v8.0 or higher)
-   Composer
-   Laravel v12.x
-   FilamentPHP v3.x
-   Node.js (v14 or higher)
-   npm or yarn
-   MySQL database

### Installation

1. Clone the repository

```bash
git clone https://github.com/muhsiminShihab/invo-manager.git
cd invo-manager
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

### Default Login Credentials

After setup, use these credentials to access the admin panel:

-   **Email**: `admin@example.com`
-   **Password**: `p@ssword`

## 📖 Usage

1. **Dashboard Access**

    - Register/Login to access the dashboard
    - View key metrics and statistics

2. **Customer Management**

    - Add and manage customer profiles
    - Track customer interactions

3. **Invoice Operations**

    - Create and manage invoices
    - Track payment status
    - Generate reports

4. **Financial Tracking**
    - Monitor due payments
    - Track outstanding balances
    - Generate financial reports

## 🚀 Deployment

For production deployment:

1. **Environment Setup**

    - Set up a production database
    - Update `.env` with production settings
    - Run `php artisan config:cache`

2. **Server Configuration**

    - Configure web server (Nginx/Apache)
    - Set up SSL certificates
    - Configure domain settings

3. **Application Services**
    - Set up queue workers (`php artisan queue:work`)
    - Configure scheduled tasks (`php artisan schedule:run`)
    - Set up backup systems

## 🤝 Contributing

We welcome contributions! Here's how you can help:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📬 Contact

Muhaimin Shihab - [LinkedIn](https://linkedin.com/muhaiminshihab)

Project Link: [https://github.com/muhsiminShihab/invo-manager](https://github.com/muhsiminShihab/invo-manager)

---

<div align="center">
Made with ❤️ by Muhaimin Shihab
</div>
