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

    $perpage = 10;
    $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
    $start = ($perpage * $pagenum) - $perpage;

    $vouchers = get_vouchers($perpage, false, $start);

    echo '<h1>Vouchers</h1>';
    voucher_table_header(['ID','Nome','Descrição', 'Prefixo', 'Ação']);
    voucher_table_boddy($vouchers);
    voucher_table_footer();

    $page_links = paginate_links( [
        'base' => add_query_arg( 'pagenum', '%#%' ),
        'format' => '',
        'prev_text' => __( '&laquo;', 'dashicons-arrow-right-alt' ),
        'next_text' => __( '&raquo;', 'dashicons-arrow-right-alt' ),
        'total' => ( 39 / $perpage ),
        'current' => $pagenum
    ]);

    if ( $page_links ) {
        echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
    }
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
function get_vouchers( $num = 25, $all = false, $start = 0 )
{
    global $wpdb;
    $prefix = $wpdb->prefix;

    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }

    $showall = "0";
    if ( $all ) {
        $showall = "1";
    }

    $limit = "limit " . ( int ) $start . "," . ( int ) $num;
    if ( 0 == ( int ) $num ) {
        $limit = "";
    }

    $sql = $wpdb->prepare("select * from wp_vouchers ".$limit.";", $showall, $showall, time(), $showall, time(), 1);

    return $wpdb->get_results($sql);
}