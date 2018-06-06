<?php
/**
 * User: jonatan
 * Date: 04/06/18
 */
require_once 'Table.php';
require_once 'Utils.php';
require_once 'Actions.php';

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

    $page_links = paginate_links([
        'base' => add_query_arg( 'pagenum', '%#%' ),
        'format' => '',
        'prev_text' => __( '&laquo;', 'dashicons-arrow-right-alt' ),
        'next_text' => __( '&raquo;', 'dashicons-arrow-right-alt' ),
        'total' => ( ($countVouchers >= 6 ? ($countVouchers + 6) : $countVouchers) / $perpage ),
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

/*
 * Edição de vouchers
 */
function update_voucher_page()
{
    if (!isset($_GET['id']) || $_GET['id'] == null) {
        create_message_response('Ocorreu um erro ao editar!', 2);
        redirect('admin.php?page=voucher');
        exit();
    }

    $voucher = get_voucher($_GET['id']);

    if (!$voucher) {
        create_message_response('Ocorreu um erro ao editar!', 2);
        redirect('admin.php?page=voucher');
        exit();
    }

    echo '<h2>Edição de Voucher</h2>';
    echo voucher_form($voucher);
}

/*
 * Page Edit
 */
add_action('admin_menu', 'page_edit');

function page_edit() {
    add_submenu_page(
        null,
        'Edição do Vouchers',
        'Edição do Vouchers',
        'manage_options',
        'update-voucher',
        'update_voucher_page'
    );
}


/*
 * Form de cadastro ou alteração
 */
function voucher_form($obj = null)
{

    echo '<form action="admin-post.php" method="post" id="voucherform">';

    if ($obj) {
        echo '<input name="action" type="hidden" value="update_voucher"/>';
        echo '<input name="id" type="hidden" value="'.$obj->id.'"/>';
    } else {
        echo '<input name="action" type="hidden" value="register_voucher"/>';
    }

    echo '<div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Nome:</label>
                                <input type="text" name="name" size="30" value="'.(isset($obj) ? $obj->name : '').'" id="title" spellcheck="true" autocomplete="off" placeholder="Digite o nome do voucher" required>
                            </div>
                        </div>
                    </div>
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Cód. Prefixo, Ex: <strong>RAIBU</strong>123:</label>
                                <input type="text" name="prefix" size="30" value="'.(isset($obj) ? $obj->codeprefix : '').'" id="title" spellcheck="true" autocomplete="off" placeholder="Digite um prefixo" required>
                            </div>
                        </div>
                    </div>
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Descrição:</label>
                                <textarea type="text" name="description" id="title" spellcheck="true" autocomplete="off" placeholder="Digite a descrição do voucher" cols="100" style="min-height: 15vh;" required>'.(isset($obj) ? $obj->description : '').'</textarea>
                            </div>
                        </div>
                    </div>
                    <div id="post-body-content">
                        <button class="button button-large button-primary pull right" type="submit">'.(isset($obj) ? 'Alterar' : 'Cadastrar').'</button>
                    </div>
                </div>
            </div>
        </form>  
    ';
}

