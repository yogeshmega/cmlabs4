<?php
    defined( 'ABSPATH' ) or die( 'No direct access!' );
?>
<div class="hpj-contact-form">
    <form name="hpj-contact-form" method="post" action="<?php echo $action; ?>">
        <input type="hidden" name="target_email" value="<?php echo $email; ?>" />
        <input type="hidden" name="type" value="<?php echo $type; ?>" />
        <div class="form-group">
            <label for="first-name"><?php _e('First Name', HPJ_CMLABS_I18N_DOMAIN); ?></label>
            <input type="text" class="form-control" name="first-name" value="<?php if (!empty($form_datas) && !empty($form_datas['first-name'])) { echo $form_datas['first-name']; } else { the_author_meta( 'first_name', $current_user->ID ); } ?>" />
        </div>
        <div class="form-group">
            <label for="last-name"><?php _e('Last Name', HPJ_CMLABS_I18N_DOMAIN); ?></label>
            <input type="text" class="form-control" name="last-name" value="<?php if (!empty($form_datas) && !empty($form_datas['last-name'])) { echo $form_datas['last-name']; } else { the_author_meta( 'last_name', $current_user->ID ); } ?>" />
        </div>
        <div class="form-group">
            <label for="company"><?php _e('Company Name', HPJ_CMLABS_I18N_DOMAIN); ?></label>
            <input type="text" class="form-control" name="company" value="<?php if (!empty($form_datas) && !empty($form_datas['company'])) { echo $form_datas['company']; } else { the_author_meta( 'user_company', $current_user->ID ); } ?>" />
        </div>
        <div class="form-group">
            <label for="email"><?php _e('Email', HPJ_CMLABS_I18N_DOMAIN); ?></label>
            <input type="email" class="form-control" name="email" value="<?php if (!empty($form_datas) && !empty($form_datas['email'])) { echo $form_datas['email']; } else { the_author_meta( 'user_email', $current_user->ID ); } ?>" />
        </div>
        <div class="form-group">
            <label for="phone"><?php _e('Phone', HPJ_CMLABS_I18N_DOMAIN); ?></label>
            <input type="tel" class="form-control" name="phone" value="<?php if (!empty($form_datas) && !empty($form_datas['phone'])) { echo $form_datas['phone']; } ?>" />
        </div>
        <div class="form-group">
            <label for="request"><?php _e('Please indicate why you need an additional license for Vortex Studio Essentials', HPJ_CMLABS_I18N_DOMAIN); ?></label>
            <textarea name="request" class="form-control" rows="10" cols="30"><?php if (!empty($form_datas) && !empty($form_datas['request'])) { echo $form_datas['request']; } ?></textarea>
        </div>
        <div>
            <button class="btn btn-default" type="submit"><?php _e('Send', HPJ_CMLABS_I18N_DOMAIN); ?></button>
        </div>
        <p class="form-submit">
            <?php echo $referer; ?>
            <?php wp_nonce_field( 'hpj-cmlabs-send-email' ) ?>
            <input name="action" type="hidden" id="action" value="hpj-cmlabs-send-email" />
        </p>
    </form>
</div>
