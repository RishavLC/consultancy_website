# Strata & Beam Engineering — Website (Core PHP)

A full civil/structural engineering company website built in plain PHP (no framework).

## Pages
- `index.php` — Home, with the mosaic image banner, services preview, workflow and featured projects
- `about.php` — Company story, timeline, values, team
- `services.php` — Full service list + shared process workflow
- `our-work.php` — Project portfolio grid with filters, and a dynamic detail view at `our-work.php?id=1` (through `6`)
- `gallery.php` — Filterable photo gallery with a lightbox
- `contact.php` — Contact form with server-side PHP validation
- `404.php` — Custom not-found page

## Structure
```
includes/data.php       All site content: services, projects, team, gallery, workflow steps — edit this to update the site
includes/functions.php  Small helpers (active nav state, inline icon renderer)
includes/header.php     Shared <head> + nav, included by every page
includes/footer.php     Shared footer, included by every page
assets/css/style.css    All styling (brown / clay-terracotta / sand palette)
assets/js/script.js     Mobile nav, scroll reveal, filters, lightbox, back-to-top
data/inquiries.json     Contact form submissions are appended here (auto-created, protected by .htaccess)
```

## Running it locally
You need PHP installed (`php -v` to check). From the project folder, run:

```bash
php -S localhost:8000
```

Then open `http://localhost:8000/index.php` in your browser. All the nav links use relative `.php` paths, so this also works dropped into any Apache/Nginx + PHP host (XAMPP, WAMP, shared hosting, etc.) — including at `http://localhost/index.php` as in your original brief.

## Images
Every image on the site currently points to `picsum.photos` as a live placeholder image service, so the whole site renders correctly out of the box. Swap these for your real project photos:

1. Save your photos into `assets/images/`
2. In `includes/data.php`, `index.php`, `about.php`, `services.php`, `our-work.php`, `gallery.php` and `contact.php`, replace the `https://picsum.photos/seed/...` URLs with `assets/images/your-file.jpg`

## Editing content
Almost everything on the site — services, the 6-step workflow, projects, gallery photos, team bios, testimonials, office hours — lives in `includes/data.php` as plain PHP arrays. Add, remove or edit an array entry and every page that uses it (cards, footer links, filters) updates automatically.

## Contact form
`contact.php` validates name, email, phone format and message length server-side (PHP, not just JavaScript) and writes each valid submission to `data/inquiries.json`. To actually send email notifications, uncomment and configure the `mail()` line inside `contact.php` (or swap in PHPMailer/SMTP) — most local dev environments don't have a mail server configured, so this is left as a clearly marked line to wire up on your host.
