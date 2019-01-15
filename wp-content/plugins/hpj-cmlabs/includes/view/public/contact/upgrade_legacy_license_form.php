<?php
    defined( 'ABSPATH' ) or die( 'No direct access!' );
?>
<div class="hpj-contact-form">                              
    <form name="hpj-contact-form" method="post" action="<?php echo $action; ?>">
        <input type="hidden" name="type" value="<?php echo $type; ?>" />
         <div class="form-group">
            <label for="license-id"><?php _e('Your License ID numbers to upgrade (Vortex 6.8 and earlier)', HPJ_CMLABS_I18N_DOMAIN); ?></label>
            <textarea name="license-id" class="form-control" rows="10" cols="30"><?php if (!empty($form_datas) && !empty($form_datas['license-id'])) { echo $form_datas['license-id']; } ?></textarea>
        </div>
        <div class="form-group">
            <label for="license-id"><?php _e('Phone', HPJ_CMLABS_I18N_DOMAIN); ?></label>
            <input type="tel" class="form-control" name="phone" value="<?php if (!empty($form_datas) && !empty($form_datas['phone'])) { echo $form_datas['phone']; } ?>" />
        </div>
        <div>
            <button class="btn btn-default" type="submit"><?php _e('Send', HPJ_CMLABS_I18N_DOMAIN); ?></button>
        </div>
        <p class="form-submit">
            <?php echo $referer; ?>
            <?php wp_nonce_field( 'hpj-cmlabs-send-upgrade-legacy-license-email' ) ?>
            <input name="action" type="hidden" id="action" value="hpj-cmlabs-send-upgrade-legacy-license-email" />
        </p>
    </form>
</div>
