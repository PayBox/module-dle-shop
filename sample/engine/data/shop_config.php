<?PHP

$ShopConfig['title'] = array("value"=>'Магазин', "type"=>"varchar", "title"=>"Название", "descr"=>"Укажите здесь название магазина");

$ShopConfig['site_mail'] = array("value"=>'', "type"=>"varchar", "title"=>"Почта сайта", "descr"=>"на данный адрес будут приходить уведомления модуля");

$ShopConfig['meta_desc'] = array("value"=>'', "type"=>"text", "title"=>"Мета описание", "descr"=>"Мета описание для главной страницы модуля");

$ShopConfig['meta_keys'] = array("value"=>'', "type"=>"text", "title"=>"Ключевые слова", "descr"=>"Ключевые слова для главной страницы модуля");

$ShopConfig['allow_add'] = array("value"=>'1', "type"=>"yesno", "title"=>"Разрешить добавление", "descr"=>"Включение/отключение функции добавления продуктов");

$ShopConfig['max_img_size'] = array("value"=>'2147483647', "type"=>"varchar", "title"=>"Максимальный размер загружаемой картинки", "descr"=>"Введите максимальный объём загружаемого изображения (в байтах)");

$ShopConfig['captcha'] = array("value"=>'1', "type"=>"yesno", "title"=>"Код безопасности", "descr"=>"Отображение кода безопасности при добавлении мероприятия для защиты от автоматического добавления");

$ShopConfig['allowed_screen'] = array("value"=>'jpg,gif,jpeg,png', "type"=>"varchar", "title"=>"Возможные типы фото", "descr"=>"Укажите через запятую расширения файлов, которые разрешено закачивать");

$ShopConfig['jpeg_quality'] = array("value"=>'80', "type"=>"varchar", "title"=>"Качество сжатия .jpg изображения", "descr"=>"Качество сжатия JPEG картинки при копировании на сервер");

$ShopConfig['multiimg_width'] = array("value"=>'250', "type"=>"varchar", "title"=>"Ширина копии 1ого загруженного изображения", "descr"=>"Это изображение появится на странице продукта как основное изображение");

$ShopConfig['width_photo'] = array("value"=>'80', "type"=>"varchar", "title"=>"Ширина уменьшенной копии загруженного изображения", "descr"=>"Укажите здесь название магазина");

$ShopConfig['limitpage'] = array("value"=>'4', "type"=>"varchar", "title"=>"Количество объявлений на страницу", "descr"=>"Количество продуктов, которое будет выводиться на страницу в разделе");

$ShopConfig['main_limitpage'] = array("value"=>'5', "type"=>"varchar", "title"=>"Количество объявлений на главной", "descr"=>"Количество продуктов, которое будет выводиться на главной странице сайта");

$ShopConfig['block_limitpage'] = array("value"=>'5', "type"=>"varchar", "title"=>"Количество объявлений в блоке", "descr"=>"Количество продуктов, которое будет выводиться в блоке последних продуктов");

$ShopConfig['comm_nummers'] = array("value"=>'10', "type"=>"varchar", "title"=>"Количество комментариев на страницу", "descr"=>"Укажите сколько комментариев выводить на одну страницу");

$ShopConfig['comment_not'] = array("value"=>'1', "type"=>"yesno", "title"=>"Комментарии", "descr"=>"Включить комментирование продуктов?");

$ShopConfig['onlyauth'] = array("value"=>'0', "type"=>"yesno", "title"=>"Продавать продукт зарегистрированным", "descr"=>"Добавление продукта в корзину ТОЛЬКО зарегистрированными пользователями");

$ShopConfig['wbm_cursite'] = array("value"=>'', "type"=>"varchar", "title"=>"WebMoney путь к сайту", "descr"=>"поле для платежа WebMoney - путь к сайту");

$ShopConfig['wbm_purse'] = array("value"=>'', "type"=>"varchar", "title"=>"WebMoney кошелек", "descr"=>"поле для платежа WebMoney - ваш кошелек WebMoney");

$ShopConfig['rbx_login'] = array("value"=>'', "type"=>"varchar", "title"=>"ROBOKASSA логин", "descr"=>"поле для платежа ROBOKASSA - ваш логин в системе");

$ShopConfig['rbx_pass'] = array("value"=>'', "type"=>"varchar", "title"=>"ROBOKASSA пароль", "descr"=>"поле для платежа ROBOKASSA - ваш пароль в системе");

$ShopConfig['pl_merchant_id'] = array("value"=>'', "type"=>"varchar", "title"=>"Platron. Номер магазина", "descr"=>"Смотреть в личном кабинете platron");

$ShopConfig['pl_secret_key'] = array("value"=>'', "type"=>"varchar", "title"=>"Platron. Секретный ключ", "descr"=>"Для подписи запросов");

$ShopConfig['pl_lifetime'] = array("value"=>'', "type"=>"varchar", "title"=>"Platron. Время жизни счета", "descr"=>"Сколько будет жить счет. 0 - не учитывается. Указывается в минутах");

$ShopConfig['pl_testmode'] = array("value"=>'1', "type"=>"yesno", "title"=>"Platron. Тестовый режим", "descr"=>"Для тестирования взаимодействия");

$ShopConfig['pl_success_url'] = array("value"=>'', "type"=>"varchar", "title"=>"Platron. Url успешного платежа", "descr"=>"Для возврата покупателя");

$ShopConfig['pl_failure_url'] = array("value"=>'', "type"=>"varchar", "title"=>"Platron. Url не успешного платежа", "descr"=>"Для возврата покупателя");

?>