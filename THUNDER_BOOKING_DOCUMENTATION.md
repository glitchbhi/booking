# ⚡ Thunder Booking System

A complete, production-ready sports ground booking system built with Laravel 11 and Tailwind CSS.

## 📋 Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [System Architecture](#system-architecture)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Schema](#database-schema)
- [User Roles](#user-roles)
- [Booking Logic](#booking-logic)
- [Testing](#testing)
- [API Endpoints](#api-endpoints)

## ✨ Features

### Core Features
- **Instant Booking System** - No approval required, book grounds in seconds
- **Virtual Wallet** - Secure payment system using virtual coins
- **Multi-Role System** - User, Owner, and Admin roles with specific permissions
- **Smart Cancellation** - Time-based refund policy (98% refund for cancellations ≥4 hours before)
- **Anti-Abuse System** - Automatic suspension after 3 late cancellations/no-shows
- **Reviews & Ratings** - Users can rate grounds after completed bookings
- **Slot Locking** - 5-10 minute automatic slot reservation during booking process
- **Offline Bookings** - Owners can add walk-in bookings
- **Email Notifications** - Automated emails for all key actions
- **Analytics Dashboard** - Comprehensive revenue and booking analytics

### User Features
- Browse and search grounds by name, location, sport type
- Filter by price, rating, and availability
- View trending and best-rated grounds
- Dynamic booking (select duration in hours or days, max 7 days)
- Wallet management (add coins, view transaction history)
- Booking history and management
- Cancel bookings with automatic refund calculation
- Rate and review completed bookings
- Request to become a ground owner

### Owner Features
- Manage multiple grounds
- Set rates per hour
- Define availability (days and time ranges)
- View all bookings (online + offline)
- Add offline bookings for walk-in customers
- Revenue dashboard with analytics
- Peak hours analysis
- Online vs offline booking statistics

### Admin Features
- Full system overview dashboard
- Manage users (view, suspend, unsuspend)
- Approve/reject owner requests
- Manage sports types (CRUD operations)
- View and manage all grounds
- System-wide analytics
- 2% commission tracking on all bookings
- View top-performing grounds and popular sports

## 🛠 Technology Stack

- **Backend:** Laravel 11
- **Frontend:** Blade Templates + Tailwind CSS
- **Database:** MySQL
- **Authentication:** Laravel Breeze (included but not yet installed)
- **Scheduler:** Laravel Scheduler (cron jobs)
- **Notifications:** Laravel Mail (Gmail SMTP)
- **Soft Deletes:** Enabled on all major tables

## 🏗 System Architecture

### Database Tables

1. **users** - Extended with role, wallet, suspension fields
2. **sports_types** - Admin-managed sports categories
3. **grounds** - Sports ground listings
4. **ground_availabilities** - Day/time availability for grounds
5. **bookings** - All booking records
6. **booking_locks** - Temporary slot reservations
7. **wallet_transactions** - Complete wallet history
8. **reviews** - User reviews for grounds
9. **owner_requests** - Ground owner registration requests

### Booking Status Lifecycle

```
booked → ongoing → completed
   ↓
cancelled
```

- **booked**: Initial state when booking is created
- **ongoing**: Automatically set when start time is reached
- **completed**: Automatically set when end time is reached
- **cancelled**: User-initiated cancellation

### Scheduled Jobs

The system includes three scheduler commands that run automatically:

1. **bookings:transition** (every minute)
   - Transitions bookings from 'booked' to 'ongoing'
   - Transitions bookings from 'ongoing' to 'completed'

2. **bookings:release-locks** (every 5 minutes)
   - Releases expired booking locks

3. **users:remove-suspensions** (hourly)
   - Removes expired user suspensions
   - Resets late cancellation counters

## 📦 Installation

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM (for assets)

### Step-by-Step Installation

1. **Clone and navigate to project**
   ```bash
   cd /opt/lampp/htdocs/thunder-booking
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database in `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=thunder_booking
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

5. **Configure mail settings in `.env`**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your_email@gmail.com
   MAIL_PASSWORD=your_app_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your_email@gmail.com
   MAIL_FROM_NAME="Thunder Booking"
   ```

6. **Run migrations and seed database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Create storage link**
   ```bash
   php artisan storage:link
   ```

8. **Install Laravel Breeze (for authentication UI)**
   ```bash
   composer require laravel/breeze --dev
   php artisan breeze:install blade
   npm install && npm run build
   php artisan migrate
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

10. **Start the scheduler (in a new terminal)**
    ```bash
    php artisan schedule:work
    ```

## 🔧 Configuration

### Setting Up Cron (Production)

For production, add this to your cron:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Email Configuration

For Gmail, you need to:
1. Enable 2-factor authentication
2. Generate an app password
3. Use the app password in `.env`

## 📊 Database Schema

### Key Relationships

- User → Grounds (one-to-many, as owner)
- User → Bookings (one-to-many)
- User → Wallet Transactions (one-to-many)
- Ground → Sport Type (many-to-one)
- Ground → Bookings (one-to-many)
- Ground → Availabilities (one-to-many)
- Booking → Review (one-to-one)
- Booking → Wallet Transactions (one-to-many)

## 👥 User Roles

### User (Default Role)
- Can browse and book grounds
- Manage wallet and bookings
- Leave reviews
- Request owner role

### Ground Owner
- All user privileges
- Manage multiple grounds
- Add offline bookings
- View analytics dashboard
- Requires admin approval

### Super Admin
- Full system access
- Approve/reject owner requests
- Manage sports types
- View system analytics
- Earn 2% commission

## 📅 Booking Logic

### Time Slot Rules
- Past bookings not allowed
- Maximum duration: 7 days (168 hours)
- Rate calculated: duration_hours × rate_per_hour
- Automatic slot conflict prevention

### Slot Locking System
- When user selects a slot, it's locked for 5-10 minutes
- Lock prevents double booking
- Automatically released if booking not completed

### Cancellation & Refund Policy

| Scenario | Refund | Penalty |
|----------|--------|---------|
| Cancel ≥4 hours before | 98% refund | None |
| Cancel <4 hours before | No refund | +1 strike |
| No-show | No refund | +1 strike |
| 3 strikes | Account suspended for 7 days | Booking blocked |

## 🧪 Testing

### Default Credentials (after seeding)

**Admin**
- Email: admin@thunderbooking.com
- Password: password

**Owners (5 accounts)**
- Email: owner1@example.com to owner5@example.com
- Password: password

**Users (10 accounts)**
- Email: user1@example.com to user10@example.com
- Password: password

### Test Scenarios

1. **User Flow**
   - Register/Login
   - Add coins to wallet
   - Browse grounds
   - Create booking
   - Cancel booking (test refund logic)
   - Review completed booking

2. **Owner Flow**
   - Request owner role (needs admin approval)
   - Add ground with availability
   - Add offline booking
   - View dashboard analytics

3. **Admin Flow**
   - Approve owner requests
   - Add/manage sports types
   - View system analytics
   - Suspend/unsuspend users

## 📡 Main Routes

### Public Routes
- `/` - Welcome/Landing page
- `/home` - Browse grounds with filters
- `/grounds` - Ground listings
- `/grounds/{id}` - Ground details

### Authenticated Routes
- `/dashboard` - Role-based dashboard redirect
- `/bookings` - User bookings
- `/wallet` - Wallet management
- `/owner-request/create` - Request owner role

### Owner Routes (Prefix: `/owner`)
- `/owner/dashboard` - Owner analytics
- `/owner/grounds` - Manage grounds
- `/owner/bookings` - View all bookings
- `/owner/bookings/create` - Add offline booking

### Admin Routes (Prefix: `/admin`)
- `/admin/dashboard` - System analytics
- `/admin/users` - User management
- `/admin/grounds` - Ground management
- `/admin/owner-requests` - Approve/reject requests
- `/admin/sports-types` - Manage sports types

## 🔒 Security Features

- Soft deletes on all major tables (data safety)
- Policy-based authorization
- CSRF protection
- Password hashing
- SQL injection protection (Eloquent ORM)
- Input validation on all forms
- Role-based middleware
- Booking history maintained (audit trail)

## 📈 Analytics Features

### Owner Dashboard
- Total revenue (with 98% after commission)
- Daily/monthly booking counts
- Peak hours analysis
- Online vs offline booking ratio
- Top performing grounds
- Revenue trends chart

### Admin Dashboard
- Total system revenue
- 2% admin commission tracking
- User statistics (total, suspended)
- Ground statistics
- Most popular sports
- Top grounds by bookings
- Revenue breakdown

## 🚀 Production Deployment Checklist

- [ ] Configure production database
- [ ] Set up Gmail SMTP or mail service
- [ ] Configure cron for scheduler
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set up queue workers (optional)
- [ ] Configure file storage (S3/DigitalOcean Spaces)
- [ ] Set up SSL certificate
- [ ] Configure backup strategy

## 📝 Future Enhancements

- Payment gateway integration (Stripe/Razorpay)
- Real-time availability calendar
- SMS notifications
- Mobile app (React Native)
- Multi-language support
- Advanced search filters
- Ground comparison feature
- Booking recommendations
- Loyalty program
- Referral system

## 🐛 Known Limitations

- Email notifications require Gmail SMTP setup
- Images are stored locally (use cloud storage in production)
- No real-time updates (consider Laravel Echo + Pusher)
- Basic analytics (consider adding charts library)

## 📄 License

This project is proprietary software. All rights reserved.

## 👨‍💻 Author

Built for Thunder Booking System
Laravel 11 + Tailwind CSS

---

**Note:** This is a complete, production-ready booking system. Ensure all environment variables are properly configured before deployment.

For support or queries, please refer to the code documentation or contact your system administrator.
