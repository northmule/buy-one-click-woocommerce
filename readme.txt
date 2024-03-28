=== Buy one click WooCommerce ===
Contributors: northmule
Donate link: https://yoomoney.ru/to/41001746944171
Tags: woocommerce, ecommerce, mode catalog, buy one click, buy now, add to cart, buy now button, buttons
Requires at least: 6.0
Tested up to: 6.4
Stable tag: 6.0
Requires PHP: 7.4
WC requires at least: 7.0
WC tested up to: 8.7
License: GPLv2 or later
License URI: http://www.apache.org/licenses/

 		
== Description ==

The plugin adds a buy button in one click to your WooCommerce

This is the best solution for WooCommere to easily add a quick order button to the site.

= Some advantages of the plugin: =
* PHP >= 7.4
* You only need WooCommere
* Several modes of operation
* Several styles for the form directly from the plugin settings
* Ability to customize styles for yourself
* Wide range of settings
* The button can be added to the item card and to the category
* Shortcode to install the button anywhere WordPress

= Support = 

* [Telegram Group @coderunphp](https://t.me/coderunphp)

* [GitHub](https://github.com/northmule/buy-one-click-woocommerce)

= Required Plugins =
* [WooCommerce](https://wordpress.org/plugins/woocommerce/)

= Bundled translations: =
* Russian
* English

= Sponsors =
This project is being developed using the best free IDE [NetBeans](https://netbeans.apache.org/)

= Donate link: =
<a href="https://yoomoney.ru/to/41001746944171" target="_blank">Visa(RU) / MasterCard(RU) / Mir / YandexMoney</a>
<a href="https://www.donationalerts.com/r/northmule" target="_blank">The whole world</a>

== Installation ==

1. Make sure you have the latest version of the plugin installed. [WooCommerce](http://www.woothemes.com/woocommerce)
2. Unpack the archive and download the "buy-one-click-woocommerce" folder in your-domain / wp-content / plugins
3. Activate the plugin
4. Go to the menu item "WooCommerce" - "BuyOneClick" to configure the add-on

== Some possibility ==
* For easy change of form styles, we can place files from "plugins/buy-one-click-woocommerce/templates/css" in folders
 "uploads/buy-one-click-woocommerce" or "themes/your_template/buy-one-click-woocommerce"


== Screenshots ==

1. Button on the site.
2. Order form.
3. Settings add-on.
4. Orders.
5. Sample Orders with Supplement for Variable Items.
6. An example of an added product with the option to add to WooCommerce


== Changelog ==
= 2.2.9 =
* Checking compatibility with new versions of WooCommerce
= 2.2.8 =
* WooCommerce HPOS
= 2.2.7 =
* fix getProductPrice
= 2.2.6 =
* fix product name
= 2.2.5 =
* Added polylang support for multilingual sites
* Added plugin "Woo Discount Rules" support for product pricing
= 2.2.4 =
* Improved compatibility with the plugin "coderun-buy-one-click-woocommerce-variations"
* Added new hooks
= 2.2.3 =
* Removed part of the old code
* Removed some of the unnecessary information from the settings
* Fixed a bug with duplicate orders when calculating the price of goods
= 2.2.2 =
* Fixed a problem with creating a zero order and an item with an empty price
* Added import and export of the main plugin settings to a file
= 2.2.1 =
* Added currency to the order form
* Added the ability to specify the status of the created order in WooCommerce
* Code reorganization
* Added a shortcode parameter to specify the price with currency
* Changed the behavior of the form for selecting the quantity, added support for the setting - "Sell individually" in the product
= 2.2.0 =
* Deep processing of the code base, rethinking the architecture of the code and bringing it to modern standards. The functionality of the plugin has not changed.
= 2.1.4 =
* Fixing the zero price in the plugin's email template
= 2.1.3 =
* Fixed the get_customer_unique_id call for older versions of WooCommerce
* Checking the restrictions on sending the form redone in the session
= 2.1.2 =
* The order number for yandex.metrica is taken from WooCommerce
* Old code removed
* The full price is calculated using WooCommerce mechanisms without saving the order. This bug led to the creation of duplicates in CRM systems due to the occurrence of events in WooCommerce
* Solved the problem with creating two orders in WooCommerce
= 2.1.1 =
* The value for the e-commerce option that corrects the button error
= 2.1.0 =
* Added a setting for sending data to the Yandex Metrica E-commerce service
* A little code optimization
= 2.0.2 =
* Fixed loading of multiple files
* Fixed the display of links to files in the plugin's order table
= 2.0.1 =
* Fixed a fatal error due to lack of configuration
= 2.0.0 =
* Refactoring the code structure
* PHP below 7.4 is no longer supported, the plugin will cause fatal errors
* Generating a translation file
= 1.18.0 =
* Обновил вкладку для установки целей Метрик
* Добавлена справка по настройке целей
* Добавил доработку на новый функционал в будущем
= 1.17.1 =
* fix smsc service bug
= 1.17.0 =
* Изменена структура
* Исправлен баг с загрузкой файлов
= 1.16.3 =
* изменена автозагрузка классов
* изменена структура кода
= 1.16.2 =
* Удалена часть старого кода
= 1.16.1 =
* Изменена структура настроек SMS, необходимо заново настроить этот раздел
* Добавлена опция убирающая блок сообщения от плагина в письме WooCommerce
* Обновление кодовой базы, изменение порядка инициализации плагина
* Исправлены некоторые ошибки
= 1.16.0 =
* Удалена поддержка обработчика вариативных товаров в виде отдельного файла с кодом
* Добавлена поддержка обработчика вариативных товаров на основе дополнительного плагина "coderun-buy-one-click-woocommerce-variations"
* Добавлена новая страница настроек где можно указать JavaScript код который будет выполнен по событиям работы плагина
* Изменены названия некоторых методов в кодовой базе
* Сделана небольшая оптимизация
* Добавлено событие buy_one_click_woocommerce_start_load_core - событие перед основной инициализацией плагина (доступны классы, настройки, переменные)
= 1.15.4 =
* Добавлен composer с зависимостями и сторонними библиотеками
* Добавлена новая опция, для перенаправления на страницу оплаты после оформления заказа
* Добавлен лог ошибок во время работы плагина. Лог сохраняется в wp-content/uploads/buy-one-click-woocommerce
* Небольшая оптимизация кода
= 1.15.3 =
* Добавлена настройка для встраивания стилей плагина в html страницы
* Исправлены мелкие ошибки
* Небольшая оптимизация кода
= 1.15.2 =
* исправлено имя загружаемого файла
= 1.15.1 =
* К шорткоду [viewBuyButton] добавлен не обязательный параметр id. В параметр id необходимо указыать ид реального товара WooCommerce для размещения кнопки в любом месте сайта
* Небольшие правки ошибок
= 1.15 =
* Добавлена новая настройка, теперь покупателя можно отправить на страницу с информацией о совершённом заказе WooCommerce
* Небольшая оптимизация кода
= 1.14 =
* Плагин инициализируется на хуке wp_loaded
= 1.13 =
* Добавлен мод шаблона WooCommerce
* Добавлена отправка ссылки на файл в письме (оба шаблона), только при включеной опции
* Изменён порядок сохранения заказа и срабатывания хуков
* Плагин инициализируется на хуке woocommerce_init, вместо wp
= 1.12 =
* Добавлена настройка связанная с количеством товаров. Форму можно включить/отключить в настройках
* Появилась возможность в форме выбрать количество товаров
* Мелкие фиксы кода
= 1.11 =
* Добавлена настройка о цене с учётом налога в отправляемом письме
* Класс BuyJavaScript переименован в Ajax
* В клас Ajax добавлен namespace
* Изменена кодировка создаваемой таблицы заказов плагина (для совместимости)
* Прочие правки
= 1.10.9 =
* Улучшена совместимость с дополнением для вариативных товаров и кнопки по шорткоду (необходимое дополнение вариации 1.12)
= 1.10.8 =
* Исправлена ошибка в функции isset_woo_order
= 1.10.7 =
* Файл стилей general.css теперь подключается из wp-content/uploads/buy-one-click-woocommerce или из папки шаблона
= 1.10.6 =
* Мелкие правки кода
= 1.10.5 =
* Фикс двух кнопок при включенном режиме "Управление запасами" в товаре (фикс от @pluzhnov)
* Фикс js, кнопка не сработает если Woo пометил её как disabled
= 1.10.4 =
* Фикс бага предыдущего обновления(отображение нескольких кнопок в карточке)
= 1.10.3 =
* Фикс бага предыдущего обновления. Фикс от пользователя Telegram - BiJey
= 1.10.2 =
* Добавлено положение кнопки для товаров которых нет на складе и для основной кнопки включена опция "woocommerce_after_add_to_cart_button"

= 1.10.1 =
* Мелкие правки багов
* Добавлен новый хук buy_click_save_order_to_table
* История заказов плагина храниться в отдельной таблице (старое место хранения больше не используется)
* Старые заказы не будут видны в истории плагина

= 1.9.13 =
* Фикс кнопки, когда включен режим управления запасами

= 1.9.12 =
* Улучшена совместимость формы с мобильными устройствами
* Оптимизированны css файлы шаблонов


= 1.9.11 =
* fix с пересчётом цены

= 1.9.10 =
* Журнал заказов плагина связон с номером заказа Woo
* Можно удалить заказ Woo из журнала плагина
* Небольшие исправления кода

= 1.9.9 =
* Проверка на спам при помощи капчи. Зависит от плагина "Advanced noCaptcha & invisible Captcha (v2 & v3)"
* Новая настройка для включения\отключения использования капчи


= 1.9.8 =
* Оптимизация кода
* Добавлено поле nonce

= 1.9.7 =
* fix указания текущего пользователя в заказе woocommerce

= 1.9.6 =
* Исправлена опция "Редирект" после отправки формы
* Мелкая реоргиназация кода в сторону оптимизации

= 1.9.5 =
* Инициализация плагина теперь на событии wp, ранее было init

= 1.9.4 =
* Добавлена возможность отправки файлов через форму
* Улучшена читаемость кода
* Улучшена производительность кода
* Добавлены новые положения кнопок
* Добавлены хуки для фильтрации некоторых данных
* Улучшена совместимость с дополнением для вариативных товаров

= 1.9.3 =
* Оптимизация кода
* Уменьшенно количество запросов к БД
* Начат переход на новую структуру плагина
* Улучшена совместимость с дополнением для вариативных товаров

= 1.9.2 =
* Добавлен спинер на кнопку. При нажатии на кнопку будет работать "крутилка". Реализация на основе loading.io

= 1.9.1 =
* Исправлены некоторые ошибки

= 1.9 =
* Добавлена возможность перевода плагина на другие языки
* Исправлены мелкие ошибки
* Добавлена "галка" Согласие в форму заказа. Включается в настройках

= 1.8.9 =
* +1 стиль формы для соответствия с вашей темой WordPress
* +1 позиция кнопки
= 1.8.8 =
* Формат ввода номера телефона [jQuery Masked Input Plugin](https://github.com/digitalBush/jquery.maskedinput)
* Удалён собственный css класс стилей кнопки(теперь используются стили вашего шаблона)
* Новая опция для связи плагина с "запасами" товара Woo
* Два режима работы плагина (добавление в корзину и класическая кнопка)
* Можно модифицировать CSS, для этого нужно в папке вашей темы создать папку buy-one-click-woocommerce и в неё скопировать файлы из папки плагина templates/
* fix формы - спасибо пользователю [VladChV](https://zixn.ru/plagin-zakazat-v-odin-klik-dlya-woocommerce.html/comment-page-12#comment-54975)
= 1.8.6 =
* fix формы
* fix шорткода
* disabled button - при отправке формы и до ответа сервера
= 1.8.5 =
* Добавлен лимит на отправку формы, чаще чем N секунд форму отправить не получится
* Добавлена настройка управления лимитом и сообщением для лимита
= 1.8.4 =
* fix bug
= 1.8.3 =
* Email поле теперь приходит в письме
* Проверка обязательных полей на стороне php
= 1.8.2 =
* Возможность СМС уведомлений продавца магазина
* Добавлен ХУК "buy_click_new_order". Описание доступно на странице настроек плагина
= 1.8 =
* 500 - ошибка сервера при отправке формы
= 1.7 =
* Мелкие ошибки в работе плагина
= 1.6 =
* Исправлены мелкие ошибки в работе плагина
* Подготовка плагина к работе с вариациями
* Поле "дополнительно" из формы - теперь приходит в шаблон письма
= 1.5.1 = 
* Исправление мелких ошибок
* Добавлены варианты шаблонов модального окна в настройках
= 1.5 = 
* Исправление мелких ошибок
* Добавлен новый шорткод и настройка
* В целом старый добрый функционал не затронут для совместимости
= 1.4.1 =
* Улучшена работа кнопки в карточке товара в случаях когда под карточкой товара есть карусель похожих товаров
* Добавлен свой обработчик в head для обработки ajax
= 1.4 =
* Новая опция вывода кнопки купить в категории товара
* Некоторые переработки функций
* Появилась возможность вывести кнопку при помощи шорткода
= 1.3.1 =
* Подстановка ajax адреса
= 1.3 =
* Вызов формы для быстрого заказа теперь происходит по ajax, т.е она не присутствует в коде страницы сразу после загрузки, это не засоряет DOM дерево страницы
* Адрес ajax обработчика теперь берётся из вашего сайта (ранее было жёстко заданно)
* Новая опция, теперь заказы могут записываться в общую таблицу Woo. В таблицу плагина они будут попадать всегда
* Обновлена информационная вкладка "Автор"
= 1.2 =
* Добавлена поддержка СМС
= 1.1 =
* Исправлены некоторые ошибки в работе плагина
* Добавлена опция включения/отключения показа кнопки
* Добавлены опции «обязательные поля»
* Добавлены варианты поведения формы при отправке заказа
* В Шаблон email сообщения добавлены ФИО и Телефон клиента
= 1.0 =
* Релиз

