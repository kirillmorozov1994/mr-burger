<?php

//Запускаем сессию
require_once 'functions.php';

//Организуем подключение к базе данных
$host = '127.0.0.1';
@$mysql = new mysqli($host, 'root', 'root', 'project_1', '3306');

//Проверка подключения к базе данных
if (mysqli_connect_errno()) {
    echo 'Connect error: ' . mysqli_connect_error();
    die;
}

//Сохраняем данные из массива $_POST
$userName = $_POST['name'];
$userEmail = $_POST['email'];
$userPhone = $_POST['phone'];
$userStreet = $_POST['street'];
$userHome = $_POST['home'];
$userPart = $_POST['part'];
$userAppt = $_POST['appt'];
$userFloor = $_POST['floor'];
$userPayment = $_POST['payment'];
$userCallback = $_POST['callback'];
$userComment = $_POST['comment'];

//Проверяем наличие пользователя в базе данных
$user = $mysql->query("SELECT email FROM `users` WHERE email = '$userEmail';")->fetch_all()[0][0];

//Если пользователя нет, записываем его в базу данных
if (!isset($user)) {
    addUser($mysql, $userName, $userEmail, $userPhone);
}

//Добавляем заказ в базу данных
addOrder($mysql, $userEmail, $userStreet, $userHome, $userPart, $userAppt, $userFloor,
    $userPayment, $userCallback, $userComment);

//Выбираем только что созданный заказ
$arrayDataOrder = $mysql->query("SELECT * FROM orders WHERE id = (SELECT max(`id`) FROM orders);")->fetch_all()[0];

//Выводим информацию о заказе на экран и записываем её в тескстовый файл
$textDataOrder = createOrder($mysql, $arrayDataOrder);


