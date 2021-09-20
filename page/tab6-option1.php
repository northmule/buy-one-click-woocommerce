<?php

use Coderun\BuyOneClick\Core;

if (!defined('ABSPATH')) {
    exit;
}

?>

<form id="design-form" method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
    <?php settings_fields(sprintf('%s_options', Core::OPTIONS_DESIGN_FORM)); ?>

    <table class="form-table">
        <h3><?php _e('Design form', 'coderun-oneclickwoo'); ?></h3>
            <select>
                <option value="input">Input</option>
                <option value="textarea">Textarea</option>
            </select>
        <button class="btn btn-warning">Add</button>
        <input type="hidden" name="action" value="update"/>
        <p class="submit">
            <input type="submit" class="button-primary"
                   value="<?php _e('Save Changes') ?>"/>
        </p>
</form>
<script>
jQuery(function($){
  var clas2s = $('#design-form').attr('action');
  console.log(clas2s)
})
</script>