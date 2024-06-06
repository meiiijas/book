<?php 
session_start();
// Проверяем, установлена ли сессионная переменная 'user' и не пуста ли она
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('Location: profile.php');
    exit; // Завершаем выполнение скрипта после перенаправления
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
<body class="login">
    
<!-- Секция HEADER - начало -->
<header class="header">

<div class="header-1">
    <a href="#" class="logo"><img src="assets/img/logo/title_only.png" width="260px"></img></a>

    <form action="" class="search-form">
        <input type="search" name="" placeholder="Введите запрос..." id="search-box">
        <label for="search-box" class="fas fa-search"></label>
    </form>
         <div class="icons">

            <a href= "login.php"class="fas fa-user"></a>
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
    <a href="#home" class="fas fa-home"></a>
    <a href="#featured" class="fas fa-list"></a>
    <a href="#arrivals" class="fas fa-tags"></a>
    <a href="#reviews" class="fas fa-comments"></a>
</nav>

<!-- Форма авторизации -->
<div class="register-form-container">
<form action="action/login_action.php" method="post" enctype="multipart/form-data">
<h3>Авторизация</h3>
<label>Логин</label>
        <input type="text" name="login" class="box" placeholder="Введите свой логин">
    <label>Пароль</label>
        <input type="password" name="password" class="box" placeholder="Введите пароль">
        <button type="submit" class="btn">Войти</button>
        <p>
            У вас еще нет аккаунта? - <a href="register.php">Зарегистрируйтесь</a>!
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