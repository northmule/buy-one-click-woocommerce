# Buy one click woocommerce
<p>The plugin for WordPress +WooCommerce allows you to make purchases in one click. The plugin has many additional settings to control the form and the behavior of the form when placing an order.</p>

**Requirements**

* The latest version of WordPress
* The best online commerce plugin WooCommerce
* PHP >= 7.4 (From plugin version 2.0.0)
* A little patience to configure the plugin :)

License URI: http://www.apache.org/licenses/

### Filters

All filters accept and return the documented value. Add them to `functions.php` of your theme.

#### File upload filters

* Allow uploading via the zip file extension form
```php
add_filter('coderun_oneclickwoo_file_valid_extension',
        static function (array $item): array {
                $item[] = 'zip';
                //... any other types
                return $item;
        });
```

* Increase the file size limit
```php
add_filter('coderun_oneclickwoo_file_valid_size',
    static function ($size): int {
        return 100000000; //bytes
    });
```

* Add allowed mime types
```php
add_filter('coderun_oneclickwoo_file_valid_mime_types',
    static function (array $mime): array {
        $mime[] = 'application/vnd.oasis.opendocument.text';
        //... any other mime
        return $mime;
    });
```

* Change the names of uploaded files
```php
add_filter('coderun_oneclickwoo_file_name',
    static function (string $newName, string $originalName): string {
        // ... Here is your magic over the name
        return $newName;
    });
```

* Override the folder where uploaded files are saved
```php
add_filter('coderun_oneclickwoo_file_load_folder_path',
    static function (array $path): array {
        // $path = ['path' => '<absolute fs path>', 'url' => '<public url>']
        $path['path'] = '/custom/upload/dir';
        $path['url'] = 'https://example.com/uploads/';
        return $path;
    });
```

#### Order form filters

* Modify the HTML of the whole order form
```php
add_filter('coderun_oneclickwoo_order_form_html',
    static function (string $form): string {
        // $form — markup of the assembled order form
        return $form;
    });
```

* Modify the HTML of the quantity field
```php
add_filter('coderun_oneclickwoo_quantity_form_html',
    static function (string $form): string {
        // $form — markup of the quantity field
        return $form;
    });
```

#### Frontend JavaScript variables filter

* Extend/change the JS variables printed on the frontend
```php
add_filter('coderun_oneclickwoo_init_front_variables',
    static function (array $variables): array {
        $variables['my_custom_key'] = 'some value';
        return $variables;
    });
```

#### Variable products filters

* Return readable data about the variation selected in the form
```php
add_filter('coderun_oneclickwoo_data_about_selected_variation_from_form',
    static function (array $form): string {
        // $form — submitted form data
        return 'Color: red, Size: M';
    });
```

* Return the ID of the selected variation
```php
add_filter('coderun_oneclickwoo_get_id_of_selected_variation',
    static function (array $form): int {
        // $form — submitted form data
        return 123;
    });
```

* Define whether the variations plugin integration is active
```php
add_filter('coderun_oneclickwoo_variations_plugin_is_used',
    static function ($context): bool {
        return true;
    });
```

Full list of hooks: [`Coderun\BuyOneClick\Utils\Hooks`](src/Utils/Hooks.php)

### Actions

* Fired when the plugin is loaded
```php
add_action('coderun_oneclickwoo_load',
    static function (): void {
        // ...
    });
```

* Fired when the plugin core starts loading (during `init`)
```php
add_action('coderun_oneclickwoo_start_load_core',
    static function (): void {
        // ...
    });
```

* The action called after the order is created
```php
add_action('coderun_oneclickwoo_new_order',
    static function (array $pluginOrder, array $orderLog): void {
        // $pluginOrder — order data
        // $orderLog — plugin log/journal
        // Here are your actions, if necessary
    },
    10, 2);
```

* The action fired after the order is saved to the database table
```php
add_action('coderun_oneclickwoo_save_order_to_table',
    static function (int $orderId): void {
        // $orderId — ID of the saved order
    });
```

* Fired before drawing the quick-order button in a variable product card
```php
add_action('coderun_oneclickwoo_before_drawing_order_button_only_for_variable_products',
    static function ($context): void {
        // $context — current product context object
    });
```

### Available shortcodes

* A shortcode that can work where there is a withdrawal of goods.
```
[viewBuyButton]
```
* A shortcode with a valid WooCommerce product ID. Can be used anywhere on the site
```
[viewBuyButton id="you_product_id"]
// Exemple:
[viewBuyButton id="10"]
[viewBuyButton id="674"]
...
```
* The shortcode for your product. The product may not exist in your online store, all product parameters are transmitted via a shortcode
```
[viewBuyButtonCustom id="your product ID" name="your product name" count="the quantity transferred with the purchase" price="price per unit of goods" price_with_currency="price with currency to display"]
// Exemple:
[viewBuyButtonCustom id="xxx01" name="Elon Reeve Musk Aircraft" count="1" price="9320000"]
[viewBuyButtonCustom id="code812-323" name="Cucumbers in a jart" count="1" price="600" price_with_currency="600 USD"]
... // Any of your data in the shortcode, sell anything
```

## Plugin Images

### The order form generated by the plugin
The set of fields can be changed through the plugin settings, as well as make them mandatory.

![The order form generated by the plugin](https://github.com/northmule/buy-one-click-woocommerce/blob/master/.github/images/OrderForm.jpg?raw=true)

### Purchase button in the product card

In the plugin settings, you can change the position of the button, as well as place the button in the product catalog

![Purchase button in the product card](https://github.com/northmule/buy-one-click-woocommerce/blob/master/.github/images/ProductCard.jpg?raw=true)

### Part of the administrative panel of the plugin
The plugin has many settings related to the display of the button, the design of the form. There is a section with orders.

![Part of the administrative panel of the plugin](https://github.com/northmule/buy-one-click-woocommerce/blob/master/.github/images/PluginSettings.jpg?raw=true)
