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
})