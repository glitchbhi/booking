Response to Project Proposal Feedback

To: Project Supervisor  
From: Krishna Prasad Sharma (DIT2024/4051)  
Date: March 9, 2026  
Re: Thunder Booking Project Proposal Revision

Acknowledgment

Thank you for the detailed feedback on my project proposal. I sincerely appreciate the time you took to review and provide constructive criticism. Your points about scope management, technical feasibility, and academic appropriateness are well-taken.

I acknowledge that my initial proposal was overly ambitious for a 3-month diploma project. I understand the concerns about wallet systems, payment processing, booking locks, and business projections. You are correct that I should focus on core booking functionality rather than trying to build a complete commercial platform.

Defense of Laravel Framework Choice

However, I respectfully request permission to continue using Laravel framework instead of procedural PHP for the following academic and practical reasons:

1. Laravel Is Designed for Learning

Laravel is specifically built with beginners in mind:
- Clear, readable syntax - Much easier to understand than complex procedural code
- Built-in classes handle common tasks (authentication, database, validation)
- Eloquent ORM makes database operations simple: User::find($id) instead of writing complex SQL queries
- Blade templating is easier than mixing PHP with HTML

Example comparison:

Procedural PHP:
$conn = mysqli_connect($host, $user, $pass, $db);
$sql = "SELECT * FROM bookings WHERE user_id = ? AND status = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "is", $user_id, $status);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$bookings = [];
while($row = mysqli_fetch_assoc($result)) {
    $bookings[] = $row;
}
mysqli_stmt_close($stmt);

Laravel:
$bookings = Booking::where('user_id', $user_id)
                   ->where('status', $status)
                   ->get();

2. Industry-Standard Skill

- Laravel is the most popular PHP framework globally
- Learning Laravel provides marketable skills for employment
- Most modern PHP jobs require framework knowledge
- This project will strengthen my portfolio for job applications

3. Built-in Security

Laravel automatically handles:
- SQL injection prevention (prepared statements built-in)
- CSRF token protection
- Password hashing (bcrypt)
- XSS protection

With procedural PHP, I would need to manually implement all security measures, increasing the risk of vulnerabilities.

4. Project Structure & Maintenance

Laravel provides:
- MVC architecture - Clean separation of logic, data, and presentation
- Organized folder structure - Easy for supervisors to navigate and evaluate
- Database migrations - Version control for database schema
- Validation classes - Clean, reusable validation rules

5. Development Speed

Laravel's built-in features mean I can focus on business logic rather than reinventing the wheel:
- Authentication system ready to use (login, registration, password reset)
- Form validation with simple rules
- Database relationships handled automatically
- Routing system is straightforward

6. Already In Progress

I have already started the project and completed:
- Set up the Laravel project structure
- Configured the development environment  
- Created initial database migrations
- Established routing architecture
- Created models and controllers
- Implemented basic authentication system

Starting over with procedural PHP would delay the project by 2-3 weeks and waste the work already completed.

Revised Scope - Addressing Your Concerns

Based on your feedback, I will simplify the project scope as follows:

REMOVED (Too Complex for Student Project):
- Wallet system with balance management
- Online payment processing
- Commission calculation and owner payouts
- Google OAuth login
- 5-minute booking lock mechanism
- AI recommendations, blockchain, IoT features
- Advanced analytics dashboard
- Owner verification with document uploads
- Dispute handling system

RETAINED (Core Booking System):
- User registration and login (email/password only)
- Browse and search sports grounds
- View ground details (name, location, sport type, pricing, facilities, images)
- Check available time slots for a specific date
- Make bookings (customer selects date, time, ground)
- Simple booking status (Pending to Confirmed by owner to Completed/Cancelled)
- Cancel bookings
- View booking history
- Owner can add/manage grounds
- Owner can view received bookings
- Owner can confirm or reject bookings
- Admin can approve ground owners
- Admin can manage grounds and users
- Basic review system (customers can rate grounds after booking)
- Email notifications for booking confirmation/cancellation

Simplified Payment Approach:

Option selected: Payment done offline at the ground

- Customer makes booking online (no payment required)
- Booking status: "Pending" until owner confirms
- Customer pays cash directly at the sports ground
- Owner marks booking as "Completed" after payment received
- System tracks payment status: Unpaid / Paid

This removes all financial transaction complexity while maintaining booking functionality.

Simplified Booking Logic:

Instead of 5-minute locks with timers:
- When customer submits booking, system checks database
- If time slot already booked, show error message
- If time slot available, create booking with "Pending" status
- Owner confirms, status changes to "Confirmed"
- On booking date, status changes to "Completed"

This is realistic and implementable.

Revised Timeline (12 Weeks)

Week 1-2: Database design, migrations, seeders (ALREADY COMPLETED)  
Week 3-4: User authentication and ground management (CRUD) (IN PROGRESS)  
Week 5-6: Booking system (availability check, create booking, view bookings)  
Week 7-8: Owner dashboard, booking confirmation/rejection  
Week 9-10: Admin panel (manage users, grounds, bookings)  
Week 11: Review system and email notifications  
Week 12: Testing, documentation, deployment  

Revised Business Projections

I will remove unrealistic claims like "ROI 757%" and "break even in 2 months."

Instead:
- This is a proof-of-concept system for academic evaluation
- Focus is on demonstrating technical skills, not commercial viability
- If successful, the system could potentially be expanded in the future

Additional Deliverables

To address your concern about UI clarity, I will include:

1. Wireframes for all major pages:
   - Home page (browse grounds)
   - Ground details page
   - Booking page with date/time selection
   - Customer dashboard
   - Owner dashboard
   - Admin panel

2. Database schema diagram showing all tables and relationships

3. System flowchart showing booking process step-by-step

Conclusion

I believe the revised, simplified scope addresses all your concerns while maintaining academic rigor and practical value. The core booking functionality remains intact, but financial complexity, advanced features, and over-engineering have been removed.

I respectfully request permission to continue using Laravel framework because:
1. It is easier to learn and use than procedural PHP
2. It provides built-in security and structure
3. It is industry-relevant and will strengthen my portfolio
4. The project is already in progress with Laravel

I am confident this revised approach will result in a completed, functional, well-documented system within the 12-week timeline.

I am prepared to discuss this further and make additional adjustments as needed.

Thank you for your guidance.


