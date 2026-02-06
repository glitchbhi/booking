# 🔧 Render Database Troubleshooting Guide

## Current Issue
Getting "Connection refused" error to 127.0.0.1:5432 instead of connecting to Render PostgreSQL database.

## Step-by-Step Fix

### ✅ Step 1: Verify PostgreSQL Database Exists

1. Go to **Render Dashboard**: https://dashboard.render.com
2. Look in your resources list for:
   - ☑️ `thunder-booking` (Web Service)
   - ☑️ `thunder-booking-db` (PostgreSQL) ← **MUST EXIST**

**If `thunder-booking-db` does NOT exist:**
1. Click **"New +"** → **"PostgreSQL"**
2. Fill in:
   ```
   Name: thunder-booking-db
   Database: thunder_booking
   User: thunder_booking_user
   Region: [Same as your web service]
   PostgreSQL Version: 16
   Plan: Free
   ```
3. Click **"Create Database"**
4. **Wait 3-5 minutes** until status shows **green checkmark**

---

### ✅ Step 2: Link Database to Web Service

#### Option A: Using Render Dashboard (Easier)

1. Go to your **PostgreSQL database** (`thunder-booking-db`)
2. Click **"Connect"** button
3. Copy the **"Internal Database URL"**
   - Should look like: `postgresql://user:pass@dpg-xxxxx-a.oregon-postgres.render.com/thunder_booking`

4. Go to your **web service** (`thunder-booking`)
5. Click **"Environment"** tab
6. Look for `DATABASE_URL`:
   
   **If it exists but is empty/wrong:**
   - Click **"Edit"**
   - Paste the Internal Database URL
   - Click **"Save"**
   
   **If it doesn't exist:**
   - Click **"Add Environment Variable"**
   - Key: `DATABASE_URL`
   - Value: Paste the Internal Database URL
   - Click **"Add"**

7. Verify these variables exist:
   ```
   DB_CONNECTION = pgsql
   DATABASE_URL = postgresql://...
   ```

8. Click **"Save Changes"** at the bottom

#### Option B: Using render.yaml (Automatic)

Your `render.yaml` already links the database. Just make sure:
1. The database name in yaml matches: `thunder-booking-db`
2. After creating the database, **redeploy** the web service

---

### ✅ Step 3: Clear Build Cache and Redeploy

1. Go to your **web service** (`thunder-booking`)
2. Click **"Manual Deploy"** tab
3. Click **"Clear build cache & deploy"**
4. **Wait 8-10 minutes** for full deployment

---

### ✅ Step 4: Check Deployment Logs

While deploying, click **"Logs"** tab and look for:

#### ✅ **SUCCESS Signs:**
```
=== DATABASE CONFIGURATION DEBUG ===
DB_CONNECTION: pgsql
DATABASE_URL: SET (postgresql://user:...)
====================================
Testing database connection...
Platform: PostgreSQL 16.x
Connection: pgsql
Running migrations...
Migrated: 2024_xx_xx_create_users_table
Migrated: 2024_xx_xx_create_grounds_table
```

#### ❌ **FAILURE Signs:**
```
DB_CONNECTION: not set
DATABASE_URL: NOT SET
SQLSTATE[08006] connection refused
```

**If you see failure:**
- Database is not linked properly
- Go back to Step 2

---

### ✅ Step 5: Manual Migration (If Needed)

If migrations fail during deployment:

1. Go to your **web service** → **"Shell"** tab
2. Run these commands one by one:

```bash
# Check database connection
php artisan db:show

# Run migrations
php artisan migrate --force

# Seed initial data (optional)
php artisan db:seed --force

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Common Issues & Solutions

### Issue 1: "DATABASE_URL not set"

**Cause:** Environment variable not configured

**Fix:**
1. Go to web service → Environment
2. Add `DATABASE_URL` with value from database's "Connect" page
3. Save and redeploy

### Issue 2: "Connection refused to 127.0.0.1"

**Cause:** App using default config instead of DATABASE_URL

**Fix:**
1. Verify `DB_CONNECTION=pgsql` is set in environment
2. Verify `DATABASE_URL` is set
3. Clear build cache and redeploy
4. **DO NOT** cache config in production

### Issue 3: "Database does not exist"

**Cause:** Database wasn't created on Render

**Fix:**
1. Create PostgreSQL database (Step 1)
2. Wait for it to be fully ready (green status)
3. Link to web service (Step 2)
4. Redeploy

### Issue 4: "Could not find driver"

**Cause:** PostgreSQL PHP extension not installed

**Fix:**
- Already fixed in Dockerfile (has `pdo_pgsql`)
- Redeploy with clear cache

### Issue 5: Environment variables not updating

**Cause:** Config cached or not saved properly

**Fix:**
```bash
# In Render Shell
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

Then restart the service.

---

## Manual Verification Checklist

Use this checklist to verify everything:

### Database Side
- [ ] PostgreSQL database `thunder-booking-db` exists
- [ ] Database status shows **green** (running)
- [ ] Database name: `thunder_booking`
- [ ] Can see "Internal Database URL" in Connect tab

### Web Service Side
- [ ] Environment variable `DB_CONNECTION` = `pgsql`
- [ ] Environment variable `DATABASE_URL` is set and starts with `postgresql://`
- [ ] No conflicting variables like `DB_HOST=127.0.0.1`
- [ ] Saved environment changes
- [ ] Redeployed after saving

### Deployment
- [ ] Deployment completed successfully (green)
- [ ] Logs show "DATABASE_URL: SET"
- [ ] Logs show "Running migrations..." with success
- [ ] No error about "connection refused"

### Application
- [ ] Can visit https://thunderbooking.onrender.com
- [ ] No database errors on homepage
- [ ] Can login
- [ ] Can add grounds
- [ ] Grounds persist after logout

---

## Alternative: Set Individual DB Variables

If `DATABASE_URL` is not working, set these individually:

1. Go to your **PostgreSQL database** → **"Info"** tab
2. Copy each value and add to web service environment:

```
DB_CONNECTION = pgsql
DB_HOST = [Hostname from database info]
DB_PORT = 5432
DB_DATABASE = thunder_booking
DB_USERNAME = [Username from database info]
DB_PASSWORD = [Password from database info]
DB_SSLMODE = require
```

Then remove or keep `DATABASE_URL` (it will be ignored if individual vars are set).

---

## Still Not Working?

### Get Live Support

1. **Check Render Status**: https://status.render.com
2. **Render Community**: https://community.render.com
3. **Check Logs**: Share the deployment logs for specific errors

### Provide These Details

If asking for help, provide:
- [ ] Error message from logs
- [ ] Screenshot of environment variables (hide passwords)
- [ ] Database status (green/red)
- [ ] Output of `php artisan db:show` from Shell

---

## Success Indicators

You'll know it's working when:
- ✅ Homepage loads without errors
- ✅ Can login and logout
- ✅ Can add/edit grounds
- ✅ Data persists after container restarts
- ✅ Logs show successful migrations
- ✅ No "127.0.0.1" in error messages

🎉 Once working, your data will be safe and persistent!
