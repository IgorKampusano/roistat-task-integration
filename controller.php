<?php

// проверяем, была ли отправлена форма
if (isset($_POST['send_request'])) {
    require_once 'settings.php';
    $name = $_POST['client_name'];
    $email = $_POST['client_email'];
    $phone = $_POST['client_phone'];
    $price = (int)$_POST['client_lead_price'];
    $isLong = false;
    // проверяем, прошло ли 30 секунд и более
    if ((time() - $_COOKIE['timeOut']) >= 30) {
        $isLong = true;
    }

    // API-метод для комплексного добавления сделки с контактом
    $method = '/api/v4/leads/complex';

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $longLiveToken,
    ];

    // массив с данными по сделке и привязанный контакт из формы
    $data = [
        [
            "name" => "Тестовая сделка",
            "price" => $price,
            "custom_fields_values" => [
                [
                    "field_id" => 220761,
                    "values" => [
                        [
                            "value" => $isLong
                        ]
                    ]
                ]
            ],
            "_embedded" => [
                "contacts" => [
                    [
                        "first_name" => $name,
                        "custom_fields_values" => [
                            [
                                "field_id" => 177259,
                                "values" => [
                                    [
                                        "enum_id" => 98075,
                                        "value" => $phone
                                    ]
                                ]
                            ],
                            [
                                "field_id" => 177261,
                                "values" => [
                                    [
                                        "enum_id" => 98087,
                                        "value" => $email
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
    curl_setopt($curl, CURLOPT_URL, "https://igorkampusano.amocrm.ru".$method);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    $out = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $code = (int)$code;
    $errors = [
        400 => 'Ошибка при формировании запроса',
        401 => 'Ошибка авторизации',
        403 => 'Доступ к ресурсу запрещен',
        404 => 'Ресурс не найден',
        500 => 'Внутренняя ошибка сервера',
        502 => 'Неверный ответ вышестоящего сервера или прокси-сервера',
        503 => 'Сервис недоступен',
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

    $response = json_decode($out, true);

    print_r($response);
}

