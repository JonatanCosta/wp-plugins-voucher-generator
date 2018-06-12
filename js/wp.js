jQuery(document).ready(function () {
    let generateVoucher = (id_voucher, email) => {
        return jQuery.post(ajaxurl, {
            id: id_voucher,
            email: email,
            action: 'generate_voucher'
        })
    }

    let mountVoucher = (voucher, voucher_code) => {
        jQuery('.form-voucher').addClass('hidden')
        jQuery('.voucher').removeClass('hidden')

        jQuery('.voucher_code p').html(voucher_code)
    }

    jQuery('.btn-generate-voucher').click(function () {
        let email = jQuery('input[name="email_voucher"]').val(),
            id_voucher = jQuery(this).data('id');

        if (email.length === 0) {
            jQuery('#emailHelp').removeClass('hidden').hide().fadeIn(200)
            jQuery('input[name="email_voucher"]').focus()
            return;
        }

        jQuery(this).attr('disabled', 'disable')
        jQuery('.loader-generate-voucher').removeClass('hidden')
        jQuery('.no-loader-generate-voucher').addClass('hidden')

        generateVoucher(id_voucher, email).then(response => {
            jQuery('.loader-generate-voucher').addClass('hidden')
            jQuery('.no-loader-generate-voucher').removeClass('hidden')
            jQuery(this).attr('disabled', false)
            mountVoucher(response.voucher, response.voucher_code)
        }, error => {
            jQuery('.loader-generate-voucher').addClass('hidden')
            jQuery('.no-loader-generate-voucher').removeClass('hidden')
            jQuery(this).attr('disabled', false)
            if (error.status == 401) {
                jQuery('#limitHelp').addClass('hidden')
                jQuery('#emailHelp').removeClass('hidden').hide().fadeIn(300)
                jQuery('input[name="email_voucher"]').focus()
                return;
            }

            if (error.status == 423) {
                jQuery('#emailHelp').addClass('hidden')
                jQuery('#limitHelp').removeClass('hidden').hide().fadeIn(300)
                jQuery('input[name="email_voucher"]').focus()
                return;
            }
        })
    })
})