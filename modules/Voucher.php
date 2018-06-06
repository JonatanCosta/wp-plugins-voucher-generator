<?php
/**
 * Created by PhpStorm.
 * User: jonatan
 * Date: 04/06/18
 * Time: 12:36
 */
session_start();
require_once 'Pages.php';
require_once 'Shortcodes.php';

/*
 * Plugin Init Function
 */
function initVoucher()
{
    add_action('admin_head', 'voucher_raibu_js');
    add_action('admin_head', 'voucher_raibu_css');
    add_action('admin_menu', 'add_menu_admin');
}

/*
 * Menu Voucher Raibu
 */
function add_menu_admin()
{
    add_menu_page('Voucher', 'Vouchers', 'manage_options', 'voucher', "vouchers_initial_page", 'dashicons-tickets-alt');
    add_submenu_page("voucher", __("Criar Voucher", "voucherpress"), __("Criar Voucher", "voucherpress"), "publish_posts", "vouchers-create", "voucher_create_voucher_page");
}

/*
 * Plugin JS Init
 */
function voucher_raibu_js()
{
    echo '
        <script type="text/javascript">
            var vp_siteurl = "' . get_option( "siteurl" ) . '";
        </script>
        <script type="text/javascript" src="' . plugin_dir_url( __FILE__ ) . '../js/index.js"></script>
    ';
}

/*
 * Plugin CSS
 */
function voucher_raibu_css()
{
    echo '
        <link rel="stylesheet" href="' . plugin_dir_url( __FILE__ ) . '../css/style-plugin.css">    
    ';
}

