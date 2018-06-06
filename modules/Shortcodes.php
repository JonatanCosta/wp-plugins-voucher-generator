<?php

function voucher_generate_shortcode( $atts )
{
    $atts = shortcode_atts([
        'id' => ''
    ],
    $atts,
    'voucher-generate');

    if (isset($atts['id'])) {
        return '
            <input type="email" name="email" class="form-control" placeholder="Digite seu E-mail" />
            <button class="btn btn-block">Receber</button>
        ';
    }
}

add_shortcode( 'voucher-generate', 'voucher_generate_shortcode' );