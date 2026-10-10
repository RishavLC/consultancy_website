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
Log in at `/admin/login.php` → **Dashboard** (`admin/dashboard.php`).

| Page | What the admin can do |
|---|---|
| `manage-banner.php` | Upload, replace and delete the home banner image; optional banner title and subtitle; show/hide it. Replacing or deleting also deletes the old image file. |
| `manage-work.php` | Work logs: add, edit, delete; project photo; optional exact **completion date**. (Opens the shared editor `content.php?type=projects`.) |
| `manage-gallery.php` | **Upload many photos at once** (up to 20), choose a category and optional caption; see every photo with Edit / Delete. |
| `manage-contact.php` | Read all contact-form messages, filter by status, mark read/replied/closed, **delete spam**. |
| `settings.php` | Company details, welcome message, company introduction, mission, vision, social links, map text. |
| `content.php?type=…` | Services, team, testimonials, key numbers, work steps, timeline, core values, office hours. |
| `password.php` | Change password. |

The dashboard shows: total work logs, gallery images and contact messages, plus the current **banner status** (Live / Hidden / Not set).

- **Show on website** unticked = hidden, not deleted. **Display Order**: lower numbers first.
- **Every image field** (banner, work logs, gallery, team) shows a **live preview** as soon as you pick a file or paste a link, and tells you if the file is too big or the link doesn't work.
- **Two ways to add a picture:** *Upload from computer* (saved on your server), or *Use an image link* — paste the web address of a picture and it is shown straight from that website without downloading anything. Tick **"Also download a copy"** to save the linked picture on your own server so it keeps working even if the other website removes it. In **Gallery** you can upload many files and/or paste many links (one per line) in one go.
- Photos: JPG/PNG/WEBP/GIF. The maximum size shown beside each upload box is the real limit of your server (hosts often default to 2 MB; raise `upload_max_filesize` and `post_max_size` in php.ini if you need bigger photos). Pasted links have no size limit because nothing is uploaded.
- To get a good image link: open the picture on its website, right-click it → *Copy image address*. It should end in .jpg, .png, .webp or .gif. Links to web *pages* (not the picture itself) will not work.
- Until a picture is added, a placeholder photo appears.
- Upgrading an older install: copy the new files over. The first page load adds the new `banners` table and the `completed_date` column automatically (no SQL needed). Existing content is not touched.

## 3b. File names compared with the project brief (PDF)
| Brief | This project |
|---|---|
| `index.php`, `about.php`, `services.php`, `gallery.php`, `contact.php` | same |
| `work.php` | `work.php` (the old `our-work.php` address redirects to it) |
| `db.php` | `db.php` (loads `config/database.php`, where credentials stay out of the code) |
| `header.php`, `footer.php`, `style.css` | thin files in the root that point to `includes/header.php`, `includes/footer.php`, `assets/css/style.css` |
| `admin/login.php`, `logout.php` | same |
| `admin/dashboard.php` | same (`admin/index.php` redirects to it) |
| `admin/manage-banner.php`, `manage-gallery.php`, `manage-contact.php` | same |
| `admin/manage-work.php` | same (opens the shared work-log editor) |
| Tables `admin`, `work_logs`, `contact_messages` | `admins`, `projects`, `enquiries` (same purpose) |
| `uploads/banners`, `work`, `gallery` | one folder, `assets/images/uploads/` (random file names, PHP execution blocked) |

## 4. Still to do (content — can't be automated)
- Replace the `picsum.photos` placeholder images (hero mosaic, page banners, About office photo) with real photos. Search the PHP files for `picsum`.
- Replace the demo services/projects/team/testimonials/stats with real ones (check claims such as "since 2008").
- Resize photos before uploading (about 1600 px wide is plenty) to keep pages fast.

## 5. What changed (security/quality pass + brief features)
**Brief features added:** home banner manager (with title/subtitle) and banner status on the dashboard; multi-photo gallery upload; delete spam messages and filter by status; optional completion date on work logs; homepage welcome message, company introduction and contact preview; mission and vision on the About page; Project Planning, Estimation & Costing and Surveying services in the starter data; PDF file names.
**Security:** installer locks itself, generates a random password and never resets an existing admin; `reset_admin.php` removed; DB credentials moved to git-ignored `config/local.php`; login rate-limiting (5 failures / 15 min); idle session timeout; logout now POST + clears cookie; all delete/status actions are POST with CSRF (tokens no longer accepted from URLs); change-password page + warning while the default password is in use; raw database errors no longer shown; upload folder blocked from running PHP; upload path-traversal hole closed; `.git`, `.sql`, `.md`, key and log files blocked; security headers; deny rules work on Apache 2.2 and 2.4.
**Contact form:** fixed bug where disabling the button could cause the submission to be silently ignored; honeypot + signed time token + 5-per-hour-per-IP limit; server checks the service value; redirect after submit (no duplicate on refresh); email notification; friendly error messages.
**Functionality:** Nepal timezone (office-hours "today" now correct); service anchor links no longer change when renamed; optional testimonial field is truly optional; friendly duplicate-entry error; settings page with clear labels, validation, social links and map text; footer social icons only show when a link is set; missing project IDs return a real 404.
**SEO/accessibility:** canonical + Open Graph tags, favicon, JSON-LD business data, per-project titles/descriptions, `sitemap.php`, `robots.txt` (generated), skip-link, `<main>`, reduced-motion support, lightbox focus handling.
