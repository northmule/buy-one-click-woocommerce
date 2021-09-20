<?php
if (!defined('ABSPATH')) {
    exit;
}

use Coderun\BuyOneClick\Core;

$core = Core::getInstance();
?>
<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
    <a class="nav-tab <?php $core->adminActiveTab('general'); ?>" href="<?php echo add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => 'general'), 'admin.php'); ?>"><span class="glyphicon glyphicon-cog"></span> <?php _e('General', 'coderun-oneclickwoo'); ?></a>
    <a class="nav-tab <?php $core->adminActiveTab('notification'); ?>" href="<?php echo add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => 'notification'), 'admin.php'); ?>"><span class="glyphicon glyphicon-envelope"></span> <?php _e('Notifications', 'coderun-oneclickwoo'); ?></a>
    <a class="nav-tab <?php $core->adminActiveTab('orders'); ?>" href="<?php echo add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => 'orders'), 'admin.php'); ?>"><span class="glyphicon glyphicon-list"></span> <?php _e('Orders', 'coderun-oneclickwoo'); ?></a>
    <a class="nav-tab <?php $core->adminActiveTab('marketing'); ?>" href="<?php echo add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => 'marketing'), 'admin.php'); ?>"><span class="glyphicon glyphicon-tent"></span> <?php _e('Marketing', 'coderun-oneclickwoo'); ?></a>
<!--    <a class="nav-tab --><?php //$core->adminActiveTab('design_form'); ?><!--" href="--><?php //echo add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => 'design_form'), 'admin.php'); ?><!--"><span class="glyphicon glyphicon-tent"></span> --><?php //_e('Design form', 'coderun-oneclickwoo'); ?><!--</a>-->
    <a class="nav-tab <?php $core->adminActiveTab('help'); ?>" href="<?php echo add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => 'help'), 'admin.php'); ?>"><span class="glyphicon glyphicon-thumbs-up"></span> <?php _e('Author', 'coderun-oneclickwoo'); ?></a>
</h2>
<div class="wrap">
    <?php $core->showPage(); //Показать страницу в зависимости от закладки ?>
</div>


