<?php 
// подключение 
require_once 'connect.php';
session_start();
// присваивание переменных 
$full_name = $_POST['full_name'];
$email     = $_POST['email'];
$login     = $_POST['login'];
$password  = $_POST['password'];
$password_confirm = $_POST['password_confirm'];

$cart = $user['cart'] ? explode(',', $user['cart']) : [];

// условие правильности пароля
if ($password == $password_confirm) {
    // хэширование пароля
    $password = md5($password);
    // Проверка уникальности логина
    $check_login_query = "SELECT * FROM `users` WHERE `login` = '$login'";
    $check_login_result = mysqli_query($connect, $check_login_query);
    if (mysqli_num_rows($check_login_result) > 0) {
        // Логин уже существует
        $_SESSION['message'] = 'Этот логин уже используется. Пожалуйста, выберите другой.';
        header('Location: ../register.php');
        exit();
    }
    // загрузка файла в папку uploads
    $path = 'uploads/' . time() . $_FILES['avatar']['name']; 
    if (!move_uploaded_file($_FILES['avatar']['tmp_name'], '../' . $path)) {
        $_SESSION['message'] = 'Ошибка загрузки изображения';
        header('Location: ../register.php');
        exit();
    } 
    // соединение с таблицей users
    $sql = "INSERT INTO `users` (`id`, `full_name`, `email`, `avatar`, `login`,  `password`, `cart`)
            VALUES (NULL, '$full_name', '$email', '$path', '$login',  '$password', '$cart')";
    if (mysqli_query($connect, $sql)) {
        $_SESSION['message'] = 'Регистрация прошла успешно!';
        header('Location: ../login.php');
        exit();
    } else {
        $_SESSION['message'] = 'Ошибка регистрации. Пожалуйста, попробуйте снова.';
        header('Location: ../register.php');
        exit();
    }
} else {
    // если пароль введен некорректно
    $_SESSION['message'] = 'Пароли не совпадают';
    header('Location: ../register.php');
    exit();
}
?>
