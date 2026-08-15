<?php
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contact Us — Strata & Beam Engineering';
$pageMeta  = 'Get in touch with Strata & Beam Engineering for a structural, geotechnical or site supervision quote.';

/**
 * ---- Server-side contact form handling (Core PHP, no framework) ----
 * Processed BEFORE any HTML is output, so this could redirect after a
 * successful POST if desired. Validates required fields, then appends
 * the enquiry to a local JSON log so it survives even without a
 * configured mail server. Swap the file-log block for mail()/PHPMailer
 * in production.
 */
$errors = [];
$success = false;
$formData = ['name' => '', 'email' => '', 'phone' => '', 'service' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $formData['name']    = trim($_POST['name'] ?? '');
    $formData['email']   = trim($_POST['email'] ?? '');
    $formData['phone']   = trim($_POST['phone'] ?? '');
    $formData['service'] = trim($_POST['service'] ?? '');
    $formData['message'] = trim($_POST['message'] ?? '');

    if ($formData['name'] === '' || mb_strlen($formData['name']) < 2) $errors['name'] = 'Please enter your full name.';
    if ($formData['email'] === '' || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Please enter a valid email address.';
    if ($formData['phone'] !== '' && !preg_match('/^[0-9+\-\s()]{7,20}$/', $formData['phone'])) $errors['phone'] = 'Please enter a valid phone number.';
    if ($formData['service'] === '') $errors['service'] = 'Please select a service.';
    if ($formData['message'] === '' || mb_strlen($formData['message']) < 10) $errors['message'] = 'Please tell us a little about your project (10+ characters).';

    if (empty($errors)) {
        $st = $pdo->prepare('INSERT INTO enquiries(name,email,phone,service,message) VALUES(?,?,?,?,?)');
        $st->execute([$formData['name'], $formData['email'], $formData['phone'], $formData['service'], $formData['message']]);
        $success = true;
        $formData = ['name' => '', 'email' => '', 'phone' => '', 'service' => '', 'message' => ''];
    }
}

$today = date('l');
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="banner-strip">
        <img src="https://picsum.photos/seed/strata-beam-c1/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-c2/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-c3/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-c4/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-c5/300/500" alt="">
    </div>
    <div class="container">
        <p class="crumb"><a href="index.php">Home</a> / Contact Us</p>
        <span class="eyebrow">Talk To An Engineer</span>
        <h1>Tell us about<br>your site.</h1>
        <p>Send project details and we'll reply with next steps — usually within one business day.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-grid">

            <div>
                <div class="info-card">
                    <h3 style="margin-bottom:18px;">Office</h3>
                    <div class="info-row"><?php icon('pin'); ?><div><b>Address</b><?php echo e($site['address']); ?></div></div>
                    <div class="info-row"><?php icon('phone'); ?><div><b>Phone</b><?php echo e($site['phone']); ?></div></div>
                    <div class="info-row"><?php icon('mail'); ?><div><b>Email</b><?php echo e($site['email']); ?></div></div>
                </div>

                <div class="form-card">
                    <h3 style="margin-bottom:16px;"><?php icon('clock'); ?> Office Hours</h3>
                    <table class="hours-table">
                        <?php foreach ($officeHours as $day => $hours): ?>
                        <tr class="<?php echo $day === $today ? 'today' : ''; ?>">
                            <td><?php echo e($day); ?><?php echo $day === $today ? ' — today' : ''; ?></td>
                            <td><?php echo e($hours); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <div class="map-embed">
                    <iframe src="https://www.google.com/maps?q=Kathmandu,Nepal&output=embed" loading="lazy" title="Office location map" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <div class="form-card">
                <h3 style="margin-bottom:6px;">Send a project enquiry</h3>
                <p class="section-lede" style="margin-bottom:26px;">Fields marked required are checked on our server before anything is sent.</p>

                <?php if ($success): ?>
                <div class="form-alert success"><?php icon('check'); ?><span>Thanks — your enquiry has been received. We'll get back to you within one business day.</span></div>
                <?php elseif (!empty($errors)): ?>
                <div class="form-alert error"><span>Please fix the highlighted fields below and resubmit.</span></div>
                <?php endif; ?>

                <form id="contactForm" action="contact.php" method="POST" novalidate>
                    <div class="form-row">
                        <div class="form-group <?php echo isset($errors['name']) ? 'has-error' : ''; ?>">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" value="<?php echo e($formData['name']); ?>" required>
                            <?php if (isset($errors['name'])): ?><div class="field-error"><?php echo e($errors['name']); ?></div><?php endif; ?>
                        </div>
                        <div class="form-group <?php echo isset($errors['email']) ? 'has-error' : ''; ?>">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" value="<?php echo e($formData['email']); ?>" required>
                            <?php if (isset($errors['email'])): ?><div class="field-error"><?php echo e($errors['email']); ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group <?php echo isset($errors['phone']) ? 'has-error' : ''; ?>">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo e($formData['phone']); ?>" placeholder="+977 98XXXXXXXX">
                            <?php if (isset($errors['phone'])): ?><div class="field-error"><?php echo e($errors['phone']); ?></div><?php endif; ?>
                        </div>
                        <div class="form-group <?php echo isset($errors['service']) ? 'has-error' : ''; ?>">
                            <label for="service">Service Needed *</label>
                            <select id="service" name="service" required>
                                <option value="">Select a service…</option>
                                <?php foreach ($services as $s): ?>
                                <option value="<?php echo e($s['title']); ?>" <?php echo $formData['service'] === $s['title'] ? 'selected' : ''; ?>><?php echo e($s['title']); ?></option>
                                <?php endforeach; ?>
                                <option value="Not sure yet" <?php echo $formData['service'] === 'Not sure yet' ? 'selected' : ''; ?>>Not sure yet</option>
                            </select>
                            <?php if (isset($errors['service'])): ?><div class="field-error"><?php echo e($errors['service']); ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group <?php echo isset($errors['message']) ? 'has-error' : ''; ?>">
                        <label for="message">Project Details *</label>
                        <textarea id="message" name="message" rows="5" required placeholder="Location, building type, approximate size, timeline…"><?php echo e($formData['message']); ?></textarea>
                        <?php if (isset($errors['message'])): ?><div class="field-error"><?php echo e($errors['message']); ?></div><?php endif; ?>
                    </div>
                    <button type="submit" name="contact_submit" value="1" class="btn btn-primary btn-block">Send Enquiry <?php icon('arrow'); ?></button>
                </form>
            </div>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
