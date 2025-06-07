<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" type="image/png" href="/public/images/logo.jpg">
  <title>Chat</title>
  <link rel="stylesheet" href="/public/styles/base.css">
  <link rel="stylesheet" href="/public/styles/header.css">
  <link rel="stylesheet" href="/public/styles/chat.css">
</head>
<body>
  <?php include __DIR__ . '/../components/header.php'; ?>
  <main>
    <h1>Chat</h1>
    <div class="chat-container">
      <div class="chat-box">
        <?php foreach($messages as $msg): ?>
          <?php
            $dt = new DateTime($msg['sent_at']);
            $time = $dt->format('H:i');
            $date = $dt->format('Y-m-d');
          ?>
          <div class="chat-message">
            <div class="msg-content">
              <strong><?= $msg['sender_id'] == $userId ? 'You' : 'Them' ?>:</strong>
              <?= htmlspecialchars($msg['message']) ?>
            </div>
            <div class="msg-meta">
              <span class="msg-time"><?= $time ?></span>
              <span class="msg-date"><?= $date ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <form method="post" class="chat-form">
        <textarea name="message" rows="2" required></textarea>
        <button type="submit" class="get-started-btn">Send</button>
      </form>
    </div>
    <script src="/public/scripts/chat.js"></script>
  </main>
</body>
</html>