<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="/public/styles/base.css">
  <link rel="stylesheet" href="/public/styles/header.css">
  <link rel="stylesheet" href="/public/styles/findteacher.css">
  <link rel="stylesheet" href="/public/styles/adminpanel.css">
</head>
<body>
<?php include __DIR__ . '/../components/header.php'; ?>

<main>
  <div class="admin-panel-wrapper">
    <div class="admin-panel-title">Admin Panel – Users</div>
    <?php if (!empty($messages)): ?>
      <div class="messages">
        <?php foreach ($messages as $m): ?>
          <p style="color: green;"><?= htmlspecialchars($m) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <table class="admin-panel-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Country</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($users as $u): ?>
        <tr>
          <td data-label="ID"><?= $u['id'] ?></td>
          <td data-label="Name"><?= htmlspecialchars($u['name'].' '.$u['surname']) ?></td>
          <td data-label="Email"><?= htmlspecialchars($u['email']) ?></td>
          <td data-label="Country"><?= htmlspecialchars($u['country']) ?></td>
          <td data-label="Role"><?= htmlspecialchars($u['role']) ?></td>
          <td data-label="Actions">
            <div class="admin-actions">
              <form method="post" style="display:inline">
                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                <button name="action" value="delete" class="admin-btn" onclick="return confirm('Delete user #<?= $u['id'] ?>?')">
                  Delete
                </button>
              </form>
              
              <form method="post" style="display:inline">
                <input type="hidden" name="user_id"  value="<?= $u['id'] ?>">
                <input type="hidden" name="new_role"
                       value="<?= $u['role']==='teacher' ? 'student' : 'teacher' ?>">
                <button name="action" value="toggleRole" class="admin-btn">
                  <?= $u['role']==='teacher' ? 'Demote' : 'Promote' ?>
                </button>
              </form>

              <a href="/index.php?page=adminviewuser&id=<?= $u['id'] ?>" class="admin-btn">
                View
              </a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
</body>
</html>