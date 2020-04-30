# module-dle-shop

#### Тестировалось и писалось для DLE 10.1 Shop версия 1.1

1. Регистрируемся на <a href="https://paybox.money" target="_blank">paybox.money</a>
2. Заходим `templates\ваш шаблон\shop\checkout.tpl` и в `select` вставляем (редактируем файлы в той кодировке, в которой они были изначально)
`<option value="paybox">Paybox</option>`
3. Открываем `engine/modules/shop.php`.
После `switch($_POST['gateway'])` вставляем
```
case"paybox":
{
    $GateWay = "Paybox";
    break;
}
```
после
```
else if($_POST['gateway']=="robox")
{
    $this->RoboxForm($MessArr);
}
```
вставляем
```
else if($_POST['gateway']=="paybox")
{
    $this->PayboxForm($MessArr);
}
```
после метода `RoboxForm` вставить метод `PayboxForm`
```
function PayboxForm($MessArr)
{
    require_once('\engine\modules\paybox\PG_Signature.php');
    global $tpl;
    global $config

    $form_fields = array(
        'pg_merchant_id' => $this->VarMod->MyConfig['pl_merchant_id']['value'],
        'pg_order_id' => $MessArr['ordernum'],
//      'pg_encoding' =>$config['charset'],
        'pg_user_ip' => $MessArr['ip'],
        'pg_user_email' => $MessArr['email'],
        'pg_contact_email' => $MessArr['email'],
        'pg_amount' => number_format($MessArr['totalsum'], 2, '.', ''),
        'pg_lifetime' => (!emptyempty($this->VarMod->MyConfig['pl_lifetime']['value']))?$this->VarMod->MyConfig['pl_lifetime']['value']:0,
        'pg_testing_mode' => (!emptyempty($this->VarMod->MyConfig['pl_testmode']['value'])) ? 1 : 0,
        'pg_description' => '#'.$MessArr['ordernum'],
        'pg_check_url' => 'http://'.$_SERVER['SERVER_NAME'].'/engine/paybox/callback.php',
        'pg_result_url' => 'http://'.$_SERVER['SERVER_NAME'].'/engine/paybox/callback.php',
        'pg_request_method' => 'GET',
        'pg_salt' => rand(21,43433), //Пaраметры беопасности сообщения. Необходима генерация pg_salt и подписи сообщения.
    );

    if(!emptyempty($this->VarMod->MyConfig['pg_success_url']['value']))
        $form_fields['pg_success_url'] = $this->VarMod->MyConfig['pg_success_url']['value'];
    if(!emptyempty($this->VarMod->MyConfig['pg_failure_url']['value']))
        $form_fields['pg_failure_url'] = $this->VarMod->MyConfig['pg_failure_url']['value'];

    preg_match_all("/\d/", $MessArr['telephone'], $array);
    $strPhone = implode('',$array[0]);
    $form_fields['pg_user_phone'] = $strPhone;

    $form_fields['pg_sig'] = PG_Signature::make('payment.php', $form_fields, $this->VarMod->MyConfig['pl_secret_key']['value']);
    $query = http_build_query($form_fields);
    header("Location: https://www.paybox.ru/payment.php?$query");
}
```
4. В `engine\data\shop_config.php` в конец вставить
```
$ShopConfig['pl_merchant_id'] = array(
    "value" => '',
    "type" => "varchar",
    "title" => "Paybox. Номер магазина",
    "descr" => "Смотреть в личном кабинете paybox"
);

$ShopConfig['pl_secret_key'] = array(
    "value" => '',
    "type" => "varchar",
    "title" => "Paybox. Секретный ключ",
    "descr" => "Для подписи запросов"
);

$ShopConfig['pl_lifetime'] = array(
    "value" => '',
    "type" => "varchar",
    "title" => "Paybox.Время жизни счета",
    "descr" => "Сколько будет жить счет. 0 - не учитывается. Указывается в минутах"
);

$ShopConfig['pl_testmode'] = array(
    "value" => '1',
    "type" => "yesno",
    "title" => "Paybox. Тестовый режим",
    "descr" => "Для тестирования взаимодействия"
);

$ShopConfig['pl_success_url'] = array(
    "value" => '',
    "type" => "varchar",
    "title" => "Paybox. Url успешного платежа",
    "descr" => "Для возврата покупателя"
);

$ShopConfig['pl_failure_url'] = array(
    "value" => '',
    "type" => "varchar",
    "title" => "Paybox. Url не успешного платежа",
    "descr" => "Для возврата покупателя"
);
```

5. Вставьте папку engine из архива в корень сайта
6. В админке при просмотре товаров добавить вывод статуса. В `engine\classes\modulus.class.php` после
`if($_GET["mod"] == "shop" && $_GET["op"] == "showlist" && $_GET["class"]=="orders")`
вставить
```
$CurrArr = array(
    "unconf" => "В ожидании",
    "paid" => "Оплачен",
    "canceld" => "Отменен",
);
```
после `$TotalList.=$items[$i]["addinfo"];` вставить
```
$TotalList .= "</td><td>";
$TotalList .= "Статус: " . $CurrArr[$items[$i]["status"]];
```

7. Зайти в список всех разделов->админка->Настройка магазина и заполнить поля, которые относятся к paybox

Для того, чтобы не принимать платеж достаточно удалить заказ.


