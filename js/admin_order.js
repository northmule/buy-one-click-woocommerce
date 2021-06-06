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


});

