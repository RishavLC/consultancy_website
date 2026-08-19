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

If your MySQL credentials differ, edit `config/database.php`.

## Using the Admin Panel (for non-technical staff)

Go to `http://localhost/consultancy_website/admin/` (or your live domain + `/admin/`) and log in.

- **Dashboard** — quick overview and links to every section.
- **Services / Projects / Gallery / Team Members / Testimonials** — each has a form to add new items and a list below it to edit or delete existing ones. Every field has a plain-English label and a short grey hint underneath explaining what it does and where it shows up on the website.
- **Photos** — click "Choose File" and upload a JPG/PNG/WEBP/GIF directly; there's no need to know a filename or use FTP. Until a real photo is uploaded, a placeholder image is shown automatically.
- **Show on website** checkbox — uncheck this to hide an item without deleting it (useful for drafts or seasonal content).
- **Display Order** — a number; lower numbers show first. Doesn't need to be sequential (0, 5, 10 works fine and leaves room to insert things later).
- **Enquiries** — every contact form submission lands here, with buttons to mark it Read / Replied / Closed.
- **Settings** — company name, phone, email, and address shown across the site.

Changes save immediately and go live on the website right away — no publish step.

## What was fixed in this pass
Every fix below was verified against a real PHP 8.3 + MySQL 8 environment (fresh install → every public page → full admin workflow → clean up), not just read in the code.

1. **`services.php` and `index.php` crashed with a fatal error** — the database query for services used different column names than the page templates expected. Fixed.
2. **Uploaded photos never appeared on the website** — Projects/Gallery/Team photo fields were plain text boxes requiring a typed filename, and even then the public pages never used that field. Added real "Choose File" upload buttons and wired every public page to actually display the uploaded photo (falling back to a placeholder until one is uploaded).
3. **Admin sessions could log you out unexpectedly** — session handling now uses its own folder inside the project (`data/sessions/`) and its own cookie name, instead of depending on the server's `php.ini` defaults, which vary between hosting setups and are the most common cause of "the admin panel keeps logging me out."
4. **Saving with a duplicate title crashed the whole page with a raw technical error** — now shows a friendly message and, for Services, automatically makes the web address unique (e.g. `structural-design-2`) instead of failing.
5. **Added CSRF protection** to every save/delete action in the admin panel, so a malicious link or embedded image on another site can't trigger a change on your behalf while you're logged in.
6. **Simplified every admin form** — removed technical fields (like typing a URL "slug" by hand), replaced free-text category/icon fields with dropdowns (so a typo can't silently break the filter buttons on the live site), and added a plain-English description under every field.
