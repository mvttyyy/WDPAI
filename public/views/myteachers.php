<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" type="image/png" href="/public/images/logo.jpg">
  <title>My Teachers</title>
  <link rel="stylesheet" href="/public/styles/base.css">
  <link rel="stylesheet" href="/public/styles/header.css">
  <link rel="stylesheet" href="/public/styles/findteacher.css">
</head>
<body>
  <?php include __DIR__ . '/../components/header.php'; ?>

  <main class="findteacher">
  <h1 class="page-title">My Teachers</h1>

  <?php if (!empty($teachers)): ?>
    <div class="teachers-list">
      <?php foreach ($teachers as $t): ?>
        <div class="teacher-card" data-teacher-id="<?= (int)$t['teacher_id'] ?>">
          <div class="teacher-photo">
            <img
              src="<?= htmlspecialchars($t['photo'] ?: '/public/images/default-teacher.jpg') ?>"
              alt="<?= htmlspecialchars($t['full_name']) ?>">
          </div>
          <div class="teacher-info">
            <h2><?= htmlspecialchars($t['full_name']) ?></h2>
            <div class="teacher-country">
              <?= htmlspecialchars($t['country']) ?>
            </div>
            <?php
            ?>
            <?php if (!empty($langsArr)): ?>
              <div class="teacher-langs">
                <?= htmlspecialchars(implode(', ', $langsArr)) ?>
              </div>
            <?php endif; ?>
            <div class="teacher-price">
              <?= number_format((float)$t['price_per_hour'], 2) ?> € per hour
            </div>
            <a href="/index.php?page=viewuser&id=<?= (int)$t['teacher_id'] ?>"
               class="btn-view">View profile</a>
          </div>
          <button class="remove-teacher-btn" data-teacher-id="<?= (int)$t['teacher_id'] ?>">
            Remove
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="no-results">You haven’t chosen any teachers yet.</p>
  <?php endif; ?>
</main>
<script src="/public/scripts/myteachers.js"></script>
</body>
</html> 