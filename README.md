## Buy one click woocommerce
<p>The plugin for WordPress +WooCommerce allows you to make purchases in one click. The plugin has many additional settings to control the form and the behavior of the form when placing an order.</p>

**Requirements**

* The latest version of WordPress
* The best online commerce plugin WooCommerce
* PHP >= 7.4 (From plugin version 2.0.0)
* A little patience to configure the plugin :)

License URI: http://www.apache.org/licenses/

### Filters plugins
You can add this code to the file function.php your theme, so that it would start working
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
[viewBuyButtonCustom id="your product ID" name="your product name" count="the quantity transferred with the purchase" price="price per unit of goods"]
// Exemple:
[viewBuyButtonCustom id="xxx01" name="Elon Reeve Musk Aircraft" count="1" price="9320000"]
[viewBuyButtonCustom id="code812-323" name="Cucumbers in a jart" count="1" price="600"]
... // Any of your data in the shortcode, sell anything
```

### Plugin Actions
* The action called after the order is created
```php
add_action('buy_click_new_order',
    static function (array $pluginOrder, array $orderField): void {
        // Here are your actions, if necessary
    },
    10 ,2);
```