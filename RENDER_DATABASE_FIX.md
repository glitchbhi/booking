# 🔴 FIX: Data Loss Issue on Render

## Problem
Your grounds and other data disappear after logout/restart because you're using **SQLite** which is **EPHEMERAL** on Render containers.

## Root Cause
- Render containers restart frequently (deployments, scaling, maintenance)
- SQLite stores data in a file (`database.sqlite`)
- When containers restart, the filesystem is reset
- **ALL DATA IS LOST**

## ✅ Solution: Use PostgreSQL

### Step 1: Create PostgreSQL Database on Render

1. **Go to Render Dashboard**: https://dashboard.render.com
2. **Click "New +"** → Select **"PostgreSQL"**
3. **Configure Database:**
   - **Name**: `thunder-booking-db`
   - **Database**: `thunder_booking`
   - **User**: `thunder_booking_user`
   - **Region**: Same region as your web service
   - **Plan**: Free (or paid for better performance)
4. **Click "Create Database"**
5. **IMPORTANT**: Copy the **Internal Database URL** (looks like: `postgresql://user:password@host/database`)

### Step 2: Update Environment Variables in Your Web Service

1. **Go to your web service** (`thunder-booking`)
2. **Click "Environment"** tab
3. **Add/Update these variables:**

   ```
   DB_CONNECTION=pgsql
   DATABASE_URL=<paste the internal database URL from step 1>
   ```

   **Note**: If you used `render.yaml` to deploy, the `DATABASE_URL` should already be linked automatically.

### Step 3: Deploy the Fixed Code

**Commit and push the changes:**

```bash
# Check what changed
git status

# Add all changes
git add config/database.php render.yaml .env.example

# Commit
git commit -m "Fix: Switch from SQLite to PostgreSQL to prevent data loss"

# Push to trigger redeploy
git push origin main
```

### Step 4: Verify Database Connection

After deployment completes:

1. **Go to Render Dashboard** → Your web service → **"Logs"**
2. Look for successful migration messages:
   ```
   Running migrations...
   Migration table created successfully.
   Migrating: 2024_xx_xx_xxxxxx_create_users_table
   Migrated: 2024_xx_xx_xxxxxx_create_users_table
   ```

3. **If migrations fail**, manually run them:
   - Go to **"Shell"** tab in Render dashboard
   - Run: `php artisan migrate:fresh --force`
   - Then: `php artisan db:seed` (if you have seeders)

### Step 5: Test Your Application

1. **Login as admin**
2. **Add a new ground**
3. **Logout and login again** → Ground should still be there!
4. **Wait 10-15 minutes** (to allow potential container restart)
5. **Check again** → Data should persist

## Why This Happens on Render (Free Tier)

| Feature | Render Free Tier | Paid Tier |
|---------|-----------------|-----------|
| Container Restarts | Frequent (15 min inactivity) | Less frequent |
| Filesystem | Ephemeral (resets) | Ephemeral (resets) |
| Database Required | External (PostgreSQL) | External (PostgreSQL) |

**Even with paid tier, NEVER use SQLite for production!**

## Database Comparison

### ❌ SQLite (OLD - Don't Use)
- ✗ File-based storage
- ✗ Lost on container restart
- ✗ No concurrent writes
- ✗ Not suitable for production
- ✓ Good for local development only

### ✅ PostgreSQL (NEW - Use This)
- ✓ External persistent storage
- ✓ Survives container restarts
- ✓ Handles concurrent users
- ✓ Production-ready
- ✓ Free tier available on Render

## Troubleshooting

### Issue: "Could not find driver" error

**Solution**: The Dockerfile already includes PostgreSQL drivers. Redeploy.

### Issue: Migration errors

**Solution**: 
```bash
# In Render Shell
php artisan migrate:fresh --force
php artisan cache:clear
php artisan config:clear
```

### Issue: Still losing data

**Check:**
1. Verify `DB_CONNECTION=pgsql` in environment variables
2. Verify `DATABASE_URL` is set correctly
3. Check logs for database connection errors
4. Ensure PostgreSQL database is running (green status in Render)

## Alternative: Use Render Persistent Disk (NOT Recommended)

While Render offers persistent disks, they:
- Cost extra ($$$)
- Are slower than PostgreSQL
- Don't scale well
- Are overkill for just database storage

**PostgreSQL is the better solution!**

## Summary

✅ **What was changed:**
1. Default database connection changed from `sqlite` to `pgsql`
2. `render.yaml` configured to use PostgreSQL
3. Database URL parsing updated

✅ **What you need to do:**
1. Create PostgreSQL database on Render
2. Set `DB_CONNECTION=pgsql` environment variable
3. Push code to redeploy
4. Verify data persists

🎉 **Result:** Your data will now be safe and persist across restarts!
