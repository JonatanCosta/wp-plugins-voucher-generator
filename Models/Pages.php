<?php
/**
 * User: jonatan
 * Date: 04/06/18
 */
require_once 'Table.php';
require_once 'Utils.php';

/*
 * Lista e Pagina inicial
 */
function vouchers_initial_page()
{
    $perpage = 5;
    $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
    $start = ($perpage * $pagenum) - $perpage;

    $vouchers = get_vouchers($perpage, false, $start);
    $countVouchers = get_vouchers_count();

    echo '<h1>Vouchers</h1>';

    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        session_unset($_SESSION['message']);
    }

    voucher_table_header(['# ID','Nome','Descrição', 'Prefixo', 'Ação']);
    voucher_table_boddy($vouchers);
    voucher_table_footer();

    $page_links = paginate_links( [
        'base' => add_query_arg( 'pagenum', '%#%' ),
        'format' => '',
        'prev_text' => __( '&laquo;', 'dashicons-arrow-right-alt' ),
        'next_text' => __( '&raquo;', 'dashicons-arrow-right-alt' ),
        'total' => ( ($countVouchers == 6 ? ($countVouchers + 6) : $countVouchers) / $perpage ),
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
    voucher_form();
}

// Action post
add_action('admin_post_custom_action_hook', 'register_voucher');

function register_voucher()
{
    try {
        global $wpdb;
        $prefix = get_db_prefix();

        $wpdb->insert($prefix.'vouchers', [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'codeprefix' => $_POST['prefix'],
            'deleted' => 0
        ]);

        create_message_response('Voucher criado com sucesso!', 1);
        return header( "Location: admin.php?page=voucher");

    } catch (\Exception $exception) {
        create_message_response('Ocorreu um erro ao criar a menssagem!', 2);
        return header( "Location: admin.php?page=voucher");
    }
}

/*
 * Form de cadastro ou alteração
 */
function voucher_form($obj = null)
{
    if ($obj) {
        return;
    }

    echo '
        <form action="admin-post.php" method="post" id="voucherform">
            <input name="action" type="hidden" value="custom_action_hook"/>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Nome:</label>
                                <input type="text" name="name" size="30" value="" id="title" spellcheck="true" autocomplete="off" placeholder="Digite o nome do voucher" required>
                            </div>
                        </div>
                    </div>
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Cód. Prefixo, Ex: <strong>RAIBU</strong>123:</label>
                                <input type="text" name="prefix" size="30" value="" id="title" spellcheck="true" autocomplete="off" placeholder="Digite um prefixo" required>
                            </div>
                        </div>
                    </div>
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Descrição:</label>
                                <textarea type="text" name="description" value="" id="title" spellcheck="true" autocomplete="off" placeholder="Digite a descrição do voucher" cols="100" style="min-height: 15vh;" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div id="post-body-content">
                        <button class="button button-large button-primary pull right" type="submit">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>  
    ';
}

/*
 *  Get All Vouchers
 */
function get_vouchers( $num = 25, $all = false, $start = 0 )
{
    global $wpdb;
    $prefix = get_db_prefix();

    $showall = "0";
    if ( $all ) {
        $showall = "1";
    }

    $limit = "limit " . ( int ) $start . "," . ( int ) $num;
    if ( 0 == ( int ) $num ) {
        $limit = "";
    }

//    $query = $wpdb->query("select * from " . $prefix . "vouchers ".$limit.";", $showall, $showall, time(), $showall, time(), 1);
    return $wpdb->get_results("select * from " . $prefix . "vouchers where deleted = 0 ".$limit.";");
}

/*
 * Count off Vouchers
 */

function get_vouchers_count()
{
    global $wpdb;
    $prefix = get_db_prefix();
    $sql = "select count(id) from " . $prefix . "vouchers;";
    return $wpdb->get_var( $sql );
}