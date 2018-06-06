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

    jQuery(".btn-remove-voucher").click(function () {
        let c = confirm('Deseja mesmo remover este voucher?');
        if (c) {
            removeVoucher(jQuery(this).data('id'))
        }
    })
})