<?php
if (!defined('ABSPATH')) {
    exit;
}

use Coderun\BuyOneClick\Constant\Pages;
use Coderun\BuyOneClick\Core;

$core = Core::getInstance();
?>
<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
    <a class="nav-tab <?php echo $core->getCssOfActiveTab(Pages::GENERAL); ?>" href="<?php echo add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => Pages::GENERAL), 'admin.php'); ?>"><span class="glyphicon glyphicon-cog"></span> <?php _e('General', 'coderun-oneclickwoo'); ?></a>
    <a class="nav-tab <?php echo $core->getCssOfActiveTab(Pages::NOTIFICATION); ?>" href="<?php echo add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => Pages::NOTIFICATION), 'admin.php'); ?>"><span class="glyphicon glyphicon-envelope"></span> <?php _e('Notifications', 'coderun-oneclickwoo'); ?></a>
    <a class="nav-tab <?php echo $core->getCssOfActiveTab(Pages::ORDERS); ?>" href="<?php echo add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => Pages::ORDERS), 'admin.php'); ?>"><span class="glyphicon glyphicon-list"></span> <?php _e('Orders', 'coderun-oneclickwoo'); ?></a>
    <a class="nav-tab <?php echo $core->getCssOfActiveTab(Pages::MARKETING); ?>" href="<?php echo add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => Pages::MARKETING), 'admin.php'); ?>"><span class="glyphicon glyphicon-tent"></span> <?php _e('Marketing', 'coderun-oneclickwoo'); ?></a>
<!--    <a class="nav-tab --><?php //$core->adminActiveTab('design_form');?><!--" href="--><?php //echo add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => 'design_form'), 'admin.php');?><!--"><span class="glyphicon glyphicon-tent"></span> --><?php //_e('Design form', 'coderun-oneclickwoo');?><!--</a>-->
</h2>
<div class="wrap">
    <?php $core->showPage(); //Показать страницу в зависимости от закладки?>
</div>


