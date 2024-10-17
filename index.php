<?php
// создаем куку для подсчета времени
if (!isset($_COOKIE['timeOut'])) {
    setcookie("timeOut", time());
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заявка в amoCRM</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>Заявка в amoCRM</h1>
    <form>
        <label class="form__input">
            <span>Ваше имя</span>
            <input type="text" name="client_name">
        </label>
        <label class="form__input">
            <span>Ваш email</span>
            <input type="email" name="client_email">
        </label>
        <label class="form__input">
            <span>Ваш телефон</span>
            <input type="tel" placeholder="+7 (___) ___-__-__" name="client_phone">
        </label>
        <label class="form__input">
            <span>Цена</span>
            <input type="text" name="client_lead_price">
        </label>
        <input class="form__button" type="submit" name="send_request" value="Отправить заявку">
    </form>
</div>
<script src="js/jquery-3.7.1.min.js"></script>
<script src="js/jquery.inputmask.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>