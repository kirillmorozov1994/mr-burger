<?php

//Организуем подключение к базе данных
$host = '127.0.0.1';
@$mysql = new mysqli($host, 'root', 'root', 'project_1', '3306');

//Проверка подключения к базе данных
if (mysqli_connect_errno()) {
    echo 'Connect error: ' . mysqli_connect_error();
    die;
}

//Список всех зарегистрированных пользователей
$usersInfo = $mysql->query("SELECT id, email, `name` FROM `users`;")->fetch_all();

//Список всех заказов
$allOrders = $mysql->query("SELECT * FROM `orders`;")->fetch_all();

//Выводим всех пользователей
echo "<b>Список всех пользователей:</b> <br>";
for ($i = 0; $i < sizeof($usersInfo); $i++) {
    echo "<b>{$usersInfo[$i][0]}.</b> {$usersInfo[$i][1]}. <br> Имя: {$usersInfo[$i][2]}  <br><br>";
}

//Проверяем заполнение полей
$dataArray = array_map(function ($item) {
    $newArray = array_map(function($value) {
        if (empty($value)) {
            $value = '-';
            return $value;
        }
        return $value;
    }, $item);
    return $newArray;
}, $allOrders);

//Выводим все заказы
echo "<b>Список всех заказов:</b> <br>";
for ($i = 0; $i < sizeof($dataArray); $i++) {
    echo "<b>{$dataArray[$i][0]}.</b> Пользователь: {$dataArray[$i][1]} <br>";
    echo "Адрес: Улица: {$dataArray[$i][2]}, дом: {$dataArray[$i][3]},корпус: {$dataArray[$i][4]}, квартира: {$dataArray[$i][5]}, этаж: {$dataArray[$i][6]} <br>";
    echo "Способ оплаты: {$dataArray[$i][7]}<br>";
    echo "Обратный звонок: {$dataArray[$i][8]}<br>";
    echo "Комментарий: {$dataArray[$i][9]}<br><br>";
}
