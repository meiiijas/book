<?php 
session_start();
require_once 'action/connect.php';
// Проверяем, установлен ли идентификатор пользователя в сессии
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
};

$user_id = $_SESSION['user']['id'];
$stmt = $connect->prepare("SELECT * FROM reviews WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$reviews = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    <!-- cdn ссылка -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- css файл-->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body >
    

<!-- Секция HEADER - начало -->
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
<!-- Секция HEADER - конец -->

<!-- bottom navbar -->

<nav class="bottom-navbar">
    <a href="catalog.php" class="fa-solid fa-store"></a>
        <a href="bucket.php" class="fas fa-shopping-cart"></a>
        <a href="profile.php" class="fas fa-user"></a>
        <a href="action/logout_action.php" class="fa-solid fa-right-from-bracket"></a>
</nav>

<div class="form-profile">
    <img src="<?= htmlspecialchars($_SESSION['user']['avatar']) ?>" alt="Аватар">
    <a href="review.php" class="fa-solid fa-plus" id="plus"></a>
    <div class="profile-details">
        <h1><?= htmlspecialchars($_SESSION['user']['full_name']) ?></h1>
        <a href="mailto:<?= htmlspecialchars($_SESSION['user']['email']) ?>"><?= htmlspecialchars($_SESSION['user']['email']) ?></a>
    </div>
</div>

<!-- Вывод обзоров -->
<div class="reviews-section">
    <h1 class="h1"><a href="#" class="fa-solid fa-book"></a> Ваши обзоры</h1>
    <div class="reviews-grid">
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <h3><?= htmlspecialchars($review['title']) ?></h3>
                <p><?= htmlspecialchars($review['description']) ?></p>
                <img src="<?= htmlspecialchars($review['img']) ?>" alt="Изображение манги">
                <a href="myreview.php?id=<?= $review['id'] ?>" class="open-review-btn">Открыть обзор</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- js файл -->
<script src="assets/js/script.js"></script>  
<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

</body>
</html>
