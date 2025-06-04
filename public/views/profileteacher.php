<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Teacher Profile</title>
  <link rel="stylesheet" href="/public/styles/base.css">
  <link rel="stylesheet" href="/public/styles/header.css">
  <link rel="stylesheet" href="/public/styles/profilecss.css">
</head>
<body>
<?php
  require_once __DIR__ . '/../../src/utils/LoginSecurity.php';
  use utils\LoginSecurity;
  LoginSecurity::start();
  $isOwner   = LoginSecurity::getUserId() === $user->getId();
  $isTeacher = LoginSecurity::getUserRole() === 'teacher';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<main class="profileteacher">
  <div class="profile-title">Your profile</div>

  <div class="profile-main-row">
    <div class="profile-main-info">
      <h2><?= htmlspecialchars($user->getFullName()) ?></h2>
      <div class="profile-country">
        <?= htmlspecialchars($user->getCountry()) ?>
      </div>
      <div class="profile-langs">
        <?php if (!empty($teacherData['offers'])): ?>
          <?= implode(', ', array_column($teacherData['offers'],'language_name')) ?>
        <?php else: ?>
          <em>(no data provided)</em>
        <?php endif; ?>
      </div>
      <div class="profile-price">
        <?php if (!empty($teacherData['offers'])): ?>
          <?= number_format(min(array_column($teacherData['offers'],'price')),2) ?> € per hour
        <?php else: ?>
          <em>(no data provided)</em>
        <?php endif; ?>
      </div>
    </div>
    <div class="profile-photo">
      <?php if (!empty($teacherData['photo'])): ?>
        <img src="<?= htmlspecialchars($teacherData['photo']) ?>" alt="Teacher photo">
      <?php else: ?>
        <div><em>(no data provided)</em></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="profile-about-wrapper">
    <div class="profile-about-title">About me</div>
    <div class="profile-about-content">
      <?php if (!empty($teacherData['bio'])): ?>
        <?= nl2br(htmlspecialchars($teacherData['bio'])) ?>
      <?php else: ?>
        <em>(no data provided)</em>
      <?php endif; ?>
    </div>
  </div>

  <div class="profile-btn-row">
    <a href="/index.php?page=mystudents&teacher_id=<?= (int)$user->getId() ?>" class="get-started-btn">My students</a>
    <div class="book-icon">
      <img src="/public/images/logo.jpg" alt="Book Icon">
    </div>
  </div>
</main>
</body>
</html>
