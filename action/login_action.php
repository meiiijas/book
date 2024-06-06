<?php 
session_start();
require_once 'connect.php';
$login     = $_POST['login'];
$password  = md5($_POST['password']);
$check_user = mysqli_query($connect,
"SELECT * FROM `users` WHERE `login` = '$login' AND `password` = '$password'");
if ($login === 'admin' && $_POST['password'] === 'admin') {
    header('Location: ../admin.php');
    exit();
}
if (mysqli_num_rows($check_user)>0 ){
    $user = mysqli_fetch_assoc($check_user);
    $_SESSION['user'] = ["id" => $user['id'],
                        "full_name" => $user['full_name'], 
                        "email" => $user['email'],
                        "avatar" => $user['avatar']];
    header('Location: ../profile.php');
} else {
    $_SESSION['message'] = 'Проверьте правильность набора данных';
    header('Location: ../login.php');
    }
?>