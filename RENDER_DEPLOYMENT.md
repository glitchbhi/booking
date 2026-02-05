# Deploying Thunder Booking to Render

## Prerequisites
- GitHub account with your code pushed to a repository
- Render account (sign up at https://render.com)

## Step-by-Step Deployment

### 1. Prepare Your Repository
Ensure these files are in your repository:
- `render.yaml` (build configuration)
- `Dockerfile` (Docker configuration for PHP)
- `build.sh` (build script - optional with Docker)
- `composer.json` and `composer.lock`
- `package.json` and `package-lock.json`

### 2. Create a Render Account
1. Go to https://render.com
2. Sign up with GitHub
3. Authorize Render to access your repositories

### 3. Create a PostgreSQL Database (or MySQL)
1. Click "New +" → "PostgreSQL"
2. Name: `thunder-booking-db`
3. Database: `thunder_booking`
4. User: `thunder_booking_user`
5. Region: Choose closest to your users
6. Select Free tier or paid plan
7. Click "Create Database"
8. **Save the connection string** provided

### 4. Create a Web Service
1. Click "New +" → "Web Service"
2. Connect your GitHub repository
3. Select your `thunder-booking` repository
4. Configuration:
   - **Name**: thunder-booking
   - **Region**: Same as database
   - **Branch**: main (or your default branch)
   - **Runtime**: Docker (automatically detected from Dockerfile)
   - **Plan**: Free or paid

### 5. Set Environment Variables
In the Render dashboard, go to "Environment" tab and add:

**Required:**
- `APP_NAME` = Thunder Booking
- `APP_ENV` = production
- `APP_DEBUG` = false
- `APP_KEY` = base64:YOUR_KEY_HERE (Generate locally: `php artisan key:generate --show`, or leave empty - will auto-generate)
- `APP_TIMEZONE` = Asia/Thimphu
- `APP_URL` = https://your-app-name.onrender.com
- `DATABASE_URL` = (Copy from PostgreSQL connection string from Render dashboard)

**Session & Cache:**
- `SESSION_DRIVER` = file
- `CACHE_DRIVER` = file
- `QUEUE_CONNECTION` = sync
- `LOG_CHANNEL` = errorlog

**Mail (Gmail example - Optional):**
- `MAIL_MAILER` = smtp
- `MAIL_HOST` = smtp.gmail.com
- `MAIL_PORT` = 587
- `MAIL_USERNAME` = your-email@gmail.com
- `MAIL_PASSWORD` = your-app-password
- `MAIL_ENCRYPTION` = tls
- `MAIL_FROM_ADDRESS` = your-email@gmail.com
- `MAIL_FROM_NAME` = Thunder Booking

**Google OAuth (Optional):**
- `GOOGLE_CLIENT_ID` = your-google-client-id
- `GOOGLE_CLIENT_SECRET` = your-google-client-secret
- `GOOGLE_REDIRECT_URI` = https://your-app-name.onrender.com/auth/google/callback

### 6. Push Your Code
Commit and push all files:
```bash
git add Dockerfile render.yaml
git commit -m "Add Docker configuration for Render deployment"
git push
```

### 7. Deploy
1. Click "Create Web Service"
2. Render will automatically:
   - Install dependencies
   - Build assets
   - Run migrations
   - Start your application

### 8. Post-Deployment
After successful deployment:
1. Visit your app URL: `https://your-app-name.onrender.com`
2. Test all functionality
3. Set up monitoring in Render dashboard

## Important Notes

### File Storage
Render's filesystem is ephemeral. For production:
1. Use cloud storage (AWS S3, Cloudinary, etc.)
2. Update `config/filesystems.php`
3. Add S3 credentials to environment variables

### Database Backups
- Render provides automatic backups on paid plans
- Free tier: Manual backups recommended

### Custom Domain
1. Go to "Settings" → "Custom Domain"
2. Add your domain
3. Update DNS records as instructed
4. Update `APP_URL` environment variable

### Free Tier Limitations
- Spins down after 15 minutes of inactivity
- Takes ~30 seconds to wake up
- Upgrade to paid plan for always-on service

## Troubleshooting

### 500 Error - Most Common Issue
**Check these in order:**

1. **APP_KEY not set or invalid:**
   - Go to Render Dashboard → Environment
   - If APP_KEY is empty, set it to any value (e.g., "placeholder")
   - Redeploy - the Dockerfile will auto-generate a proper key
   - OR locally run `php artisan key:generate --show` and add the output

2. **Database connection error:**
   - Verify `DATABASE_URL` is set correctly
   - Format should be: `postgresql://user:password@host:port/database`
   - Ensure PostgreSQL database is created and running
   - Check logs for "SQLSTATE" errors

3. **View application logs:**
   - In Render Dashboard, go to "Logs" tab
   - Look for PHP errors or Laravel exceptions
   - Check for missing environment variables

4. **Clear all caches:**
   - In Render Dashboard, go to "Shell" tab
   - Run: `php artisan config:clear`
   - Run: `php artisan cache:clear`
   - Run: `php artisan view:clear`

### Build Fails
- Check build logs in Render dashboard
- Verify `composer.json` and `package.json` are correct
- Ensure `build.sh` has execute permissions

### Database Connection Error
- Verify `DATABASE_URL` is set correctly
- Check database is in same region
- Ensure migrations ran successfully

### Assets Not Loading
- Check `npm run build` completed successfully
- Verify `public/build` directory exists
- Check `APP_URL` is set correctly

### 500 Error
- Check application logs in Render dashboard
- Verify `APP_KEY` is set
- Check `storage` and `bootstrap/cache` permissions

## Updating Your App
Push to GitHub:
```bash
git add .
git commit -m "Your changes"
git push
```
Render will automatically redeploy.

## Support
- Render Docs: https://render.com/docs
- Laravel Deployment: https://laravel.com/docs/deployment
