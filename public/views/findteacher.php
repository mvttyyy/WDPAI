<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Find a Teacher</title>
  <link rel="stylesheet" href="/public/styles/base.css">
  <link rel="stylesheet" href="/public/styles/findteacher.css">
  <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>
  <?php include __DIR__ . '/../components/header.php'; ?>

  <main class="findteacher">
    <h1 class="page-title">Find your perfect teacher</h1>

    <form action="/index.php" method="get" class="search-form">
      <input type="hidden" name="page" value="findteacher">
      <label for="language_id">Language:</label>
      <select name="language_id" id="language_id">
        <option value="0">— choose language —</option>
        <?php foreach($languages as $lang): ?>
          <option
            value="<?= (int)$lang['language_id'] ?>"
            <?= $lang['language_id']==$selectedLang ? 'selected' : '' ?>
          >
            <?= htmlspecialchars($lang['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <button type="submit">Search</button>
    </form>

    <?php if ($selectedLang > 0): ?>
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
        <p class="no-results">No teachers found.</p>
      <?php endif; ?>
    <?php endif; ?>

  </main>
</body>
</html>
