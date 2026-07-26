<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Messages';
$active = 'messages';

if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];
    mysqli_query($conn, "UPDATE contact_messages SET is_read = 1 WHERE id = $id");
}
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM contact_messages WHERE id = $id");
    header('Location: messages.php');
    exit;
}

$messages = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY created_at DESC");

require_once __DIR__ . '/includes/admin-header.php';
?>

<h2 class="mb-4">Contact Messages</h2>

<div class="bg-white p-3">
  <table class="table align-middle mb-0">
    <thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th></th></tr></thead>
    <tbody>
    <?php while ($m = mysqli_fetch_assoc($messages)): ?>
      <tr class="<?= $m['is_read'] ? '' : 'table-warning' ?>">
        <td><?= h($m['name']) ?></td>
        <td><?= h($m['email']) ?><br><small><?= h($m['phone']) ?></small></td>
        <td><?= h($m['subject']) ?></td>
        <td style="max-width:280px;"><?= h(excerpt($m['message'], 15)) ?></td>
        <td><small><?= fdate($m['created_at']) ?></small></td>
        <td>
          <?php if (!$m['is_read']): ?><a href="messages.php?read=<?= $m['id'] ?>" class="btn btn-sm btn-outline-success">Mark Read</a><?php endif; ?>
          <a href="messages.php?delete=<?= $m['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this message?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
