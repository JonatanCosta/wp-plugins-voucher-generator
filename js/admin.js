jQuery(document).ready(function () {
    let removeVoucher = (id) => {
        jQuery.post('admin-ajax.php', {
            id: id,
            action: 'delete_voucher'
        }).then(response => {
            alert('Voucher deletado com sucesso!')
            window.location.reload()
        }, error => {
            alert(error.message)
        })
    }

    let desativarVoucher = (id) => {
        jQuery.post('admin-ajax.php', {
            id: id,
            action: 'disable_voucher'
        }).then(response => {
            alert('Voucher desativado com sucesso!')
            window.location.reload()
        }, error => {
            alert(error.message)
        })
    }

    let ativarVoucher = (id) => {
        jQuery.post('admin-ajax.php', {
            id: id,
            action: 'active_voucher'
        }).then(response => {
            alert('Voucher ativar com sucesso!')
            window.location.reload()
        }, error => {
            alert(error.message)
        })
    }

    let utilizarVoucher = (code) => {
        return jQuery.post('admin-ajax.php', {
            code: code,
            action: 'use_voucher'
        })
    }

    jQuery(".btn-remove-voucher").click(function () {
        let c = confirm('Deseja mesmo remover este voucher?');
        if (c) {
            removeVoucher(jQuery(this).data('id'))
        }
    })

    jQuery(".btn-desativar-voucher").click(function () {
        let c = confirm('Deseja mesmo desativar este voucher?');
        if (c) {
            desativarVoucher(jQuery(this).data('id'))
        }
    })

    jQuery(".btn-ativar-voucher").click(function () {
        let c = confirm('Deseja mesmo ativar este voucher?');
        if (c) {
            ativarVoucher(jQuery(this).data('id'))
        }
    })

    jQuery(".btn-use-code").click(function () {
        let code = jQuery('input[name="voucher_code"]').val()

        if (code.length == 0) {
            jQuery('#codeHelp').removeClass('hidden').hide().fadeIn(300)
            jQuery('#codeHelp span p').html('Digite um código válido!')
            jQuery('input[name="voucher_code"]').focus()
            return;
        }

        jQuery(this).attr('disabled', 'disabled').html('Carregando')

        utilizarVoucher(code).then(response => {
            jQuery(this).attr('disabled', false).html('Utilizar Código')
            jQuery('.box-details-voucher').removeClass('hidden')
            jQuery('.box-send-code').addClass('hidden')
            jQuery('.box-details-voucher h1').html('Cupom: '+response.voucher.name)
            jQuery('.box-details-voucher p').html(response.voucher.description)

        }, error => {
            jQuery(this).attr('disabled', false).html('Utilizar Código')
            jQuery('#codeHelp').removeClass('hidden').hide().fadeIn(300)
            jQuery('#codeHelp span p').html('Código inválido, expirado ou já usado!')
            jQuery('input[name="voucher_code"]').focus()
        })
    })
})