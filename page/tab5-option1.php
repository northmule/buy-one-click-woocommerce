<?php
use Coderun\BuyOneClick\Core;

if (!defined('ABSPATH')) {
    exit;
}

?>
<form method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
    <?php settings_fields(sprintf('%s_options', Core::OPTIONS_MARKETING)); ?>

    <table class="form-table">
        <h3><?php _e('Additional settings','coderun-oneclickwoo'); ?></h3>
        <tr valign="top">
            <th scope="row"><?php _e('Event after clicking the button','coderun-oneclickwoo'); ?></th>
            <td>
                <textarea cols="20" rows="4" name="<?php echo Core::OPTIONS_MARKETING; ?>[after_clicking_on_button]"><?php echo Core::getInstance()->getOption('after_clicking_on_button', Core::OPTIONS_MARKETING) ?></textarea>
                <p><span class="description">
                    <?php _e('The JavaScript code that will be called when you click on the button to open the order form.','coderun-oneclickwoo'); ?>
                        <br>
                     <?php _e('Please note that the code may disrupt the normal operation of the plugin. Always check the changes after saving the settings.','coderun-oneclickwoo'); ?>
                    </span>
                </p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Event after successful form submission','coderun-oneclickwoo'); ?></th>
            <td>
                <textarea cols="20" rows="4" name="<?php echo Core::OPTIONS_MARKETING; ?>[successful_form_submission]"><?php echo Core::getInstance()->getOption('successful_form_submission', Core::OPTIONS_MARKETING) ?></textarea>
                <p><span class="description">
                        <?php _e('The JavaScript code that will be called after the successful submission of the order form.','coderun-oneclickwoo'); ?>
                        <br>
                    <?php _e('Please note that the code may disrupt the normal operation of the plugin. Always check the changes after saving the settings.','coderun-oneclickwoo'); ?>
                    </span>
                </p>
            </td>
        </tr>
    </table>
    <input type="hidden" name="action" value="update" />
    <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
</form>