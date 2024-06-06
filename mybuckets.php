<?php
session_start();
require_once 'action/connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user']['id'];

$query = "SELECT id, titles, total_sum, status FROM buckets WHERE user_id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои заказы</title>

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
    <a href="#home" class="fas fa-home"></a>
    <a href="#featured" class="fas fa-list"></a>
    <a href="#arrivals" class="fas fa-tags"></a>
    <a href="#reviews" class="fas fa-comments"></a>
</nav>

<div class="form-profile">
    <img src="<?= htmlspecialchars($_SESSION['user']['avatar']) ?>" alt="Аватар">
    <div class="profile-details">
        <h1><?= htmlspecialchars($_SESSION['user']['full_name']) ?></h1>
        <a href="mailto:<?= htmlspecialchars($_SESSION['user']['email']) ?>"><?= htmlspecialchars($_SESSION['user']['email']) ?></a>
    </div>
</div>

<h1 class="h1">Мои заказы <a href="#" class="fas fa-shopping-cart"></a></h1>
<div class="buckets-container">
    <table>
        <thead>
            <tr>
                <th>Номер заказа</th>
                <th>Название товаров</th>
                <th>Сумма заказа</th>
                <th>Статус</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['titles']) ?></td>
                    <td><?= htmlspecialchars($row['total_sum']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


<h2 class="h2" >Ваш заказ был отклонен? Свяжитесь с нами по контакному номеру!</h2>
</body>
</html>
