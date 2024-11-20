<?php
if (!defined('ABSPATH')) {
    exit;
}

use Coderun\BuyOneClick\Constant\Pages;
use Coderun\BuyOneClick\Core;

$core = Core::getInstance();
?>
<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
    <a class="nav-tab <?php esc_html_e($core->getCssOfActiveTab(Pages::GENERAL)); ?>" href="<?php esc_html_e( add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => Pages::GENERAL), 'admin.php')); ?>"><span class="glyphicon glyphicon-cog"></span> <?php esc_html_e('General', 'coderun-oneclickwoo'); ?></a>
    <a class="nav-tab <?php esc_html_e($core->getCssOfActiveTab(Pages::NOTIFICATION)); ?>" href="<?php esc_html_e(add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => Pages::NOTIFICATION), 'admin.php')); ?>"><span class="glyphicon glyphicon-envelope"></span> <?php esc_html_e('Notifications', 'coderun-oneclickwoo'); ?></a>
    <a class="nav-tab <?php esc_html_e($core->getCssOfActiveTab(Pages::ORDERS)); ?>" href="<?php esc_html_e(add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => Pages::ORDERS), 'admin.php')); ?>"><span class="glyphicon glyphicon-list"></span> <?php esc_html_e('Orders', 'coderun-oneclickwoo'); ?></a>
    <a class="nav-tab <?php esc_html_e($core->getCssOfActiveTab(Pages::MARKETING)); ?>" href="<?php esc_html_e(add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => Pages::MARKETING), 'admin.php')); ?>"><span class="glyphicon glyphicon-tent"></span> <?php esc_html_e('Marketing', 'coderun-oneclickwoo'); ?></a>
<!--    <a class="nav-tab --><?php //$core->adminActiveTab('design_form');?><!--" href="--><?php //echo add_query_arg(array('page' => Core::URL_SUB_MENU, 'tab' => 'design_form'), 'admin.php');?><!--"><span class="glyphicon glyphicon-tent"></span> --><?php //esc_html_e('Design form', 'coderun-oneclickwoo');?><!--</a>-->
</h2>
<div class="wrap">
    <?php $core->showPage(); //Показать страницу в зависимости от закладки?>
</div>


