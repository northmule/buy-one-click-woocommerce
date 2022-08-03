<?php
if (!defined('ABSPATH')) {
    exit;
}
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\ValueObject\OrderDataForAdmin as OrderDataForAdminValueObject;
use Coderun\BuyOneClick\Utils\Order as UtilsOrder;

/** @var Core $this */
?>
<h3><?php _e('Orders via plugin', 'coderun-oneclickwoo'); ?> <?php echo Core::NAME_PLUGIN; ?></h3>
<p><?php _e('All orders sent via the button', 'coderun-oneclickwoo'); ?> "<?php echo $this->getCommonOptions()->getNameButton(); ?>"</p>
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
    <?php foreach (Coderun\BuyOneClick\Repository\Order::getInstance()->getOrders() as $order) { ?>
        <tr class="success order<?php echo $order->getId(); ?>">
            <th>
                <?php
                echo '<br>'.__('Plugin Order №', 'coderun-oneclickwoo').": {$order->getId()}";
                ?>
                <?php
              
                $orderData = new OrderDataForAdminValueObject(
                    json_decode($order->getForm(), true)
                );
                if ($order->getWooOrderId()) {
                    echo '<br>'.__('Woo Order №', 'coderun-oneclickwoo').": <a href='/wp-admin/post.php?post={$order->getWooOrderId()}&action=edit'>{$order->getWooOrderId()}</a>";
                }
                if ($orderData->getUuid()) {
                   echo sprintf('<p>Uuid: %s</p>', $orderData->getUuid());
                }
                ?>
            </th>
            <th><?php echo $order->getDateCreate()->format('d.m.Y H:i:s'); ?></th>
            <th><?php echo $order->getProductId(); ?></th>
            <th><?php echo $orderData->getUserName(); ?></th>
            <th><?php echo $orderData->getUserPhone(); ?></th>
            <th><?php echo $orderData->getUserEmail(); ?></th>
            <th>
                <?php echo $order->getProductName(); ?>
                <br>
                <?php echo $orderData->isProductIsVariable() ? $orderData->getVariationData() : ''; ?>
                <br>
                <?php
                    echo __('Quantity', 'coderun-oneclickwoo') . ': ' . $orderData->getQuantityProduct();
                
                    foreach ($orderData->getFiles() as $key => $urlFile) {
                        echo sprintf('<a href="%s" target="_blank">%s %s</a><br>', $urlFile, __('File' ,'coderun-oneclickwoo'), ++$key);
                    }
                    ?>
            </th>
            <th><?php echo $order->getProductPrice(); ?></th>
            <th><?php echo $orderData->getUserComment(); ?></th>
            <th><?php echo $orderData->getProductLinkAdmin(); ?></th>
            <th><?php
                $sms = json_decode($order->getSmsLog(), true);
                if (!empty($sms) && is_array($sms)) {
                    echo 'id:' . $sms[0] . '</br>' . __('Count sms', 'coderun-oneclickwoo') . ':' . $sms[1] . '</br>' . __('Cost of', 'coderun-oneclickwoo') . ':' . $sms[2] . '</br>' . __('Balance', 'coderun-oneclickwoo') . ':' . $sms[3];
                    if (isset($sms['debud'])) {
                        echo sprintf('<p>Debug: %s</p>', $sms['debud']);
                    }
                }
                ?></th>
            <th><a orderstat="<?php
                if ($order->getStatus() == 2) {
                    echo '2';
                } else {
                    echo '1';
                }
                ?>" class="updatestatus" id="<?php echo $order->getId(); ?>" href="<?php echo $url_tab . '#id=' . $order->getId(); ?>">
                    <?php
                    if ($order->getStatus() == 1) {
                        echo '<span class="glyphicon glyphicon-ban-circle">' . __('NOT', 'coderun-oneclickwoo') . '</span>';
                    } else {
                        echo '<span class="glyphicon glyphicon-ok-circle">' . __('OK', 'coderun-oneclickwoo') . '</span>';
                    }
                    ?>



                </a>
            </th>

            <th>
                <a class="removeorder" id="<?php echo $order->getId(); ?>" href="<?php echo $url_tab; ?>#id=<?php echo $order->getId(); ?>">
                    <span class="glyphicon glyphicon-remove-circle"><?php _e('OnlyPlugin', 'coderun-oneclickwoo'); ?></span>
                </a>
                <?php if (UtilsOrder::thereIsAWooCommerceOrder($order->getWooOrderId() ?? 0)) { ?>
                    <br><br>
                    <a class="removeorder_woo" data-plugin_id="<?php echo $order->getId(); ?>" data-woo_id="<?php echo $order->getWooOrderId(); ?>" href="<?php echo $url_tab; ?>#id=<?php echo $order->getWooOrderId(); ?>">
                        <span class="glyphicon glyphicon-remove-circle"><?php _e('OnlyWoo', 'coderun-oneclickwoo'); ?></span>
                    </a>
                <?php } ?>
            </th>
        </tr>
    <?php } ?>
    </tbody>



</table>