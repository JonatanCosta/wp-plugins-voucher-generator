<?php
require_once 'Actions.php';

function voucher_generate_shortcode( $atts )
{
    $active_voucher = get_active_voucher();
    $voucher_config = get_voucher_config();
    
    if ( $active_voucher ) {
        $atts = shortcode_atts([
            'id' => ''
        ],
        $atts,
         'voucher-generate');

        if (isset($atts['id'])) {
            return '
                <h5 class="text-center">Descontos:</h5>
                <h1 class="text-center">'.$active_voucher->name.'</h1>
                <p class="text-center">
                    '.$active_voucher->description.'<br>
                    <small>
                        '.
                        nl2br($voucher_config->terms)
                        .'
                    </small>
                </p>
                <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-lg-push-3 form-voucher">
                    <div class="form-group">
                        <input type="email" name="email_voucher" class="form-control" placeholder="Digite um endereço de e-mail válido" style="color: #FFF !important;"/>
                        <span id="emailHelp" class="label label-danger form-text text-muted hidden" style="border-radius: 0px; margin: 0 auto; display: table;">
                            <small>Endereço de email inválido ou já cadastrado!</small>
                        </span>
                        <span id="limitHelp" class="label label-default form-text text-muted hidden" style="border-radius: 0px; margin: 0 auto; display: table;">
                            <small>Limite diário atingido, tente novamente amanhã.</small>
                        </span>
                    </div>
                    
                    <button class="btn btn-block btn-danger btn-generate-voucher" data-id="'.$active_voucher->id.'">
                        <span class="loader-generate-voucher hidden">
                            <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i> Aguarde
                        </span>
                        <span class="no-loader-generate-voucher">
                            <i class="fa fa-ticket fa-3x fa-fw"></i> Gerar Cupom de Desconto
                        </span>
                    </button>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 col-lg-push-3 col-md-push-2 voucher hidden">
                    <div class="col-md-12 img-logo-voucher">
                    <img src="'.$voucher_config->logo_url.'" class="img-responsive" style="max-height: 150px; max-width: 500px">
                    </div>
                    <div class="col-md-12">
                    <h6 class="text-center" style="color: #FFF">Código:</h6>
                    <div class="voucher_code">
                        <p style="margin-bottom: 0px !important;">
                        </p>
                    </div>
                    </div>
                </div>
                </div>
            ';
        }
    }
    return '
            <h5 class="text-center">Descontos:</h5>
            <p class="text-center">
                No momento não há nenhum cupom desconto ativo.
            </p>
        ';
}

add_shortcode( 'voucher-generate', 'voucher_generate_shortcode' );