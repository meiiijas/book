<?php 
session_start();

// Проверяем, если пользователь уже авторизован, перенаправляем на страницу профиля
if ($_SESSION['user']) {
    header('Location: profile.php');
    exit; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    <!-- cdn ссылка -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- css файл-->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="register">
    

<!-- Секция HEADER - начало -->
<header class="header">
<div class="header-1">
    <a href="#" class="logo"><img src="assets/img/logo/title_only.png" width="260px"></img></a>

    <form action="" class="search-form">
        <input type="search" name="" placeholder="Введите запрос..." id="search-box">
        <label for="search-box" class="fas fa-search"></label>
    </form>
        <div class="icons">
            <div id="search-btn" class="fas fa-search"></div>
            <a href="#" class="fas fa-heart"></a>
            <a href="#" class="fas fa-shopping-cart"></a>
            <div id="login-btn" class="fas fa-user"></div>
        </div>
</div>

<div class="header-2">
    <nav class="navbar">
        <a href="index.php">Главная</a>
        <a href="#featured">Избранное</a>
        <a href="#catalog.php">Поступления</a>
        <a href="#reviews">Обзоры</a>
    </nav>
</div>
</header>
<!-- Секция HEADER - конец -->



<!-- Форма регистрации -->
<div class="register-form-container">
<form action="action/register_action.php" method="post" enctype="multipart/form-data">
<h3>Регистрация</h3>
    <label>ФИО</label>
        <input type="text" name="full_name" class="box" placeholder="Введите свое полное имя">
    <label>Почта</label>
        <input type="email" name="email" class="box" placeholder="Введите адрес своей почты">
     <label>Логин</label>
        <input type="text" name="login" class="box" placeholder="Введите свой логин">
     <label>Изображение профиля</label>   
        <input type="file" class="box" name="avatar">
    <label>Пароль</label>
        <input type="password" name="password" class="box" placeholder="Введите пароль">
    <label>Подтверждение пароля</label>
        <input type="password" name="password_confirm" class="box" placeholder="Подтвердите пароль">
       
        <button type="submit" class="btn">Войти</button>
        <p>
            У вас уже есть аккаунт? - <a href="login.php">авторизируйтесь</a>!
        </p>
        <?php
            if ($_SESSION['message']) {
                echo '<p class="msg"> ' . $_SESSION['message'] . ' </p>';
            }
            unset($_SESSION['message']);
        ?>
    </form>
    </div>

    
<!-- <div class="loader-container">
    <img src="assets/img/logo-gif.gif">
</div> -->


















<!-- js файл -->
<script src="assets/js/script.js"></script>  
<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>


</body>
</html>