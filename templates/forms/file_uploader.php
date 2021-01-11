<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<?php
/**
 * Форма загрузки файла
 */
?>


<div class="file-load-block">
    <div class="form-group">
        <input  id="upload_file_form" class="input-file" type="file" multiple accept="*" name="files[]" />
        <label for="upload_file_form" class="btn btn-tertiary js-labelFile">
            <i class="icon fa fa-check"></i>
            <span class="js-fileName"><?php echo $options['upload_input_file_descript']; ?></span>
        </label>
    </div>
</div>


<style>
    #formOrderOneClick .file-load-block {margin-top: 15px;}
    #formOrderOneClick .file-load-block .btn-tertiary{color:#555;padding:0;line-height:40px;margin:auto;display:block;border:1px solid #555}
    #formOrderOneClick .file-load-block .btn-tertiary:hover,.example-2 .btn-tertiary:focus{color:#888;border-color:#888}
    #formOrderOneClick .file-load-block .input-file{width:.1px;height:.1px;opacity:0;overflow:hidden;position:absolute;z-index:-1;}
    #formOrderOneClick .file-load-block .input-file + .js-labelFile{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;padding:0 10px;cursor:pointer;text-align: center;}
    #formOrderOneClick .file-load-block .input-file + .js-labelFile .icon:before{content:"\f093"}
    #formOrderOneClick .file-load-block .input-file + .js-labelFile.has-file .icon:before{content:"\f00c";color:#5AAC7B}
</style>

<script>

    (function () {

        'use strict';

        jQuery('#formOrderOneClick .input-file').each(function () {
            var input = jQuery(this),
                    label = input.next('.js-labelFile'),
                    labelVal = label.html();

            jQuery(input).on('change', function (element) {
                var fileName = '';
                if (element.target.value)
                    fileName = element.target.value.split('\\').pop();
                fileName ? label.addClass('has-file').find('.js-fileName').html(fileName) : label.removeClass('has-file').html(labelVal);
            });
        });

    })();

</script>