<?php
if (!defined('ABSPATH')) {
    exit;
}
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Help;
?>
<h3><?php _e('Orders via plugin', 'coderun-oneclickwoo'); ?> <?php echo Core::NAME_PLUGIN; ?></h3>
<p><?php _e('All orders sent via the button', 'coderun-oneclickwoo'); ?> "<?php echo  Core::getInstance()->getOption('namebutton'); ?>"</p>
<input type="button" class="btn btn-default btn-sm removeallorder" value="<?php _e('Delete history', 'coderun-oneclickwoo'); ?>"/>
<?php
$url_tab = add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => 'orders'), 'admin.php');

?>
<table class="table table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th>№ </th>
        <th><?php _e('Date and time of addition', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Item Number', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Full name', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Phone', 'coderun-oneclickwoo'); ?></th>
        <th>Email</th>
        <th><?php _e('Product Information', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Price', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Message', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Product', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('SMS', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Status', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Remove', 'coderun-oneclickwoo'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach (Coderun\BuyOneClick\Order::getInstance()->get_orders() as $order) { ?>
        <tr class="success order<?php echo $order['id']; ?>">
            <th>
                <?php
                echo '<br>'.__('Plugin Order №', 'coderun-oneclickwoo').": {$order['id']}";
                ?>
                <?php
                if(!empty($order['woo_order_id'])) {
                    echo '<br>'.__('Woo Order №', 'coderun-oneclickwoo').": <a href='/wp-admin/post.php?post={$order['woo_order_id']}&action=edit'>{$order['woo_order_id']}</a>";
                }
                $form = json_decode($order['form'],true);
                $sms = json_decode($order['sms_log'], true);
                ?>
            </th>
            <th><?php echo $order['date_create']; ?></th>
            <th><?php echo $order['product_id']; ?></th>
            <th><?php echo $form['user_name']; ?></th>
            <th><?php echo $form['user_phone']; ?></th>
            <th><?php echo $form['user_email']; ?></th>
            <th>
                <?php echo $form['product_name']; ?>
                <br>
                <?php
                if(isset($form['quantity_product'])) {
                    echo __('Quantity', 'coderun-oneclickwoo') . ': ' . $form['quantity_product'];
                }
                ?>
            </th>
            <th><?php echo $order['product_price']; ?></th>
            <th><?php echo $form['user_cooment']; ?></th>
            <th><?php echo isset($form['product_link_admin']) ? $form['product_link_admin'] : ''; ?></th>
            <th><?php
                if (!empty($sms['sms_log']) && is_array($sms['sms_log'])) {
                    echo 'id:' . $sms[0] . '</br>' . __('Count.sms', 'coderun-oneclickwoo') . ':' . $sms[1] . '</br>' . __('Cost of', 'coderun-oneclickwoo') . ':' . $sms[2] . '</br>' . __('Balance', 'coderun-oneclickwoo') . ':' . $sms[3];
                }
                ?></th>
            <th><a orderstat="<?php
                if ($order['status'] == 2) {
                    echo '2';
                } else {
                    echo '1';
                }
                ?>" class="updatestatus" id="<?php echo $order['id']; ?>" href="<?php echo $url_tab . '#id=' . $order['id']; ?>">
                    <?php
                    if ($order['status'] == 1) {
                        echo '<span class="glyphicon glyphicon-ban-circle">' . __('NOT', 'coderun-oneclickwoo') . '</span>';
                    } else {
                        echo '<span class="glyphicon glyphicon-ok-circle">' . __('OK', 'coderun-oneclickwoo') . '</span>';
                    }
                    ?>



                </a>
            </th>

            <th>
                <a class="removeorder" id="<?php echo $order['id'] ?>" href="<?php echo $url_tab; ?>#id=<?php echo $order['id']; ?>">
                    <span class="glyphicon glyphicon-remove-circle"><?php _e('OnlyPlugin', 'coderun-oneclickwoo'); ?></span>
                </a>
                <?php if(!empty($order['woo_order_id']) && Help::getInstance()->isset_woo_order($order['woo_order_id'])) { ?>
                    <br><br>
                    <a class="removeorder_woo" data-plugin_id="<?php echo $order['id']; ?>" data-woo_id="<?php echo $order['woo_order_id'] ?>" href="<?php echo $url_tab; ?>#id=<?php echo $order['woo_order_id']; ?>">
                        <span class="glyphicon glyphicon-remove-circle"><?php _e('OnlyWoo', 'coderun-oneclickwoo'); ?></span>
                    </a>
                <?php } ?>
            </th>
        </tr>
    <?php } ?>
    </tbody>



</table>