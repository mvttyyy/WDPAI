<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Become a Teacher</title>
  <link rel="stylesheet" href="/public/styles/base.css">
  <link rel="stylesheet" href="/public/styles/header.css">
  <link rel="stylesheet" href="/public/styles/becometeacher.css">
</head>
<body>
  <?php include __DIR__ . '/../components/header.php'; ?>

  <main class="becometeacher">
    <div class="card form-container">
      <h1 class="card-title">
        <?= empty($bio) && empty($selectedLangs) ? 'Become a Teacher' : 'Edit Your Profile' ?>
      </h1>

      <?php if (!empty($messages)): ?>
        <div class="messages">
          <?php foreach($messages as $m): ?>
            <p class="alert"><?= htmlspecialchars($m) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form action="/index.php?page=becometeacher" method="post" enctype="multipart/form-data">
        <div class="field">
          <label for="bio">About you</label>
          <textarea name="bio" id="bio" rows="4" required><?= htmlspecialchars($bio ?? '') ?></textarea>
        </div>

        <div class="field dual-list">
          <div class="list-col">
            <label>Available</label>
            <select id="available" multiple>
              <?php foreach($languages as $lang):
                $sel = in_array($lang['language_id'], $selectedLangs ?? [], true);
                if (!$sel): ?>
                  <option value="<?= $lang['language_id'] ?>"><?= htmlspecialchars($lang['name']) ?></option>
              <?php endif; endforeach; ?>
            </select>
          </div>

          <div class="list-controls">
            <button type="button" id="to-selected" class="btn-small">&gt;&gt;</button>
            <button type="button" id="to-available" class="btn-small">&lt;&lt;</button>
          </div>

          <div class="list-col">
            <label>Selected</label>
            <select id="selected" name="languages[]" multiple required>
              <?php foreach($languages as $lang):
                $sel = in_array($lang['language_id'], $selectedLangs ?? [], true);
                if ($sel): ?>
                  <option value="<?= $lang['language_id'] ?>"><?= htmlspecialchars($lang['name']) ?></option>
              <?php endif; endforeach; ?>
            </select>
          </div>
        </div>

        <div class="field">
          <label for="price">Hourly price (€)</label>
          <input type="number" step="0.01" name="price" id="price"
                 value="<?= htmlspecialchars($price ?? '') ?>" required>
        </div>

        <div class="field">
          <label for="photo">Your photo</label>
          <?php if (!empty($photoPreview)): ?>
            <div class="photo-preview">
              <img src="<?= htmlspecialchars($photoPreview) ?>" alt="Current photo">
            </div>
          <?php endif; ?>
          <input type="file" name="photo" id="photo" accept="image/*" <?= empty($photoPreview) ? 'required' : '' ?> >
        </div>

        <button type="submit" class="btn-primary">Submit</button>
      </form>
    </div>
  </main>

  <script src="/public/scripts/dual-list.js"></script>
</body>
</html>
