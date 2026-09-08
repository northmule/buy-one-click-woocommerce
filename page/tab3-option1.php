<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if (!defined('ABSPATH')) {
    exit;
}
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\ValueObject\OrderDataForAdmin as OrderDataForAdminValueObject;
use Coderun\BuyOneClick\Utils\Order as UtilsOrder;

/** @var Core $this */
?>
<h3><?php esc_html_e('Orders via plugin', 'buy-one-click-woocommerce'); ?> <?php echo esc_html(Core::NAME_PLUGIN); ?></h3>
<p><?php esc_html_e('All orders sent via the button', 'buy-one-click-woocommerce'); ?> "<?php echo esc_html($this->getCommonOptions()->getNameButton()); ?>"</p>
<input type="button" class="btn btn-default btn-sm removeallorder" value="<?php esc_attr_e('Delete history', 'buy-one-click-woocommerce'); ?>"/>
<?php
$booc_url_tab = add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => 'orders'), 'admin.php');

?>
<table class="table table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th>№ </th>
        <th><?php esc_html_e('Date and time of addition', 'buy-one-click-woocommerce'); ?></th>
        <th><?php esc_html_e('Item Number', 'buy-one-click-woocommerce'); ?></th>
        <th><?php esc_html_e('Full name', 'buy-one-click-woocommerce'); ?></th>
        <th><?php esc_html_e('Phone', 'buy-one-click-woocommerce'); ?></th>
        <th><?php esc_html_e('Email', 'buy-one-click-woocommerce'); ?></th>
        <th><?php esc_html_e('Product Information', 'buy-one-click-woocommerce'); ?></th>
        <th><?php esc_html_e('Price', 'buy-one-click-woocommerce'); ?></th>
        <th><?php esc_html_e('Message', 'buy-one-click-woocommerce'); ?></th>
        <th><?php esc_html_e('Product', 'buy-one-click-woocommerce'); ?></th>
        <th><?php esc_html_e('SMS', 'buy-one-click-woocommerce'); ?></th>
        <th><?php esc_html_e('Status', 'buy-one-click-woocommerce'); ?></th>
        <th><?php esc_html_e('Remove', 'buy-one-click-woocommerce'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach (Coderun\BuyOneClick\Repository\Order::getInstance()->getOrders() as $order) { ?>
        <tr class="success order<?php echo esc_attr($order->getId()); ?>">
            <th>
                <?php
                echo '<br>' . esc_html__('Plugin Order №', 'buy-one-click-woocommerce') . ': ' . esc_html($order->getId());
                ?>
                <?php

                $booc_order_data = new OrderDataForAdminValueObject(
                    json_decode($order->getForm(), true)
                );
                if ($order->getWooOrderId()) {
                    $booc_woo_link = admin_url('post.php?' . http_build_query(['post' => $order->getWooOrderId(), 'action' => 'edit']));
                    echo '<br>' . esc_html__('Woo Order №', 'buy-one-click-woocommerce') . ": <a href='" . esc_url($booc_woo_link) . "'>" . esc_html($order->getWooOrderId()) . "</a>";
                }
                if ($booc_order_data->getUuid()) {
                    echo sprintf('<p>Uuid: %s</p>', esc_html($booc_order_data->getUuid()));
                }
                ?>
            </th>
            <th><?php echo esc_html($order->getDateCreate()->format('d.m.Y H:i:s')); ?></th>
            <th><?php echo esc_html($order->getProductId()); ?></th>
            <th><?php echo esc_html($booc_order_data->getUserName()); ?></th>
            <th><?php echo esc_html($booc_order_data->getUserPhone()); ?></th>
            <th><?php echo esc_html($booc_order_data->getUserEmail()); ?></th>
            <th>
                <?php echo esc_html($order->getProductName()); ?>
                <br>
                <?php echo esc_html($booc_order_data->isProductIsVariable() ? $booc_order_data->getVariationData() : ''); ?>
                <br>
                <?php
                    echo esc_html__('Quantity', 'buy-one-click-woocommerce') . ': ' . esc_html($booc_order_data->getQuantityProduct());

                    foreach ($booc_order_data->getFiles() as $booc_key => $booc_url_file) {
                        echo sprintf('<a href="%s" target="_blank">%s %s</a><br>', esc_url($booc_url_file), esc_html__('File' ,'buy-one-click-woocommerce'), esc_html(++$booc_key));
                    }
                    ?>
            </th>
            <th><?php echo esc_html($order->getProductPrice()); ?></th>
            <th><?php echo esc_html($booc_order_data->getUserComment()); ?></th>
            <th><?php echo esc_html($booc_order_data->getProductLinkAdmin()); ?></th>
            <th><?php
                $booc_sms = json_decode($order->getSmsLog(), true);
                if (!empty($booc_sms) && is_array($booc_sms)) {
                    echo 'id:' . esc_html($booc_sms[0]) . '</br>' . esc_html__('Count sms', 'buy-one-click-woocommerce') . ':' . esc_html($booc_sms[1]) . '</br>' . esc_html__('Cost of', 'buy-one-click-woocommerce') . ':' . esc_html($booc_sms[2]) . '</br>' . esc_html__('Balance', 'buy-one-click-woocommerce') . ':' . esc_html($booc_sms[3]);
                    if (isset($booc_sms['debud'])) {
                        echo sprintf('<p>Debug: %s</p>', esc_html($booc_sms['debud']));
                    }
                }
                ?></th>
            <th><a orderstat="<?php
                if ($order->getStatus() == 2) {
                    echo '2';
                } else {
                    echo '1';
                }
                ?>" class="updatestatus" id="<?php echo esc_attr($order->getId()); ?>" href="<?php echo esc_url($booc_url_tab . '#id=' . $order->getId()); ?>">
                    <?php
                    if ($order->getStatus() == 1) {
                        echo '<span class="glyphicon glyphicon-ban-circle">' . esc_html__('NOT', 'buy-one-click-woocommerce') . '</span>';
                    } else {
                        echo '<span class="glyphicon glyphicon-ok-circle">' . esc_html__('OK', 'buy-one-click-woocommerce') . '</span>';
                    }
                    ?>



                </a>
            </th>

            <th>
                <a class="removeorder" id="<?php echo esc_attr($order->getId()); ?>" href="<?php echo esc_url($booc_url_tab); ?>#id=<?php echo esc_attr($order->getId()); ?>">
                    <span class="glyphicon glyphicon-remove-circle"><?php esc_html_e('OnlyPlugin', 'buy-one-click-woocommerce'); ?></span>
                </a>
                <?php if (UtilsOrder::thereIsAWooCommerceOrder($order->getWooOrderId() ?? 0)) { ?>
                    <br><br>
                    <a class="removeorder_woo" data-plugin_id="<?php echo esc_attr($order->getId()); ?>" data-woo_id="<?php echo esc_attr($order->getWooOrderId()); ?>" href="<?php echo esc_url($booc_url_tab); ?>#id=<?php echo esc_attr($order->getWooOrderId()); ?>">
                        <span class="glyphicon glyphicon-remove-circle"><?php esc_html_e('OnlyWoo', 'buy-one-click-woocommerce'); ?></span>
                    </a>
                <?php } ?>
            </th>
        </tr>
    <?php } ?>
    </tbody>