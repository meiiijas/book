<?php
session_start();
require_once 'action/connect.php';

// Проверяем, установлен ли идентификатор пользователя в сессии
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user']['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $img = $_FILES['img'];

    // Загружаем изображениеa
    $img_path = 'reviews/' . basename($img['name']);
    move_uploaded_file($img['tmp_name'], $img_path);

    // Вставка данных в базу данных
    $stmt = $connect->prepare("INSERT INTO reviews (user_id, title, description, img) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $title, $description, $img_path);
    $stmt->execute();
    $stmt->close();

    header('Location: profile.php');
    exit();
}
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

<div class="review-form-container">
<img src="<?= htmlspecialchars($_SESSION['user']['avatar']) ?>" class="img-review" alt="Аватар">
    <form class="review-form" action="review.php" method="post" enctype="multipart/form-data">
        <label for="title">Название манги:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Описание:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="img">Картинка:</label>
        <input type="file" id="img" name="img" accept="image/*" required>

        <button type="submit">Отправить обзор</button>
    </form>
</div>
</body>
</html>
