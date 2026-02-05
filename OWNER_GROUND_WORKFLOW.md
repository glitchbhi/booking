# Owner Ground Workflow - Complete Guide

## Overview
When a user applies to become a ground owner and is approved by the admin, their ground is automatically created. This eliminates the need for owners to manually re-enter ground information.

## Complete Workflow

### Step 1: User Applies to Become Owner
**Prerequisites**: User must have ground details ready (name, license, pricing, images, etc.)

1. User fills out multi-step owner request form with:
   - Ground information (name, license, category, team size)
   - Pricing details (day/night rates with time slots)
   - Ground images (up to 5 photos)
   - Business details (address, contact, operating hours, facilities)
   - Reason for becoming an owner

2. System validates and stores owner request with status = 'pending'

3. Notifications sent:
   - ✅ All admins notified of new owner request
   - ✅ User receives confirmation email

### Step 2: Admin Reviews and Approves
**Admin Actions**:
1. Navigate to **Admin Dashboard → Owner Requests**
2. Review applicant details, ground information, and images
3. Click **Approve** (or Reject with reason)

**What Happens on Approval**:
1. User role changes: `'user'` → `'owner'`
2. User owner_status: `'pending'` → `'approved'`
3. **Ground is automatically created** with:
   - Owner ID linked to the approved user
   - All ground details from the owner request
   - Ground status: `is_active = false` (inactive)
   - Images copied from owner request
   - Default availability schedule (based on operating hours)
4. Email sent to user confirming approval

### Step 3: Owner Logs In
**Owner Experience**:
1. Login to account (now has 'owner' role)
2. Redirected to **Owner Dashboard**
3. Navigate to **My Grounds**
4. See automatically created ground with:
   - ⚠️ **"Inactive"** badge (red)
   - All submitted details pre-filled
   - Notification explaining it's awaiting admin activation

**Owner Actions Available**:
- ✅ **View** ground details
- ✅ **Edit** ground information (update photos, pricing, description, etc.)
- ✅ **Manage Schedule** (set availability hours)
- ❌ Cannot activate ground (admin-only action)

### Step 4: Admin Activates Ground
**Admin Actions**:
1. Navigate to **Admin Dashboard → Ground Management**
2. Find the newly created ground (filter: Status = Inactive)
3. Click on ground to view details
4. Click **Toggle Status** to activate

**What Happens on Activation**:
1. Ground `is_active` → `true`
2. Ground becomes **visible on public browse page**
3. Users can now **search, view, and book** the ground

## Key Features

### Automatic Ground Creation
✅ No duplicate data entry required  
✅ All owner request data transferred to ground  
✅ Images automatically copied  
✅ Availability schedule auto-generated  
✅ Owner immediately sees their ground in dashboard  

### Two-Step Approval Security
✅ **Step 1**: Verify user is legitimate → Grant owner role  
✅ **Step 2**: Verify ground meets quality standards → Activate ground  

### Owner Can Edit Before Activation
✅ Fix any mistakes in original application  
✅ Upload better photos  
✅ Adjust pricing or descriptions  
✅ Update business hours  

### Clear Status Indicators
- 🔴 **Inactive Badge**: Ground not yet visible to public
- 🟢 **Active Badge**: Ground live and bookable
- 📩 **Notification Banner**: Explains inactive status to owner

## Database Schema

### owner_requests table
- Stores all ground details during application
- Fields: ground_name, license_number, category, team_size, pricing, images, business details
- Status: pending → approved/rejected

### grounds table
- Created automatically on owner request approval
- owner_id → FK to users.id (the approved owner)
- is_active → false initially, true after admin activation
- All other fields populated from owner_request

### users table
- role: 'user' → 'owner' (on approval)
- owner_status: 'pending' → 'approved' (on approval)

## Code Flow

### OwnerRequestService::approveRequest()
```php
1. Update owner_request: status = 'approved'
2. Update user: role = 'owner', owner_status = 'approved'
3. Find SportsType by category name
4. Create Ground with owner_id = user_id
5. Set is_active = false
6. Create 7 default availabilities (Mon-Sun)
7. Send OwnerRequestApproved notification
```

### Owner Dashboard - My Grounds
```php
Auth::user()->grounds() // Returns all grounds owned by logged-in owner
→ Includes both active and inactive grounds
→ Displays status badge for each
```

## Common Scenarios

### Scenario 1: Owner Approved, Checking Dashboard
**Question**: "I was approved as owner but I don't see my ground"

**Answer**: 
- Check if you're logged in with the correct account
- Navigate to: Dashboard → My Grounds (not "Add New Ground")
- Your ground should appear with "Inactive" badge
- If still missing, contact admin (possible ground creation error)

### Scenario 2: Ground Showing as Inactive
**Question**: "My ground shows as inactive, what do I do?"

**Answer**:
- This is normal! All grounds start as inactive
- The admin needs to review and activate your ground
- You can edit ground details while waiting
- Once activated, it will appear to all users
- No action needed from you except wait for admin approval

### Scenario 3: Owner Wants to Add Second Ground
**Question**: "How do I add another ground?"

**Answer**:
- Navigate to: My Grounds → Click "Add New Ground"
- Fill out the ground creation form
- New grounds also start as inactive
- Admin must activate each ground individually

### Scenario 4: Editing Automatically Created Ground
**Question**: "Can I change the photos/pricing after approval?"

**Answer**:
- Yes! Click "Edit" on your ground card
- Update any details (photos, pricing, description, etc.)
- Changes are saved immediately
- Ground remains inactive until admin activates

## Benefits of This Workflow

### For Owners
✅ **No Duplicate Work**: Don't re-enter ground details after approval  
✅ **Immediate Access**: See ground in dashboard right after approval  
✅ **Edit Freedom**: Update ground details while waiting for activation  
✅ **Clear Status**: Know exactly when ground will go live  

### For Admins
✅ **Quality Control**: Review owner credentials separately from ground quality  
✅ **Flexible Approval**: Approve owner but hold ground if details need fixing  
✅ **Easy Management**: All grounds in one place with clear active/inactive status  
✅ **Prevent Spam**: Two-step process reduces fake listings  

### For Users (Customers)
✅ **Quality Assurance**: Only admin-approved grounds are visible  
✅ **Accurate Listings**: Grounds match owner application details  
✅ **Reliable Bookings**: No inactive or unverified grounds shown  

## Troubleshooting

### Ground Not Created After Approval
**Possible Causes**:
1. Sport type (category) doesn't exist in database
2. Database error during ground creation
3. Missing required fields in owner request

**Solution**:
- Check Laravel logs: `storage/logs/laravel.log`
- Verify SportsType exists for the requested category
- Admin can manually create ground using "Add New Ground"

### Owner Can't See Their Ground
**Check**:
1. Is user logged in with correct account?
2. Does user have `role = 'owner'`?
3. Run in database: `SELECT * FROM grounds WHERE owner_id = [user_id]`
4. Check User model has `grounds()` relationship defined

### Ground Images Not Showing
**Check**:
1. Storage link created: `php artisan storage:link`
2. Images array stored correctly in database
3. Image paths valid: `storage/ground-images/...`
4. Public storage folder has correct permissions

## Technical Implementation

### Files Modified
1. `app/Services/OwnerRequestService.php` - Auto-create ground logic
2. `app/Notifications/OwnerRequestApproved.php` - Updated email notification
3. `resources/views/owner/grounds/index.blade.php` - Added inactive ground notice
4. `app/Http/Controllers/Owner/GroundManagementController.php` - Existing controller handles display

### Database Relationships
```
users (owner)
  └─ hasMany → grounds
       └─ belongsTo → sport_types
       └─ hasMany → availabilities
       └─ hasMany → bookings
```

### Key Methods
- `OwnerRequestService::approveRequest()` - Creates ground automatically
- `OwnerRequestService::generateGroundDescription()` - Creates description from request
- `OwnerRequestService::extractLocation()` - Parses location from address
- `GroundManagementController::index()` - Displays owner's grounds

## Future Enhancements

### Potential Improvements
- [ ] Bulk ground activation for admins
- [ ] Ground preview before activation
- [ ] Automated ground activation after owner verification
- [ ] Ground quality checklist for admins
- [ ] Owner dashboard notification when ground is activated
- [ ] Ground analytics (views, favorites) before activation
