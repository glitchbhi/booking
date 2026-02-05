# Rating & Capacity Updates - February 5, 2026

## Summary of Changes

### 1. Fixed Capacity Information Display

**Problem**: Capacity info was showing incorrect data (raw team_size numbers like "5", "7", "11") instead of formatted capacity strings like "5-a-side", "11-a-side", etc.

**Solution**: 
- Updated `OwnerRequestService.php` to properly format capacity based on team size and sport category
- Added `formatCapacity()` helper method that converts:
  - Football/Soccer: "5" → "5-a-side", "11" → "11-a-side"
  - Basketball/Volleyball: "5" → "5v5", "3" → "3v3"
  - Cricket: "6" → "6-players", "11" → "11-players"
  - Default: "{number} players"

**Files Modified**:
- `app/Services/OwnerRequestService.php`
  - Changed `'capacity' => $request->team_size` to `'capacity' => $this->formatCapacity($request->team_size, $request->category)`
  - Added `formatCapacity()` method

### 2. Rating System Overhaul

**Previous System**:
- Only users who completed a booking could rate
- One review per booking
- Could not delete or update reviews

**New System**:
- **All authenticated users** can rate any ground
- **One rating per user per ground** (enforced by database constraint)
- Users can **delete their review and re-rate** at any time
- No booking requirement - promotes more feedback

**Database Changes**:
- Created migration: `2026_02_05_125823_update_reviews_table_for_user_based_rating.php`
- Removed unique constraint on `booking_id`
- Made `booking_id` nullable (no longer required)
- Added unique constraint on `['user_id', 'ground_id']` - ensures one review per user per ground
- Removed foreign key constraint on `booking_id` to allow flexibility

**Controller Changes**:
- `ReviewController.php` completely rewritten:
  - `store()` method now accepts `Ground` instead of `Booking`
  - Checks for existing review from the same user for that ground
  - Added `destroy()` method for deleting reviews
  - Returns user-friendly error messages

**Route Changes**:
- Updated from `POST /reviews/{booking}` to `POST /reviews/{ground}`
- Added `DELETE /reviews/{review}` for deletion
- Removed `GET /reviews/create/{booking}` (no longer needed)

**View Changes**:
- `resources/views/grounds/show.blade.php`:
  - Removed check for completed bookings
  - Shows rating form to all authenticated users who haven't rated yet
  - Shows existing review with delete button if user has already rated
  - Shows login prompt for guest users
  - Delete button includes confirmation dialog
  - Displays user's existing rating with timestamp

## Benefits

### For Users
✅ **Easy Access**: Any user can rate without needing a completed booking
✅ **Flexibility**: Can change their mind and re-rate by deleting old review
✅ **Transparency**: See their own rating with option to manage it
✅ **Simple**: One-click delete and re-rate process

### For Ground Owners
✅ **More Feedback**: More reviews = better credibility and visibility
✅ **Authentic Reviews**: Still limited to one per user prevents spam
✅ **Fair System**: Users who want to update ratings can do so

### For Platform
✅ **Better Data**: More reviews improve ground recommendations
✅ **User Engagement**: Encourages participation without barriers
✅ **Trust Building**: Authentic one-per-user policy prevents manipulation

## Technical Details

### Database Schema
```sql
-- reviews table
user_id: FK to users (who reviewed)
ground_id: FK to grounds (which ground)
booking_id: nullable FK to bookings (optional reference)
rating: 1-5 integer
comment: text (optional)
UNIQUE KEY (user_id, ground_id) -- One review per user per ground
```

### Key Methods

**OwnerRequestService::formatCapacity()**
```php
private function formatCapacity($teamSize, $category): ?string
{
    if (!$teamSize) return null;
    
    if (in_array($category, ['Football', 'Soccer'])) {
        return "{$teamSize}-a-side";
    }
    
    if (in_array($category, ['Basketball', 'Volleyball'])) {
        return "{$teamSize}v{$teamSize}";
    }
    
    // ... more formats
}
```

**ReviewController::store()**
- Validates rating (1-5) and optional comment
- Checks for existing review from user
- Creates new review linked to ground and user

**ReviewController::destroy()**
- Verifies user owns the review
- Soft deletes the review
- Triggers Ground::updateRating() via model event

## Testing Checklist

- [x] Migration runs successfully
- [x] All authenticated users can see rating form
- [x] Guest users see login prompt
- [x] Users can submit ratings without bookings
- [x] Duplicate ratings are prevented (unique constraint)
- [x] Users can delete their own reviews only
- [x] After deletion, users can submit new ratings
- [x] Ground average rating updates correctly
- [x] Capacity displays correctly for auto-created grounds
- [x] No errors in application

## Future Enhancements

1. **Review Moderation**: Admin ability to hide/remove inappropriate reviews
2. **Review Reporting**: Allow users to flag inappropriate reviews
3. **Helpful Votes**: Let users vote on helpful reviews
4. **Photo Reviews**: Allow users to upload images with reviews
5. **Owner Responses**: Let ground owners respond to reviews
6. **Review Analytics**: Show rating trends over time in admin dashboard

## Migration Instructions

If deploying to production:

```bash
# Backup database first
php artisan db:backup

# Run migration
php artisan migrate

# Verify changes
php artisan tinker
>>> \DB::select("SHOW CREATE TABLE reviews");
```

## Rollback Instructions

If issues occur:

```bash
# Rollback the migration
php artisan migrate:rollback --step=1

# This will:
# - Remove unique constraint on (user_id, ground_id)
# - Restore booking_id as required with foreign key
# - Restore unique constraint on booking_id
```
