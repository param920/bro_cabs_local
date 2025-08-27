# BroCabs Backend

<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a>
</p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://packagist.org/packages/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://packagist.org/packages/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://packagist.org/packages/laravel/framework" alt="License"></a>
</p>

## 🚕 About BroCabs

**BroCabs** is a comprehensive taxi/ride-sharing backend application built with Laravel 8. This is a multi-tenant system that handles ride requests, driver management, payment processing, and real-time communication between users, drivers, and dispatchers.

### ✨ Key Features
- **Multi-tenant architecture** for scalable operations
- **Real-time ride tracking** with MQTT and WebSockets
- **Multiple payment gateway integrations** (Stripe, PayPal, Razorpay, Cashfree, MercadoPago, Braintree, CCAvenue)
- **Location-based services** with spatial data support
- **Push notifications** for mobile apps
- **Comprehensive admin panel** for fleet management
- **Driver and owner management** systems
- **Advanced analytics and reporting**

### 🏗️ Technology Stack
- **Backend Framework**: Laravel 8.x
- **PHP Version**: ^7.3 or ^8.2
- **Database**: MySQL with spatial extensions
- **Cache/Queue**: Redis
- **Real-time**: MQTT, WebSockets
- **Payment**: Multiple gateway integrations
- **File Storage**: AWS S3
- **Maps**: Google Maps API
- **Notifications**: Firebase Cloud Messaging (FCM)

## 🚀 Quick Start

### Prerequisites
- PHP ^7.3 or ^8.2
- Composer
- Node.js & NPM
- MySQL 5.7+
- Redis 5.x+

### Installation
```bash
# Clone the repository
git clone [YOUR_REPOSITORY_URL]
cd BroCabs_Backend

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Build assets
npm run dev

# Start development server
php artisan serve
```

**📖 For detailed setup instructions, see [DEVELOPER_SETUP.md](DEVELOPER_SETUP.md)**

## 🌐 API Documentation

The API is available at `/api/v1/` with the following main endpoints:

- **Authentication**: User, Driver, Admin, Dispatcher login/registration
- **User Management**: Profile, preferences, ride history
- **Driver Management**: Registration, verification, earnings
- **Ride Requests**: Create, track, cancel, rate rides
- **Payment Processing**: Multiple gateway support
- **Real-time Updates**: Live location, status changes
- **Admin Panel**: Fleet management, analytics, user management

API documentation is generated using Scribe and available at `/docs` after running the application.

## 🏗️ Project Structure

```
BroCabs_Backend/
├── app/
│   ├── Http/Controllers/     # API Controllers (V1, V2)
│   ├── Models/               # Eloquent Models
│   │   ├── Admin/           # Admin-related models
│   │   ├── Request/         # Ride request models
│   │   ├── Payment/         # Payment models
│   │   └── User/            # User, Driver, Owner models
│   ├── Services/             # Business logic services
│   ├── Jobs/                 # Background job processing
│   ├── Events/               # Event classes
│   ├── Listeners/            # Event listeners
│   ├── Notifications/        # Push/SMS/Email notifications
│   └── Transformers/         # API response transformers
├── config/                   # Configuration files
├── database/
│   ├── migrations/           # Database schema
│   └── seeders/             # Initial data
├── routes/
│   └── api/v1/              # API route definitions
├── resources/
│   └── views/               # Admin panel views
└── storage/                  # File storage
```

## 🔧 Development

### Available Commands
```bash
# Development
php artisan serve              # Start development server
npm run dev                    # Build assets for development
npm run watch                  # Watch for asset changes

# Database
php artisan migrate            # Run migrations
php artisan db:seed           # Seed database
php artisan migrate:fresh     # Reset and migrate

# Cache
php artisan cache:clear       # Clear application cache
php artisan config:clear      # Clear config cache
php artisan route:clear       # Clear route cache

# Testing
php artisan test              # Run tests
php artisan test --coverage   # Run with coverage
```

### Code Style
- Follow PSR-12 coding standards
- Use Laravel naming conventions
- Write meaningful commit messages
- Include tests for new features

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter=UserTest

# Run with coverage
php artisan test --coverage
```

## 📚 Documentation & Resources

### Internal Documentation
- **[DEVELOPER_SETUP.md](DEVELOPER_SETUP.md)** - Complete setup guide for new developers
- **API Documentation** - Available at `/docs` after running the app
- **Code Examples** - Check existing controllers and services

### External Resources
- [Laravel 8.x Documentation](https://laravel.com/docs/8.x)
- [Laravel Passport](https://laravel.com/docs/8.x/passport)
- [Laravel Sanctum](https://laravel.com/docs/8.x/sanctum)
- [Laravel Notifications](https://laravel.com/docs/8.x/notifications)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Workflow
- Create feature branches from `main`
- Write tests for new functionality
- Ensure all tests pass
- Update documentation as needed
- Submit PR for review

## 🚨 Support & Troubleshooting

### Getting Help
- Check the [DEVELOPER_SETUP.md](DEVELOPER_SETUP.md) for common issues
- Review Laravel logs in `storage/logs/`
- Check API documentation at `/docs`
- Reach out to the development team

### Common Issues
- **Permission errors**: Check storage and cache permissions
- **Database connection**: Verify `.env` configuration
- **Composer issues**: Check PHP version and extensions
- **Asset building**: Ensure Node.js dependencies are installed

## 📄 License

This project is proprietary software. All rights reserved.

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

---

**Happy Coding! 🚕✨**
