# Thunder Booking System - Presentation Slides

---

## Slide 1: Project Overview
**Thunder Booking System**

• Web platform for booking sports grounds/facilities
• Connects users with ground owners through centralized marketplace
• Real-time availability management with conflict detection
• User review system enabling transparent trust-building
• Admin oversight ensuring quality and compliance standards

---

## Slide 2: Problem Statement
**What Problems Do We Solve?**

• Manual booking coordination via phone calls causing confusion
• Double-bookings due to lack of real-time visibility
• No centralized review mechanism creating trust issues
• Inconsistent pricing and unclear ground information
• Difficult owner onboarding without verification process

---

## Slide 3: Key Features Overview
**9 Major System Features**

• User authentication with Google OAuth and email/password
• Ground management with multi-step image uploads
• Real-time booking with conflict detection system
• Dynamic pricing with day/night rate differentiation
• Payment proof workflow with 10-minute expiry timers
• Comprehensive review and rating system
• Owner onboarding pipeline with admin approval
• Advanced search and filtering by sport type, price, capacity
• Maintenance scheduling blocking bookings automatically

---

## Slide 4: User Roles & Permissions
**Four User Types**

• **Guest:** Browse grounds, view details, apply filters
• **User:** Book grounds, submit reviews, request owner status
• **Owner:** Manage grounds, track bookings, handle payments
• **Admin:** Full control, approvals, analytics dashboard

---

## Slide 5: System Architecture
**Technical Design**

• Routes → Controllers → Services → Models → Database
• Service layer for business logic separation
• Policy-based authorization for granular permissions
• Middleware for authentication and role checking
• Blade templates with Alpine.js for interactivity

---

## Slide 6: Database Design
**8 Core Tables**

• **Users:** Roles, suspension, late cancellation tracking
• **Grounds:** Pricing, capacity, availability, maintenance flags
• **Bookings:** Status flow, payment tracking, admin commission
• **Reviews:** 1-5 star ratings with optional comments
• **OwnerRequests:** Multi-step approval pipeline
• **SportsTypes:** Ground categorization
• **SystemRatings:** Platform satisfaction feedback
• **GroundAvailability:** Weekly schedule per ground

---

## Slide 7: Booking Workflow
**From Request to Completion**

• User searches and filters available grounds
• Selects date/time and checks real-time availability
• Creates booking and uploads payment proof image
• Booking status: pending → payment_submitted → booked
• System sends confirmation email to user and owner
• Owner confirms payment received, booking completed

---

## Slide 8: Pricing Intelligence
**Dynamic Rate Calculation**

• Grounds set day rates (e.g., ₹500/hour) and night rates (₹750/hour)
• Configurable time windows for rate switching
• Bookings crossing periods charged per applicable rate
• 10% discount applied for bookings ≥17 hours
• Automatic calculation handles period crossing accurately

---

## Slide 9: Owner Onboarding
**Three-Tier Verification**

• Users submit multi-step owner request with ground details
• Admin reviews business info, images, credentials thoroughly
• Approval creates ground, promotes user to owner, sends confirmation
• Rejection provides feedback enabling improvement
• Single transaction ensures data consistency

---

## Slide 10: Payment System
**Secure Payment Tracking**

• Users upload payment proof images during booking
• 10-minute expiry timer forces timely payment confirmation
• Admin verifies payment before booking confirmation
• Payment status transitions tracked with timestamps
• Refund amounts tracked per booking for accountability

---

## Slide 11: Notifications System
**16 Automated Email Types**

• Booking: Confirmation, Approved, Cancelled, Payment Submitted
• Account: Created, Suspended, Owner Account Created
• Owner Request: New (admin), Approved, Rejected
• Ground: Deleted, Maintenance Status Changed
• All notifications queue-based for async processing

---

## Slide 12: Key Achievements
**Technical Excellence**

• Real-time conflict detection prevents double-bookings reliably
• Three-tier verification ensures quality grounds and owners
• Flexible day/night pricing supports diverse strategies
• Async notifications prevent system delays significantly
• Soft deletes enable audit trails and data recovery
• Google OAuth integration supports multiple authentication methods

---

## Slide 13: Challenges Solved
**Problem-Solving Examples**

• **Double-Booking:** Datetime overlap logic with database constraints
• **Payment Delays:** 10-minute expiry and owner confirmation options
• **Owner Quality:** Multi-step form with business verification
• **Email Failures:** Async queue prevents booking failures
• **Complex Pricing:** Service layer handles period crossing calculations

---

## Slide 14: Technology Stack
**Frontend & Backend**

• **Backend:** Laravel 10, PHP 8.1+, MySQL, Socialite OAuth
• **Frontend:** Vite, Tailwind CSS, Alpine.js, Axios
• **Tools:** PHPUnit testing, Faker seeding, Pint linting
• **Infrastructure:** XAMPP local, Render deployment-ready

---

## Slide 15: Limitations & Future Work
**Roadmap Forward**

• Manual payment verification → Payment gateway integration
• Email-only notifications → Push notifications and in-app alerts
• Basic analytics → Advanced reporting with exports
• Single ground per owner → Multi-facility management
• No messaging → Direct user-to-owner messaging system

---

## Slide 16: Learning Outcomes
**Skills Developed**

• Laravel architecture, database design, security implementation
• Role-based authorization, payment workflows, file handling
• Email notifications, queue systems, API design
• Responsive frontend development with interactivity
• Testing, debugging, problem-solving, project management

---

## Slide 17: Demo Scenario
**Complete User Journey (25 minutes)**

• Guest browses grounds with filters and sorting
• User creates booking with payment proof and gets confirmation
• Owner submits application for ground listing approval
• Admin reviews and approves owner, ground becomes active
• Owner manages ground, updates pricing, schedules maintenance
• User submits review after booking completion
