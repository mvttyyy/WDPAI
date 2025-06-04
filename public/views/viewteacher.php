<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($user->getFullName()) ?></title>
  <link rel="stylesheet" href="/public/styles/base.css">
  <link rel="stylesheet" href="/public/styles/header.css">
  <link rel="stylesheet" href="/public/styles/profilecss.css">
  <link rel="stylesheet" href="/public/styles/viewteacher.css">
</head>
<body>
  <?php
    require_once __DIR__ . '/../../src/utils/LoginSecurity.php';
    use utils\LoginSecurity;
    LoginSecurity::start();
    LoginSecurity::requireLogin();

    $currentUser = LoginSecurity::getUserId();
    $currentRole = LoginSecurity::getUserRole();
    $teacherId   = (int)($_GET['id'] ?? 0);

    $canChoose = $currentRole === 'student'
            && $currentUser !== $teacherId
            && !in_array($teacherId, $selectedTeachers, true);
  ?>

  <?php include __DIR__ . '/../components/header.php'; ?>

  <?php if (!empty($_GET['chosen']) && $_GET['chosen'] == 1): ?>
    <div class="messages">
      <p class="alert">You have successfully chosen this teacher!</p>
    </div>
  <?php endif; ?>

  <?php
    $showChat = false;
    if ($currentRole === 'student' && $currentUser !== $teacherId) {
        if (!empty($selectedTeachers) && in_array($teacherId, $selectedTeachers)) {
            $showChat = true;
        }
    }
  ?>

  <?php if ($showChat): ?>
    <a href="/index.php?page=chat&user_id=<?= $teacherId ?>" class="btn-primary chat-btn-top">Chat with teacher</a>
  <?php endif; ?>

  <main class="teacher-profile-container">
    <div class="teacher-profile-header">
      <div class="teacher-profile-photo">
        <img src="<?= htmlspecialchars($profile['photo'] ?: '/public/images/default-teacher.jpg') ?>"
             alt="Photo of <?= htmlspecialchars($user->getFullName()) ?>">
      </div>
      <div class="teacher-profile-maininfo">
        <h1><?= htmlspecialchars($user->getFullName()) ?></h1>
        <div class="teacher-profile-country">
          <?= htmlspecialchars($user->getCountry()) ?>
          <?php if (!empty($profile['country_flag'])): ?>
            <span><?= $profile['country_flag'] ?></span>
          <?php endif; ?>
        </div>
        <div class="teacher-profile-langs">
          <?= implode(', ', array_column($offers,'language_name')) ?>
        </div>
        <div class="teacher-profile-price">
          <?= !empty($offers) ? number_format(min(array_column($offers,'price')),2) . ' € per hour' : '' ?>
        </div>
      </div>
    </div>

    <div class="teacher-profile-about-title">About me</div>
    <div class="teacher-profile-about-text"><?= nl2br(htmlspecialchars($profile['bio'])) ?></div>

    <div class="teacher-profile-btn-row">
      <div class="book-icon">
        <img src="/public/images/logo.jpg" alt="Book Icon">
      </div>
      <?php if ($canChoose): ?>
        <form action="/index.php?page=chooseteacher" method="post" style="display:inline;">
          <input type="hidden" name="teacher_id" value="<?= $teacherId ?>">
          <button type="submit" class="btn-primary">Choose</button>
        </form>
      <?php endif; ?>
      <div class="book-icon">
        <img src="/public/images/logo.jpg" alt="Book Icon">
      </div>
    </div>
  </main>
</body>
</html>