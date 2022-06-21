jQuery(document).ready(function ($) {
    $(document).on('change', '#kk-products', function (e) {
        e.preventDefault();
        const data = {
            'action': 'kk_ajax',
            'product': $(this).val(),
            'nonce': kk.nonce
        };
        $.ajax({
            type: "GET",
            url: kk.ajax_url,
            data,
            beforeSend: function () {
                $(".elementor-shortcode").addClass('kk-loading');
            },
            success: function (response) {
                $('.elementor-shortcode').html(response);
                $(".elementor-shortcode").removeClass('kk-loading');
            }
        });

    });

});