<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
   <link rel="icon" type="image/png" href="/public/images/logo.jpg">
  <title>Profile</title>
  <link rel="stylesheet" href="/public/styles/base.css">
  <link rel="stylesheet" href="/public/styles/header.css">
  <link rel="stylesheet" href="/public/styles/profilecss.css">
  <style>
    .profile-title {
      font-size: 72px;
      margin-top: 32px;
      margin-bottom: 24px;
    }
    @media (max-width: 900px) {
      .profile-title { font-size: 40px; }
    }
    @media (max-width: 600px) {
      .profile-title { font-size: 28px; }
    }
  </style>
</head>
<body>
  <?php
    require_once __DIR__ . '/../../src/utils/LoginSecurity.php';
    use utils\LoginSecurity;
    LoginSecurity::start();
    $isOwner = LoginSecurity::getUserId() === $user->getId();
    $isStudent = LoginSecurity::getUserRole() === 'student';
  ?>

  <?php include __DIR__ . '/../components/header.php'; ?>

  <main class="profilestudent">
    <div class="profile-title">Your profile</div>
    <div class="profile-main-row">
      <div class="profile-main-info" style="margin: 0 auto; text-align: center;">
        <h2><?= htmlspecialchars($user->getFullName()) ?></h2>
        <div class="profile-country">
          <?= htmlspecialchars($user->getCountry()) ?>
        </div>
        <div class="profile-langs">
          Student
        </div>
      </div>
    </div>

    <?php if ($isOwner && $isStudent): ?>
      <div class="profile-btn-row" style="margin-top: 32px;">
        <a href="/index.php?page=myteachers" class="get-started-btn">My teachers</a>
        <div class="book-icon">
          <img src="/public/images/logo.jpg" alt="Book Icon">
        </div>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>