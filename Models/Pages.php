<?php
/**
 * User: jonatan
 * Date: 04/06/18
 */

require_once 'Table.php';
/*
 * Lista e Pagina inicial
 */
function vouchers_initial_page()
{
    $vouchers = get_vouchers();

    echo '<h2>Vouchers</h2>';
    voucher_table_header(['ID','Nome','Descrição', 'Prefixo', 'Ação']);
    voucher_table_boddy($vouchers);
    voucher_table_footer();
}

/*
 * Criação de Vouchers
 */
function voucher_create_voucher_page()
{
    echo '<h2> Criação de Vouchers </h2>';
}

/*
 *  Get Vouchers
 */
function get_vouchers( $num = 25, $all = 0, $start = 0 )
{
    global $wpdb;
    $prefix = $wpdb->prefix;

    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }

    $sql = $wpdb->prepare("select * from wp_vouchers;", $all, $all, time(), $all, time(), 1);

    return $wpdb->get_results($sql);
}