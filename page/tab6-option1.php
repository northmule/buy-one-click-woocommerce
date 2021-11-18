<?php

use Coderun\BuyOneClick\Core;

if (!defined('ABSPATH')) {
    exit;
}

?>

<form id="design-form" method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
    <?php settings_fields(sprintf('%s_options', Core::OPTIONS_DESIGN_FORM)); ?>

    <div id="fb-editor"></div>

</form>
<script>
  jQuery(function($) {
    var options = {
      disableFields: [
        'autocomplete',
        'button',
       // 'checkbox-group',
       // 'date',
       // 'file',
       // 'header',
       // 'hidden',
       // 'number',
        'paragraph',
        'radio-group',
       // 'select',
       // 'starRating',
       // 'text',
       // 'textarea',
      ],
      disabledAttrs: [
        'access',
        // 'className',
        // 'description',
        'inline',
        'label',
        'max',
        'maxlength',
        'min',
        'multiple',
        'name',
        'options',
       // 'other',
        'placeholder',
        'required',
       // 'rows',
        //'step',
        'style',
        // 'subtype',
        'toggle',
        'value'
      ]
    };
    // https://formbuilder.online/docs/
    $(document.getElementById('fb-editor')).formBuilder(options);
  });
</script>