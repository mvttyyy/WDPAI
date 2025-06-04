<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Search Results</title>
  <link rel="stylesheet" href="/public/styles/base.css">
  <link rel="stylesheet" href="/public/styles/findteacher.css">
  <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>
  <?php include __DIR__ . '/../components/header.php'; ?>

  <main class="findteacher search-results">
    <h1 class="page-title">Search results for "<?= htmlspecialchars($query) ?>"</h1>

    <?php if (!empty($teachers)): ?>
      <div class="teachers-list">
        <?php foreach($teachers as $t): ?>
          <div class="teacher-card">
            <div class="teacher-photo">
              <img src="<?= htmlspecialchars($t['photo'] ?: '/public/images/default-teacher.jpg') ?>"
                   alt="<?= htmlspecialchars($t['full_name']) ?>">
            </div>
            <div class="teacher-info">
              <h2><?= htmlspecialchars($t['full_name']) ?></h2>
              <p class="teacher-country"><?= htmlspecialchars($t['country']) ?></p>
              <p class="teacher-langs"><?= htmlspecialchars($t['language_name']) ?></p>
              <p class="teacher-price"><?= number_format($t['price'], 2) ?> € per hour</p>
              <a href="/index.php?page=viewuser&id=<?= $t['teacher_id'] ?>" class="btn-view">View profile</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="no-results">No teachers found for "<?= htmlspecialchars($query) ?>".</p>
    <?php endif; ?>

  </main>
</body>
</html>
