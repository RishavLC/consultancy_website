# Structura — Civil Engineering Consultancy Website

Core PHP + HTML + Bootstrap 5 website with a MySQL-backed dynamic
"Our Work", "Gallery" and home banner, plus a simple admin panel.

## Pages
- `index.php` — Home (dynamic banner slider + static sections)
- `about.php` — About Us (static)
- `services.php` — Our Services (static)
- `our-work.php` / `work-detail.php` — Project logs (dynamic, from DB)
- `gallery.php` — Gallery (dynamic, from DB)
- `contact.php` — Contact Us (static form, saves messages to DB)
- `admin/` — Simple admin panel to manage banners, work logs, gallery, and view messages

## Setup (XAMPP / WAMP / any Apache+MySQL+PHP stack)

1. Copy the whole `civil-consultancy` folder into your server's web root
   (e.g. `htdocs/civil-consultancy`).
2. Create the database: open phpMyAdmin (or `mysql` CLI) and import
   `database.sql`. This creates the `civil_consultancy` database, all
   tables, one admin user, and sample banner rows.
3. Open `includes/db.php` and set your MySQL host / username / password
   if they differ from the XAMPP defaults (`localhost` / `root` / empty).
4. Visit `http://localhost/civil-consultancy/` for the site.
5. Visit `http://localhost/civil-consultancy/admin/login.php` for the
   admin panel.
   - Username: `admin`
   - Password: `Admin@123`
   - Change this password after first login (update the `admin_users`
     table with a new `password_hash()` value).

## Managing the dynamic content

- **Home Banner** — Admin → Home Banners. Add/edit/delete slides
  (image, title, subtitle, button). The home page banner is a
  Bootstrap Carousel built from the `banners` table — add or change
  slides here and they update instantly on the homepage.
- **Our Work** — Admin → Our Work. Add project logs with category,
  client, location, dates, status, cover image and description.
- **Gallery** — Admin → Gallery. Upload images, optionally link each
  one to a project.
- **Messages** — Admin → Messages. View/mark-read/delete messages
  submitted through the Contact Us form.

## Notes
- Uploaded images are stored under `assets/uploads/banners`,
  `assets/uploads/work`, and `assets/uploads/gallery`.
- All database queries use prepared statements (`mysqli_prepare`) to
  prevent SQL injection.
- Color palette intentionally avoids blue: charcoal (#1c1c1c) + safety
  amber (#d97b1c), matching a construction/engineering feel.
- No JS framework — only Bootstrap 5's own JS (carousel, modal) plus
  one small script for contact form validation.
