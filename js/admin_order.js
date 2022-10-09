function getAjaxUrl() {

    var zixn_ajaxUrl = '/wp-admin/admin-ajax.php';

    if (typeof ajaxurl === 'undefined') {

        return zixn_ajaxUrl;

    } else {
        return ajaxurl;
    }
}
jQuery(document).ready(function () {

    //Удалить всю таблицу
    jQuery('.removeallorder').on('click', function () {
        jQuery.ajax({
            type: "POST",
            url: getAjaxUrl(),
            async: false,
            data: {
                action: 'removeorderall',
                nonce: buyadminnonce
            },
            success: function (resp) {
                if (resp.success) {
                    jQuery('table tbody').fadeOut();
                }
            }
        });
    });

//Удалить элемент таблицы заказов
    jQuery('.removeorder').click(function () {

        var id = jQuery(this).attr('id');
        jQuery(".order" + id).hide("slow");
        jQuery.ajax({
            type: "POST",
            url: getAjaxUrl(),
            async: false,
            data: {
                action: 'removeorder',
                text: id
            }
        });


    });
    /**
     * Удалить заказ Woo ассоциированный с журналом плагина
     */
    jQuery('.removeorder_woo').click(function (e) {
        e.preventDefault();
        var self=this;
        jQuery.ajax({
            type: "POST",
            url: getAjaxUrl(),
            async: false,
            data: {
                action: 'removeorder',
                orderId:  jQuery(this).attr('data-woo_id'),
                pluginId: jQuery(this).attr('data-plugin_id')
            }
        }).done(function(response){
            if(response.success) {
                jQuery(self).hide();
            }
        });



    });

    //Обновление статус
    jQuery('.updatestatus').click(function () {
        var id = jQuery(this).attr('id');
        var statusold = jQuery(this).attr('orderstat');
        //alert(statusold);
        if (statusold === "1") {
            var status = '2'
            jQuery(this).attr('2');
            jQuery(this).html('<span class="glyphicon glyphicon-ok-circle">ОК</span>');


        } else {
            var status = '1'
            jQuery(this).html('<span class="glyphicon glyphicon-ban-circle">НЕТ</span>');

        }
        var info = {
            id: id,
            status: status
        };
        jQuery.ajax({
            type: "POST",
            url: getAjaxUrl(),
            async: false,
            data: {
                action: 'updatestatus',
                text: info
            }

        });

    });

    /**
     * Экспорт настроек
     */
    jQuery('input[name="export_options"]').on('click', function () {
        jQuery.ajax({
            type: "POST",
            url: getAjaxUrl(),
            data: {
                action: 'buy_one_click_export_options',
            }

        }).done(function (response, status, xhr){
            const blob = new Blob([JSON.stringify(response.data)], {type: xhr.getResponseHeader('Content-Type: text/json')});
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'buy_one_click_setting.json';
            link.click();
        });
    });

    /**
     * Импорт настроек
     */
    jQuery('input[name="import_options"]').on('click', function () {
        const input = jQuery('#settings_file_select');
        const label = input.closest('.upload');
        const form = jQuery('#form_settings_file_select');
        input.trigger('click');
        input.on('change', function(e) {
            var formData = new FormData();
            formData.append('action', 'buy_one_click_import_options');
            formData.append('file', input[0].files[0]);
            jQuery.ajax({
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                url: getAjaxUrl(),
                data: formData,
            }).done(function (response){
                if (response.success) {
                    window.location.reload();
                }
                if (response.data.message) {
                    jQuery('.setting_message_result').text(response.data.message);
                }

            });
        });

    });

});

