<?php

require_once 'settings.php';

$link = "https://$subdomain.amocrm.ru/oauth2/access_token";

$data = [
    'client_id'     => $clientId,
    'client_secret' => $clientSecret,
    'grant_type'    => 'refresh_token',
    'refresh_token' => $refreshToken,
    'redirect_uri'  => $redirectURI,
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
curl_setopt($curl, CURLOPT_URL, $link);
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
$out = curl_exec($curl);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

$code = (int)$code;
$errors = [
    301 => 'Запрашиваемый ресурс на сервере отсутствует или перемещен в другое место.',
    400 => 'Неправильная структура массива передаваемых данных, либо неверные идентификаторы пользовательских полей.',
    401 => 'Не авторизован. На сервере нет данных об аккаунте. Вам необходимо сделать запрос на другой сервер по переданному IP.',
    403 => 'Аккаунт заблокирован за многократное превышение количества запросов в секунду.',
    404 => 'Ресурс не найден.',
    500 => 'Внутренняя ошибка сервера.',
    502 => 'Неверный ответ вышестоящего сервера или прокси-сервера.',
    503 => 'Сервис недоступен.'
];

try
{
    /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
    if ($code < 200 || $code > 204) {
        throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Неопределенная ошибка', $code);
    }
}
catch(\Exception $e)
{
    die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
}

/**
 * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
 * нам придётся перевести ответ в формат, понятный PHP
 */

$response = json_decode($out, true);

$arrParamsAmo = [
    "access_token"  => $response['access_token'],
    "refresh_token" => $response['refresh_token'],
    "token_type"    => $response['token_type'],
    "expires_in"    => $response['expires_in'],
    "endTokenTime"  => $response['expires_in'] + time(),
];

$arrParamsAmo = json_encode($arrParamsAmo);

print_r($arrParamsAmo);

