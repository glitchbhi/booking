# ChatGPT Prompt: Generate Figma Wireframes for Sports Ground Booking System

## Instructions for ChatGPT

Please generate a complete wireframe design system for a Sports Ground Booking Management System in Figma. Follow these specifications:

---

## SYSTEM OVERVIEW

**Project Name:** Sports Ground Booking Management System

**Core Functionality:**
- User registration and authentication
- Ground listings with filtering (by sports type and location)
- Booking system with date/time picker
- Reservation management and cancellations
- Owner dashboard for ground management
- Review and rating system
- Email notifications system
- Availability calendar view
- Admin dashboard with statistics
- Payment gateway integration points
- Booking history and status tracking

---

## WIREFRAME REQUIREMENTS

### 1. USER AUTHENTICATION SCREENS
- Sign Up Page (email, password, user type selection: customer/owner)
- Login Page (email, password, remember me)
- Password Reset/Recovery Page
- Email Verification Screen
- User Profile Setup/Onboarding

### 2. CUSTOMER FLOWS
#### Browse & Search Screens
- Home/Landing Page (search bar, featured grounds, filters)
- Grounds List with Filters
  - Filter by: Sports Type, Location, Price Range, Rating, Availability
  - View options: List View, Map View, Grid View
  - Ground Cards (image, name, price, rating, availability)
- Ground Detail Page
  - Photo gallery carousel
  - Detailed information (facilities, amenities, rules)
  - Pricing breakdown
  - Availability calendar
  - Reviews and ratings section
  - Owner/contact information
  - Call-to-action button: "Book Now"

#### Booking Screens
- Booking Form Page
  - Date picker (calendar)
  - Time slot selector
  - Duration selection
  - Pricing summary (per hour/day fees, taxes)
  - Special requests/notes section
  - Terms & conditions agreement
- Payment Gateway Screen
  - Payment method selection (card, digital wallet, etc.)
  - Payment details form
  - Order review before confirmation
  - Payment processing screen
- Booking Confirmation Screen
  - Confirmation number
  - Booking summary (ground, date, time, price)
  - Confirmation sent message
  - Download receipt option
  - Next steps/actions

#### Customer Dashboard
- Bookings Overview
  - Active bookings (upcoming)
  - Past bookings (history)
  - Cancelled bookings
  - Booking status indicators (Pending, Confirmed, Cancelled, Completed)
- Booking Details Page
  - Full booking information
  - Cancel/Modify options
  - Contact ground owner button
  - Leave Review button
- Review Submission Page
  - Star rating selector
  - Review text editor
  - Photo upload for review
  - Submit button
- Booking History Page
  - Sortable/filterable list
  - Timeline view option
  - Export/print option

### 3. OWNER FLOWS
#### Ground Management
- Owner Dashboard
  - Statistics cards (total bookings, revenue, rating, active grounds)
  - Ground list (owner's grounds)
  - Quick stats/analytics
- Add/Edit Ground Page
  - Ground information (name, description, location, address)
  - Sports type(s) selection
  - Facilities/amenities checklist
  - Photo upload gallery
  - Pricing per time slot
  - Rules and policies editor
  - Save/Publish button
- Ground Availability Management
  - Calendar grid view (monthly)
  - Block unavailable dates
  - Set availability hours
  - Bulk operations

#### Booking Management (Owner)
- Owner Bookings Dashboard
  - Pending approvals
  - Confirmed bookings
  - Upcoming bookings (calendar view)
  - Completed bookings
- Booking Details (Owner View)
  - Customer information
  - Booking details
  - Accept/Reject/Cancel buttons
  - Send message to customer
  - Generate invoice
- Owner Analytics Page
  - Revenue charts (daily, weekly, monthly)
  - Booking trends
  - Popular time slots
  - Customer statistics

### 4. ADMIN FLOWS
#### Admin Dashboard
- Overview Statistics
  - Total users, total grounds, total bookings
  - Revenue metrics
  - System health indicators
- User Management
  - User list with filters
  - User details/edit
  - Account suspension/deactivation
  - User verification status
- Ground Approval System
  - Pending ground submissions
  - Ground review page
  - Approve/Reject buttons
  - Rejection reason editor
- Booking Management (Admin)
  - All bookings list
  - Dispute resolution section
  - Booking status management
- Reports & Analytics
  - System usage reports
  - Revenue reports
  - User engagement metrics
  - Ground performance rankings
- Settings Panel
  - System configuration
  - Notification preferences
  - Commission settings
  - Payment settings

### 5. MODALS & COMPONENTS
- Confirmation Dialog (generic)
- Cancel Booking Modal
  - Reason selector
  - Refund information
  - Confirmation
- Contact Ground Owner Modal
  - Message composer
  - Subject line
  - Send button
- Date Picker Component
  - Calendar with availability indicators
  - Time slot grid
- Rating/Review Modal
- Filter Panel
- Loading States
- Error Messages
- Success Notifications
- Toast Messages

### 6. MOBILE-SPECIFIC SCREENS
- Mobile Navigation Menu (hamburger)
- Mobile-optimized search/filter screen
- Mobile booking form (step-by-step wizard)
- Mobile payment flow
- Mobile dashboard (card-based layout)

---

## FIGMA INTEGRATION INSTRUCTIONS

### Prerequisites:
1. Have an active Figma account
2. Create a new Figma file called "Sports Ground Booking System - Wireframes"
3. Set up a Figma team (optional but recommended)

### Creating Wireframes:
1. **Set Up Design System:**
   - Create a colors library (primary, secondary, success, error, neutral)
   - Define typography scale (headings, body text, captions)
   - Create spacing scale (8px grid system)
   - Build reusable component library

2. **Organize by Pages:**
   - Page 1: Design System & Components
   - Page 2: Authentication Flows
   - Page 3: Customer Screens
   - Page 4: Booking Flow
   - Page 5: Customer Dashboard
   - Page 6: Owner Screens
   - Page 7: Admin Dashboard
   - Page 8: Mobile Responsive Views
   - Page 9: Modals & Components
   - Page 10: Prototyping Flows

3. **Create Frames:**
   - Use frame sizes: Desktop (1366x768), Mobile (375x667), Tablet (768x1024)
   - Use consistent naming convention: [Page]/[Section]/[Component]
   - Add descriptive titles to all frames

4. **Prototyping:**
   - Connect screens with interactive flows
   - Create user journey prototypes
   - Add interaction labels and transitions

### API/Automation Connection:
1. **Figma File API:**
   - Export wireframes as JSON/SVG for documentation
   - Use Figma's REST API to programmatically create frames
   - Automate asset exports

2. **Connecting to Development:**
   - Export components as images for documentation
   - Use "Dev Mode" to share specifications with developers
   - Generate handoff documents automatically

3. **Collaboration:**
   - Share Figma link with team members
   - Enable commenting for feedback
   - Version control through Figma's file history

### Recommended Figma Plugins for This Project:
- **Wireframe Kit** - Premade wireframe components
- **Autoflow** - Auto-generate user flow diagrams
- **Design Tokens** - Maintain consistent design system
- **Figma Tokens** - Sync design tokens with code
- **Unsplash** - Add placeholder images
- **Table Generator** - Create data tables for admin screens

---

## DESIGN GUIDELINES

### Color Palette:
- **Primary:** #007AFF (Blue) - Call-to-action, primary buttons
- **Secondary:** #5AC8FA (Light Blue) - Secondary actions
- **Success:** #34C759 (Green) - Positive actions, confirmations
- **Error:** #FF3B30 (Red) - Errors, destructive actions
- **Warning:** #FF9500 (Orange) - Warnings, attention needed
- **Neutral:** #F2F2F7 to #1C1C1E (Light to Dark backgrounds)

### Typography:
- **Headings:** Inter or SF Pro Display (Bold, 28-32px)
- **Subheadings:** Inter (Semibold, 18-20px)
- **Body:** Inter (Regular, 14-16px)
- **Captions:** Inter (Light, 12px)

### Spacing:
- 8px base unit grid
- Margins: 16px (2 units), 24px (3 units), 32px (4 units)
- Padding: 12px, 16px, 20px

### Components to Create Reusable:
- Buttons (Primary, Secondary, Tertiary, Disabled states)
- Input Fields (Text, Email, Phone, Password)
- Cards (Ground card, Booking card, Review card)
- Navigation (Top bar, Bottom tab bar, Sidebar)
- Forms (Booking form, Contact form, Review form)

---

## DELIVERABLES

1. **Wireframes:** All screens as specified above
2. **Prototyping:** Interactive flows showing user journeys
3. **Component Library:** Reusable components in Figma
4. **Design Specs:** Documented sizing, spacing, typography
5. **Responsive Variants:** Mobile and tablet adaptations
6. **Documentation:** Figma file link shared with team

---

## OUTPUT FORMAT

After creating the wireframes in Figma:

1. **Generate a Figma Sharing Link** from File > Share > Copy Link
2. **Create Design Handoff Document** with:
   - Component specifications
   - Color codes and typography
   - Responsive breakpoints
   - Interaction documentation
3. **Export Key Screens** as PNG/SVG for documentation
4. **Create User Flow Diagram** showing navigation paths

---

## NOTES FOR CHATGPT

- Prioritize usability and user experience in wireframe design
- Ensure all screens are mobile-responsive
- Follow mobile-first design approach
- Include clear call-to-action buttons on every screen
- Add helpful tooltips/hints for complex interactions
- Maintain visual consistency across all wireframes
- Consider accessibility (clear contrast, readable fonts)
- Include placeholder text that is realistic and helpful
- Show loading states and error states for all forms
- Add feedback/confirmation messages for all user actions

---

## HOW TO USE THIS PROMPT

1. Copy the entire content above
2. Go to ChatGPT (https://chat.gpt.com)
3. Paste this prompt into a new conversation
4. ChatGPT will provide:
   - Detailed wireframe descriptions
   - Figma setup instructions
   - Frame-by-frame breakdown
   - Design system guidelines
   - Step-by-step instructions for creating in Figma
5. Follow ChatGPT's instructions to create wireframes in your Figma workspace
6. Use the generated wireframes for development handoff or client presentations

