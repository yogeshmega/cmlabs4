<?php
    defined( 'ABSPATH' ) or die( 'No direct access!' );  

    function display_list_page($pages, $parentId = 0, $level = 0, $pageIds = null) {
        if (!empty($pageIds)) {
            if (!is_array($pageIds)) {
                $pageIds = array($pageIds);
            }
        }
        if (!empty($pages)) {
            foreach ($pages as $page) {
                if ($page->post_parent == $parentId) {
            ?>
                    <option value='<?php echo $page->ID; ?>' <?php echo (!empty($pageIds) && in_array((int)$page->ID, $pageIds)) ? 'selected' : '' ?>><?php echo str_repeat(' - ', (int)$level) . htmlspecialchars($page->post_title); ?></option>                                
            <?php
                    display_list_page($pages, $page->ID, $level + 1, $pageIds);    
                }    
            }
        }    
    }
    
    function display_field($field, $label, $description, $pages, $isMultiple = false, $attributes = '') {
        ?>
        <tr valign="top">
            <th scope="row">
                <label for="<?php echo $field; ?>">
                    <?php echo $label; ?><br><small><?php echo $description; ?></small>
                </label>
            </th>
                
            <td>
                <select name="<?php echo $field; ?><?php echo ($isMultiple) ? '[]' : '' ?>" id="<?php echo $field; ?>" <?php echo ($isMultiple) ? 'multiple' : '' ?> <?php echo $attributes; ?>>
                    <option value=''><?php _e('Please select a page', HPJ_CMLABS_I18N_DOMAIN); ?></option>
                    <?php display_list_page($pages, 0, 0, get_option($field)); ?>
                </select>

            </td>
        </tr>
        <?php
    }
?>
                                                                           

<h2><?php _e('HPJ CMLabs', HPJ_CMLABS_I18N_DOMAIN); ?></h2>
<div>
    
    <form method='post' action='options.php'>
        <?php wp_nonce_field('update-options'); ?>
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <table>
                    <!--
                    <tr>
                        <td>
                            <div class="stuffbox" id="namediv">
                                <h3>
                                    <label class="wp-neworks-label"><?php _e('Pages', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                                </h3>
                                <div class="inside">
                                    <table class="form-table">
                                        <tbody>
                                            <?php
                                                display_field('hpj_cmlabs_setting_licenses_page_id', __('Licenses', HPJ_CMLABS_I18N_DOMAIN), __('This page will list logged user licenses', HPJ_CMLABS_I18N_DOMAIN), $pages);
                                                display_field('hpj_cmlabs_setting_license_download_page_id', __('License download page', HPJ_CMLABS_I18N_DOMAIN), __('This page will be called to download license.lic file', HPJ_CMLABS_I18N_DOMAIN), $pages);
                                                display_field('hpj_cmlabs_setting_activation_page_id', __('Manual activation page', HPJ_CMLABS_I18N_DOMAIN), __('This page will display manual activation form', HPJ_CMLABS_I18N_DOMAIN), $pages);
                                                display_field('hpj_cmlabs_setting_profile_page_id', __('Profile page', HPJ_CMLABS_I18N_DOMAIN), __('This page will allow users to update their infos', HPJ_CMLABS_I18N_DOMAIN), $pages);
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="stuffbox" id="namediv">
                                <h3>
                                    <label class="wp-neworks-label"><?php _e('Permission', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                                </h3>
                                <div class="inside">
                                    <table class="form-table">
                                        <tbody>
                                            <?php
                                                display_field('hpj_cmlabs_setting_protected_pages_ids', __('Protected pages', HPJ_CMLABS_I18N_DOMAIN), __('Authentication will be required to access this pages', HPJ_CMLABS_I18N_DOMAIN), $pages, true, 'size="10"');
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    -->
                    <tr>
                        <td>
                            <div class="stuffbox" id="namediv">
                                <h3>
                                    <label class="wp-neworks-label"><?php _e('Contact', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                                </h3>
                                <div class="inside">
                                    <table class="form-table">
                                        <tbody>
                                            <tr>
                                                <td><?php _e('Sale email', HPJ_CMLABS_I18N_DOMAIN); ?></td>
                                                <td><input type="text" name="hpj_cmlabs_setting_contact_sale_email" value="<?php echo get_option('hpj_cmlabs_setting_contact_sale_email'); ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td><?php _e('Support email', HPJ_CMLABS_I18N_DOMAIN); ?></td>
                                                <td><input type="text" name="hpj_cmlabs_setting_contact_support_email" value="<?php echo get_option('hpj_cmlabs_setting_contact_support_email'); ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td><?php _e('Licensing email', HPJ_CMLABS_I18N_DOMAIN); ?></td>
                                                <td><input type="text" name="hpj_cmlabs_setting_contact_licensing_email" value="<?php echo get_option('hpj_cmlabs_setting_contact_licensing_email'); ?>" /></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="page_options" value="hpj_cmlabs_setting_licenses_page_id, hpj_cmlabs_setting_license_download_page_id, hpj_cmlabs_setting_activation_page_id, hpj_cmlabs_setting_profile_page_id, hpj_cmlabs_setting_protected_pages_ids, hpj_cmlabs_setting_contact_sale_email, hpj_cmlabs_setting_contact_support_email, hpj_cmlabs_setting_contact_licensing_email" />
        <p class="submit">
            <input type="submit" name="Submit" value="Save Changes" class="button button-primary">
        </p>
    </form>
    
</div>
