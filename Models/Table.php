<?php
/*
 * Voucher Table Header
 */
function voucher_table_header( $headings )
{
    echo '
    <a class="button button-primary pull right" style="margin-bottom: 1vh; margin-right: 1vh">Adicionar Novo Voucher</a>
	<table class="widefat post fixed">
	<thead>
	<tr>
	';
    foreach ( $headings as $heading ) {
        echo '<th>' . __( $heading, "voucherpress" ) . '</th>';
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
                <td colspan="5" style="text-align: center"> Não há nenhum voucher cadastrado</td>
              </tr>';
        return;
    }

    foreach ($vouchers as $voucher) {
        echo '<tr>
                <td>'.$voucher->id.'</td>
                <td>'.$voucher->name.'</td>
                <td>'.$voucher->description.'</td>
                <td>'.$voucher->codeprefix.'</td>
              </tr>';
    }
}

function voucher_table_footer()
{
    echo '
	</tbody>
	</table>
	';
}