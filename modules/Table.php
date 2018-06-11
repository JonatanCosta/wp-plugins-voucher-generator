<?php
/*
 * Voucher Table Header
 */
function voucher_table_header( $headings )
{
    echo '
    <a class="button button-primary pull right" style="margin-bottom: 1vh; margin-right: 1vh;" href="?page=vouchers-create">Adicionar Novo Voucher</a>
    <a class="button button-secondary pull right" style="margin-bottom: 1vh; margin-right: 1vh;" href="?page=vouchers-use">Utilizar Código</a>
	<table class="wp-list-table widefat fixed striped">
	<thead>
	<tr>
	';
    foreach ( $headings as $key=>$heading ) {
        echo '<th style="'.($key == 0 ? 'width: 30px' : '').'">' . __( $heading, "voucherpress" ) . '</th>';
    }
    echo '
	</tr>
	</thead>
	<tbody>
	';
}

function voucher_table_boddy($vouchers)
{
    if (!$vouchers || count($vouchers) === 0) {
        echo '<tr>
                <td colspan="5" style="text-align: center"> Não há nenhum voucher cadastrado. </td>
              </tr>';
        return;
    }

    foreach ($vouchers as $voucher) {
        echo '<tr>
                <td style="width: 10px;">'.$voucher->id.'</td>
                <td>'.$voucher->name.'</td>
                <td>'.$voucher->description.'</td>
                <td>'.$voucher->codeprefix.'</td>
                <td>'.$voucher->generates_per_day.'</td>
                <td>'.voucher_table_action($voucher).'</td>
              </tr>';
    }
}

function voucher_table_action($voucher)
{
    return
           ($voucher->active == 1 ?
            '<a class="button button-active btn-desativar-voucher" data-id="'.$voucher->id.'">Ativo</a>' :
            '<a class="button button-warning btn-ativar-voucher" data-id="'.$voucher->id.'">Ativar</a>').
            '<a class="button button-primary mg-left-5" href="admin.php?page=update-voucher&&id='.$voucher->id.'">Editar</a>'.
            '<a class="button button-danger btn-remove-voucher mg-left-5" data-id="'.$voucher->id.'">Remover</a>';
}

function voucher_table_footer()
{
    echo '
	</tbody>
	</table>
	';
}