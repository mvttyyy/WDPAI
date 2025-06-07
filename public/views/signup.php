<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <link rel="icon" type="image/png" href="/public/images/logo.jpg">
  <title>Sign Up</title>
  <link rel="stylesheet" href="/public/styles/base.css" />
  <link rel="stylesheet" href="/public/styles/header.css" />
  <link rel="stylesheet" href="/public/styles/signup.css" />
  <script src="/public/scripts/signup-notif.js"></script>
</head>
<body>

<?php include __DIR__ . '/../components/header.php'; ?>

<main>
  <div class="login-container">
    <h1 class="login-title">Create an account</h1>

    <?php if (!empty($messages)): ?>
      <div class="messages">
        <?php foreach ($messages as $msg): ?>
          <p style="color: red;"><?= htmlspecialchars($msg) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form action="/index.php?page=signup" method="post" class="login-form">
      <label for="name">Name</label>
      <input id="name" type="text" name="name" placeholder="Your name" required />

      <label for="surname">Surname</label>
      <input id="surname" type="text" name="surname" placeholder="Your surname" required />

      <label for="country">Country</label>
      <input id="country" type="text" name="country" placeholder="Your country" required />

      <label for="email">Email</label>
      <input id="email" type="email" name="email" placeholder="Your email" required />

      <label for="password">Password</label>
      <input id="password" type="password" name="password" placeholder="Your password" required />

      <button type="submit" class="get-started-btn">Sign up</button>
    </form>

    <p class="terms-text">
      By signing up you agree to our <strong>Terms of Service</strong> and <strong>Privacy Policy</strong>
    </p>

    <p class="signup-link">
      Already have an account? <a href="/index.php?page=login">Log in</a>
    </p>

    <div class="book-icon">
      <img src="/public/images/logo.jpg" alt="Book Icon" />
    </div>
  </div>
</main>

</body>
</html>
