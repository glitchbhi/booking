# Revised Project Proposal: Thunder Booking - Sports Ground Booking System

## 1. Cover Page

**Project Title:** Thunder Booking - Sports Ground Booking Management System

**Client:** Sports Facility Owners and Sports Enthusiasts in Bhutan

**Student Name:** Krishna Prasad Sharma  
**Student ID:** DIT2024/4051  
**Program:** Diploma in Information Technology (DIT)  
**Institute:** Royal Institute of Management  
**Academic Year:** 2024–2026  
**Supervisor:** Ugyen Tshering

---

## 2. Introduction

Sports ground booking in Bhutan currently relies on informal methods such as phone calls, WhatsApp messages, or direct visits to facility owners. This manual process leads to:

- Customers cannot check real-time availability of sports grounds
- Double-booking conflicts occur frequently
- No centralized system to browse and compare facilities
- No standardized booking confirmation or history tracking
- Ground owners struggle with manual booking management

**Thunder Booking** is a web-based platform designed to digitize the sports ground booking process. The system allows:

- Ground owners to list their facilities with details, pricing, and availability
- Customers to browse available grounds, check time slots, and make bookings online
- Administrators to manage owners, grounds, and bookings

**Key Focus:** This project focuses on core booking functionality with simple, offline payment. The system demonstrates practical database design, CRUD operations, authentication, and business logic implementation within academic scope.

---

## 3. Problem Statement

### Current Challenges:

**For Customers:**
- Must call multiple ground owners individually to check availability
- No way to compare grounds by location, sport type, or price
- No booking confirmation receipts
- Cannot view booking history
- Difficulty finding available time slots during weekends

**For Ground Owners:**
- Manage bookings manually through phone calls or notebooks
- High risk of double-booking during peak hours
- No platform to showcase facilities to wider audience
- Manual tracking of time slots and customer information

**For Sports Community:**
- Underutilization of sports facilities
- No transparent pricing information
- Booking conflicts leading to disputes

---

## 4. Project Aim

To develop a web-based **Sports Ground Booking Management System** using Laravel framework that enables ground owners to list their facilities, customers to browse and book available time slots, and administrators to oversee platform operations.

---

## 5. Project Objectives

1. Create a centralized platform for sports facility listings in Bhutan
2. Implement user authentication system with three user roles (Customer, Owner, Admin)
3. Develop ground management module for owners to add and update their facilities
4. Build booking system with date and time slot selection
5. Provide real-time availability checking to prevent double-booking
6. Enable booking status management (Pending, Confirmed, Completed, Cancelled)
7. Create dashboards for customers, owners, and administrators
8. Implement basic review and rating system for grounds
9. Support multiple sports types (Football, Basketball, Badminton, Futsal, Cricket, etc.)
10. Send email notifications for booking confirmations
11. Design mobile-responsive user interface
12. Implement proper security measures (password encryption, SQL injection prevention)

---

## 6. System Scope

### 6.1 Customer Features

**Guest Users (No Login):**
- Browse all listed sports grounds
- View ground details (name, location, sport type, facilities, images, pricing)
- Filter grounds by sport type
- Search grounds by name or location

**Registered Customers (After Login):**
- Register and login with email and password
- Update profile information
- Check available time slots for a specific ground and date
- Make booking by selecting ground, date, and time slots
- View own booking history
- View booking status (Pending, Confirmed, Completed, Cancelled)
- Cancel bookings
- Write reviews and ratings after completing a booking
- Receive email notifications for booking confirmation and cancellation

### 6.2 Ground Owner Features

**Registration:**
- Register as ground owner with business contact details
- Submit application for approval
- Wait for admin verification

**After Approval:**
- Add new sports grounds with complete information:
  - Ground name and description
  - Location and contact number
  - Sport type (from predefined list)
  - Facilities and amenities
  - Upload multiple ground images
  - Capacity (team size)
  - Hourly pricing (day rate and night rate)
- Edit ground information
- Set ground availability (which dates ground is available)
- Define operating hours (daily opening and closing time)
- View all bookings received
- Confirm or reject booking requests
- Mark bookings as completed after customer visits
- View booking calendar
- Respond to customer reviews
- View ground statistics (total bookings, average rating)

### 6.3 Administrator Features

**Owner Management:**
- View all owner registration requests
- Approve or reject owner applications with reason
- View list of all registered owners
- Suspend or ban problematic owners

**Ground Management:**
- View all listed grounds
- Approve or reject ground listings
- Deactivate inappropriate or fake grounds
- Edit ground information if needed

**Sports Type Management:**
- Add new sports categories (Football, Basketball, Badminton, etc.)
- Update sports type information
- Activate/deactivate sports categories

**Booking Oversight:**
- View all bookings across the platform
- Filter bookings by status, date, ground, or user
- View booking statistics
- Handle booking disputes or issues

**User Management:**
- View all registered users (customers and owners)
- Suspend or ban users for policy violations
- View user activity logs

**Reports:**
- Generate reports on platform usage
- View total bookings, users, and grounds statistics
- Download booking data for analysis

---

## 7. Core Functional Modules

### Module 1: Authentication & Authorization
- User registration (customer and owner separately)
- Login and logout
- Password reset functionality
- Role-based access control (Customer, Owner, Admin)
- Profile management

### Module 2: Ground Management (Owner)
- Add new ground with all details
- Upload multiple images
- Edit ground information
- Set pricing (hourly rates)
- Define availability and operating hours
- Activate/deactivate ground listings

### Module 3: Booking System (Customer)
- Browse and search grounds
- Filter by sport type and location
- View ground details and availability calendar
- Select booking date and time slots (hourly slots)
- Submit booking request
- View booking confirmation
- Cancel bookings
- Track booking status

### Module 4: Booking Management (Owner)
- View incoming booking requests
- Confirm or reject bookings
- View booking calendar
- Mark bookings as completed
- Track payment status (Paid/Unpaid)

### Module 5: Review System
- Customers can rate ground (1-5 stars)
- Write detailed review comments
- View own review history
- Display average ratings on ground listing

### Module 6: Admin Panel
- Dashboard with platform statistics
- Manage owners (approve, suspend, view)
- Manage grounds (approve, deactivate, edit)
- Manage sports types (add, edit, activate)
- View all bookings
- Generate reports

### Module 7: Notification System
- Email notification on booking confirmation
- Email notification on booking cancellation
- Email notification when owner confirms booking
- Email notification for owner when new booking received
- Email notification for owner approval/rejection

---

## 8. Payment Model (Simplified)

To keep the project within academic scope, payment is handled offline:

**Process:**
1. Customer selects ground, date, and time slots
2. System shows total amount based on hourly rate
3. Customer submits booking (no payment required online)
4. Booking status: **"Pending"** waiting for owner confirmation
5. Owner reviews booking and confirms or rejects
6. If confirmed, booking status: **"Confirmed"**
7. Customer visits ground on booking date
8. Customer pays cash directly to ground owner
9. Owner marks booking as **"Completed"** and payment status as "Paid"

**Database tracks:**
- Total amount (calculated from hours × rate)
- Payment status: "Unpaid" or "Paid"
- Booking status: "Pending", "Confirmed", "Completed", "Cancelled"

**Benefits of this approach:**
- No complex payment gateway integration
- No financial security concerns
- Focus remains on booking logic
- Realistic for student project timeline
- Still provides complete booking workflow

---

## 9. Booking Logic (Simplified)

### How Time Slots Work:

1. Grounds operate in **hourly time slots** (e.g., 6:00 AM - 7:00 AM, 7:00 AM - 8:00 AM)
2. Owner defines operating hours (e.g., 6:00 AM to 10:00 PM)
3. System generates available hourly slots within operating hours
4. Customer can book one or multiple consecutive hours

### Preventing Double-Booking:

**Simple approach without complex locking:**

1. Customer selects date and time slots
2. Customer clicks "Book Now"
3. System immediately checks database:
   ```sql
   SELECT * FROM bookings 
   WHERE ground_id = ? 
   AND booking_date = ? 
   AND (start_time < ? AND end_time > ?)
   AND status NOT IN ('Cancelled')
   ```
4. If existing booking found → Show error "Time slot already booked"
5. If no conflict → Create new booking with status "Pending"
6. Owner confirms → Status becomes "Confirmed"

**Why this works:**
- Database transaction ensures atomicity
- Only one booking can be created per time slot
- No complex timeout mechanisms needed
- Sufficient for academic project demonstration

---

## 10. Database Design

### Main Tables:

**1. users**
- id, name, email, phone, password, role (customer/owner/admin), created_at, updated_at

**2. sports_types**
- id, name, description, icon, is_active

**3. grounds**
- id, owner_id, sport_type_id, name, description, location, contact_phone, facilities, images (JSON), capacity, rate_per_hour_day, rate_per_hour_night, day_start_time, night_start_time, opening_time, closing_time, is_active, is_approved, created_at, updated_at

**4. ground_availabilities**
- id, ground_id, date, is_available (for specific dates owner marks as closed)

**5. bookings**
- id, user_id, ground_id, booking_date, start_time, end_time, total_hours, rate_per_hour, total_amount, status (pending/confirmed/completed/cancelled), payment_status (unpaid/paid), notes, created_at, updated_at

**6. reviews**
- id, user_id, ground_id, booking_id, rating (1-5), comment, created_at

**7. owner_requests**
- id, user_id, business_name, phone, address, status (pending/approved/rejected), rejection_reason, created_at, reviewed_at

### Relationships:
- users → grounds (one-to-many)
- users → bookings (one-to-many)
- users → reviews (one-to-many)
- grounds → bookings (one-to-many)
- grounds → reviews (one-to-many)
- sports_types → grounds (one-to-many)

---

## 11. Technology Stack

### Backend:
- **Framework:** Laravel 10.x (PHP 8.1+)
- **Database:** MySQL 8.0
- **Authentication:** Laravel Breeze (built-in authentication scaffolding)
- **Email:** Laravel Mail with SMTP configuration

### Frontend:
- **CSS Framework:** Tailwind CSS
- **JavaScript:** Alpine.js (for interactive components)
- **Template Engine:** Blade (Laravel's templating engine)
- **Build Tool:** Vite

### Development Environment:
- **Web Server:** Apache (XAMPP on Windows)
- **Version Control:** Git
- **Code Editor:** VS Code

### Why Laravel?
- Clean MVC architecture for organized code
- Built-in authentication system saves development time
- Eloquent ORM simplifies database operations
- Blade templating keeps views clean
- Migration system provides version control for database
- Industry-standard framework (improves employability)
- Easier to learn than complex procedural PHP
- Built-in security features (CSRF, SQL injection prevention, password hashing)

---

## 12. System Requirements

### Hardware Requirements:
- **Development:** Basic laptop with 4GB RAM minimum
- **Server:** Shared hosting with cPanel support (Render, Heroku, or local server)

### Software Requirements:
- PHP 8.1 or higher
- MySQL 8.0 or higher
- Composer (PHP dependency manager)
- Node.js and npm (for frontend assets)
- Any modern web browser (Chrome, Firefox, Edge, Safari)

---

## 13. Non-Functional Requirements

### Security:
- All passwords encrypted using bcrypt hashing
- SQL injection prevention through Eloquent ORM parameterized queries
- CSRF token protection on all forms
- XSS protection through Blade output escaping
- Role-based access control for different user types

### Performance:
- Page load time under 3 seconds on standard connection
- Database queries optimized with proper indexing
- Image optimization for faster loading

### Usability:
- Clean, intuitive user interface
- Mobile-responsive design (works on phones, tablets, desktops)
- Clear navigation structure
- Helpful error messages and validation feedback
- Consistent design across all pages

### Reliability:
- Form validation on both client and server side
- Database transaction integrity for bookings
- Proper error handling and logging

### Maintainability:
- MVC architecture for code organization
- Comprehensive code comments
- Detailed documentation
- Git version control for tracking changes

---

## 14. Project Timeline (12 Weeks)

### Week 1-2: Setup & Database Design
- ✅ Install and configure Laravel project
- ✅ Set up development environment
- Create database schema
- Create migrations for all tables
- Design wireframes for main pages

### Week 3-4: Authentication & Ground Management
- Implement user registration and login
- Build owner application system
- Create ground CRUD operations (Add, Edit, Delete, View)
- Image upload functionality
- Admin approval for owners and grounds

### Week 5-6: Booking System (Customer Side)
- Build ground browsing and search
- Create filter by sport type
- Display ground details page
- Implement availability calendar
- Create booking form with date/time selection
- Prevent double-booking logic
- Show booking confirmation

### Week 7-8: Booking Management (Owner Side)
- Owner dashboard showing received bookings
- Booking confirmation/rejection functionality
- Booking calendar view
- Mark bookings as completed
- Update payment status

### Week 9-10: Admin Panel & Reviews
- Admin dashboard with statistics
- Manage owners (approve, suspend)
- Manage grounds (approve, deactivate)
- Manage sports types (add, edit)
- Customer review and rating system
- Display ratings on ground listings

### Week 11: Notifications & Testing
- Email notifications setup
- Send emails for booking events
- Comprehensive testing (functionality, security, usability)
- Bug fixes
- Performance optimization

### Week 12: Documentation & Deployment
- Write user manual
- Complete technical documentation
- Prepare deployment guide
- Deploy to hosting server
- Final testing on live environment
- Prepare project presentation

---

## 15. Expected Deliverables

1. **Working System:**
   - Fully functional web application
   - Deployed on accessible URL
   - Source code on Git repository

2. **Documentation:**
   - User manual (how to use system)
   - Technical documentation (system architecture, database design)
   - Installation guide
   - Testing report

3. **Presentation Materials:**
   - Project presentation slides
   - System demonstration video
   - Wireframes and database diagrams

---

## 16. Risk Analysis & Mitigation

### Risk 1: Time Management
- **Risk:** May not finish all features in 12 weeks
- **Mitigation:** Prioritize core features first (auth, grounds, bookings). Reviews and advanced features are secondary.

### Risk 2: Learning Curve
- **Risk:** New to Laravel framework
- **Mitigation:** Laravel has excellent documentation and tutorial videos. Follow Laravel Daily and Laracasts tutorials.

### Risk 3: Database Design Errors
- **Risk:** Poor database design may require restructuring later
- **Mitigation:** Complete detailed database design with supervisor review before coding begins.

### Risk 4: Double-Booking Logic
- **Risk:** Concurrent bookings may cause conflicts
- **Mitigation:** Use database transactions and immediate availability checking before booking creation.

### Risk 5: Image Upload Issues
- **Risk:** Large images may cause storage or performance problems
- **Mitigation:** Implement image validation (max size, file type) and compression.

---

## 17. Success Criteria

The project will be considered successful if:

1. ✅ Customers can register, browse grounds, and make bookings
2. ✅ Owners can list grounds and manage bookings
3. ✅ Administrators can approve owners and manage platform
4. ✅ No double-booking can occur for same time slot
5. ✅ Email notifications sent for booking events
6. ✅ System is secure (encrypted passwords, protected from SQL injection)
7. ✅ Mobile-responsive design works on different devices
8. ✅ System is properly documented
9. ✅ All major functionality tested and working
10. ✅ Deployed and accessible online

---

## 18. Limitations (Academic Project Scope)

This project will NOT include:

- ❌ Online payment gateway integration (Stripe, PayPal)
- ❌ Wallet system with balance management
- ❌ Commission calculation or automated payouts
- ❌ Google OAuth or social media login
- ❌ Advanced booking lock mechanism with timers
- ❌ SMS notifications
- ❌ Mobile application (iOS/Android)
- ❌ AI-powered recommendations
- ❌ Advanced analytics dashboard with charts
- ❌ Document upload for owner verification
- ❌ Multi-language support

These features are beyond diploma project scope and would require significantly more time and resources.

---

## 19. Future Enhancements (Post-Project)

If the project is successful and time permits, future versions could include:

1. Integration with payment gateways for online payment
2. Mobile application (React Native or Flutter)
3. SMS notifications in addition to email
4. Advanced analytics for owners (revenue charts, peak hours)
5. Booking reminder system
6. Tournament scheduling feature
7. Player matching system
8. Multi-location ground chains
9. Promotional discount system
10. Chat feature between customers and owners

---

## 20. Conclusion

This revised proposal focuses on achievable goals within academic constraints while maintaining practical value. The core booking system functionality remains robust, but complex features like payment processing, wallets, and advanced locking mechanisms have been removed.

The system will successfully demonstrate:
- Database design and implementation
- User authentication and authorization
- CRUD operations
- Business logic implementation (booking availability, status management)
- Email integration
- Security best practices
- Responsive UI design
- MVC architecture using Laravel

This streamlined approach ensures project completion within 12 weeks while providing a solid portfolio piece and practical learning experience.

---

**Prepared by:**  
Krishna Prasad Sharma  
Student ID: DIT2024/4051  
Diploma in Information Technology  
Royal Institute of Management  
Date: March 9, 2026
