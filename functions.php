<?php

//Функция для добавления нового пользователя
function addUser($dataBase, $userName, $uresEmail, $userPhone)
{
    //Записываем пользователя в базу данных с пользователями
    $ret = $dataBase->query("INSERT INTO users (`name`, email, phone) VALUES ('$userName', '$uresEmail', '$userPhone');");
    //Если запись данные не удалось, завершаем скрипт и выдаем ошибку
    if (!$ret) {
        echo "Запрос завершился ошибкой: " . $dataBase->error;
        die;
    }
}

//Функция для добавления заказа
function addOrder(
    $dataBase,
    $uresEmail,
    $uresStreet,
    $userHome,
    $userPart,
    $userAppt,
    $userFloor,
    $userPayment,
    $userCallback,
    $userComment
) {


    //Записываем пользователя в базу данных с заказами
    $ret = $dataBase->query("INSERT INTO orders (user_email, street, house, corps, room, floor, payment, `call`, `comment`) 
    VALUES ('$uresEmail', '$uresStreet', '$userHome', '$userPart', '$userAppt', '$userFloor', '$userPayment', '$userCallback', '$userComment');");

    //Определяем количество заказов одного пользователя
    $ordersNumber = (int)$dataBase->query("SELECT orders.user_email,COUNT(*) FROM orders;")->fetch_all()[0][1];

    //Записываем их в базу данных в поле orders_number в таблице users
    $dataBase->query("UPDATE users SET orders_number = '$ordersNumber' WHERE email = '$uresEmail';");

    //Если запись данные не удалось, завершаем скрипт и выдаем ошибку
    if (!$ret) {
        echo "Запрос завершился ошибкой: " . $dataBase->error;
        die;
    }
}

function createOrder($dataBase, $arrayDataOrder)
{
    //Проверяем заполнение полей
    $dataArray = array_map(function ($item) {
        if (empty($item)) {
            $item = '-';
            return $item;
        }
        return $item;
    }, $arrayDataOrder);

    //Записываем номер заказа
    $nubmerOrder = 'Заказ №: ' . $dataArray[0] . PHP_EOL;

    //Содержимое заказа
    $contentOrder = "Содержимое заказа: DarkBeefBurger за 500 рублей, 1 шт." . PHP_EOL;

    //Записываем адресс
    $addressrOrder = 'Ваш заказ будет доставлен по адресу:' . PHP_EOL .
        "Улица: {$dataArray[2]}, дом {$dataArray[3]},корпус: {$dataArray[4]}, квартира: {$dataArray[5]}, этаж: {$dataArray[6]}" . PHP_EOL;

    //Способ оплаты
    $paymentOrder = "Способ оплаты: {$dataArray[7]}" . PHP_EOL;

    //Обратный звонок
    $callbackOrder = "Обратный звонок: {$dataArray[8]}" . PHP_EOL;

    //Комментарий
    $commentOrder = "Комментарий: {$dataArray[9]}" . PHP_EOL;

    //Определяем количество заказов одного пользователя
    $ordersNumber = "Это ваш: " . ((int)$dataBase->query("SELECT orders.user_email,COUNT(*) FROM orders;")->fetch_all()[0][1] + 1) . " заказ" . PHP_EOL;

    //Определяем дату заказа
    $dateOrder = "Дата заказа: " . date('d.m.Y H:i');

    //Полная информация о заказе в текстовый файл
    $dataOrderText = $nubmerOrder . $addressrOrder . $paymentOrder . $callbackOrder . $commentOrder . $ordersNumber . $dateOrder;

    //Полная информация о заказе на экран пользователю
    $dataOrderScreen = "{$nubmerOrder }<br> {$contentOrder}<br> {$addressrOrder}<br> {$paymentOrder}<br> {$callbackOrder}<br> {$commentOrder}<br> {$ordersNumber}<br> {$dateOrder}";

    //Выводим информацию на экран
    echo $dataOrderScreen;

    //Записываем данные в текстовый файл
    $file = 'order.txt';
    file_put_contents($file, $dataOrderText);

}