<?php
namespace utils;

class LoginSecurity {
  public static function start() {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  public static function login(int $userId, string $email, string $role) {
    self::start();
    $_SESSION['user_id']   = $userId;
    $_SESSION['user_email']= $email;
    $_SESSION['role']      = $role;
  }

  public static function logout() {
    self::start();
    session_unset();
    session_destroy();
    header('Location: /index.php?page=login');
    exit;
  }

  public static function requireLogin() {
    self::start();
    if (empty($_SESSION['user_id'])) {
      header('Location: /index.php?page=login');
      exit;
    }
  }

  public static function getUserId(): ?int {
    self::start();
    return $_SESSION['user_id'] ?? null;
  }

  public static function getUserRole(): ?string {
    self::start();
    return $_SESSION['role'] ?? null;
  }
}
