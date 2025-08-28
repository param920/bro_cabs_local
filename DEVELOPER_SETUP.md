# BroCabs Backend - Developer Setup Guide

## 🚀 Project Overview

**BroCabs** is a comprehensive taxi/ride-sharing backend application built with Laravel 8. This is a multi-tenant system that handles ride requests, driver management, payment processing, and real-time communication between users, drivers, and dispatchers.

### Key Features
- **Multi-tenant architecture** for scalable operations
- **Real-time ride tracking** with MQTT and WebSockets
- **Multiple payment gateway integrations** (Stripe, PayPal, Razorpay, Cashfree, etc.)
- **Location-based services** with spatial data support
- **Push notifications** for mobile apps
- **Comprehensive admin panel** for fleet management
- **Driver and owner management** systems

## 📋 Prerequisites

### System Requirements
- **PHP**: ^7.3 or ^8.2
- **Composer**: Latest version
- **Node.js**: 14.x or higher
- **NPM**: 6.x or higher
- **MySQL**: 5.7 or higher
- **Redis**: 5.x or higher (for caching/queues)
- **Git**: Latest version

### Required PHP Extensions
```bash
php -m | grep -E "(bcmath|ctype|fileinfo|json|mbstring|openssl|pdo|tokenizer|xml|curl|gd|zip|redis)"
```

## 🛠️ Installation & Setup

### Step 1: Clone the Repository
```bash
git clone [YOUR_REPOSITORY_URL]
cd BroCabs_Backend
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Install Node.js Dependencies
```bash
npm install
```

### Step 4: Environment Configuration
```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 5: Configure Environment Variables
Edit the `.env` file with your configuration:

#### Database Configuration
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=brocabs_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### Application Configuration
```env
APP_NAME="BroCabs"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_KEY=base64:your_generated_key
```

#### Payment Gateway APIs
```env
# Stripe
STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key

# PayPal
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_SECRET=your_paypal_secret

# Razorpay
RAZORPAY_KEY=your_razorpay_key
RAZORPAY_SECRET=your_razorpay_secret

# Cashfree
CASHFREE_APP_ID=your_cashfree_app_id
CASHFREE_SECRET_KEY=your_cashfree_secret

# MercadoPago
MERCADOPAGO_ACCESS_TOKEN=your_mercadopago_token

# Braintree
BRAINTREE_MERCHANT_ID=your_braintree_merchant_id
BRAINTREE_PUBLIC_KEY=your_braintree_public_key
BRAINTREE_PRIVATE_KEY=your_braintree_private_key

# CCAvenue
CCAVENUE_MERCHANT_ID=your_ccavenue_merchant_id
CCAVENUE_ACCESS_CODE=your_ccavenue_access_code
CCAVENUE_WORKING_KEY=your_ccavenue_working_key
```

#### External Services
```env
# AWS Services
AWS_ACCESS_KEY_ID=your_aws_access_key
AWS_SECRET_ACCESS_KEY=your_aws_secret_key
AWS_DEFAULT_REGION=your_aws_region
AWS_BUCKET=your_s3_bucket_name

# Google Services
GOOGLE_MAPS_API_KEY=your_google_maps_api_key
GOOGLE_TRANSLATE_API_KEY=your_google_translate_key

# Firebase (FCM)
FCM_SERVER_KEY=your_fcm_server_key
FCM_SENDER_ID=your_fcm_sender_id

# SMS Gateway
SMS_PROVIDER=your_sms_provider
SMS_API_KEY=your_sms_api_key
SMS_SECRET=your_sms_secret

# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email_username
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_from_email
MAIL_FROM_NAME="BroCabs"
```

### Step 6: Database Setup
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE brocabs_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate

# Seed the database with initial data
php artisan db:seed
```

### Step 7: Storage Setup
```bash
# Create storage link
php artisan storage:link

# Set proper permissions
chmod -R 775 storage bootstrap/cache
```

### Step 8: Build Frontend Assets
```bash
# Development
npm run dev

# Production
npm run production

# Watch for changes
npm run watch
```

### Step 9: Queue Configuration (Optional)
```bash
# Start queue worker
php artisan queue:work

# Or use supervisor for production
```

## 🚦 Running the Application

### Development Server
```bash
php artisan serve
```
Access your application at: `http://localhost:8000`

### API Endpoints
The API is available at: `http://localhost:8000/api/v1/`

#### Key API Routes
- **Authentication**: `/api/v1/user/login`, `/api/v1/driver/login`
- **User Management**: `/api/v1/user/register`, `/api/v1/user/profile`
- **Driver Management**: `/api/v1/driver/register`, `/api/v1/driver/profile`
- **Ride Requests**: `/api/v1/request/create`, `/api/v1/request/status`
- **Payment**: `/api/v1/payment/process`, `/api/v1/payment/history`
- **Admin**: `/api/v1/admin/dashboard`, `/api/v1/admin/users`

## 🧪 Testing

### Run Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter=UserTest

# Run with coverage
php artisan test --coverage
```

### API Testing
Use tools like Postman or Insomnia to test the API endpoints. The API documentation is available at `/docs` after running the application.

## 📁 Project Structure

```
BroCabs_Backend/
├── app/
│   ├── Http/Controllers/     # API Controllers
│   ├── Models/               # Eloquent Models
│   ├── Services/             # Business Logic
│   ├── Jobs/                 # Background Jobs
│   ├── Events/               # Event Classes
│   ├── Listeners/            # Event Listeners
│   └── Notifications/        # Notification Classes
├── config/                   # Configuration Files
├── database/
│   ├── migrations/           # Database Migrations
│   └── seeders/             # Database Seeders
├── routes/
│   └── api/v1/              # API Routes
├── resources/
│   └── views/               # Blade Templates
└── storage/                  # File Storage
```

## 🔧 Common Commands

### Artisan Commands
```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate API documentation
php artisan scribe:generate

# Create new controller
php artisan make:controller Api/V1/NewController

# Create new model
php artisan make:model NewModel -m

# Create new migration
php artisan make:migration create_new_table
```

### Composer Commands
```bash
# Update dependencies
composer update

# Install new package
composer require package/name

# Dump autoload
composer dump-autoload
```

## 🚨 Troubleshooting

### Common Issues

#### 1. Permission Denied
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 2. Database Connection Failed
- Check database credentials in `.env`
- Ensure MySQL service is running
- Verify database exists

#### 3. Composer Memory Limit
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

#### 4. Node Modules Issues
```bash
rm -rf node_modules package-lock.json
npm install
```

#### 5. Storage Link Failed
```bash
rm public/storage
php artisan storage:link
```

## 📚 Additional Resources

### Documentation
- [Laravel 8.x Documentation](https://laravel.com/docs/8.x)
- [Laravel Passport Documentation](https://laravel.com/docs/8.x/passport)
- [Laravel Sanctum Documentation](https://laravel.com/docs/8.x/sanctum)

### Development Tools
- **Laravel Debugbar**: Available at `/debugbar` in development
- **Laravel Telescope**: For debugging and monitoring
- **API Documentation**: Generated with Scribe at `/docs`

### Code Style
- Follow PSR-12 coding standards
- Use Laravel naming conventions
- Write meaningful commit messages

## 🤝 Getting Help

### Internal Resources
- Check existing code examples in the project
- Review API documentation at `/docs`
- Check Laravel logs in `storage/logs/`

### External Resources
- [Laravel Forums](https://laracasts.com/discuss)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/laravel)
- [Laravel Discord](https://discord.gg/laravel)

---

**Happy Coding! 🚕✨**

If you encounter any issues during setup, please check the troubleshooting section above or reach out to the development team.
