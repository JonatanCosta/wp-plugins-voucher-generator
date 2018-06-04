<?php
/**
 * Created by PhpStorm.
 * User: jonatan
 * Date: 04/06/18
 * Time: 12:36
 */
require_once 'Pages.php';

/*
 * Plugin Init
 */
function initVoucherRaibu()
{
    add_action('wp_head', 'voucher_raibu_js');
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