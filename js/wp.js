jQuery(document).ready(function () {
    let generateVoucher = (id_voucher, email) => {
        return jQuery.post('wp-admin/admin-ajax.php', {
            id: id_voucher,
            email: email,
            action: 'generate_voucher'
        })
    }

    jQuery('.btn-generate-voucher').click(function () {
        let email = jQuery('input[name="email_voucher"]').val(),
            id_voucher = jQuery(this).data('id');

        if (email.length === 0) {
            jQuery('#emailHelp').removeClass('hidden').hide().fadeIn(200)
            jQuery('input[name="email_voucher"]').focus()
            return;
        }

        generateVoucher(id_voucher, email).then(response => {

        }, error => {

        })
    })
})