<?php
session_start();
require_once 'action/connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: profile.php');
    exit();
}

$review_id = intval($_GET['id']);
$query = $connect->prepare('SELECT * FROM reviews WHERE id = ?');
$query->bind_param('i', $review_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    echo 'Обзор не найден.';
    exit();
}

$review = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обзор - <?= htmlspecialchars($review['title']) ?></title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="header">
    <div class="header-1">
        <a href="#" class="logo"><img src="assets/img/logo/title_only.png" width="260px"></a>

        <div class="icons">
            <a href="catalog.php" class="fa-solid fa-store"></a>
            <a href="bucket.php" class="fas fa-shopping-cart"></a>
            <a href="profile.php" class="fas fa-user"></a>
            <a href="action/logout_action.php" class="fa-solid fa-right-from-bracket"></a>
        </div>
    </div>

    <div class="header-2">
        <nav class="navbar">
            <a href="#home">Главная</a>
            <a href="#featured">Избранное</a>
            <a href="#arrivals">Поступления</a>
            <a href="#reviews">Обзоры</a>
        </nav>
    </div>
</header>

<nav class="bottom-navbar">
    <a href="catalog.php" class="fa-solid fa-store"></a>
    <a href="bucket.php" class="fas fa-shopping-cart"></a>
    <a href="profile.php" class="fas fa-user"></a>
    <a href="action/logout_action.php" class="fa-solid fa-right-from-bracket"></a>
</nav>

<div class="review-details">
    <div class="review-content">
        <div class="review-text">
            <h1><?= htmlspecialchars($review['title']) ?></h1>
            <p><?= htmlspecialchars($review['description']) ?></p>
        </div>
        <div class="review-image">
            <img src="<?= htmlspecialchars($review['img']) ?>" alt="Изображение манги">
        </div>
    </div>
    <a href="profile.php" class="close-review-btn">Вернуться на страницу профиля</a>
</div>
<!-- js файл -->
<script src="assets/js/script.js"></script>  
<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

</body>
</html>
