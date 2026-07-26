<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Contact Us';
$active = 'contact';

$successMsg = '';
$errorMsg = '';

// Handle form submission (simple core PHP, same page)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $errorMsg = 'Please fill in your name, email and message.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = 'Please enter a valid email address.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $phone, $subject, $message);
        if (mysqli_stmt_execute($stmt)) {
            $successMsg = 'Thank you! Your message has been sent. Our team will get back to you shortly.';
        } else {
            $errorMsg = 'Something went wrong. Please try again later.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <h1>Contact Us</h1>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php">Home</a></li><li class="breadcrumb-item active">Contact Us</li></ol></nav>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row g-4 mb-5 text-center">
      <div class="col-md-4">
        <div class="service-card h-100">
          <div class="icon-box mx-auto"><i class="bi bi-geo-alt-fill"></i></div>
          <h5>Our Office</h5>
          <p class="mb-0">Putalisadak, Kathmandu, Nepal</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="service-card h-100">
          <div class="icon-box mx-auto"><i class="bi bi-telephone-fill"></i></div>
          <h5>Call Us</h5>
          <p class="mb-0">+977-1-4XXXXXX</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="service-card h-100">
          <div class="icon-box mx-auto"><i class="bi bi-envelope-fill"></i></div>
          <h5>Email Us</h5>
          <p class="mb-0">info@structuraconsult.com</p>
        </div>
      </div>
    </div>

    <div class="row g-5">
      <div class="col-lg-6">
        <h3 class="mb-3">Send Us A Message</h3>

        <?php if ($successMsg): ?><div class="alert alert-success"><?= h($successMsg) ?></div><?php endif; ?>
        <?php if ($errorMsg): ?><div class="alert alert-danger"><?= h($errorMsg) ?></div><?php endif; ?>

        <form method="POST" id="contactForm" class="needs-validation" novalidate>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name *</label>
              <input type="text" name="name" class="form-control" required value="<?= h($_POST['name'] ?? '') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email Address *</label>
              <input type="email" name="email" class="form-control" required value="<?= h($_POST['email'] ?? '') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone Number</label>
              <input type="text" name="phone" class="form-control" value="<?= h($_POST['phone'] ?? '') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Subject</label>
              <input type="text" name="subject" class="form-control" value="<?= h($_POST['subject'] ?? '') ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Message *</label>
              <textarea name="message" rows="5" class="form-control" required><?= h($_POST['message'] ?? '') ?></textarea>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-amber">Send Message</button>
            </div>
          </div>
        </form>
      </div>

      <div class="col-lg-6">
        <h3 class="mb-3">Find Us</h3>
        <div class="ratio ratio-4x3">
          <iframe src="https://www.google.com/maps?q=Kathmandu,Nepal&output=embed" style="border:0;" allowfullscreen loading="lazy"></iframe>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
