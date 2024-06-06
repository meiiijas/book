<?php
session_start();
require_once './action/connect.php';



// Обновление статуса корзины
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bucket_id']) && isset($_POST['status'])) {
    $bucket_id = intval($_POST['bucket_id']);
    $status = $_POST['status'];

    $query = "UPDATE buckets SET status = ? WHERE id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('si', $status, $bucket_id);

    if ($stmt->execute()) {
        echo "Статус корзины обновлен";
    } else {
        echo "Ошибка при обновлении статуса корзины: " . $stmt->error;
    }
    exit;
}

// Получение всех корзин из базы данных
$query = "SELECT buckets.*, users.full_name FROM buckets JOIN users ON buckets.user_id = users.id";
$result = mysqli_query($connect, $query);

if (!$result) {
    die("Ошибка выполнения запроса: " . mysqli_error($connect));
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
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

<h1 class="h1">Админ-панель - Управление корзинами</h1>
<div class="admin-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Товары</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['titles']) ?></td>
                    <td><?= htmlspecialchars($row['total_sum']) ?> ₽</td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <form method="post" class="status-form">
                            <input type="hidden" name="bucket_id" value="<?= $row['id'] ?>">
                            <select name="status">
                                <option value="в обработке" <?= $row['status'] == 'в обработке' ? 'selected' : '' ?>>в обработке</option>
                                <option value="принято" <?= $row['status'] == 'принято' ? 'selected' : '' ?>>принято</option>
                                <option value="собран" <?= $row['status'] == 'собран' ? 'selected' : '' ?>>собран</option>
                                <option value="ожидает в пв" <?= $row['status'] == 'ожидает в пв' ? 'selected' : '' ?>>ожидает в пв</option>
                                <option value="отклонен" <?= $row['status'] == 'отклонен' ? 'selected' : '' ?>>отклонен</option>
                            </select>
                            <button type="submit">Обновить</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
document.querySelectorAll('.status-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('admin.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            alert(text);
            location.reload();
        })
        .catch(error => console.error('Ошибка:', error));
    });
});
</script>
</body>
</html>
