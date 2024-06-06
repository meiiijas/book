<?php
session_start();
require_once './action/connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    $connect->begin_transaction();

    try {
        $query = "SELECT cart FROM users WHERE id = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $cart = $user['cart'] ? explode(',', $user['cart']) : [];
        if (!in_array($product_id, $cart)) {
            $cart[] = $product_id;
        }
        $cart_str = implode(',', $cart);

        $query = "UPDATE users SET cart = ? WHERE id = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('si', $cart_str, $user_id);
        if (!$stmt->execute()) {
            throw new Exception('Ошибка при обновлении корзины пользователя');
        }

        $query = "UPDATE catalog SET amount = amount - 1 WHERE id = ? AND amount > 0";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('i', $product_id);
        if (!$stmt->execute() || $stmt->affected_rows === 0) {
            throw new Exception('Ошибка при обновлении количества товара или недостаточно товара на складе');
        }

        $connect->commit();
        echo 'Товар добавлен в корзину и количество товара уменьшено на 1';
    } catch (Exception $e) {
        $connect->rollback();
        http_response_code(500);
        echo 'Ошибка: ' . $e->getMessage();
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_cart'])) {
    $cart_items = isset($_POST['cart']) ? $_POST['cart'] : [];
    $total_sum = isset($_POST['total_sum']) ? floatval($_POST['total_sum']) : 0.0;
    $titles = isset($_POST['titles']) ? $_POST['titles'] : [];

    $cart_str = implode(',', array_map('intval', $cart_items));
    $titles_str = implode(',', $titles);

    $query = "INSERT INTO buckets (user_id, product_ids, titles, total_sum, status) VALUES (?, ?, ?, ?, 'в обработке')";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('issd', $user_id, $cart_str, $titles_str, $total_sum);
    if ($stmt->execute()) {
        // Очищаем корзину пользователя
        $query = "UPDATE users SET cart = '' WHERE id = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();

        header('Location: mybuckets.php');
    } else {
        echo "Ошибка при сохранении корзины: " . $stmt->error;
    }
    exit;
}

$query = "SELECT cart FROM users WHERE id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$cart = $user['cart'] ? explode(',', $user['cart']) : [];

if (!empty($cart)) {
    $cart_ids = implode(',', array_map('intval', $cart));
    $query = "SELECT * FROM catalog WHERE id IN ($cart_ids)";
    $result = mysqli_query($connect, $query);

    if (!$result) {
        die("Ошибка выполнения запроса: " . mysqli_error($connect));
    }
} else {
    $result = null;
}

$total_sum = 0;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Моя корзина</title>

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
    <a href="mybuckets.php" class="fa-solid fa-tag" id="plus"></a>
    <div class="profile-details">
        <h1><?= htmlspecialchars($_SESSION['user']['full_name']) ?></h1>
        <a href="mailto:<?= htmlspecialchars($_SESSION['user']['email']) ?>"><?= htmlspecialchars($_SESSION['user']['email']) ?></a>
    </div>
</div>

<h1 class="h1">Моя корзина <a href="#" class="fas fa-shopping-cart"></a></h1>
<div class="catalog-container">

    <?php if ($result && mysqli_num_rows($result) > 0) { ?>
        <form method="post">
            <?php while ($row = mysqli_fetch_assoc($result)) {
                $total_sum += $row['price']; ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($row['img']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                    <div class="product-info">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p class="description"><?= htmlspecialchars($row['description']) ?></p>
                        <div class="price"><?= htmlspecialchars($row['price']) ?> ₽</div>
                        <a href="#" class="fa-solid fa-ban" onclick="removeFromCart(<?= $row['id'] ?>)"></a>
                        <a href="#" id="description-button" class="fas fa-eye"></a>
                        <input type="hidden" name="cart[]" value="<?= $row['id'] ?>">
                        <input type="hidden" name="titles[]" value="<?= htmlspecialchars($row['title']) ?>">
                    </div>
                </div>
            <?php } ?>
            <input type="hidden" name="total_sum" value="<?= $total_sum ?>">
            <button type="submit" name="save_cart">Сохранить корзину</button>
        </form>
    </div>
    <div class="total-sum">
        <h2 class="h2"><a class="fa-solid fa-wallet"></a> Сумма: <?= $total_sum ?> ₽</h2>
    </div>
    <?php } else { ?>
        <h2 class="h2">Корзина пуста.</h2>
    <?php } ?>

<script>
    function removeFromCart(productId) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "remove_from_cart.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                location.reload();
            } else {
                alert("Ошибка при удалении товара из корзины");
            }
        };
        xhr.send("product_id=" + productId);
    }

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
</body>
</html>
