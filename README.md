# Consultancy Website - PHP + MySQL Dynamic Version

## XAMPP setup
1. Extract this folder into `C:\xampp\htdocs\`.
2. Start Apache and MySQL.
3. Open `http://localhost/consultancy_website/install.php` and click **Install / Seed Database**.
4. If the admin login says invalid credentials, open `http://localhost/consultancy_website/admin/reset_admin.php` once, then use:
   - Username: `admin`
   - Password: `admin123`
5. Login at `http://localhost/consultancy_website/admin/login.php`.
6. Delete or rename `install.php` and `admin/reset_admin.php` after setup.

## Database
Default XAMPP settings are used:
- Host: `127.0.0.1`
- Database: `consultancy_db`
- User: `root`
- Password: empty