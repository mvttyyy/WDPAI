<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="/public/images/logo.jpg">
  <title>StudyWise</title>
  <link rel="stylesheet" href="/public/styles/base.css">
  <link rel="stylesheet" href="/public/styles/header.css">
  <link rel="stylesheet" href="/public/styles/index.css">
</head>
<body>

<?php include __DIR__ . '/../components/header.php'; ?>

<main>
  <div class="teachers">
    <img src="/public/images/teacher1.jpg" alt="Teacher 1" class="teacher-img teacher-top-left">
    <img src="/public/images/teacher2.jpg" alt="Teacher 2" class="teacher-img teacher-top-right">
    <div class="center-content">
      <div class="headline">
        Learn <strong>fast</strong> with hundreds of <strong>passionate</strong> teachers
      </div>
      <div class="book-icon">
        <img src="/public/images/logo.jpg" alt="Book Icon">
      </div>
      <a href="/index.php?page=findteacher" class="get-started-btn">Get started</a>
    </div>
    <img src="/public/images/teacher3.jpg" alt="Teacher 3" class="teacher-img teacher-bottom-left">
    <img src="/public/images/teacher4.jpg" alt="Teacher 4" class="teacher-img teacher-bottom-right">
  </div>
</main>

</body>
</html>
