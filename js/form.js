//(function (jQuery, undefinde) {

jQuery(document).ready(function () {
    jQuery(document).on('click', '.popup .close_order, .overlay', function (e) {
        e.preventDefault();
        jQuery('.popup, .overlay').css({'opacity': '0', 'visibility': 'hidden'});
        jQuery('#buyoneclick_form_order input:checkbox').removeAttr("checked");
        jQuery('#buyoneclick_form_order input[type=hidden].valTrFal').val('valTrFal_disabled');
        buyone_click_body_scroll();
    });

    jQuery(function () {
        jQuery('#buyoneclick_form_order input:checkbox').change(function () {
            if (jQuery(this).is(':checked')) {
                jQuery('#buyoneclick_form_order input[type=hidden].valTrFal').val('valTrFal_true');
            } else {

                jQuery('#buyoneclick_form_order input[type=hidden].valTrFal').val('valTrFal_disabled');
            }
        });
    });
    //Доп сообщение

    jQuery(document).on('click', '#formOrderOneClick .popummessage .close_message, #formOrderOneClick .overlay_message', function () {
        jQuery('#formOrderOneClick .popummessage, #formOrderOneClick .overlay_message').css({'opacity': '0', 'visibility': 'hidden'});
        buyone_click_body_scroll();

    });
});

function getAjaxUrl() {
    return buyone_ajax.ajaxurl;
}

function buyone_click_body_scroll() {

    var formVisible = jQuery('#formOrderOneClick .popup').css('visibility')

    if(formVisible === 'visible'){
        jQuery('body').css('overflow','hidden')
    }else {
        jQuery('body').css('overflow','');
    }
}


jQuery(document).ready(function () {


    /**
     * Отправит форму с заказом
     */
    jQuery(document).on('submit', '#buyoneclick_form_order', function (e) {
        e.preventDefault();

        var self = this;

        jQuery('#buyoneclick_form_order .buyButtonOkForm').prop('disabled', 'disabled');

        var root_selector = '#buyoneclick_form_order';

        jQuery('#buyoneclick_form_order .buyButtonOkForm').addClass('running');

        jQuery.ajax({
            url: getAjaxUrl(),
            type: "POST",
            data: new FormData(this),
            cache: false,
            processData: false,
            contentType: false
        }).done(function (response) {
            setTimeout(function () {
                jQuery("#buyoneclick_form_order .form-message-result").html('');
            }, 3000);

            var obj = response;

            if (!obj.success) {
                jQuery(root_selector + " .form-message-result").html(obj.data.message)
                return false;
            }
            if (buyone_ajax.success_action === 1) { //Действие по умолчанию
                jQuery(root_selector + " .form-message-result").html(buyone_ajax.after_message_form)
            } else if (buyone_ajax.success_action === 2) { // Закрытие формы через action мил сек
                jQuery(root_selector + " .form-message-result").html(buyone_ajax.after_message_form)
                setTimeout(function(){
                    jQuery('#formOrderOneClick .close_order').trigger('click');
                },buyone_ajax.after_submit_form);
            } else if (buyone_ajax.success_action === 3) { // Показать сообщение action
                jQuery('#formOrderOneClick .close_order').trigger('click');
                jQuery('#formOrderOneClick .popummessage, #formOrderOneClick .overlay_message').css('opacity', '1');
                jQuery('#formOrderOneClick .popummessage, #formOrderOneClick .overlay_message').css('visibility', 'visible');

            } else if (buyone_ajax.success_action === 4) { // Сделать редирект action
                jQuery("#buyoneclick_form_order .form-message-result").html(buyone_ajax.after_message_form)
                window.location.href = buyone_ajax.after_submit_form;
            } else if (buyone_ajax.success_action === 5 || buyone_ajax.success_action === 6) { // Сделать редирект WooCommerce
                if (response.success && response.data && response.data.redirectUrl) {
                    jQuery("#buyoneclick_form_order .form-message-result").html(buyone_ajax.after_message_form)
                    window.location.href = response.data.redirectUrl;
                }
            }
            if (typeof buyone_ajax.callback_successful_form_submission !== 'undefined') {
                var callback = new Function(buyone_ajax.callback_successful_form_submission);
                callback();
            }
        }).fail(function (response) {
            console.log(response);
            jQuery(root_selector + " .form-message-result").html('server error 500');

        }).always(function () {
            jQuery('#buyoneclick_form_order .buyButtonOkForm').prop("disabled", false);
            jQuery('#buyoneclick_form_order .buyButtonOkForm').removeClass('running');
        });


    });

    /**
     * Кнопка "Заказать в один клик"
     * Нарисует форму
     */
    jQuery(document).on('click', 'button.clickBuyButton', function (e) {
        e.preventDefault();
        var self = jQuery(this);
        if(jQuery(self).hasClass('disabled')){
            return;
        }
        var zixnAjaxUrl = getAjaxUrl();
        var butObj = 'body';
        // var butObj = self.parent();

        var button = jQuery(this);

        var urlpost = window.location.href;
        var productid = jQuery(this).attr('data-productid');
        var action = 'getViewForm';
        var variation_selected = 0;
        var variation_attr = '';

        jQuery(button).addClass('running');

        if (typeof buyone_ajax.work_mode !== 'undefined' && buyone_ajax.work_mode == 1) {
            action = 'add_to_cart';
            variation_selected = jQuery(this).attr('data-variation_id');
            variation_attr = jQuery('.variations_form.cart').serialize();
        } else {
            variation_selected = jQuery(this).attr('data-variation_id');
        }

        jQuery.ajax({
            type: "POST",
            url: zixnAjaxUrl,
            // async: false,
            data: {
                action: action,
                urlpost: urlpost,
                productid: productid,
                variation_selected: variation_selected,
                variation_attr: variation_attr,
            },
            success: function (response) {
                if (action === 'add_to_cart') {
                    window.location.href = response;
                    return true;
                }
                jQuery('#formOrderOneClick').remove();
                jQuery(butObj).append(response);
                jQuery('.popup, .overlay').css('opacity', '1');
                jQuery('.popup, .overlay').css('visibility', 'visible');

                if (typeof buyone_ajax.tel_mask != 'undefined') {
                    jQuery('#buyoneclick_form_order [name="txtphone"]').mask(buyone_ajax.tel_mask);
                }
                jQuery(button).removeClass('running');
                buyone_click_body_scroll();
                if (typeof buyone_ajax.callback_after_clicking_on_button !== 'undefined') {
                    var callback = new Function(buyone_ajax.callback_after_clicking_on_button);
                    callback();
                }
            }
        });
    });
    jQuery(document).on('click', 'button.clickBuyButtonCustom', function (e) {
        e.preventDefault();
        var zixnAjaxUrl = getAjaxUrl();
        var butObj = this;

        var urlpost = window.location.href;
        var productid = jQuery(butObj).attr('data-productid');
        var name = jQuery(butObj).attr('data-name');
        var count = jQuery(butObj).attr('data-count');
        var price = jQuery(butObj).attr('data-price');

        jQuery.ajax({
            type: "POST",
            url: zixnAjaxUrl,
            async: false,
            data: {
                action: 'getViewFormCustom',
                urlpost: urlpost,
                productid: productid,
                name: name,
                count: count,
                price: price,
            },
            success: function (response) {
                jQuery('#formOrderOneClick').remove();
                jQuery(butObj).after(response);
                jQuery('.popup, .overlay').css('opacity', '1');
                jQuery('.popup, .overlay').css('visibility', 'visible');
                buyone_click_body_scroll();
                if (typeof buyone_ajax.callback_after_clicking_on_button !== 'undefined') {
                    var callback = new Function(buyone_ajax.callback_after_clicking_on_button);
                    callback();
                }
            }
        });
    });

});
