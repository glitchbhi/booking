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

Sports facility booking in Bhutan currently relies on informal methods—phone calls, social media messages, and in-person visits. This creates inefficiencies: customers cannot check real-time availability, double-bookings occur frequently, payment tracking is manual, and there's no transparent review system.

Thunder Booking is a centralized web-based platform that digitizes the entire sports ground booking process. The system enables verified ground owners to list facilities with detailed information, pricing, and availability schedules. Customers can browse grounds by location and sport type, check real-time availability, make instant bookings, and complete secure payments through an automated wallet system.

Key features include booking lock mechanism (prevents double-bookings), email notifications, comprehensive review system, and admin oversight for owner verification and platform integrity. This project creates a transparent marketplace connecting facility owners with customers while promoting active lifestyles in Bhutan.

---

## 3. Current Situation & Problem Definition

Current Challenges:

For Customers:
- Must contact multiple owners individually to check availability
- No centralized comparison platform
- No booking receipts or history tracking
- Inconsistent payment methods
- No facility reviews or quality assurance

For Ground Owners:
- Manual booking management through calls/WhatsApp
- High double-booking risk during peak times
- Manual payment tracking
- No platform for wider market exposure
- Limited reputation-building opportunities

For Sports Community:
- Underutilization of existing facilities
- Lack of pricing transparency
- Booking conflicts causing disputes
- Limited facility information access

Core Problems:
1. No centralized booking platform with real-time availability
2. Manual payment verification and tracking
3. No owner/facility verification system
4. Poor booking management and communication
5. Limited market access for small ground owners

Solution: A modern, automated sports ground booking system addressing all these challenges.

---

## 4. Project Aim

To develop a secure, web-based Sports Ground Booking Management System that automates facility listing, real-time availability checking, instant booking confirmation, secure payment processing, wallet management, and comprehensive reporting for all stakeholders.

---

## 5. Project Objectives

1. Develop centralized online booking marketplace for Bhutan sports facilities
2. Provide real-time availability with calendar-based time slot management
3. Enable secure authentication with role-based access (Customer, Owner, Admin)
4. Implement owner verification system for legitimate operators
5. Support multiple payment methods including automated wallet system
6. Implement booking lock mechanism preventing double-bookings
7. Provide comprehensive dashboards and analytics
8. Enable review and rating system for quality assurance
9. Support multiple sports types (Football, Basketball, Badminton, Cricket, Futsal, etc.)
10. Implement automated email notifications for all booking events
11. Create booking transition system auto-updating status based on time
12. Provide detailed analytics and reports for all stakeholders
13. Support mobile-responsive design for all devices

---

## 6. System Capabilities

### 6.1 Customer Capabilities

Guest Users (No Login):
- Browse all sports grounds
- View ground details (location, facilities, images, pricing, ratings)
- Filter by sport type, location, availability
- Search grounds by name or area

Registered Customers (After Login):
- Register/Login with email or Google OAuth
- Check real-time availability calendar
- Select date and time slots (hourly booking)
- Make instant bookings with 5-minute booking lock
- Manage wallet (add money, pay for bookings, view transactions)
- Track booking status (Pending → Confirmed → Completed/Cancelled)
- Cancel bookings with automatic refunds
- View booking history and download receipts
- Write reviews and rate grounds (1-5 stars)
- Receive email notifications for all booking events

### 6.2 Ground Owner Capabilities

Registration & Verification:
- Register with business details and documents (CID, business license)
- Submit facility photos and bank account details
- Track application status

After Approval:
- Add/manage grounds with complete details (name, location, sport type, facilities, images)
- Set pricing (daytime/nighttime rates per hour)
- Configure availability schedules
- View all bookings received with real-time notifications
- Update booking status
- View earnings, commission deductions, and wallet balance
- Request payouts and download financial reports
- View booking analytics and ground performance metrics
- Respond to customer reviews

### 6.3 Administrator Capabilities

Owner & Ground Management:
- Review and verify owner registration requests with documents
- Approve/reject owner applications and ground listings
- Monitor platform activity and suspend problematic users

Sports Type Management:
- Add/edit sports categories
- Activate/deactivate categories

Platform Oversight:
- View all bookings, users, and transactions
- Handle booking disputes
- Monitor wallet transactions and process owner payouts
- Manage commission structure
- Generate comprehensive reports (users, bookings, revenue)
- Configure platform settings (commission rates, booking rules, email templates)
- View dashboard analytics with visual charts

---

## 7. System Automated Functions

The system automatically performs:

Booking Automation:
- Real-time availability checking before confirmation
- Booking lock for 5 minutes during checkout
- Auto-release expired locks
- Booking status transitions based on date/time (Pending → Confirmed → Completed)
- Automatic cancellation of unpaid bookings

Payment & Wallet Automation:
- Automatic wallet transactions (debit, credit, refund)
- Commission calculation and deduction (10-15%)
- Refund processing for cancellations

Notification Automation:
- Email notifications for booking confirmations, cancellations, wallet transactions, owner approvals

System Management:
- Ground availability updates after bookings
- Expired suspension removal (hourly check)
- Lock release cleanup (every 5 minutes)
- Database archiving and daily backups
- Dashboard statistics updates in real-time
- Automated report generation (daily, weekly, monthly)

---

## 8. Functional Requirements Summary

Customer Module: Register/Login, Browse/Search grounds, Filter by multiple criteria, Make bookings with payment, Manage wallet, Track bookings, Cancel with refunds, Write reviews, View history

Owner Module: Register with verification, Add/manage grounds, Upload images, Set pricing and availability, View bookings, Update status, Track earnings and commissions, View analytics, Download reports

Admin Module: Verify owners and grounds, Manage users and sports types, Monitor all bookings and transactions, Handle disputes, Process payouts, Generate reports, Configure system settings

System Module: Automated availability updates, Booking lock management, Status transitions, Commission calculations, Email notifications, Wallet transactions, Backup automation, Audit logging

---

## 9. Non-Functional Requirements

Security: Encrypted passwords (bcrypt), CSRF protection, SQL injection prevention, XSS protection, role-based access control, session management

Performance: Page load < 3 seconds, Support 500+ concurrent users, Database queries < 100ms, Real-time availability updates

Reliability: 99.5% uptime, Automated daily backups, Transaction integrity (ACID compliance), Booking lock prevents double-booking

Usability: Clean intuitive interface, Mobile-responsive design, WCAG 2.1 Level A accessibility, New users can book within 5 minutes

Scalability: Support growing users and grounds, Handle 1000+ bookings/day, Modular architecture for easy feature additions

Maintainability: MVC architecture (Laravel), Comprehensive documentation, Git version control, Detailed logging

Compatibility: Chrome, Firefox, Safari, Edge (latest), iOS 12+, Android 8+, 320px to 4K displays

---

## 10. Database Design

Main Tables:

1. users: User information (id, name, email, phone, password, role, owner_status, google_id, is_suspended, suspension_reason, suspended_until)

2. sports_types: Sport categories (id, name, description, icon, is_active)

3. grounds: Facility listings (id, owner_id, sport_type_id, name, description, location, phone, facilities, images, capacity, ground_size, rate_per_hour_day, rate_per_hour_night, day_start_time, day_end_time, is_active, total_bookings)

4. ground_availabilities: Time slot availability (id, ground_id, date, available_from, available_to, is_available)

5. bookings: Booking records (id, user_id, ground_id, booking_date, start_time, end_time, total_hours, rate_per_hour, total_amount, status, payment_status, notes, cancellation_reason, cancelled_at)

6. booking_locks: Prevents double-booking (id, ground_id, user_id, booking_date, start_time, end_time, locked_at, expires_at)

7. wallet_transactions: Financial records (id, user_id, booking_id, type, amount, balance_before, balance_after, description, status)

8. reviews: Customer reviews (id, user_id, ground_id, booking_id, rating, comment, is_verified)

9. system_ratings: Platform ratings (id, user_id, rating, feedback)

10. owner_requests: Owner applications (id, user_id, business_name, business_license_number, cid_number, cid_document, business_license, bank_name, bank_account_number, bank_account_name, ground_name, ground_location, sport_type, facilities, status, rejection_reason, reviewed_at)

Relationships:
- One-to-Many: users → grounds, bookings, wallet_transactions, reviews
- Foreign key constraints with CASCADE for data integrity
- Indexes on frequently queried fields for performance

---

## 11. Use Case Diagram

Actors: Customer, Ground Owner, Administrator, System (Automated)

Customer Use Cases:
- Guest: Browse/Search grounds, Filter by sport type, View details and reviews
- Registered: Register/Login, Make bookings, Pay with wallet, Track status, Cancel bookings, Write reviews, Manage profile

Ground Owner Use Cases:
- Register as owner, Submit documents, Add/manage grounds, Set pricing, View bookings, Update status, Track earnings, Request payouts, View analytics, Respond to reviews

Administrator Use Cases:
- Verify owners and documents, Approve/reject grounds, Manage sports types, Monitor bookings/transactions, Handle disputes, Process payouts, Generate reports, Configure system settings

System (Automated) Use Cases:
- Check availability, Create/release booking locks, Update booking status, Process wallet transactions, Calculate commission, Send email notifications, Remove expired suspensions, Archive data, Generate reports, Database backup

---

## 12. Feasibility Study

Technical Feasibility: ✅ Highly Feasible
- Technology Stack: HTML, CSS, JavaScript, PHP/Laravel, MySQL, Apache/Nginx
- All open-source, well-documented, widely supported
- Team has required skills or can acquire them
- No complex or unproven technologies

Economic Feasibility: ✅ Highly Viable
- Development Cost: 7,000 BTN (Domain: 2,000/year, Hosting: 5,000/year)
- Revenue Model: 10-15% commission per booking
- Example: 100 bookings/month @ 500 BTN avg = 5,000 BTN/month revenue
- Break-even: 2 months
- First Year ROI: 757% (60,000 revenue - 7,000 cost = 53,000 profit)

Operational Feasibility: ✅ Highly Acceptable
- Familiar online booking interface
- Simple 3-step booking process
- Mobile-friendly design
- Minimal training required
- High smartphone penetration in Bhutan
- Growing digital payment adoption

Schedule Feasibility: ✅ Achievable
- Total Duration: 12 weeks
- Requirements Analysis: 2 weeks
- System Design: 2 weeks
- Development: 6 weeks
- Testing: 1.5 weeks
- Deployment: 0.5 weeks

Legal Feasibility: ✅ Compliant
- No special license required
- Complies with Bhutan ICT policies
- Clear Terms of Service and Privacy Policy
- Proper data protection measures

Social Feasibility: ✅ Positive Impact
- Promotes healthy lifestyle (GNH health pillar)
- Supports local businesses
- Creates employment opportunities
- Culturally appropriate and beneficial

---

## 13. Risk Analysis

| Risk | Probability | Impact | Mitigation |
|------|------------|--------|------------|
| Data Breach | Medium | High | SSL encryption, password hashing, security audits, firewall |
| Double Booking | High | High | Booking lock mechanism, database locks, real-time checks |
| Payment Fraud | Low | High | Payment verification, transaction logging, dispute resolution |
| System Downtime | Medium | Medium | Daily backups, monitoring, disaster recovery plan |
| Fake Owners | Medium | High | Document verification (CID, license), admin approval |
| Poor Adoption | Medium | Medium | User-friendly design, tutorials, customer support, incentives |
| Technical Errors | High | Low | Thorough testing, code reviews, error logging |
| Scalability Issues | Medium | Medium | Efficient database design, caching, load testing |

---

## 14. Testing Strategy

Unit Testing: Test individual functions (authentication, booking logic, wallet calculations, availability checking) using PHPUnit. Target: 70%+ code coverage

Integration Testing: Test module interactions (login → booking → payment, booking → wallet → refund, lock expiry → availability)

System Testing: Validate complete functionality (all user workflows), performance (response time < 3 seconds), cross-browser compatibility, mobile responsiveness

User Acceptance Testing (UAT): Test with 5 customers, 2 owners, 1 admin. Validate real-world scenarios. Acceptance: 90%+ satisfaction

Security Testing: SQL injection attempts, XSS attacks, CSRF validation, password encryption, session security using OWASP ZAP

Performance Testing: Load test with 500 concurrent users, stress test beyond normal load, measure response times

Success Criteria:
- All critical test cases pass
- 90%+ of all test cases pass
- No critical/high-severity bugs
- 85%+ user satisfaction
- Performance benchmarks met

---

## 15. Business Benefits

For Customers:
- 24/7 convenient booking access
- Real-time availability and transparent pricing
- Verified facilities with authentic reviews
- Instant confirmations and booking history
- Secure payments and easy refunds

For Ground Owners:
- Wider market reach beyond local area
- Automated booking and payment management
- Professional online presence
- Reputation building through reviews
- Data-driven business insights
- Increased revenue through better utilization

For Community:
- Promotes active lifestyle and sports participation
- Better infrastructure utilization
- Supports local sports businesses
- Economic growth and employment
- Community building through sports

For Administrator:
- Centralized control and monitoring
- Commission-based sustainable revenue
- Quality assurance through verification
- Data-driven decision making
- Scalable business model

---

## 16. Expected Outcomes

Technical Outcomes:
- Fully functional responsive web platform
- Secure database with 10+ tables
- Real-time booking system with lock mechanism
- Automated wallet and notification system
- Admin dashboard with analytics

Business Outcomes (First 6 Months):
- 20+ verified ground owners
- 50+ ground listings
- 200+ registered customers
- 500+ completed bookings
- 10,000+ BTN revenue

Business Outcomes (First Year):
- 50+ ground owners
- 100+ grounds listed
- 1,000+ active customers
- 5,000+ bookings
- 100,000+ BTN revenue
- Break-even achieved
- Market leadership in Bhutan

Performance Metrics:
- 99%+ system uptime
- < 3 second page load
- 500+ concurrent users support
- 1000+ bookings/day capacity

User Satisfaction:
- 85%+ customer satisfaction
- 4+ star average rating
- 70%+ repeat booking rate
- 90%+ email open rate

---

## 17. Future Enhancements

Phase 2 (6-12 months):
- Mobile applications (iOS/Android)
- Integrated payment gateway (mBoB, mPay)
- Advanced booking features (recurring bookings, group bookings)
- In-app messaging system
- AI-based recommendations

Phase 3 (12-24 months):
- Social features (find players, create events, tournaments)
- Equipment rental marketplace
- Loyalty and referral program
- Multi-language support (Dzongkha, Nepali)
- Virtual ground tours (360° photos)
- Coaching services booking
- Weather integration
- Corporate booking portal

Technology Upgrades:
- Progressive Web App (PWA)
- Blockchain for transparent transactions
- IoT integration (smart locks)
- Augmented Reality ground visualization

---

## 18. Ethical, Legal & Security Considerations

Ethical:
- Collect only necessary data with user consent
- Transparent data usage policy
- No selling user data
- Equal opportunity for all owners
- Fair review system
- Honest communication

Legal:
- Register as e-commerce business
- Comply with Bhutan ICT Policy
- Clear Terms of Service and Privacy Policy
- User agreements for owners and customers
- Booking and cancellation policies
- Intellectual property protection

Security:
- SSL/TLS encryption (HTTPS)
- Password hashing (bcrypt)
- Role-based access control
- CSRF and XSS protection
- SQL injection prevention
- Session security with timeout
- File upload restrictions
- Regular security audits
- Daily automated backups
- Secure payment handling

Data Protection:
- Comply with Bhutan Data Protection Act
- User consent for data collection
- Right to access and delete data
- Anonymized data for analytics
- Secure data storage

---

## 19. Project Timeline (12 Weeks)

Week 1-2: Planning & Analysis
- Week 1: Requirement gathering, stakeholder meetings, documentation
- Week 2: Database design, system architecture, UI/UX mockups

Week 3-8: Development
- Week 3: Environment setup, authentication system (email & Google OAuth)
- Week 4: Customer module (browse, search, filter, booking UI)
- Week 5: Booking system (lock mechanism, wallet, payment)
- Week 6: Owner module (registration, ground management, dashboard)
- Week 7: Admin module (verification, approval, analytics)
- Week 8: Advanced features (reviews, notifications, automation)

Week 9-10: Testing
- Week 9: Unit testing, integration testing, bug fixes
- Week 10: System testing, security testing, UAT

Week 11-12: Deployment & Launch
- Week 11: Documentation, server setup, data preparation
- Week 12: Deployment, launch, post-deployment monitoring

Milestones:
- Week 1: Requirements Complete
- Week 2: Design Complete
- Week 5: Booking System Functional
- Week 8: All Features Implemented
- Week 10: Testing Complete
- Week 12: Live System Operational

---

## 20. Conclusion

Thunder Booking addresses a critical gap in Bhutan's sports infrastructure management by providing a modern, efficient digital platform for booking sports facilities. The current manual system creates inefficiencies, lost opportunities, and frustrations for all stakeholders.

Key Project Strengths:
- Low development cost (7,000 BTN) with high ROI (757% first year)
- Addresses real, persistent problem in sports facility booking
- Benefits all stakeholders (customers, owners, community, admin)
- Technically feasible with proven technologies
- Economically viable with sustainable revenue model
- Legally compliant and socially beneficial
- Supports Bhutan's GNH philosophy (health pillar)
- Scalable for nationwide expansion

Expected Impact:
The system will modernize sports facility booking through automation, verification, and secure payments. It will reduce booking errors, improve trust, expand market reach for local owners, and promote active lifestyles through structured digital platform. Within the first year, the platform aims to serve 1,000+ customers, 50+ ground owners, and process 5,000+ bookings.

Technical Innovation:
Real-time availability checking, booking lock mechanism, automated wallet transactions, intelligent notifications, and comprehensive analytics create a seamless user experience while maintaining system integrity.

Social Value:
By making sports facilities more accessible, Thunder Booking promotes community health, supports local businesses, and contributes to Bhutan's digital economy while preserving cultural sports traditions.

Recommendation:
Based on comprehensive feasibility analysis, clear stakeholder benefits, alignment with national goals, and realistic implementation plan, this project is strongly recommended for approval and development. It has high probability of success and will create lasting positive impact on Bhutan's sports culture and digital economy.

---

## Appendices

Glossary:
- API: Application Programming Interface
- CSRF: Cross-Site Request Forgery  
- GNH: Gross National Happiness
- MVC: Model-View-Controller
- OAuth: Open Authorization
- ROI: Return on Investment
- SSL/TLS: Secure Sockets Layer / Transport Layer Security
- UAT: User Acceptance Testing

Contact Information:
- Student: Krishna Prasad Sharma
- Student ID: DIT2024/4051
- Program: Diploma in Information Technology
- Institute: Royal Institute Of Management
- Supervisor: Ugyen Tshering

System Demo:
- Development URL: http://localhost:8000
- System Email: thunderbooking975@gmail.com
- System Name: Thunder Booking

---

Project Approval

Student Signature: ___________________ Date: ___________

Supervisor Signature: ___________________ Date: ___________

Institute Seal:

---

END OF PROJECT PROPOSAL

Thunder Booking - Sports Ground Booking Management System
Total Pages: 25
Project Duration: 12 Weeks
Academic Year: 2024-2026
Student: Krishna Prasad Sharma (DIT2024/4051)
Royal Institute Of Management
