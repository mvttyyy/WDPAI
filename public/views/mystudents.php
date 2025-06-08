<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" type="image/png" href="/public/images/logo.jpg">
  <title>My Students</title>
  <link rel="stylesheet" href="/public/styles/base.css">
  <link rel="stylesheet" href="/public/styles/header.css">
  <link rel="stylesheet" href="/public/styles/mystudents.css">
</head>
<body>
  <?php include __DIR__ . '/../components/header.php'; ?>

  <main class="findteacher">
    <h1 class="page-title">My Students</h1>

    <?php if (!empty($students)): ?>
      <table class="students-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Selected On</th>
            <th>Profile</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($students as $s): ?>
          <tr>
            <td><?= htmlspecialchars($s['full_name']) ?></td>
            <td><?= htmlspecialchars($s['created_at']) ?></td>
            <td>
              <a href="/index.php?page=profilestudent&id=<?= (int)$s['id'] ?>" class="btn-primary">View profile</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="no-results">You have no students yet.</p>
    <?php endif; ?>
  </main>
</body>
</html>