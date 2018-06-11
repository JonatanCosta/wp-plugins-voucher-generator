<?php
/**
 * User: Jonatan Costa da Rosa
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
    $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
    $start = ($perpage * $pagenum) - $perpage;

    $vouchers = get_vouchers($perpage, false, $start);
    $countVouchers = get_vouchers_count();

    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        session_unset($_SESSION['message']);
    }

    echo '<div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content">
                <h1>Vouchers: </h1>
                ';
    voucher_table_header(['ID', 'Nome', 'Descrição', 'Prefixo', 'Qtd. por dia', 'Ação']);
    voucher_table_boddy($vouchers);
    voucher_table_footer();

    $page_links = paginate_links([
        'base' => add_query_arg('pagenum', '%#%'),
        'format' => '',
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
        'total' => (($countVouchers >= 6 ? ($countVouchers + 6) : $countVouchers) / $perpage),
        'current' => $pagenum
    ]);

    if ($page_links) {
        echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
    }
    echo '</div>
            <div class="post-body-content">
                <h1>Ações: </h1>
                <a class="button button-primary button-hero" href="?page=vouchers-use">Utilizar Código</a>
                <a class="button button-active button-hero">Lista de E-mails CSV</a>
            </div>
            </div>
          </div>';
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

function page_edit()
{
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
        echo '<input name="id" type="hidden" value="' . $obj->id . '"/>';
    } else {
        echo '<input name="action" type="hidden" value="register_voucher"/>';
    }

    echo '<div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Nome:</label>
                                <input type="text" name="name" size="30" value="' . (isset($obj) ? $obj->name : '') . '" id="title" spellcheck="true" autocomplete="off" placeholder="Digite o nome do voucher" required>
                            </div>
                        </div>
                    </div>
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Cód. Prefixo, Ex: <strong>RAIBU</strong>123:</label>
                                <input type="text" name="prefix" size="30" value="' . (isset($obj) ? $obj->codeprefix : '') . '" id="title" spellcheck="true" autocomplete="off" placeholder="Digite um prefixo" required>
                            </div>
                        </div>
                    </div>
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Gerações por dia, <b>digite 0 para ilimitado</b>:</label>
                                <input type="number" name="generates" size="30" value="' . (isset($obj) ? $obj->generates_per_day : '') . '" id="title" spellcheck="true" autocomplete="off" placeholder="Digite um número de códigos que podem ser gerados por dia." required>
                            </div>
                        </div>
                    </div>
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Descrição:</label>
                                <textarea type="text" name="description" id="title" spellcheck="true" autocomplete="off" placeholder="Digite a descrição do voucher" cols="100" style="min-height: 15vh;" required>' . (isset($obj) ? $obj->description : '') . '</textarea>
                            </div>
                        </div>
                    </div>
                    <div id="post-body-content">
                        <button class="button button-large button-primary pull right" type="submit">' . (isset($obj) ? 'Alterar' : 'Cadastrar') . '</button>
                    </div>
                </div>
            </div>
        </form>  
    ';
}

/*
 * Use Voucher Code Page
 */
function voucher_use_voucher_page()
{
    echo '<h2>Utilizar Código</h2>';
    form_use_code();
}

/*
 * Use Voucher Form
 */
function form_use_code()
{
    echo '
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content" class="box-send-code">
                    <div id="titlediv">
                        <div id="titlewrap">
                            <label>Código:</label>
                            <input type="text" name="voucher_code" size="30" value="" id="title" spellcheck="true" autocomplete="off" placeholder="Digite o código do voucher" required>
                             <div id="codeHelp" class="form-group hidden">
                                <span class="label label-danger form-text text-muted" style="border-radius: 0px; margin: 5px auto 5px auto; display: block;">
                                    <p style="margin: 0px;"></p>
                                </span>
                            </div>
                            <button class="button button-hero button-active btn-use-code" type="submit" style="width: 100%">Utilizar Código</button>
                        </div>
                    </div>
                </div>
                <div id="post-body-content" class="box-details-voucher hidden">
                    <h1>Cupom: RAIBU10</h1>
                    <p>
                        Receba 20% de desconto em toda a compra na Raibu.
                    </p>
                    <div class="label label-success form-text text-muted" style="border-radius: 0px; margin: 5px auto 5px auto; display: block;">
                        <p style="margin: 0px;">Cupom utilizado com sucesso!</p>
                    </div>
                    <a href="admin.php?page=vouchers-use" class="button button-primary pull right">Utilizar outro código</a>
                </div>
            </div>
        </div>
    ';
}

/*
 * Config. voucher page
 */
function voucher_config_page()
{
    $configs = get_voucher_config();

    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        session_unset($_SESSION['message']);
    }

    echo '
        <h1>Configurações</h1>
   ';

    form_config($configs);


}

/*
 * Form to configure voucher
 */
function form_config($config)
{
    echo '<form action="admin-post.php" method="post" id="voucherform">';
    echo '<input name="action" type="hidden" value="configure_voucher"/>';
    echo '<div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Logo URL: <strong>*Max. Size: 500x150*</strong></label>
                                <input type="text" name="logo_url" size="30" value="' . $config->logo_url . '" id="title" spellcheck="true" autocomplete="off" placeholder="Digite a URL da imagem" required>
                                <a href="' . $config->logo_url . '" target="_blank">Visualizar</a>
                            </div>
                        </div>
                    </div>
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                               ' . wp_editor($config->terms, 'voucher-config-editor', [
            'editor_height' => 300,
            'textarea_name' => 'terms',
            'quicktags' => ['buttons' => 'strong,em,del,ul,ol,li,close,br'],
            'media_buttons' => false,
            'tabindex' => 0
        ]) . ' 
                            </div>
                        </div>
                    </div>
                    <div id="post-body-content">
                        <button class="button button-primary button-hero pull right" type="submit">Salvar</button>
                    </div>
                </div>
            </div>
        </form>  
    ';
}
