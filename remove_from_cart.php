<?php
session_start();
require_once './action/connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $user_id = $_SESSION['user']['id']; 
    $connect->begin_transaction();

    try {
        $query = "SELECT cart FROM users WHERE id = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $cart = $user['cart'] ? explode(',', $user['cart']) : [];
        $cart = array_diff($cart, [$product_id]);
        $cart_str = implode(',', $cart);

        $query = "UPDATE users SET cart = ? WHERE id = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('si', $cart_str, $user_id);
        if (!$stmt->execute()) {
            throw new Exception('Ошибка при обновлении корзины пользователя');
        }

        // Увеличиваем количество товара на 1
        $query = "UPDATE catalog SET amount = amount + 1 WHERE id = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('i', $product_id);
        if (!$stmt->execute()) {
            throw new Exception('Ошибка при обновлении количества товара');
        }

        $connect->commit();
        echo 'Товар удален из корзины и количество товара увеличено на 1';
    } catch (Exception $e) {
        $connect->rollback();
        http_response_code(500);
        echo 'Ошибка: ' . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo 'Некорректный запрос';
}
?>
