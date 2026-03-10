# PHP 8.2+ Upgrade Instructions for XAMPP

## Current Issue
Your Laravel 12 application requires PHP 8.2+ but XAMPP is running PHP 8.1.25.

## Option 1: Install New XAMPP (Easiest)

1. **Download XAMPP 8.2+**
   - Go to: https://www.apachefriends.org/download.html
   - Download XAMPP with PHP 8.2.x or 8.3.x

2. **Install to new directory** (e.g., `C:\xampp82`)
   - This keeps your old XAMPP intact

3. **Copy project files**
   ```powershell
   Copy-Item -Recurse C:\xampp\htdocs\booking C:\xampp82\htdocs\
   ```

4. **Start new XAMPP**
   - Stop old XAMPP services
   - Start Apache and MySQL from new XAMPP Control Panel

5. **Update environment variables** (if needed)
   - Windows Search → "Environment Variables"
   - Edit PATH to point to new PHP: `C:\xampp82\php`

## Option 2: Manual PHP Upgrade (Advanced)

1. **Download PHP 8.2+ for Windows**
   - Visit: https://windows.php.net/download
   - Download "VS16 x64 Thread Safe" version
   - Extract ZIP file

2. **Backup current PHP**
   ```powershell
   Rename-Item C:\xampp\php C:\xampp\php_8.1_backup
   ```

3. **Install new PHP**
   ```powershell
   Move-Item <extracted-php-folder> C:\xampp\php
   ```

4. **Copy php.ini configuration**
   ```powershell
   Copy-Item C:\xampp\php_8.1_backup\php.ini C:\xampp\php\php.ini
   ```

5. **Update Apache configuration**
   - Edit: `C:\xampp\apache\conf\extra\httpd-xampp.conf`
   - Verify PHP module paths point to new PHP version

6. **Restart Apache** from XAMPP Control Panel

## Option 3: Use PHP Portable (Quick Test)

1. **Download PHP 8.2+ portable**
   ```powershell
   # Download and extract PHP 8.2 to a folder like C:\php82
   ```

2. **Test your application**
   ```powershell
   cd C:\xampp\htdocs\booking
   C:\php82\php.exe artisan serve
   ```

## After Upgrading PHP

1. **Verify PHP version**
   ```powershell
   php -v
   ```
   Should show PHP 8.2.x or higher

2. **Reinstall Composer dependencies**
   ```powershell
   cd C:\xampp\htdocs\booking
   composer install
   ```

3. **Generate application key**
   ```powershell
   php artisan key:generate
   ```

4. **Run migrations** (if needed)
   ```powershell
   php artisan migrate
   ```

5. **Start development server**
   ```powershell
   php artisan serve
   ```

6. **Access application**
   - URL: http://localhost:8000
   - Admin Email: thunderbooking975@gmail.com
   - Password: Thunder@booking123

## Troubleshooting

### If Apache won't start after upgrade:
- Check Apache error logs: `C:\xampp\apache\logs\error.log`
- Verify PHP module loads correctly in httpd-xampp.conf

### If php command not found:
- Close and reopen terminal/PowerShell
- Or use full path: `C:\xampp\php\php.exe`

### If extensions missing:
- Edit `C:\xampp\php\php.ini`
- Uncomment needed extensions (remove `;` at start of line):
  ```ini
  extension=curl
  extension=fileinfo
  extension=gd
  extension=mbstring
  extension=openssl
  extension=pdo_mysql
  ```

## Database Configuration

Your database is already set up:
- **Database:** dit04_krishna_prasad_client_db
- **Host:** 127.0.0.1
- **User:** root
- **Password:** (empty)
- **All tables created:** ✓

## Quick Start Commands

```powershell
# After upgrading PHP to 8.2+
cd C:\xampp\htdocs\booking
composer install
php artisan key:generate
php artisan serve
```

Then visit: http://localhost:8000
