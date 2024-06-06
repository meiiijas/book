<?php
session_start();
require_once './action/connect.php';

// Получение данных из таблицы catalog
$query = "SELECT * FROM catalog";
$result = mysqli_query($connect, $query);

if (!$result) {
    die("Ошибка выполнения запроса: " . mysqli_error($connect));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    <!-- cdn ссылка -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- css файл-->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="catalog">
    
<!-- Секция HEADER - начало -->
<header class="header">

<div class="header-1">
    <a href="#" class="logo"><img src="assets/img/logo/title_only.png" width="260px"></img></a>

    <div class="icons">
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
    <a href="#home" class="fas fa-home"></a>
    <a href="#featured" class="fas fa-list"></a>
    <a href="#arrivals" class="fas fa-tags"></a>
    <a href="#reviews" class="fas fa-comments"></a>
</nav>

<div class="catalog-container">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="product-card">
            <img src="<?= htmlspecialchars($row['img']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
            <div class="product-info">
                
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p class="description"><?= htmlspecialchars($row['description']) ?></p>
                <div class="price"><?= htmlspecialchars($row['price']) ?> ₽</div>
                
                <a href="#" class="fas fa-shopping-cart" onclick="addToCart(<?= $row['id'] ?>)"></a>
                <a href="#" id="description-button" class="fas fa-eye"></a>
                <h1 class="amount">В наличии: <?= htmlspecialchars($row['amount']) ?></h1>
            </div>
        </div>
    <?php } ?>
</div>

<!-- Настя, это запрос для обращения к корзине -->
<script>
    function addToCart(productId) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "bucket.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert("Товар добавлен в корзину");
            } else {
                alert("Ошибка при добавлении товара в корзину");
            }
        };
        xhr.send("product_id=" + productId);
    }
</script>



<script>
    document.querySelectorAll('#description-button').forEach(button => {
        button.addEventListener('click', () => {
            const productInfo = button.closest('.product-info');
            const description = productInfo.querySelector('.description');
            if (description.style.display === 'none' || description.style.display === '') {
                description.style.display = 'block';
                button.textContent = '';
            } else {
                description.style.display = 'none';
                button.textContent = '';
            }
        });
    });
</script>

<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
</body>
</html>
