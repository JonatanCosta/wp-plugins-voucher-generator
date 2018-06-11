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
    date_default_timezone_set('America/Sao_Paulo');
    add_action('wp_head', 'voucher_wp_js');
    add_action('wp_head', 'voucher_wp_css');
    add_action('admin_head', 'voucher_admin_js');
    add_action('admin_head', 'voucher_admin_css');
    add_action('admin_menu', 'add_menu_admin');
}

/*
 * Menu Voucher Raibu
 */
function add_menu_admin()
{
    add_menu_page('Voucher', 'Vouchers', 'manage_options', 'voucher', "vouchers_initial_page", 'dashicons-tickets-alt');
    add_submenu_page('voucher', 'Criar Voucher', 'Criar Voucher', 'publish_posts', 'vouchers-create', 'voucher_create_voucher_page');
    add_submenu_page('voucher', 'Utilizar Código', 'Utilizar Código', 'publish_posts', 'vouchers-use', 'voucher_use_voucher_page');
    add_submenu_page('voucher', 'Confirgurações do Voucher', 'Configurações', 'publish_posts', 'voucher-config', 'voucher_config_page');
}

/*
 * Plugin JS Admin Init
 */
function voucher_admin_js()
{
    echo '
        <script type="text/javascript">
            var vp_siteurl = "' . get_option( "siteurl" ) . '";
        </script>
        <script type="text/javascript" src="' . plugin_dir_url( __FILE__ ) . '../js/admin.js"></script>
    ';
}

/*
 * Plugin JS Admin Init
 */
function voucher_wp_js()
{
    echo '
        <script type="text/javascript">
            var vp_siteurl = "' . get_option( "siteurl" ) . '";
        </script>
        <script type="text/javascript" src="' . plugin_dir_url( __FILE__ ) . '../js/wp.js"></script>
    ';
}

/*
 * Plugin CSS
 */
function voucher_admin_css()
{
    echo '
        <link rel="stylesheet" href="' . plugin_dir_url( __FILE__ ) . '../css/admin.css">    
    ';
}

/*
 * Plugin CSS
 */
function voucher_wp_css()
{
    echo '
        <link rel="stylesheet" href="' . plugin_dir_url( __FILE__ ) . '../css/wp.css">    
    ';
}

