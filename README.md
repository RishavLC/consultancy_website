# Consultancy Website — PHP + MySQL

## 1. Local setup (XAMPP)
1. Extract this folder into `C:\xampp\htdocs\`.
2. Start Apache and MySQL in XAMPP.
3. Open `http://localhost/consultancy_website/install.php` and click **Install / Seed Database**.
4. The installer shows a **random admin password once** — copy it. The installer then deletes itself and locks.
5. Log in at `/admin/login.php` (username `admin`) and immediately use **Change Password**.

If the installer ever fails, the exact error is in the PHP error log (XAMPP: `xampp/php/logs/php_error_log`). To see errors on screen **on your own PC only**, set `APP_DEBUG` to `true` in `config/local.php`.

## 2. Going live — checklist
1. In phpMyAdmin / cPanel create a database **and a dedicated user** (not `root`) with access to only that database.
2. Copy `config/local.php.example` to `config/local.php` and fill in the real database name, user and password. Never upload your real passwords inside `database.php`.
3. Upload everything **except** the `.git` folder.
4. Run `install.php` once, copy the password it shows, log in, change the password.
5. Make sure `data/` and `assets/images/uploads/` are writable by the web server.
6. Install an SSL certificate, then open `.htaccess` and remove the `#` in front of the HTTPS-redirect block.
7. In **Admin → Website Settings** enter the real phone, email (new enquiries are emailed here), address and social links.
8. Replace the placeholder content (see below) and test: contact form, a photo upload, every page on a phone.
9. Set up daily database backups plus a copy of `assets/images/uploads/`.
10. Submit `https://YOUR-DOMAIN/sitemap.php` in Google Search Console.

Notes: this needs **Apache** (it relies on `.htaccess`). On Nginx you must add equivalent rules that deny access to `/data`, `/config`, `/includes`, and dot-files. Enquiry emails use PHP `mail()`; if your host blocks it, set `MAIL_FROM` in `config/local.php` to an address on your domain, or ask the host to enable mail. Enquiries are always saved in **Admin → Enquiries** even if email fails.

## 3. Admin panel
Dashboard → each section has an add/edit form and a list. Sections: Services, Projects, Gallery, Team, Testimonials, **Key Numbers, How We Work steps, Company Timeline, Company Values, Office Hours** (these five were previously only editable through SQL), Enquiries, Website Settings, Change Password.
- **Show on website** unticked = hidden, not deleted. **Display Order**: lower numbers first.
- Photos: upload JPG/PNG/WEBP/GIF up to 5 MB. Until uploaded, a placeholder photo appears.

## 4. Still to do (content — can't be automated)
- Replace the `picsum.photos` placeholder images (hero mosaic, page banners, About office photo) with real photos. Search the PHP files for `picsum`.
- Replace the demo services/projects/team/testimonials/stats with real ones (check claims such as "since 2008").
- Resize photos before uploading (about 1600 px wide is plenty) to keep pages fast.

## 5. What changed in this security/quality pass
**Security:** installer locks itself, generates a random password and never resets an existing admin; `reset_admin.php` removed; DB credentials moved to git-ignored `config/local.php`; login rate-limiting (5 failures / 15 min); idle session timeout; logout now POST + clears cookie; all delete/status actions are POST with CSRF (tokens no longer accepted from URLs); change-password page + warning while the default password is in use; raw database errors no longer shown; upload folder blocked from running PHP; upload path-traversal hole closed; `.git`, `.sql`, `.md`, key and log files blocked; security headers; deny rules work on Apache 2.2 and 2.4.
**Contact form:** fixed bug where disabling the button could cause the submission to be silently ignored; honeypot + signed time token + 5-per-hour-per-IP limit; server checks the service value; redirect after submit (no duplicate on refresh); email notification; friendly error messages.
**Functionality:** Nepal timezone (office-hours "today" now correct); service anchor links no longer change when renamed; optional testimonial field is truly optional; friendly duplicate-entry error; settings page with clear labels, validation, social links and map text; footer social icons only show when a link is set; missing project IDs return a real 404.
**SEO/accessibility:** canonical + Open Graph tags, favicon, JSON-LD business data, per-project titles/descriptions, `sitemap.php`, `robots.txt` (generated), skip-link, `<main>`, reduced-motion support, lightbox focus handling.
