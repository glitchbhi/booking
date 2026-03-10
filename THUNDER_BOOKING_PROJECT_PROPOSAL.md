# Project Proposal: Thunder Booking - Sports Ground Booking System

## 1. Cover Page

Project Title: Thunder Booking - Sports Ground Booking Management System

Client: Sports Facility Owners and Sports Enthusiasts in Bhutan

Student Name: Krishna Prasad Sharma

Student ID: DIT2024/4051

Program: Diploma in Information Technology (DIT)

Institute: Royal Institute Of Management

Academic Year: 2024–2026

Supervisor: Ugyen Tshering

---

## 2. Introduction

With the growing interest in sports and recreational activities in Bhutan, there is an increasing demand for accessible sports facilities such as football grounds, basketball courts, badminton courts, and futsal arenas. However, the current booking process is largely informal and unorganized, relying on phone calls, social media messages, or in-person visits to facility owners.

This manual approach creates significant challenges:
- Customers cannot check real-time availability of grounds
- Booking conflicts and double-bookings occur frequently
- No standardized pricing or time slot management
- Payment tracking is manual and unreliable
- No proper booking history or receipts
- Ground owners struggle to manage multiple bookings efficiently
- No transparent review system to verify facility quality

**Thunder Booking** is a centralized web-based platform designed to digitize and automate the entire sports ground booking process. The system allows verified ground owners to list their facilities with detailed information, pricing, availability schedules, and real-time booking management. Customers can browse available grounds by location and sport type, view ratings and reviews, check real-time availability, make instant bookings, and complete secure online payments.

The platform includes an automated wallet system for seamless transactions, booking lock mechanism to prevent double-bookings, email notifications for booking confirmations, and a comprehensive review system to maintain quality standards. Admin oversight ensures platform integrity through owner verification, ground approval, and dispute resolution.

This project aims to develop a secure, efficient, and user-friendly booking platform that connects sports facility owners with customers, promotes active lifestyles, and creates a transparent marketplace for sports infrastructure in Bhutan.

---

## 3. Current Situation

At present, the sports ground booking process in Bhutan faces several challenges:

**For Customers:**
- Must call or message multiple ground owners individually to check availability
- No centralized platform to compare grounds by sport type, location, or price
- Cannot see facility conditions, amenities, or previous customer reviews
- No confirmation receipts or booking records
- Difficult to find available grounds during peak hours or weekends
- Payment methods are inconsistent (cash only in many cases)
- No refund policy or cancellation procedures
- Cannot track booking history

**For Ground Owners:**
- Manage bookings through phone calls, notebooks, or WhatsApp groups
- High risk of double-booking during busy periods
- Manual payment tracking and reconciliation
- No platform to showcase facilities to wider audience
- Difficulty managing time slots and pricing
- No way to build reputation through verified reviews
- Limited market reach beyond local community
- Manual availability updates

**For Sports Community:**
- Underutilization of existing sports facilities
- Lack of transparency in pricing
- No quality assurance mechanism
- Limited access to facility information
- Booking conflicts lead to disputes

This fragmented and manual system results in inefficiency, lost revenue for owners, frustrated customers, and underutilization of valuable sports infrastructure.

---

## 4. Problem Definition

The current manual booking system creates several critical problems:

### Primary Problems:
1. **No Centralized Booking Platform**
   - Multiple facilities scattered across different locations
   - No unified search and discovery system
   - Information asymmetry between owners and customers

2. **Real-Time Availability Issues**
   - Cannot check instant availability
   - Double-booking conflicts
   - Manual calendar management prone to errors

3. **Payment and Transaction Challenges**
   - No secure online payment system
   - Manual payment verification
   - No automated refund mechanism
   - Poor transaction record keeping

4. **Lack of Trust and Verification**
   - No verification of ground owners
   - No authentic facility reviews
   - No quality standards enforcement
   - Fraud and misrepresentation risks

5. **Poor Booking Management**
   - No automated booking confirmation
   - No booking modification system
   - Difficult cancellation process
   - No booking history tracking

6. **Communication Gaps**
   - Delayed responses from owners
   - No automated notifications
   - Miscommunication about timing, pricing, and facilities

7. **Limited Market Access**
   - Small ground owners have limited visibility
   - Customers unaware of available facilities
   - No promotional platform for new facilities

**Therefore, a modern, automated, and centralized sports ground booking system is urgently needed to address these challenges and create an efficient marketplace for sports facilities.**

---

## 5. Project Aim

To design and develop a secure, web-based **Sports Ground Booking Management System** that automates facility listing, real-time availability checking, instant booking confirmation, secure payment processing, wallet management, and comprehensive reporting for sports ground owners, customers, and administrators.

---

## 6. Project Objectives

1. **Develop a centralized online booking marketplace** for sports facilities across Bhutan
2. **Provide real-time availability display** with calendar-based time slot management
3. **Enable secure user authentication** with role-based access (Customer, Owner, Admin)
4. **Implement owner verification system** to ensure legitimate facility operators
5. **Support multiple payment methods** including wallet system, online payment, and cash on arrival
6. **Create automated wallet system** for deposits, payments, refunds, and owner payouts
7. **Implement booking lock mechanism** to prevent double-bookings during checkout
8. **Provide comprehensive dashboards** for customers, owners, and administrators
9. **Enable review and rating system** for quality assurance and transparency
10. **Support multiple sports types** (Football, Basketball, Badminton, Volleyball, Cricket, Futsal, etc.)
11. **Implement automated notification system** via email for all booking events
12. **Create booking transition system** to automatically update booking status based on time
13. **Enable ground availability scheduling** for owners to set operating hours
14. **Provide detailed analytics and reports** for all stakeholders
15. **Support mobile-responsive design** for access on all devices

---

## 7. System Capabilities

### 7.1 Customer Capabilities

**Guest Users (No Login Required):**
- Browse all available sports grounds
- View ground details (name, location, facilities, images, pricing, ratings)
- Filter grounds by sport type, location, availability
- Search grounds by name or area
- View ground reviews and ratings
- Check ground capacity and amenities

**Registered Customers (After Login):**
- **Account Management:**
  - Register with email/phone and password
  - Login securely with email verification
  - Google OAuth integration for quick login
  - Update profile information
  - View account dashboard

- **Booking Management:**
  - Check real-time availability calendar
  - Select date and time slots (hourly booking)
  - View pricing (daytime/nighttime rates)
  - Make instant bookings
  - Booking lock prevents others from booking same slot during checkout (5-minute timeout)
  - Receive booking confirmation instantly
  - View booking details (booking ID, ground info, time, date, status)
  - Track booking status (Pending → Confirmed → Completed/Cancelled)
  - Cancel bookings (with refund based on policy)
  - View complete booking history
  - Download booking receipts

- **Wallet System:**
  - Add money to wallet via online payment
  - View wallet balance
  - Pay for bookings using wallet balance
  - View transaction history (credits, debits, refunds)
  - Automatic refund to wallet for cancellations

- **Review System:**
  - Rate ground owners (1-5 stars)
  - Write detailed reviews
  - View own review history
  - Rate overall system experience

- **Notifications:**
  - Email notifications for booking confirmation
  - Booking reminder notifications
  - Cancellation notifications
  - Wallet transaction alerts
  - Promotional updates

### 7.2 Ground Owner Capabilities

**Registration & Verification:**
- Register as ground owner with business details
- Submit verification documents (CID, business license, ground photos)
- Provide bank account details for payouts
- Wait for admin approval
- Receive approval/rejection notification

**After Approval - Owner Dashboard Access:**

**Ground Management:**
- Add new grounds with complete details:
  - Ground name and description
  - Location/address with contact number
  - Sport type (Football, Basketball, Badminton, etc.)
  - Facilities and amenities
  - Multiple ground images upload
  - Capacity (team size)
  - Ground dimensions/size
- Set pricing:
  - Daytime rate (per hour)
  - Nighttime rate (per hour)
  - Define day/night time boundaries
- Upload multiple facility images
- Edit ground information
- Activate/deactivate ground listings
- Set ground availability schedules

**Booking Management:**
- View all bookings received
- Real-time booking notifications
- See booking details (customer info, date, time, payment status)
- Update booking status
- View upcoming bookings calendar
- View booking history
- Handle customer queries

**Financial Management:**
- View total earnings
- Track individual booking payments
- View wallet balance
- See deducted platform commission
- View available balance (after commission)
- Track payment status (Pending, Paid, Refunded)
- Request payout
- View transaction history
- Download financial reports

**Analytics & Reports:**
- View total bookings count
- See booking trends (daily, weekly, monthly)
- Track most popular time slots
- Customer demographics
- Revenue analytics
- Ground performance metrics

**Communication:**
- Receive customer review notifications
- Respond to customer reviews
- View ground ratings
- Get booking-related notifications

### 7.3 Administrator Capabilities

**Owner & Ground Management:**
- **Owner Verification:**
  - Review owner registration requests
  - Verify submitted documents (CID, business license, bank details)
  - Approve or reject owner applications
  - Provide rejection reasons
  - Monitor owner activity
  - Suspend/ban problematic owners

- **Ground Approval:**
  - Review submitted ground listings
  - Verify ground information and images
  - Approve or reject grounds
  - Request modifications if needed
  - Monitor active grounds
  - Deactivate fake or problematic listings

**Sports Type Management:**
- Add new sports categories
- Update existing sports types
- Set category status (active/inactive)
- Organize sports classification

**Booking Oversight:**
- View all platform bookings
- Monitor booking status transitions
- Filter bookings by status, date, ground, owner
- Handle booking disputes
- View booking analytics
- Generate booking reports

**User Management:**
- View all registered users (customers, owners)
- Search users by role, email, phone
- View user details and activity
- Suspend user accounts if needed
- Handle account suspension appeals
- Remove expired suspensions automatically
- Monitor user complaints

**Financial Management:**
- Monitor all platform transactions
- Track wallet credits and debits
- View owner earnings
- Manage commission structure
- Process owner payout requests
- Generate financial reports
- View platform revenue

**Review & Rating Management:**
- Monitor all reviews and ratings
- View owner ratings
- Handle review disputes
- Remove inappropriate reviews
- Track system ratings

**System Analytics:**
- Platform-wide dashboard
  - Total users (customers, owners)
  - Total grounds
  - Total bookings
  - Total revenue
  - Active bookings
  - Pending owner requests
- Generate comprehensive reports:
  - User growth reports
  - Booking trends
  - Revenue reports
  - Owner performance
  - Customer activity
  - Sports type popularity
- Visual analytics (charts, graphs)
- Export data to CSV/PDF

**System Configuration:**
- Manage platform settings
- Set commission rates
- Configure booking rules
- Set booking lock timeout
- Manage email templates
- Configure payment methods
- System maintenance mode

**Security & Compliance:**
- Monitor suspicious activities
- Review security logs
- Manage user permissions
- Ensure data privacy compliance
- Handle data backup
- Security audit reports

---

## 8. System Automated Functions

The Thunder Booking system will automatically perform the following operations:

### Booking Automation:
1. **Real-Time Availability Check**
   - Automatically check ground availability before booking confirmation
   - Prevent double-booking conflicts
   - Update availability instantly after booking

2. **Booking Lock Mechanism**
   - Lock selected time slot for 5 minutes during checkout
   - Prevent other users from booking same slot simultaneously
   - Automatically release lock after timeout or booking completion

3. **Booking Transition**
   - Automatically change booking status based on date/time:
     - Pending → Confirmed (upon payment)
     - Confirmed → Completed (after booking date passes)
   - Run scheduled task every minute via cron job

4. **Booking Expiry**
   - Automatically cancel unpaid bookings after timeout
   - Release locked time slots
   - Send cancellation notification

### Payment & Wallet Automation:
5. **Automatic Wallet Transactions**
   - Deduct booking amount from customer wallet
   - Credit owner wallet after successful booking
   - Calculate and deduct platform commission
   - Process refunds automatically for cancellations

6. **Commission Calculation**
   - Automatically calculate platform commission (e.g., 10-15%)
   - Deduct from owner earnings
   - Track commission per booking
   - Generate commission reports

### Notification Automation:
7. **Email Notifications** (Automatically sent for):
   - Booking confirmation to customer
   - New booking notification to owner
   - Booking cancellation alerts
   - Wallet credit/debit notifications
   - Payment confirmation
   - Owner request approval/rejection
   - Account suspension alerts

### Stock & Availability Automation:
8. **Ground Availability Management**
   - Automatically block booked time slots
   - Update availability calendar in real-time
   - Prevent overlapping bookings
   - Handle multiple concurrent booking attempts

### User Management Automation:
9. **Suspension Management**
   - Automatically remove expired user suspensions every hour
   - Check suspension expiry dates
   - Restore account access automatically
   - Send notification upon restoration

10. **Lock Release Automation**
    - Release expired booking locks every 5 minutes
    - Clean up stale locks automatically
    - Free up time slots for other customers

### Data Management Automation:
11. **Database Cleanup**
    - Archive old bookings
    - Remove expired locks
    - Clean temporary data
    - Optimize database performance

12. **Backup Automation**
    - Automatic daily database backups
    - Transaction log backups
    - System state snapshots

### Reporting Automation:
13. **Automated Report Generation**
    - Daily booking summary
    - Weekly revenue reports
    - Monthly owner performance reports
    - System usage statistics

14. **Analytics Updates**
    - Update dashboard statistics in real-time
    - Calculate booking trends
    - Track user activity metrics
    - Generate visual charts and graphs

These automated functions ensure system efficiency, reduce manual intervention, maintain data integrity, and provide seamless user experience.

---

## 9. Functional Requirements

### 9.1 Customer Module

**Registration & Authentication:**
- Register with name, email, phone, password
- Email verification
- Google OAuth login integration
- Secure password reset via email
- Profile management

**Ground Discovery:**
- Browse all grounds
- Search by ground name, location, sport type
- Filter by:
  - Sport type
  - Location/area
  - Availability
  - Price range
  - Rating
- View ground details:
  - Name, description, images
  - Location, contact number
  - Facilities, capacity
  - Pricing (day/night rates)
  - Ratings and reviews
  - Availability calendar

**Booking Management:**
- Select ground
- Choose date from calendar
- Select available time slots
- View total price calculation
- Add to booking (locked for 5 minutes)
- Complete booking with payment
- View booking confirmation
- Track booking status
- Cancel booking (with refund policy)
- View booking history
- Download booking receipt

**Wallet Management:**
- View wallet balance
- Add money to wallet
- Pay for bookings via wallet
- View transaction history
- Receive automatic refunds

**Review System:**
- Rate completed bookings (1-5 stars)
- Write detailed reviews
- View own reviews
- Rate system experience

### 9.2 Ground Owner Module

**Registration & Verification:**
- Register as owner with business details
- Upload verification documents
- Submit CID and business license
- Provide bank account details
- Track application status

**Ground Management:**
- Add new grounds
- Upload facility images (multiple)
- Set ground details (name, location, sport type, facilities)
- Configure pricing (day rate, night rate, time boundaries)
- Set capacity and team size
- Update ground information
- Activate/deactivate listings

**Booking Management:**
- View incoming bookings
- See booking details
- Confirm bookings
- View booking calendar
- Check customer information
- Update booking status
- View completed bookings

**Financial Management:**
- View total earnings
- Track booking payments
- View wallet balance
- See commission deductions
- Request payouts
- Download financial reports
- View transaction history

**Analytics:**
- Total bookings count
- Revenue statistics
- Booking trends
- Popular time slots
- Ground performance metrics

### 9.3 Admin Module

**Owner Management:**
- View owner requests
- Verify owner documents
- Approve/reject registrations
- Monitor owner activity
- Suspend/activate owners

**Ground Management:**
- Review ground submissions
- Approve/reject grounds
- Monitor active grounds
- Deactivate problematic listings

**Sports Type Management:**
- Add sports categories
- Edit categories
- Activate/deactivate categories

**User Management:**
- View all users
- Search users by role
- Suspend user accounts
- Monitor user activity
- Handle complaints

**Booking Oversight:**
- View all bookings
- Filter by status, date, ground
- Monitor transactions
- Handle disputes
- Generate reports

**Financial Management:**
- View platform revenue
- Track commissions
- Process payouts
- Financial analytics
- Export financial data

**System Management:**
- Platform dashboard
- User statistics
- Booking analytics
- Revenue reports
- System configuration

### 9.4 System Module

**Automated Operations:**
- Stock availability update
- Booking lock management
- Booking status transitions
- Commission calculations
- Email notifications
- Wallet transactions
- Suspension expiry checks
- Lock release automation

**Security:**
- User authentication
- Password encryption
- Role-based access control
- Session management
- CSRF protection

**Data Management:**
- Database transactions
- Backup automation
- Data integrity checks
- Audit logging

---

## 10. Non-Functional Requirements

### 10.1 Security Requirements
- **Authentication:** Secure login with encrypted passwords (bcrypt hashing)
- **Authorization:** Role-based access control (Customer, Owner, Admin)
- **Data Encryption:** Sensitive data encrypted at rest and in transit
- **Session Management:** Secure session handling with timeout
- **CSRF Protection:** Token-based CSRF protection on all forms
- **SQL Injection Prevention:** Parameterized queries using Laravel ORM
- **XSS Protection:** Input sanitization and output encoding
- **Password Policy:** Minimum 8 characters with complexity requirements
- **Two-Factor Authentication:** Email-based verification for critical actions

### 10.2 Performance Requirements
- **Page Load Time:** Maximum 3 seconds for all pages
- **Concurrent Users:** Support 500+ simultaneous users
- **Database Response:** Query execution under 100ms
- **API Response Time:** Maximum 500ms for API calls
- **Real-time Updates:** Booking availability updates within 1 second
- **Image Loading:** Optimized images with lazy loading
- **Search Performance:** Search results returned within 1 second

### 10.3 Reliability Requirements
- **System Uptime:** 99.5% availability (allowing ~3.5 hours downtime/month)
- **Data Backup:** Automated daily backups with 30-day retention
- **Disaster Recovery:** Recovery Time Objective (RTO) of 4 hours
- **Error Handling:** Graceful error messages and logging
- **Transaction Integrity:** ACID compliance for all financial transactions
- **Data Consistency:** Preventing double-booking through locking mechanism

### 10.4 Usability Requirements
- **User Interface:** Clean, intuitive, and easy to navigate
- **Responsive Design:** Mobile-friendly interface for all devices
- **Accessibility:** WCAG 2.1 Level A compliance
- **Language:** Clear English text with Bhutanese context
- **Help System:** Tooltips and help text for complex features
- **Error Messages:** Clear, actionable error messages
- **Learning Curve:** New users can make booking within 5 minutes

### 10.5 Scalability Requirements
- **User Growth:** Support increasing number of registered users
- **Ground Listings:** Handle 100+ grounds initially, expandable to 1000+
- **Booking Volume:** Process 1000+ bookings per day
- **Database Scalability:** Efficient indexing for growing data
- **Modular Architecture:** Easy to add new features
- **API Design:** RESTful APIs for future mobile app integration

### 10.6 Maintainability Requirements
- **Code Structure:** MVC architecture with Laravel framework
- **Documentation:** Comprehensive code comments and documentation
- **Version Control:** Git-based version control
- **Modular Design:** Separate modules for easy updates
- **Logging:** Detailed application and error logs
- **Testing:** Unit and integration tests for core functionality

### 10.7 Compatibility Requirements
- **Browsers:** Support Chrome, Firefox, Safari, Edge (latest versions)
- **Mobile Devices:** iOS 12+, Android 8+
- **Screen Resolutions:** 320px to 4K displays
- **Internet Connection:** Functional on 3G and above

### 10.8 Compliance Requirements
- **Data Privacy:** Comply with Bhutan Data Protection Act
- **Payment Security:** PCI DSS compliance for payment handling
- **Terms of Service:** Clear user agreements
- **Privacy Policy:** Transparent data usage policy
- **Legal Documentation:** Proper contracts for ground owners

---

## 11. Business Benefits

### 11.1 Benefits for Customers
- **Convenience:**
  - 24/7 online access to browse and book grounds
  - No need for phone calls or in-person visits
  - Book from anywhere using mobile or computer
  
- **Transparency:**
  - Compare multiple grounds easily
  - View authentic reviews and ratings
  - See real-time availability and pricing
  - Clear cancellation and refund policies
  
- **Time-Saving:**
  - Instant booking confirmation
  - Quick search and filtering
  - Automated notifications
  - Booking history tracking
  
- **Trust & Security:**
  - Verified ground owners
  - Secure online payments
  - Booking guarantees
  - Dispute resolution system

### 11.2 Benefits for Ground Owners
- **Increased Revenue:**
  - Reach wider audience beyond local area
  - Automated booking management reduces no-shows
  - Better time slot utilization
  - Dynamic pricing options
  
- **Efficient Operations:**
  - Eliminate manual booking management
  - Reduce phone calls and messages
  - Automated payment tracking
  - Real-time booking calendar
  
- **Market Exposure:**
  - Showcase facilities with photos and details
  - Build reputation through reviews
  - Promotional platform
  - Equal opportunity for small owners
  
- **Business Insights:**
  - Booking trends and analytics
  - Revenue reports
  - Customer insights
  - Peak hour identification

### 11.3 Benefits for Community
- **Promotes Active Lifestyle:**
  - Easy access to sports facilities
  - Encourages regular physical activity
  - Community sports events facilitation
  
- **Economic Growth:**
  - Support local sports facility businesses
  - Create platform-related employment
  - Boost sports tourism
  
- **Infrastructure Utilization:**
  - Better utilization of existing facilities
  - Reduced idle time
  - Identifies areas needing new facilities

### 11.4 Benefits for Administrator
- **Centralized Control:**
  - Monitor entire platform from one dashboard
  - Quick response to issues
  - Data-driven decision making
  
- **Revenue Generation:**
  - Commission-based sustainable model
  - Scalable business model
  - Multiple revenue streams potential
  
- **Quality Assurance:**
  - Verification system maintains standards
  - Review system ensures quality
  - Easy removal of problematic users/grounds

---

## 12. Database Design

The Thunder Booking system uses a **MySQL relational database** with the following structure:

### Main Tables

#### 1. users
Stores all user information (customers, owners, admins)
```
- id (Primary Key)
- name
- email (unique)
- phone
- password (encrypted)
- role (user/owner/admin)
- owner_status (pending/approved/rejected)
- google_id (for OAuth)
- google_token
- is_suspended (boolean)
- suspension_reason
- suspended_until
- email_verified_at
- created_at
- updated_at
```

#### 2. sports_types
Categories of sports
```
- id (Primary Key)
- name (e.g., Football, Basketball)
- description
- icon
- is_active
- created_at
- updated_at
```

#### 3. grounds
Sports facility listings
```
- id (Primary Key)
- owner_id (Foreign Key → users.id)
- sport_type_id (Foreign Key → sports_types.id)
- name
- description
- location
- phone
- facilities
- images (JSON array)
- capacity (team size)
- ground_size
- rate_per_hour_day
- rate_per_hour_night
- day_start_time
- day_end_time
- is_active
- total_bookings
- created_at
- updated_at
```

#### 4. ground_availabilities
Time slot availability for grounds
```
- id (Primary Key)
- ground_id (Foreign Key → grounds.id)
- date
- available_from
- available_to
- is_available
- created_at
- updated_at
```

#### 5. bookings
Customer booking records
```
- id (Primary Key)
- user_id (Foreign Key → users.id)
- ground_id (Foreign Key → grounds.id)
- booking_date
- start_time
- end_time
- total_hours
- rate_per_hour
- total_amount
- status (pending/confirmed/completed/cancelled)
- payment_status (paid/pending/refunded)
- notes
- cancellation_reason
- cancelled_at
- created_at
- updated_at
```

#### 6. booking_locks
Prevents double-booking during checkout
```
- id (Primary Key)
- ground_id (Foreign Key → grounds.id)
- user_id (Foreign Key → users.id)
- booking_date
- start_time
- end_time
- locked_at
- expires_at
- created_at
- updated_at
```

#### 7. wallet_transactions
Financial transaction records
```
- id (Primary Key)
- user_id (Foreign Key → users.id)
- booking_id (Foreign Key → bookings.id, nullable)
- type (credit/debit/refund/commission)
- amount
- balance_before
- balance_after
- description
- status (completed/pending/failed)
- created_at
- updated_at
```

#### 8. reviews
Customer reviews and ratings
```
- id (Primary Key)
- user_id (Foreign Key → users.id)
- ground_id (Foreign Key → grounds.id)
- booking_id (Foreign Key → bookings.id)
- rating (1-5)
- comment
- is_verified
- created_at
- updated_at
```

#### 9. system_ratings
Overall platform ratings
```
- id (Primary Key)
- user_id (Foreign Key → users.id)
- rating (1-5)
- feedback
- created_at
- updated_at
```

#### 10. owner_requests
Ground owner registration requests
```
- id (Primary Key)
- user_id (Foreign Key → users.id)
- business_name
- business_license_number
- cid_number
- cid_document (file path)
- business_license (file path)
- bank_name
- bank_account_number
- bank_account_name
- ground_name
- ground_location
- sport_type
- facilities
- status (pending/approved/rejected)
- rejection_reason
- reviewed_at
- created_at
- updated_at
```

### Database Relationships

**One-to-Many:**
- users → grounds (one owner has many grounds)
- users → bookings (one customer has many bookings)
- users → wallet_transactions
- users → reviews
- sports_types → grounds
- grounds → bookings
- grounds → ground_availabilities
- grounds → booking_locks
- grounds → reviews

**Foreign Key Constraints:**
- ON DELETE CASCADE for dependent records
- ON UPDATE CASCADE for maintaining data integrity

### Indexes for Performance
- users: email, role, owner_status
- grounds: owner_id, sport_type_id, is_active
- bookings: user_id, ground_id, booking_date, status
- booking_locks: ground_id, expires_at
- wallet_transactions: user_id, type, created_at

This database design ensures:
- Data integrity through foreign keys
- Efficient querying with proper indexing
- Secure transaction handling
- Scalability for growing data
- Audit trail for all operations

---

## 13. Use Case Diagram

### Actors:
1. **Customer** (Sports enthusiasts, teams, individuals)
2. **Ground Owner** (Facility operators)
3. **Administrator** (System manager)
4. **System** (Automated processes)

### Use Cases by Actor:

#### Customer Interactions:
**Guest (Unauthenticated):**
- Browse Grounds
- Search Grounds
- Filter by Sport Type
- View Ground Details
- View Reviews

**Registered Customer:**
- Register Account
- Login (Email/Password or Google OAuth)
- Reset Password
- Update Profile
- Browse Grounds
- Search & Filter Grounds
- View Ground Details & Availability
- Select Date & Time
- Make Booking
- Pay with Wallet
- Add Money to Wallet
- View Wallet Balance
- Track Booking Status
- Cancel Booking
- View Booking History
- Write Reviews
- Rate Grounds
- Rate System
- Receive Email Notifications
- Download Booking Receipt

#### Ground Owner Interactions:
**Prospective Owner:**
- Register as Owner
- Submit Verification Documents
- Track Application Status

**Approved Owner:**
- Login to Owner Dashboard
- Add New Ground
- Upload Ground Images
- Set Ground Pricing
- Configure Availability
- Update Ground Details
- Activate/Deactivate Ground
- View Incoming Bookings
- Confirm Bookings
- View Booking Calendar
- Check Booking Details
- View Customer Information
- Update Booking Status
- View Total Earnings
- Track Commission
- View Wallet Balance
- Request Payout
- View Transaction History
- Download Financial Reports
- View Booking Analytics
- View Ground Performance
- Respond to Reviews
- Receive Booking Notifications

#### Administrator Interactions:
**User Management:**
- View All Users
- Search Users
- Suspend User Accounts
- Remove Suspensions
- Monitor User Activity

**Owner Management:**
- Review Owner Requests
- Verify Documents (CID, License)
- Approve Owner Registrations
- Reject Owner Requests
- Monitor Owner Activity
- Suspend Owner Accounts

**Ground Management:**
- Review Ground Submissions
- Approve Grounds
- Reject Ground Listings
- Deactivate Problematic Grounds
- Monitor All Grounds

**Sports Type Management:**
- Add Sports Categories
- Update Sports Types
- Activate/Deactivate Categories

**Booking Management:**
- View All Platform Bookings
- Filter Bookings
- Monitor Booking Status
- Handle Booking Disputes
- Generate Booking Reports

**Financial Management:**
- View Platform Revenue
- Monitor Wallet Transactions
- Track Commission Earnings
- Process Owner Payouts
- View Financial Analytics
- Export Financial Data

**Review Management:**
- Monitor All Reviews
- Remove Inappropriate Reviews
- View Rating Statistics

**System Analytics:**
- View Dashboard Statistics
- Generate Reports (User, Booking, Revenue)
- View Booking Trends
- Monitor System Performance
- Export Analytics Data

**System Configuration:**
- Manage Platform Settings
- Configure Commission Rates
- Set Booking Rules
- Manage Email Templates
- System Maintenance

#### System (Automated) Interactions:
- Check Ground Availability
- Create Booking Lock
- Release Expired Locks
- Update Booking Status
- Process Wallet Transactions
- Calculate Commission
- Send Email Notifications
- Transition Booking Status
- Remove Expired Suspensions
- Archive Old Bookings
- Generate Automatic Reports
- Update Dashboard Statistics
- Database Backup
- Clean Temporary Data

### Relationships:
- **Customer** ←→ Booking ←→ **Ground Owner**
- **Ground Owner** ←→ Ground Management ←→ **Admin**
- **Customer** ←→ Payment ←→ **System**
- **All Actors** ←→ Authentication ←→ **System**
- **System** ←→ Notifications → **All Actors**

---

## 14. Feasibility Study

### 14.1 Technical Feasibility

**Technology Stack:**
- **Frontend:** HTML, CSS, JavaScript, Bootstrap/Tailwind CSS
- **Backend:** PHP with Laravel Framework (v10.x)
- **Database:** MySQL
- **Server:** Apache/Nginx with PHP 8.1+
- **Development Tools:** VS Code, Composer, Git
- **Email Service:** SMTP (Gmail)

**Assessment:**
✅ **Highly Feasible**
- All technologies are open-source and free
- Laravel is well-documented with large community
- MySQL is industry-standard database
- Team has basic knowledge of web development
- Abundant online learning resources available
- Can be hosted on affordable shared hosting
- PHP and MySQL are widely supported

**Technical Requirements:**
- Web server (Apache/Nginx)
- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer for dependency management
- 1 GB RAM minimum
- 10 GB storage space

**Conclusion:** Technically feasible with available resources and skills.

### 14.2 Economic Feasibility

**Development Costs:**
| Item | Cost (BTN) |
|------|------------|
| Domain Name (.bt) | 2,000/year |
| Web Hosting (Shared) | 5,000/year |
| Email Service (Gmail) | Free |
| SSL Certificate | Free (Let's Encrypt) |
| Development Tools | Free (Open Source) |
| Database | Free (MySQL) |
| Testing | 0 |
| **Total First Year** | **7,000** |

**Ongoing Costs:**
- Domain renewal: 2,000/year
- Hosting: 5,000/year
- Maintenance: Minimal
- **Total Annual:** 7,000/year

**Revenue Model:**
1. **Commission-based:**
   - 10-15% commission per booking
   - Example: If 100 bookings/month @ 500 BTN average
   - Revenue: 100 × 500 × 0.10 = 5,000 BTN/month
   - Annual: 60,000 BTN

2. **Premium Listings:**
   - Featured ground placement: 1,000 BTN/month
   - 10 premium listings = 10,000 BTN/month

3. **Advertisement:**
   - Sports equipment vendors
   - Sports events promotion

**Break-even Analysis:**
- Development cost: 7,000 BTN
- Monthly revenue (conservative): 5,000 BTN
- Break-even: 2 months

**Return on Investment:**
- First year net: 60,000 - 7,000 = 53,000 BTN
- ROI: 757%

**Conclusion:** Economically viable with low investment and high potential returns.

### 14.3 Operational Feasibility

**User Perspective:**
✅ **Highly Feasible**
- **For Customers:**
  - Familiar online booking interface
  - Similar to popular e-commerce platforms
  - Simple 3-step booking process
  - Mobile-friendly design
  - No steep learning curve
  - Minimal training required

- **For Ground Owners:**
  - Intuitive dashboard design
  - Similar to social media post creation
  - Easy ground management
  - Simple booking tracking
  - Clear financial reports
  - Basic computer skills sufficient

- **For Administrators:**
  - Comprehensive but straightforward admin panel
  - Similar to common CMS platforms
  - Easy verification process
  - Clear monitoring tools

**Implementation Factors:**
- ✅ Existing smartphone penetration in Bhutan
- ✅ Growing internet usage
- ✅ Familiar with online shopping
- ✅ Increasing cashless payment adoption
- ✅ Young, tech-savvy sports community

**Change Management:**
- Gradual rollout starting with urban areas
- Free onboarding support for ground owners
- Tutorial videos and help documentation
- Customer support via email/phone
- Regular feedback collection

**Conclusion:** Operationally feasible with high acceptance probability.

### 14.4 Schedule Feasibility

**Project Timeline: 12 Weeks**

| Phase | Duration | Feasibility |
|-------|----------|-------------|
| Requirements Analysis | 2 weeks | ✅ Feasible |
| System Design | 2 weeks | ✅ Feasible |
| Development | 6 weeks | ✅ Feasible |
| Testing | 1.5 weeks | ✅ Feasible |
| Deployment | 0.5 weeks | ✅ Feasible |

**Assessment:**
- 12-week timeline is realistic for MVP
- Adequate time for each phase
- Buffer time included for issues
- Parallel activities possible (design + development)
- Team availability confirmed

**Conclusion:** Schedule is achievable with proper planning.

### 14.5 Legal Feasibility

**Regulatory Compliance:**
✅ **Compliant**
- No special license required for booking platform
- Ground owners use existing business licenses
- Platform acts as intermediary
- Tax obligations clear (owners pay taxes)
- Terms of Service protects all parties

**Data Protection:**
- Complies with Bhutan's data protection norms
- User consent for data collection
- Secure data storage
- Right to data deletion
- Privacy policy in place

**Contractual:**
- Clear Terms & Conditions
- Owner agreements defined
- Booking policies documented
- Cancellation terms specified
- Dispute resolution process

**Intellectual Property:**
- No copyright violations
- User-generated content policy
- Image usage rights from owners
- Platform branding original

**Conclusion:** Legally feasible with proper documentation.

### 14.6 Social Feasibility

**Social Impact:**
✅ **Positive Impact**
- Promotes healthy lifestyle
- Supports local businesses
- Creates employment opportunities
- Improves sports infrastructure utilization
- Builds community engagement

**Cultural Acceptance:**
- Aligns with Bhutan's GNH philosophy (health pillar)
- Supports youth development
- Promotes traditional and modern sports
- Preserves archery and other traditional sports facilities

**Accessibility:**
- Available to all age groups
- Urban and rural areas (with internet access)
- Affordable for general population
- Inclusive design for disabilities

**Conclusion:** Socially beneficial and culturally appropriate.

---

## 15. Risk Analysis

### Risk Matrix

| Risk | Probability | Impact | Severity | Mitigation Strategy |
|------|------------|--------|----------|---------------------|
| **Data Breach / Security Attack** | Medium | High | HIGH | - SSL/TLS encryption<br>- Password hashing (bcrypt)<br>- SQL injection prevention<br>- Regular security audits<br>- Firewall protection<br>- Security monitoring |
| **Payment Fraud** | Low | High | MEDIUM | - Payment verification system<br>- Transaction logging<br>- Wallet history tracking<br>- Dispute resolution process<br>- User verification |
| **Double Booking** | High | High | HIGH | - Booking lock mechanism<br>- Database transaction isolation<br>- Real-time availability check<br>- Automated conflict detection |
| **System Downtime** | Medium | Medium | MEDIUM | - Regular backups (daily)<br>- Database replication<br>- Monitoring system<br>- Disaster recovery plan<br>- 24-hour support |
| **Fake Ground Owners** | Medium | High | HIGH | - Document verification (CID, license)<br>- Phone verification<br>- Physical address verification<br>- Review system<br>- Admin approval process |
| **Poor User Adoption** | Medium | Medium | MEDIUM | - User-friendly design<br>- Tutorial videos<br>- Customer support<br>- Marketing campaign<br>- Incentives for early adopters |
| **Technical Errors/Bugs** | High | Low | MEDIUM | - Thorough testing<br>- Code reviews<br>- Error logging<br>- Bug tracking system<br>- Regular updates |
| **Network/Internet Issues** | Low | Medium | LOW | - Offline booking request form<br>- Progressive Web App (PWA)<br>- Optimized for slow connections<br>- Local caching |
| **Database Corruption** | Low | High | MEDIUM | - Automated daily backups<br>- Database replication<br>- Transaction logs<br>- Recovery procedures |
| **Legal Disputes** | Low | High | MEDIUM | - Clear Terms of Service<br>- User agreements<br>- Documented policies<br>- Dispute resolution process<br>- Legal consultation |
| **Scalability Issues** | Medium | Medium | MEDIUM | - Efficient database design<br>- Code optimization<br>- Caching strategies<br>- Load testing<br>- Scalable hosting |
| **Competition** | High | Medium | MEDIUM | - Unique features<br>- Quality service<br>- Competitive pricing<br>- Strong marketing<br>- Continuous improvement |

### Risk Response Plans

**High Priority Risks:**

1. **Data Breach Prevention:**
   - Implement SSL certificate (HTTPS)
   - Use Laravel's built-in security features
   - Hash all passwords with bcrypt
   - Validate and sanitize all inputs
   - Regular security patches
   - Secure API endpoints

2. **Double Booking Prevention:**
   - Database-level locks during booking
   - 5-minute booking hold mechanism
   - Real-time availability synchronization
   - Automated conflict detection
   - Transaction rollback on errors

3. **Fake Owner Prevention:**
   - Mandatory document verification
   - Multi-step approval process
   - Contact verification (phone/email)
   - Review and rating system
   - Suspension mechanism

**Contingency Plans:**

**If System Goes Down:**
- Switch to backup server within 2 hours
- Notify users via SMS/email
- Emergency contact number active
- Status page for transparency

**If Payment Issues:**
- Manual payment verification option
- Customer support intervention
- Transaction reversal process
- Alternative payment methods

**If Negative Reviews:**
- Investigation process
- Mediation service
- Policy enforcement
- Problem resolution

---

## 16. Testing Strategy

### 16.1 Testing Approach

**Testing Levels:**
1. Unit Testing
2. Integration Testing
3. System Testing
4. User Acceptance Testing (UAT)
5. Security Testing
6. Performance Testing

### 16.2 Unit Testing

**Objective:** Test individual components/functions

**Test Cases:**
- User authentication functions
- Booking creation logic
- Wallet transaction calculations
- Commission calculation
- Availability checking algorithm
- Lock creation and expiry
- Email notification triggers
- Date/time validations

**Tools:** PHPUnit (Laravel's built-in testing)

**Coverage Target:** 70%+ code coverage

### 16.3 Integration Testing

**Objective:** Test interaction between modules

**Test Areas:**
- User login → Booking flow
- Booking → Payment → Wallet
- Ground availability → Booking lock
- Owner approval → Ground listing
- Booking completion → Review system
- Payment → Commission deduction
- Notification system integration

**Scenarios:**
- Customer makes booking → Owner receives notification
- Customer cancels → Refund processed → Wallet credited
- Lock expires → Slot becomes available
- Booking date passes → Status changes to completed

### 16.4 System Testing

**Objective:** Test complete system functionality

**Functional Testing:**
- ✅ Customer can register and login
- ✅ Customer can browse and search grounds
- ✅ Customer can make booking
- ✅ Customer can pay via wallet
- ✅ Owner can add grounds
- ✅ Owner can view bookings
- ✅ Admin can approve owners
- ✅ Booking lock prevents double booking
- ✅ Email notifications sent correctly
- ✅ Wallet transactions recorded
- ✅ Reviews can be posted

**Non-Functional Testing:**
- Response time under 3 seconds
- System handles 100 concurrent users
- Database queries optimized
- Mobile responsiveness
- Cross-browser compatibility

### 16.5 User Acceptance Testing (UAT)

**Objective:** Validate with real users

**Test Groups:**
- 5 Customers
- 2 Ground Owners
- 1 Administrator

**Test Scenarios:**
1. **Customer Journey:**
   - Register account
   - Search for football ground
   - Select date and time
   - Make booking
   - Receive confirmation email
   - Cancel booking
   - Check refund

2. **Owner Journey:**
   - Register as owner
   - Submit documents
   - Add new ground
   - Upload images
   - Set pricing
   - Receive booking
   - View earnings

3. **Admin Journey:**
   - Review owner request
   - Approve owner
   - Approve ground
   - Monitor bookings
   - Generate report

**Acceptance Criteria:**
- 90%+ user satisfaction
- All critical functions work
- No major bugs
- Intuitive interface confirmed

### 16.6 Security Testing

**Tests:**
- SQL Injection attempts
- XSS attack attempts
- CSRF token validation
- Password encryption verification
- Session hijacking prevention
- File upload security
- API endpoint security
- Unauthorized access attempts

**Tools:**
- OWASP ZAP
- Manual penetration testing
- Laravel security features audit

### 16.7 Performance Testing

**Load Testing:**
- Simulate 500 concurrent users
- Measure response times
- Check database performance
- Monitor server resources

**Stress Testing:**
- Push system beyond normal load
- Identify breaking points
- Test recovery mechanisms

**Metrics:**
- Average response time < 2 seconds
- 99% requests completed successfully
- No memory leaks
- Database connection pool adequate

### 16.8 Test Documentation

**Test Plan Document:**
- Test objectives
- Test scope
- Test schedule
- Resources required
- Entry/exit criteria

**Test Cases:**
- Test case ID
- Description
- Pre-conditions
- Test steps
- Expected result
- Actual result
- Pass/Fail status

**Bug Report:**
- Bug ID
- Severity (Critical/High/Medium/Low)
- Description
- Steps to reproduce
- Screenshots
- Environment details

### 16.9 Test Schedule

| Testing Phase | Duration | Timeline |
|---------------|----------|----------|
| Unit Testing | Ongoing | Throughout development |
| Integration Testing | 3 days | Week 9 |
| System Testing | 4 days | Week 10 |
| UAT | 3 days | Week 11 |
| Security Testing | 2 days | Week 11 |
| Performance Testing | 2 days | Week 12 |
| Bug Fixes | Ongoing | Weeks 10-12 |

### 16.10 Success Criteria

**System passes testing if:**
- ✅ All critical test cases pass
- ✅ 90%+ of test cases pass
- ✅ No critical or high-severity bugs remain
- ✅ User acceptance meets 85%+ satisfaction
- ✅ Performance benchmarks achieved
- ✅ Security audit passes
- ✅ Cross-browser compatibility confirmed

---

## 17. Future Enhancements

### Phase 2 (6-12 months after launch)

1. **Mobile Applications**
   - Native iOS app (Swift)
   - Native Android app (Kotlin/Java)
   - React Native cross-platform app
   - Push notifications
   - Offline booking capability

2. **Integrated Payment Gateway**
   - mBoB payment integration
   - mPay payment integration
   - Bank transfer integration
   - Online card payments
   - QR code payments

3. **Advanced Booking Features**
   - Recurring bookings (weekly/monthly)
   - Team/group booking management
   - Tournament booking system
   - Bulk booking discounts
   - Season pass subscriptions

4. **Enhanced Communication**
   - In-app messaging system
   - Chat between customer and owner
   - WhatsApp integration
   - SMS notifications
   - Video call for ground preview

5. **Smart Recommendations**
   - AI-based ground suggestions
   - Personalized recommendations based on history
   - Smart pricing suggestions for owners
   - Peak hour predictions

### Phase 3 (12-24 months)

6. **Social Features**
   - Find players/teams nearby
   - Create sports events
   - Tournament management
   - League organization
   - Player profiles and statistics

7. **Equipment Rental**
   - Rent sports equipment
   - Locker rental
   - Uniform rental
   - Equipment marketplace

8. **Loyalty Program**
   - Points system for bookings
   - Referral rewards
   - Membership tiers
   - Exclusive discounts
   - Birthday bonuses

9. **Advanced Analytics**
   - Predictive analytics for demand
   - Revenue forecasting
   - Customer behavior analysis
   - Interactive dashboards
   - Business intelligence reports

10. **Multi-language Support**
    - Dzongkha language
    - Nepali language
    - Hindi language
    - Language switcher

11. **Virtual Ground Tour**
    - 360° ground photos
    - Virtual reality preview
    - Aerial drone footage
    - Live ground camera feed

12. **Coaching Services**
    - Book sports coaches
    - Training session scheduling
    - Coach profiles and ratings
    - Group training packages

13. **Weather Integration**
    - Weather forecast display
    - Automatic booking alerts for bad weather
    - Rain check policy
    - Weather-based recommendations

14. **Insurance Integration**
    - Booking insurance option
    - Injury coverage
    - Equipment damage protection

15. **Corporate Booking Portal**
    - Company accounts
    - Corporate packages
    - Bulk booking management
    - Invoice generation for companies
    - CSR program integration

### Technology Upgrades

16. **Progressive Web App (PWA)**
    - Offline functionality
    - App-like experience
    - Add to home screen
    - Background sync

17. **Blockchain Integration**
    - Transparent transaction records
    - Smart contracts for bookings
    - Cryptocurrency payment option

18. **IoT Integration**
    - Smart locks for grounds
    - Automated access control
    - Real-time occupancy sensors
    - Energy usage tracking

19. **Augmented Reality (AR)**
    - AR ground visualization
    - Virtual ground placement
    - AR navigation to grounds

20. **Voice Assistant Integration**
    - Alexa/Google Home booking
    - Voice search
    - Voice-based booking confirmation

---

## 18. Project Benefits Summary

### 18.1 For Customers

**Convenience:**
- ✅ 24/7 booking access from anywhere
- ✅ No phone calls or physical visits needed
- ✅ Instant booking confirmation
- ✅ Mobile-friendly interface

**Transparency:**
- ✅ Real-time availability display
- ✅ Clear pricing (day/night rates)
- ✅ Authentic reviews and ratings
- ✅ Verified ground owners
- ✅ Complete booking history

**Efficiency:**
- ✅ Quick search and filtering
- ✅ Compare multiple grounds easily
- ✅ Save favorite grounds
- ✅ Automated reminders
- ✅ Easy cancellation with refunds

**Trust & Security:**
- ✅ Secure payment system
- ✅ Booking guarantees
- ✅ Dispute resolution
- ✅ Data privacy protection
- ✅ Verified facilities

### 18.2 For Ground Owners

**Revenue Growth:**
- ✅ Reach customers beyond local area
- ✅ 24/7 booking availability
- ✅ Better time slot utilization
- ✅ Reduced idle time
- ✅ Dynamic pricing flexibility
- ✅ Increased booking volume

**Operational Efficiency:**
- ✅ Automated booking management
- ✅ No manual calendar maintenance
- ✅ Reduced phone calls
- ✅ Automatic payment tracking
- ✅ Real-time booking updates
- ✅ Digital record keeping

**Business Growth:**
- ✅ Wider market exposure
- ✅ Professional online presence
- ✅ Build reputation through reviews
- ✅ Equal opportunity with big facilities
- ✅ Data-driven business insights
- ✅ Competitive advantage

**Financial Management:**
- ✅ Clear financial reports
- ✅ Transparent commission structure
- ✅ Automated wallet system
- ✅ Easy payout process
- ✅ Revenue analytics

### 18.3 For Sports Community

**Health & Wellness:**
- ✅ Promotes active lifestyle
- ✅ Easy access to sports facilities
- ✅ Encourages regular exercise
- ✅ Community sports events

**Social Impact:**
- ✅ Brings sports enthusiasts together
- ✅ Youth engagement
- ✅ Builds community spirit
- ✅ Reduces social isolation

**Infrastructure:**
- ✅ Better utilization of existing facilities
- ✅ Identifies demand for new facilities
- ✅ Supports facility maintenance
- ✅ Infrastructure data for planning

**Economic:**
- ✅ Supports local businesses
- ✅ Creates employment
- ✅ Boosts sports economy
- ✅ Tourism potential

### 18.4 For Administrator

**Platform Management:**
- ✅ Centralized control dashboard
- ✅ Real-time monitoring
- ✅ Quick issue resolution
- ✅ Data-driven decisions

**Revenue:**
- ✅ Commission-based sustainable income
- ✅ Scalable business model
- ✅ Multiple revenue streams
- ✅ Low operational costs

**Quality Control:**
- ✅ Verification system maintains standards
- ✅ Review system ensures quality
- ✅ Easy removal of bad actors
- ✅ Transparent operations

**Growth:**
- ✅ Scalable to entire Bhutan
- ✅ Expandable to other sports services
- ✅ Platform for partnerships
- ✅ Market leadership opportunity

### 18.5 National Benefits

**Economic Development:**
- ✅ Supports SME growth
- ✅ Digital economy contribution
- ✅ Employment generation
- ✅ Tax revenue increase

**Social Development:**
- ✅ Promotes Gross National Happiness (GNH)
- ✅ Youth development
- ✅ Healthy population
- ✅ Community building

**Technological Advancement:**
- ✅ Digital literacy improvement
- ✅ Innovation in sports sector
- ✅ E-commerce adoption
- ✅ Smart city initiative support

**Environmental:**
- ✅ Reduced travel for booking (phone/in-person)
- ✅ Paperless transactions
- ✅ Efficient resource utilization
- ✅ Carbon footprint reduction

---

## 19. Expected Outcomes

### 19.1 Technical Outcomes

**Fully Functional System:**
- ✅ Web-based booking platform operational
- ✅ Responsive design (mobile, tablet, desktop)
- ✅ Secure authentication system
- ✅ Real-time availability checking
- ✅ Automated booking lock mechanism
- ✅ Integrated wallet system
- ✅ Email notification system
- ✅ Review and rating system
- ✅ Admin dashboard with analytics
- ✅ Owner dashboard with reports
- ✅ Customer-friendly interface

**Database System:**
- ✅ Relational database (MySQL)
- ✅ 13+ tables with proper relationships
- ✅ Data integrity maintained
- ✅ Efficient indexing
- ✅ Automated backup system

**Security:**
- ✅ Encrypted passwords (bcrypt)
- ✅ CSRF protection
- ✅ SQL injection prevention
- ✅ Role-based access control
- ✅ Secure session management
- ✅ SSL/TLS encryption

### 19.2 Functional Outcomes

**For Customers:**
- ✅ Browse 10+ sports grounds initially
- ✅ Make bookings in under 3 minutes
- ✅ Receive instant booking confirmation
- ✅ Track booking status in real-time
- ✅ Manage wallet balance
- ✅ View complete booking history
- ✅ Post reviews and ratings

**For Ground Owners:**
- ✅ Complete registration in 10 minutes
- ✅ List grounds with 5+ images
- ✅ Set flexible pricing (day/night rates)
- ✅ Receive instant booking notifications
- ✅ Manage bookings from dashboard
- ✅ View earnings and analytics
- ✅ Request payouts easily

**For Administrators:**
- ✅ Verify owner documents within 24 hours
- ✅ Approve/reject grounds quickly
- ✅ Monitor all platform activities
- ✅ Generate reports in PDF/Excel
- ✅ Track commission automatically
- ✅ Manage sports categories
- ✅ Handle disputes efficiently

### 19.3 Performance Outcomes

**Speed:**
- ✅ Page load time under 3 seconds
- ✅ Search results within 1 second
- ✅ Booking confirmation instant
- ✅ Database queries optimized (< 100ms)

**Capacity:**
- ✅ Support 500+ concurrent users
- ✅ Handle 1000+ bookings/month
- ✅ Store 100+ ground listings
- ✅ Process 5000+ transactions

**Reliability:**
- ✅ 99%+ system uptime
- ✅ Zero data loss
- ✅ Automated backup daily
- ✅ Error recovery mechanisms

### 19.4 Business Outcomes

**First 6 Months:**
- ✅ 20+ verified ground owners
- ✅ 50+ ground listings
- ✅ 200+ registered customers
- ✅ 500+ bookings completed
- ✅ 10,000+ BTN revenue

**First Year:**
- ✅ 50+ ground owners
- ✅ 100+ grounds listed
- ✅ 1000+ active customers
- ✅ 5000+ bookings
- ✅ 100,000+ BTN revenue
- ✅ Break-even achieved
- ✅ Positive ROI

**Market Position:**
- ✅ #1 sports booking platform in Bhutan
- ✅ Recognized brand
- ✅ Strong user base in urban areas
- ✅ Partnerships with sports associations

### 19.5 Social Outcomes

**Community Impact:**
- ✅ 1000+ people gain easy access to sports
- ✅ Increased physical activity in community
- ✅ Better utilization of sports infrastructure
- ✅ Support for 20+ small business owners

**Quality of Life:**
- ✅ Convenient sports access for all
- ✅ Promotes healthy lifestyle
- ✅ Saves time (no phone calls/visits)
- ✅ Transparent and fair system

**Cultural:**
- ✅ Preserves traditional sports (archery grounds)
- ✅ Promotes modern sports adoption
- ✅ Youth engagement in sports
- ✅ Community building through sports

### 19.6 Learning Outcomes

**Student Development:**
- ✅ Practical web development experience
- ✅ Full-stack development skills
- ✅ Database design expertise
- ✅ Project management experience
- ✅ Real-world problem-solving
- ✅ Client communication skills
- ✅ Testing and deployment knowledge

**Knowledge Gained:**
- ✅ Laravel framework mastery
- ✅ MVC architecture understanding
- ✅ RESTful API design
- ✅ Payment system integration
- ✅ Email notification systems
- ✅ Security best practices
- ✅ Agile development methodology

### 19.7 Measurable KPIs

**User Metrics:**
- ✅ 1000+ registered users (Year 1)
- ✅ 80%+ user retention rate
- ✅ 4+ star average rating
- ✅ 70%+ repeat booking rate

**Business Metrics:**
- ✅ 5000+ bookings (Year 1)
- ✅ 100,000+ BTN revenue (Year 1)
- ✅ 500,000+ BTN GMV (Year 1)
- ✅ 25%+ month-over-month growth

**Technical Metrics:**
- ✅ 99%+ uptime
- ✅ < 3 second page load
- ✅ < 5 critical bugs per month
- ✅ 70%+ code coverage (tests)

**Customer Satisfaction:**
- ✅ 85%+ customer satisfaction
- ✅ 4+ star average review
- ✅ < 5% booking cancellation rate
- ✅ 90%+ email open rate

---

## 20. Ethical, Legal & Security Considerations

### 20.1 Ethical Considerations

**Data Privacy:**
- ✅ Collect only necessary personal information
- ✅ Transparent data usage policy
- ✅ User consent for data collection
- ✅ Right to access personal data
- ✅ Right to delete account and data
- ✅ No selling of user data to third parties
- ✅ Anonymized data for analytics

**Fair Business Practices:**
- ✅ Equal opportunity for all ground owners
- ✅ No preferential treatment without disclosure
- ✅ Transparent commission structure
- ✅ Fair review and rating system
- ✅ Clear terms and conditions
- ✅ Honest marketing and communication

**Customer Protection:**
- ✅ Accurate ground information required
- ✅ Verified owners only
- ✅ Dispute resolution mechanism
- ✅ Refund policy clearly stated
- ✅ Protection from fraud
- ✅ Customer support available

**Social Responsibility:**
- ✅ Promote responsible sports culture
- ✅ Support local businesses
- ✅ Environmental consciousness
- ✅ Accessibility for all
- ✅ Youth safety considerations

### 20.2 Legal Considerations

**Regulatory Compliance:**
- ✅ Register as e-commerce business
- ✅ Comply with Bhutan ICT Policy
- ✅ Follow Department of Trade regulations
- ✅ Tax compliance (TPN registration)
- ✅ Business licensing requirements
- ✅ Consumer protection laws

**Contracts & Agreements:**
- **Terms of Service:**
  - User obligations and rights
  - Platform usage rules
  - Liability limitations
  - Dispute resolution process
  
- **Privacy Policy:**
  - Data collection methods
  - Data usage purposes
  - Third-party sharing
  - Security measures
  - User rights
  
- **Owner Agreement:**
  - Terms of ground listing
  - Commission structure
  - Payout terms
  - Quality requirements
  - Suspension/termination conditions
  
- **Booking Policy:**
  - Booking confirmation process
  - Payment terms
  - Cancellation policy
  - Refund conditions
  - Force majeure clause

**Intellectual Property:**
- ✅ Platform design and code ownership
- ✅ Brand name and logo protection
- ✅ User-generated content ownership
- ✅ Image usage rights from owners
- ✅ Third-party library licenses

**Liability Protection:**
- ✅ Disclaimer of liability for:
  - Injuries during sports activities
  - Ground quality issues
  - Customer-owner disputes
  - Weather-related cancellations
  - Force majeure events
- ✅ Users agree to terms before booking
- ✅ Insurance recommendations

**Transaction Laws:**
- ✅ Electronic transaction compliance
- ✅ Payment processing regulations
- ✅ Receipt generation
- ✅ Financial record keeping
- ✅ Anti-money laundering awareness

### 20.3 Security Considerations

**User Authentication:**
- ✅ Strong password requirements (8+ chars, mixed case, numbers)
- ✅ Password hashing using bcrypt (Laravel default)
- ✅ Email verification mandatory
- ✅ Secure password reset mechanism
- ✅ Session timeout after inactivity
- ✅ Remember me functionality (secure)
- ✅ Failed login attempt limiting

**Data Security:**
- ✅ **Encryption:**
  - SSL/TLS for data in transit (HTTPS)
  - Database encryption at rest
  - Password hashing (irreversible)
  - Sensitive data encryption
  
- ✅ **Access Control:**
  - Role-based access (Customer, Owner, Admin)
  - Middleware protection for routes
  - API authentication tokens
  - Admin-only functionalities restricted
  
- ✅ **Database Security:**
  - Parameterized queries (Laravel ORM)
  - SQL injection prevention
  - Database user with limited permissions
  - Regular database backups
  - No direct database exposure

**Application Security:**
- ✅ **Input Validation:**
  - Server-side validation always
  - Client-side validation for UX
  - Sanitize all user inputs
  - File upload restrictions (type, size)
  - Prevent malicious code injection
  
- ✅ **CSRF Protection:**
  - Laravel CSRF tokens on all forms
  - Token verification for state-changing operations
  - SameSite cookie attributes
  
- ✅ **XSS Prevention:**
  - Output encoding (Blade templates)
  - Content Security Policy headers
  - Sanitize user-generated content
  - No eval() or unescaped output
  
- ✅ **Session Security:**
  - Secure session cookies
  - HTTP-only cookies
  - Session fixation prevention
  - Regular session regeneration

**Payment Security:**
- ✅ PCI DSS compliance considerations
- ✅ No storage of sensitive payment data
- ✅ Secure wallet transaction logging
- ✅ Transaction verification
- ✅ Fraud detection mechanisms
- ✅ Encrypted financial data

**File Upload Security:**
- ✅ Allowed file types only (jpg, png, pdf)
- ✅ File size limits (max 5MB per image)
- ✅ Rename files on upload
- ✅ Store outside web root
- ✅ Virus scanning (if possible)
- ✅ Content-type verification

**API Security:**
- ✅ Authentication required for sensitive endpoints
- ✅ Rate limiting to prevent abuse
- ✅ Input validation on all endpoints
- ✅ CORS configuration
- ✅ API versioning

**Infrastructure Security:**
- ✅ Regular security updates (OS, PHP, MySQL)
- ✅ Firewall configuration
- ✅ Intrusion detection
- ✅ DDoS protection (hosting provider)
- ✅ Regular security audits
- ✅ Backup and disaster recovery plan

**Monitoring & Logging:**
- ✅ Application error logging
- ✅ Security event logging
- ✅ Failed login attempt tracking
- ✅ Transaction audit trail
- ✅ Regular log review
- ✅ Anomaly detection

**Incident Response:**
- ✅ Security incident response plan
- ✅ Data breach notification procedure
- ✅ Regular backups for recovery
- ✅ Contact information for reporting issues
- ✅ System restoration procedures

**User Education:**
- ✅ Security tips on website
- ✅ Phishing awareness
- ✅ Password best practices
- ✅ Secure device usage recommendations
- ✅ Report suspicious activity option

### 20.4 Compliance Checklist

**Data Protection:**
- ☑ Privacy policy published
- ☑ User consent obtained
- ☑ Data retention policy defined
- ☑ Right to access implemented
- ☑ Right to delete implemented
- ☑ Data breach response plan ready

**Business Compliance:**
- ☑ Business license obtained
- ☑ TPN registered (tax)
- ☑ Terms of Service published
- ☑ Refund policy clear
- ☑ Commission structure documented

**Security Compliance:**
- ☑ HTTPS enabled
- ☑ Password policy enforced
- ☑ Regular backups configured
- ☑ Access controls implemented
- ☑ Security testing completed

---

## 21. Project Timeline

### Total Duration: 12 Weeks

### Week-by-Week Breakdown:

#### **Phase 1: Planning & Analysis (Weeks 1-2)**

**Week 1: Requirement Analysis**
- Days 1-2: Stakeholder meetings
  - Interview potential customers
  - Meet with ground owners
  - Define user needs
- Days 3-4: System requirement documentation
  - Functional requirements
  - Non-functional requirements
  - Define user roles and permissions
- Days 5-7: Feasibility study
  - Technical feasibility
  - Economic feasibility
  - Prepare project proposal

**Week 2: System Design**
- Days 1-2: Database design
  - ER diagram creation
  - Table structure definition
  - Relationship mapping
  - Normalization
- Days 3-4: System architecture
  - Technology stack finalization
  - MVC architecture planning
  - API endpoint design
- Days 5-7: UI/UX design
  - Wireframes creation
  - User flow diagrams
  - Interface mockups
  - Approval from stakeholders

#### **Phase 2: Development (Weeks 3-8)**

**Week 3: Environment Setup & Authentication**
- Days 1-2: Development environment
  - Install Laravel
  - Configure database
  - Set up Git repository
  - Configure development tools
- Days 3-5: Authentication system
  - User registration
  - Login (email/password)
  - Google OAuth integration
  - Password reset
  - Email verification
- Days 6-7: User roles and permissions
  - Customer, Owner, Admin roles
  - Middleware for role-based access
  - Dashboard routing

**Week 4: Customer Module**
- Days 1-2: Ground browsing
  - Ground listing page
  - Ground detail page
  - Image carousel
  - Search functionality
- Days 3-4: Filtering and sorting
  - Filter by sport type
  - Filter by location
  - Price range filter
  - Sort options
- Days 5-7: Booking system (Part 1)
  - Availability calendar
  - Time slot selection
  - Booking form
  - Booking validation

**Week 5: Booking & Payment**
- Days 1-3: Booking system (Part 2)
  - Booking lock mechanism
  - Booking creation
  - Booking confirmation
  - Status tracking
- Days 4-5: Wallet system
  - Wallet balance display
  - Add money functionality
  - Payment with wallet
  - Transaction history
- Days 6-7: Booking management
  - View bookings
  - Cancel booking
  - Refund process
  - Booking history

**Week 6: Owner Module**
- Days 1-2: Owner registration
  - Registration form
  - Document upload
  - Application submission
  - Status tracking
- Days 3-4: Ground management
  - Add ground form
  - Upload images (multiple)
  - Set pricing (day/night)
  - Configure availability
- Days 5-7: Owner dashboard
  - View bookings
  - Booking calendar
  - Earnings display
  - Financial reports
  - Ground analytics

**Week 7: Admin Module**
- Days 1-2: Owner verification
  - View owner requests
  - Document verification interface
  - Approve/reject functionality
  - Notification system
- Days 3-4: Ground management
  - Ground approval system
  - Sports type CRUD
  - Ground monitoring
- Days 5-7: Admin dashboard
  - Platform statistics
  - User management
  - Booking oversight
  - Financial reports
  - Revenue analytics

**Week 8: Advanced Features**
- Days 1-2: Review system
  - Review submission
  - Rating display
  - Review moderation
  - System rating
- Days 3-4: Notification system
  - Email templates
  - Booking notifications
  - Wallet notifications
  - Owner notifications
- Days 5-7: Automation
  - Booking transitions (cron job)
  - Lock expiry cleanup
  - Suspension expiry
  - Commission calculations

#### **Phase 3: Testing (Weeks 9-10)**

**Week 9: Testing Phase 1**
- Days 1-2: Unit testing
  - Test authentication
  - Test booking logic
  - Test wallet calculations
  - Test availability checking
- Days 3-4: Integration testing
  - Test booking flow end-to-end
  - Test payment flow
  - Test notification system
  - Test owner approval flow
- Days 5-7: Bug fixing
  - Fix identified issues
  - Re-test fixed bugs
  - Update documentation

**Week 10: Testing Phase 2**
- Days 1-2: System testing
  - Full system functionality
  - Cross-browser testing
  - Mobile responsiveness
  - Performance testing
- Days 3-4: Security testing
  - Penetration testing
  - SQL injection attempts
  - XSS testing
  - CSRF validation
- Days 5-7: User Acceptance Testing (UAT)
  - Real user testing
  - Collect feedback
  - Make refinements
  - Final bug fixes

#### **Phase 4: Deployment & Documentation (Weeks 11-12)**

**Week 11: Pre-Deployment**
- Days 1-2: Documentation
  - User manual creation
  - Admin guide
  - Owner guide
  - Technical documentation
- Days 3-4: Deployment preparation
  - Production server setup
  - Domain configuration
  - SSL certificate installation
  - Database migration to production
- Days 5-7: Data preparation
  - Seed initial data
  - Create admin account
  - Test data cleanup
  - Backup procedures

**Week 12: Deployment & Launch**
- Days 1-2: Deployment
  - Code deployment to production
  - Final configuration
  - Production testing
  - Performance monitoring
- Days 3-4: Launch preparation
  - Marketing materials
  - Social media announcements
  - Contact ground owners
  - Customer outreach
- Days 5-7: Post-deployment
  - Monitor system performance
  - Address immediate issues
  - User support
  - Collect initial feedback
  - Project handover

### Milestones

| Milestone | Week | Deliverable |
|-----------|------|-------------|
| Requirements Complete | 1 | Requirements Document |
| Design Complete | 2 | Design Documents, Wireframes |
| Authentication Done | 3 | Working Login System |
| Customer Module Complete | 5 | Booking System Functional |
| Owner Module Complete | 6 | Ground Management Ready |
| Admin Module Complete | 7 | Admin Dashboard Operational |
| Feature Complete | 8 | All Features Implemented |
| Testing Complete | 10 | Test Reports, Bug-Free System |
| Deployment Complete | 12 | Live System Operational |

### Resource Allocation

**Human Resources:**
- 1 Full-stack Developer (All 12 weeks)
- 1 Project Supervisor (Weekly meetings)
- 3-5 Beta testers (Week 10-11)

**Technical Resources:**
- Development machine
- Code editor (VS Code)
- Web hosting account
- Domain name
- Email service (Gmail)
- Testing devices (phone, tablet)

### Risk Buffer
- Extra time allocated in bug fixing phases
- Parallel testing during development
- Contingency plan for delays

---

## 22. Conclusion

### Project Summary

The **Thunder Booking - Sports Ground Booking Management System** addresses a critical gap in Bhutan's sports infrastructure management by providing a modern, efficient, and user-friendly digital platform for booking sports facilities. The current manual and fragmented booking process creates inefficiencies, lost opportunities, and frustrations for both customers and ground owners. This project offers a comprehensive solution that benefits all stakeholders while promoting active lifestyles and supporting local businesses.

### Key Achievements

**Technical Innovation:**
- Scalable web-based platform using Laravel framework
- Real-time availability checking and booking lock mechanism
- Automated wallet system for seamless transactions
- Intelligent notification system via email
- Secure authentication with Google OAuth integration
- Comprehensive admin dashboard with analytics
- Mobile-responsive design for all devices

**Business Value:**
- Low development cost (7,000 BTN) with high ROI potential (757% first year)
- Commission-based sustainable revenue model
- Scalable business model for nationwide expansion
- Support for local ground owners and sports economy
- Creates employment opportunities
- Promotes Gross National Happiness through active lifestyle

**Social Impact:**
- Easy access to sports facilities for all citizens
- Promotes healthy lifestyle and physical activity
- Supports local businesses and entrepreneurs
- Builds community through sports
- Preserves traditional sports (archery grounds) while promoting modern sports
- Youth engagement and development
- Transparent and fair marketplace

**User Benefits:**
- **Customers:** Convenient booking, real-time availability, secure payments, booking history
- **Ground Owners:** Wider market reach, automated management, financial transparency, business insights
- **Administrators:** Centralized control, quality assurance, revenue tracking, data-driven decisions

### Feasibility Confirmation

**Technical Feasibility:** ✅ **Highly Feasible**
- All required technologies are open-source, well-documented, and widely supported
- Development team has necessary skills or can acquire them
- No complex or unproven technologies required

**Economic Feasibility:** ✅ **Highly Viable**
- Minimal investment required (7,000 BTN)
- Quick break-even timeline (2 months)
- Strong ROI potential (757% first year)
- Multiple revenue streams

**Operational Feasibility:** ✅ **Highly Acceptable**
- User-friendly interface requiring minimal training
- Familiar online booking process
- High smartphone and internet penetration
- Growing digital payment adoption

**Legal Feasibility:** ✅ **Fully Compliant**
- No special licensing required
- Compliant with Bhutan ICT policies
- Clear terms of service and privacy policy
- Proper data protection measures

### Expected Impact

**In 6 Months:**
- 20+ verified ground owners
- 50+ ground listings
- 200+ registered customers
- 500+ bookings completed
- Established presence in urban areas

**In 1 Year:**
- 50+ ground owners
- 100+ grounds listed
- 1000+ active customers
- 5000+ bookings
- 100,000+ BTN revenue
- Break-even achieved
- Market leadership in Bhutan

**Long-term (2-3 Years):**
- Nationwide coverage across all 20 dzongkhags
- 200+ ground owners
- 500+ sports facilities listed
- 10,000+ active users
- Mobile applications launched
- Integrated payment gateway
- International expansion potential

### Sustainability

The project ensures long-term sustainability through:
- **Financial Model:** Commission-based revenue provides ongoing income without requiring upfront fees from users
- **Technical Foundation:** Built on stable, well-supported technologies (Laravel, MySQL)
- **Scalability:** Architecture designed to handle growing users and features
- **Market Need:** Addresses real, persistent problem in sports facility management
- **Low Maintenance:** Automated systems reduce ongoing maintenance costs

### Competitive Advantage

Thunder Booking will have first-mover advantage in Bhutan's sports booking market with:
- Specialized focus on sports facilities (not generic booking platform)
- Local context understanding (Bhutanese sports culture)
- Affordable pricing for local market
- Government and sports association partnership potential
- Strong verification and quality control system
- Comprehensive feature set from launch

### Alignment with National Goals

The project supports Bhutan's national objectives:
- **Gross National Happiness (GNH):** Promotes health pillar through active sports participation
- **Digital Bhutan:** Contributes to digital economy and e-governance initiatives
- **Youth Employment:** Creates platform-related jobs and entrepreneurship opportunities
- **Local Business Support:** Empowers small sports facility owners
- **Health and Wellness:** Encourages regular physical activity
- **Social Cohesion:** Builds community through sports

### Risk Management

All identified risks have mitigation strategies:
- **Security:** SSL encryption, password hashing, regular audits
- **Double Booking:** Booking lock mechanism, database locks
- **Payment Fraud:** Transaction verification, audit trails
- **System Downtime:** Daily backups, disaster recovery plan
- **User Adoption:** Marketing campaign, user-friendly interface

### Ethical and Legal Compliance

The project adheres to:
- Data privacy and protection standards
- Fair business practices
- Consumer protection laws
- Transparent terms and conditions
- Secure handling of personal and financial data
- Role-based access control

### Final Statement

Thunder Booking is a technically sound, economically viable, and socially beneficial project that will modernize sports facility management in Bhutan. The system provides a win-win solution for all stakeholders: customers get convenient access to verified facilities, ground owners gain wider market reach and automated management, and the community benefits from improved sports infrastructure utilization and promotion of active lifestyles.

The 12-week development timeline is realistic and achievable, with clear milestones and deliverables at each phase. The low investment requirement (7,000 BTN) combined with strong revenue potential makes this an attractive business opportunity. The project's technical architecture is scalable and maintainable, ensuring long-term sustainability.

Most importantly, Thunder Booking addresses a real problem faced daily by sports enthusiasts and facility owners across Bhutan. By bringing efficiency, transparency, and convenience to the sports booking process, this platform will make sports more accessible to everyone while supporting local businesses and promoting the nation's health and happiness.

### Recommendation

Based on the comprehensive feasibility analysis, clear benefits to all stakeholders, alignment with national goals, and realistic implementation plan, **it is strongly recommended that the Thunder Booking project be approved and developed.** The project has high probability of success and will create lasting positive impact on Bhutan's sports culture and digital economy.

---

## Appendices

### Appendix A: Glossary

- **API:** Application Programming Interface
- **CSRF:** Cross-Site Request Forgery
- **GNH:** Gross National Happiness
- **GMV:** Gross Merchandise Value
- **KPI:** Key Performance Indicator
- **MVC:** Model-View-Controller
- **OAuth:** Open Authorization
- **ORM:** Object-Relational Mapping
- **PWA:** Progressive Web App
- **ROI:** Return on Investment
- **SSL/TLS:** Secure Sockets Layer / Transport Layer Security
- **UAT:** User Acceptance Testing
- **UI/UX:** User Interface / User Experience
- **XSS:** Cross-Site Scripting

### Appendix B: References

1. Laravel Framework Documentation - https://laravel.com/docs
2. MySQL Database Documentation
3. Bhutan ICT Policies and Regulations
4. E-commerce Best Practices
5. Web Security Standards (OWASP)
6. UX Design Principles

### Appendix C: Contact Information

**Project Team:**
- Student: [Your Name]
- Email: [Your Email]
- Phone: [Your Phone]

**Supervisor:**
- Name: [Supervisor Name]
- Email: [Supervisor Email]
- Institute: [Institute Name]

**System Demo:**
- URL: http://localhost:8000 (Development)
- Email: thunderbooking975@gmail.com
- System Name: Thunder Booking

---

## Project Approval

**Student Signature:** ___________________ **Date:** ___________

**Supervisor Signature:** ___________________ **Date:** ___________

**Institute Seal:**

---

**END OF PROJECT PROPOSAL**

**Thunder Booking - Sports Ground Booking Management System**
**Total Pages: 50+**
**Project Duration: 12 Weeks**
**Academic Year: 2024-2026**
