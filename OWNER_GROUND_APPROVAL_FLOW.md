# Owner & Ground Approval Flow

## Overview
This document explains the two-step approval process for owners and their grounds in the Thunder Booking system.

## Process Flow

### Step 1: User Requests to Become Owner
1. **User Submission**: Regular users fill out the owner request form with:
   - Ground information (name, license, category, team size)
   - Pricing details (day/night rates with time slots)
   - Ground images (up to 5)
   - Business details (address, contact, operating hours, facilities)
   - Reason for becoming an owner

2. **Request Status**: Owner request is created with `status = 'pending'`
   - User's `owner_status` is set to `'pending'`
   - Email notification sent to all admins
   - Confirmation email sent to user

### Step 2: Admin Reviews Owner Request
Admin can:
- **View** all owner requests (pending and historical)
- **Approve** or **Reject** requests

#### When Admin APPROVES Owner Request:
1. Owner request `status` → `'approved'`
2. User's `role` → `'owner'`
3. User's `owner_status` → `'approved'`
4. **Ground is automatically created** from the owner request data:
   - Ground name, category, pricing, images, etc. are copied
   - **Important**: Ground is created with `is_active = false`
   - Ground requires separate admin approval before going live
5. Default availability schedule created (based on operating hours)
6. Email notification sent to user

#### When Admin REJECTS Owner Request:
1. Owner request `status` → `'rejected'`
2. User's `owner_status` → `'rejected'`
3. Email notification sent to user with rejection reason
4. No ground is created

### Step 3: Admin Approves Ground
After owner request approval, the ground exists but is **inactive** (`is_active = false`).

Admin must:
1. Navigate to **Admin → Ground Management**
2. Review the automatically created ground
3. **Toggle Status** to activate the ground (`is_active = true`)

#### Only Active Grounds are Displayed:
- Browse page shows only `where('is_active', true)`
- Ground listings filter by `active()` scope
- Users cannot book inactive grounds

## Key Database Fields

### Users Table
- `role`: 'user' | 'owner' | 'admin'
- `owner_status`: 'none' | 'pending' | 'approved' | 'rejected'

### Owner Requests Table
- `status`: 'pending' | 'approved' | 'rejected'
- Contains all ground info submitted by the user
- Used to auto-create ground upon approval

### Grounds Table
- `is_active`: boolean - **Critical field**
- `owner_id`: FK to users table (must be approved owner)
- Contains all operational ground data

## File Changes Made

### 1. OwnerRequestService.php
**Added automatic ground creation on approval:**
- When admin approves owner request, ground is auto-created
- Ground inherits all data from owner request
- Ground is set to `is_active = false` (requires admin activation)
- Default availabilities created based on operating hours
- Helper methods added:
  - `generateGroundDescription()` - Creates description from request data
  - `extractLocation()` - Parses location from full address

**Fixed team_size field:**
- Changed from `ground_length/ground_width` to `team_size`
- Now correctly stores number of players per team (5-11)

### 2. Owner Request Migration
- Replaced dimension fields with `team_size` (unsigned tiny integer)
- Supports 5-11 players per team (football rules)

### 3. OwnerRequestController.php
- Updated validation to use `team_size` instead of dimensions
- Validates integer between 5-11 for team-based sports

### 4. Create Multi-Step Form
- Changed from "Ground Dimensions" to "Team Size"
- Shows only for team sports (Football, Cricket, etc.)
- JavaScript validation ensures 5-11 range

## Admin Actions Required

### To Complete Owner Approval:
1. ✅ Review and approve owner request
   - System auto-creates ground (inactive)
2. ✅ Navigate to Ground Management
3. ✅ Review the auto-created ground details
4. ✅ **Click "Toggle Status" to activate** (`is_active = true`)
5. ✅ Ground now appears in public browse/search

### Ground Status Management:
- **Inactive** (`is_active = false`): Ground hidden from users
- **Active** (`is_active = true`): Ground visible and bookable
- Admin can toggle status anytime to suspend/reactivate grounds

## Benefits of Two-Step Approval

### Security & Quality Control:
- ✅ Admin verifies user is legitimate before granting owner role
- ✅ Admin reviews ground details before making it live
- ✅ Prevents spam/fake ground listings

### Flexibility:
- ✅ Owner can be approved without ground going live immediately
- ✅ Admin can prepare/edit ground details before activation
- ✅ Grounds can be temporarily deactivated without deleting

### Data Integrity:
- ✅ All ground data preserved from owner request
- ✅ No manual re-entry of information
- ✅ Automatic availability schedule creation

## Troubleshooting

### "Approved ground is not displaying"
**Cause**: Ground was created but `is_active = false`

**Solution**:
1. Login as admin
2. Go to Admin → Ground Management
3. Find the ground
4. Click "Toggle Status" to activate

### "Owner cannot create grounds"
**Cause**: Either:
- Owner request not approved yet (user still has `owner_status = 'pending'`)
- User's `role` is not set to 'owner'

**Solution**:
1. Check if owner request is approved
2. Verify user has `role = 'owner'` and `owner_status = 'approved'`

### "Ground created but missing data"
**Cause**: Owner request had incomplete information

**Solution**:
1. Admin can manually edit the ground
2. Go to Ground Management → Edit Ground
3. Fill in missing fields
4. Save and activate

## Future Enhancements

### Potential Improvements:
- Add approval workflow notifications with action links
- Allow owners to edit ground details after creation
- Implement ground approval queue with bulk actions
- Add ground verification checklist for admins
- Enable temporary suspension with reason tracking

## Related Files
- `app/Services/OwnerRequestService.php` - Core approval logic
- `app/Http/Controllers/Admin/OwnerRequestManagementController.php` - Admin actions
- `app/Http/Controllers/Admin/GroundManagementController.php` - Ground management
- `app/Models/Ground.php` - Ground model with `scopeActive()`
- `database/migrations/*_owner_requests_table.php` - Database schema
