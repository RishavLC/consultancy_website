<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? h($pageTitle) . ' | Admin' : 'Admin Panel' ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
  body { background:#f1efe9; }
  .admin-sidebar { background:#1c1c1c; min-height:100vh; }
  .admin-sidebar a { color:#ccc; display:block; padding:.7rem 1.2rem; text-decoration:none; }
  .admin-sidebar a.active, .admin-sidebar a:hover { background:#d97b1c; color:#fff; }
  .admin-sidebar .brand { color:#fff; font-weight:700; padding:1rem 1.2rem; display:block; }
  .btn-amber { background:#d97b1c; border-color:#d97b1c; color:#fff; }
  .btn-amber:hover { background:#b6620f; border-color:#b6620f; color:#fff; }
</style>
</head>
<body>
<div class="d-flex">
  <div class="admin-sidebar" style="width:220px;">
    <span class="brand">STRUCTURA <small>Admin</small></span>
    <a href="dashboard.php" class="<?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="banners.php" class="<?= ($active ?? '') === 'banners' ? 'active' : '' ?>"><i class="bi bi-images me-2"></i>Home Banners</a>
    <a href="work.php" class="<?= ($active ?? '') === 'work' ? 'active' : '' ?>"><i class="bi bi-briefcase me-2"></i>Our Work</a>
    <a href="gallery.php" class="<?= ($active ?? '') === 'gallery' ? 'active' : '' ?>"><i class="bi bi-image me-2"></i>Gallery</a>
    <a href="messages.php" class="<?= ($active ?? '') === 'messages' ? 'active' : '' ?>"><i class="bi bi-envelope me-2"></i>Messages</a>
    <a href="../index.php" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i>View Site</a>
    <a href="logout.php"><i class="bi bi-box-arrow-left me-2"></i>Logout</a>
  </div>
  <div class="flex-grow-1 p-4">
