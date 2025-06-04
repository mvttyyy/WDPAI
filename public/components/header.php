<?php
// public/views/components/header.php
require_once __DIR__ . '/../../src/utils/LoginSecurity.php';
use utils\LoginSecurity;

LoginSecurity::start();
$isLoggedIn = (bool) LoginSecurity::getUserId();
$role       = LoginSecurity::getUserRole(); // 'student', 'teacher' lub 'admin'
?>
<header>
  <div class="logo">
    <a href="/index.php">
      <span>StudyWise</span>
    </a>
  </div>

  <button class="hamburger" aria-label="Toggle menu">&#9776;</button>

  <form action="/index.php" method="get" class="search-form">
    <input type="hidden" name="page" value="search">
    <input
      class="language-select"
      type="text"
      name="q"
      placeholder="Search by name or language…"
      value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
    >
  </form>

  <nav>
    <a href="/index.php?page=findteacher">Find a teacher</a>
    <?php if (!$isLoggedIn): ?>
      <a href="/index.php?page=becometeacher">Become a teacher</a>
      <a href="/index.php?page=login">Log in</a>
      <a href="/index.php?page=signup">Sign up</a>
    <?php else: ?>
      <?php if ($role === 'student'): ?>
        <a href="/index.php?page=becometeacher">Become a teacher</a>
      <?php endif; ?>
      <?php if ($role === 'admin'): ?>
        <a href="/index.php?page=admin">Admin Panel</a>
      <?php endif; ?>
      <a href="/index.php?page=mychats">My chats</a>
      <a href="/index.php?page=profile">Profile</a>
      <a href="/index.php?page=logout">Log out</a>
    <?php endif; ?>
  </nav>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const btn = document.querySelector('.hamburger');
      const nav = document.querySelector('header nav');
      btn.addEventListener('click', () => nav.classList.toggle('open'));
    });
  </script>
</header>