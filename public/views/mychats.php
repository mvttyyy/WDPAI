<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" type="image/png" href="/public/images/logo.jpg">
  <title>My Chats</title>
  <link rel="stylesheet" href="/public/styles/base.css">
  <link rel="stylesheet" href="/public/styles/header.css">
  <link rel="stylesheet" href="/public/styles/mychats.css">
</head>
<body>
  <?php include __DIR__ . '/../components/header.php'; ?>
  <main>
    <div class="mychats-container">
      <div class="mychats-title">My Chats</div>
      <?php if (isset($chatCount)): ?>
        <div class="mychats-count">
          You participate in <?= (int)$chatCount ?> chat<?= $chatCount == 1 ? '' : 's' ?>.
        </div>
      <?php endif; ?>
      <?php if (!empty($chats)): ?>
        <ul class="mychats-list">
          <?php foreach($chats as $chat): ?>
            <li>
              <a href="/index.php?page=chat&user_id=<?= $role === 'student' ? $chat['teacher_id'] : $chat['student_id'] ?>" class="get-started-btn">
                <?= $role === 'student'
                      ? htmlspecialchars(trim(($chat['teacher_name'] ?? '') . ' ' . ($chat['teacher_surname'] ?? '')))
                      : htmlspecialchars(trim(($chat['student_name'] ?? '') . ' ' . ($chat['student_surname'] ?? ''))) ?>
              </a>
              <span class="mychats-meta">
                (<?= (int)$chat['message_count'] ?> messages,
                last: <?= htmlspecialchars($chat['last_message_at'] ?? '-') ?>)
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>You have no chats yet.</p>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>