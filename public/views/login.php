<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" type="image/png" href="/public/images/logo.jpg">
  <title>Log In</title>
  <link rel="stylesheet" href="/public/styles/base.css">
  <link rel="stylesheet" href="/public/styles/header.css">
  <link rel="stylesheet" href="/public/styles/login.css">
  <script src="/public/scripts/signup-notif.js"></script>
</head>
<body>
  <?php include __DIR__ . '/../components/header.php'; ?>

<main>
  <div class="login-container">
    <h1 class="login-title">Sign in</h1>

    <?php if (!empty($messages)): ?>
      <div class="messages">
        <?php foreach(array_unique($messages) as $msg): ?>
          <p style="color: red;"><?= htmlspecialchars($msg) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form action="/index.php?page=login" method="post" class="login-form">
      <label for="email">Email</label>
      <input id="email" type="email" name="email" placeholder="Your email" required>
      <label for="password">Password</label>
      <input id="password" type="password" name="password" placeholder="Your password" required>
      <button type="submit" class="get-started-btn">Sign in</button>
    </form>

    <p class="signup-link">
      Don’t have an account? <a href="/index.php?page=signup">Sign up</a>
    </p>

    <div class="book-icon">
      <img src="/public/images/logo.jpg" alt="Book Icon">
    </div>
  </div>
</main>

</body>
</html>