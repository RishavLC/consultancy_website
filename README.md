# Strata & Beam Engineering — Dynamic PHP + MySQL Website

This version keeps the original Core PHP frontend but moves the website content and contact enquiries into **MySQL**.

## Requirements
- PHP 7.4+ (PHP 8.x recommended)
- MySQL 5.7+ / MariaDB 10.4+
- Apache/XAMPP/WAMP or another PHP host
- PHP PDO MySQL extension enabled

## Quick setup on XAMPP
1. Copy `consultancy_website` into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** in XAMPP.
3. Open `config/database.php` and change DB credentials if your MySQL password is not blank.
4. Visit `http://localhost/consultancy_website/install.php`.
5. Click **Install / Seed Database**.
6. Login at `http://localhost/consultancy_website/admin/login.php`.
7. Default login: **admin / admin123**.
8. Change the admin password in the database after first login, and delete/rename `install.php` after setup.

### Alternative phpMyAdmin setup
- Import `schema.sql` first.
- Import `seed.sql` second.
- Then open the website.

## What is now dynamic?
- Services
- Projects and project details
- Gallery
- Team members
- Testimonials
- Office/site settings
- Statistics
- Contact enquiries

The frontend still uses the same `$services`, `$projects`, `$gallery`, `$team`, etc. variables, but `includes/data.php` now reads them from MySQL.

## Admin panel
- Dashboard counts
- Add/edit/delete Services
- Add/edit/delete Projects
- Add/edit/delete Gallery items
- Add/edit/delete Team members
- Add/edit/delete Testimonials
- Edit company settings
- View and update enquiry status

## Database
Database name: `consultancy_db`

Main tables:
`site_settings`, `admins`, `services`, `workflow_steps`, `projects`, `gallery`, `team_members`, `milestones`, `company_values`, `testimonials`, `office_hours`, `site_stats`, `enquiries`.

## Images
The existing website continues to use the original `picsum.photos` placeholders. The database stores image filenames so you can later replace them with files under `assets/images/` and update the records from the admin/database.
