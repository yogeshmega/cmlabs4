<?php
    defined( 'ABSPATH' ) or die( 'No direct access!' );

    $url = admin_url(HPJ_CMLABS_ADMIN_URL_DOWNLOAD);
    $versionLabels = array(
        1 => __('Latest', HPJ_CMLABS_I18N_DOMAIN),
        2 => __('Previous', HPJ_CMLABS_I18N_DOMAIN),
		3 => __('Staging', HPJ_CMLABS_I18N_DOMAIN),
    );
?>

<h2><?php _e('HPJ CMLabs : Downloads', HPJ_CMLABS_I18N_DOMAIN); ?></h2>
<div>
    <div id="post-body" class="metabox-holder">
        
        <div id="post-body-content">
            <h2 class="nav-tab-wrapper">
                <a href="<?php echo admin_url(HPJ_CMLABS_ADMIN_URL_APPLICATION); ?>" class="nav-tab">Applications</a>
                <a href="<?php echo admin_url(HPJ_CMLABS_ADMIN_URL_DOWNLOAD); ?>" class="nav-tab">Downloads</a>
            </h2>                       
            
            <div class="metabox-holder">    
                <table>
                    <tr>
                        <td>
                            <form method='post' action='<?php echo $url; ?>'>
                                <input type="hidden" name="hpj_cmlabs_setting_download_id" value="<?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_download_id'])) { echo $form_datas['hpj_cmlabs_setting_download_id']; } ?>" />
                                <div class="stuffbox">
                                    <h3>
                                        <label class="wp-neworks-label"><?php _e('Add download file', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                                    </h3>
                                    <div class="inside">
                                        <table class="form-table">
                                            <tbody>
                                                <tr valign="top">
                                                    <th scope="row">
                                                        <label for="">
                                                            <?php _e('Name', HPJ_CMLABS_I18N_DOMAIN); ?> *
                                                        </label>    
                                                    </th>
                                                    <td>
                                                        <input type="text" name="hpj_cmlabs_setting_download_name" value="<?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_download_name'])) { echo $form_datas['hpj_cmlabs_setting_download_name']; } ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        <label for="">
                                                            <?php _e('Link', HPJ_CMLABS_I18N_DOMAIN); ?> *
                                                        </label>    
                                                    </th>
                                                    <td>
                                                        <textarea name="hpj_cmlabs_setting_download_url" id="hpj_cmlabs_setting_download_url" cols="30" rows="3"><?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_download_url'])) { echo $form_datas['hpj_cmlabs_setting_download_url']; } ?></textarea>
                                                    </td>        
                                                </tr>
                                                <tr valign="top">
                                                    <th scope="row">
                                                        <label for="">
                                                            <?php _e('Platform', HPJ_CMLABS_I18N_DOMAIN); ?>
                                                        </label>    
                                                    </th>
                                                    <td>
                                                        <?php
                                                            $platforms = array(
                                                                'Windows' => __('Windows', HPJ_CMLABS_I18N_DOMAIN),
                                                                'Linux' => __('Linux', HPJ_CMLABS_I18N_DOMAIN),
                                                                'Mac OS' => __('Mac OS', HPJ_CMLABS_I18N_DOMAIN),
                                                                'All' => __('All', HPJ_CMLABS_I18N_DOMAIN),
                                                            );
                                                        ?>
                                                        <select name="hpj_cmlabs_setting_download_platform">
                                                            <option value=''><?php _e('Select a platform', HPJ_CMLABS_I18N_DOMAIN); ?></option>
                                                            <?php foreach ($platforms as $key => $platform) { ?>
                                                                <option value='<?php echo $key; ?>' <?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_download_platform']) && $key == $form_datas['hpj_cmlabs_setting_download_platform']) { echo 'selected'; } ?>><?php echo $platform; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        <label for="">
                                                            <?php _e('Size', HPJ_CMLABS_I18N_DOMAIN); ?> *
                                                        </label>    
                                                    </th>
                                                    <td>
                                                        <input type="text" name="hpj_cmlabs_setting_download_size" value="<?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_download_size'])) { echo $form_datas['hpj_cmlabs_setting_download_size']; } ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label for="">
                                                            <?php _e('Description', HPJ_CMLABS_I18N_DOMAIN); ?>
                                                        </label>
                                                    </th>
                                                    <td colspan="3">
                                                        <textarea name="hpj_cmlabs_setting_download_description" id="hpj_cmlabs_setting_download_description" cols="30" rows="3"><?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_download_description'])) { echo $form_datas['hpj_cmlabs_setting_download_description']; } ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label for="">
                                                            <?php _e('Requirement', HPJ_CMLABS_I18N_DOMAIN); ?> *
                                                        </label>
                                                    </th>
                                                    <td colspan="3">
                                                        <textarea name="hpj_cmlabs_setting_download_requirement" id="hpj_cmlabs_setting_download_requirement" cols="30" rows="3"><?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_download_requirement'])) { echo $form_datas['hpj_cmlabs_setting_download_requirement']; } ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label for="">
                                                            <?php _e('Application', HPJ_CMLABS_I18N_DOMAIN); ?> *
                                                        </label>
                                                    </th>
                                                    <td>
                                                        <select name="hpj_cmlabs_setting_download_application_id" id="hpj_cmlabs_setting_download_application_id">
                                                            <option value=""><?php _e('Select an existing application', HPJ_CMLABS_I18N_DOMAIN); ?></option>
                                                            <?php
                                                                if (!empty($applications)) {
                                                                    foreach ($applications as $application) {
                                                                    ?>
                                                                        <option value="<?php echo $application->id; ?>" <?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_download_application_id']) && $application->id == $form_datas['hpj_cmlabs_setting_download_application_id']) { echo 'selected'; } ?>><?php echo $application->name . ((!empty($application->category) && trim($application->category) != '') ? ' - ' . $application->category : ''); ?></option>
                                                                    <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </select>    
                                                    </td>
                                                </tr>
                                                <tr valign="top">
                                                    <th scope="row">
                                                        <label for="">
                                                            <?php _e('Published', HPJ_CMLABS_I18N_DOMAIN); ?>
                                                        </label>    
                                                    </th>
                                                    <td>
                                                        <input type="checkbox" name="hpj_cmlabs_setting_download_published" value="1" <?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_download_published']) && (int)$form_datas['hpj_cmlabs_setting_download_published']) { echo 'checked'; } ?> />
                                                    </td>
                                                </tr>
                                                <?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_download_id'])) { ?>
                                                <tr valign="top">
                                                    <th scope="row">
                                                        <label for="">
                                                            <?php _e('Date', HPJ_CMLABS_I18N_DOMAIN); ?> *
                                                        </label>    
                                                    </th>
                                                    <td>
                                                        <input type="date" name="hpj_cmlabs_setting_download_date" value="<?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_download_date'])) { echo date('Y-m-d', strtotime($form_datas['hpj_cmlabs_setting_download_date'])); } ?>" />
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <input type="hidden" name="action" value="hpj_cmlabs_setting_download_save">
                                <p class="submit">
                                    <input type="submit" name="Submit" value="<?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_download_id'])) { _e('Update', HPJ_CMLABS_I18N_DOMAIN); } else { _e('Save', HPJ_CMLABS_I18N_DOMAIN); } ?>" class="button button-primary">
                                </p>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="stuffbox" id="namediv">
                                <h3>
                                    <label class="wp-neworks-label"><?php _e('Downloads', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                                </h3>
                                <div class="inside">
                                    <?php if (!empty($downloads)) { ?>
                                        <?php foreach ($downloads as $key => $values) { ?>
                                            <h4><?php echo $key; ?></h4>
                                            <table class="wp-list-table widefat striped">
                                                <thead>
                                                    <tr>
                                                        <th><?php _e('ID', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                                                        <th><?php _e('Name', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                                                        <th><?php _e('Link', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                                                        <th><?php _e('Size', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                                                        <th><?php _e('Platform', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                                                        <th><?php _e('Requirement', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                                                        <th><?php _e('Published', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                                                        <th><?php _e('Date', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        <?php foreach ($values as $v) { ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($v->id); ?></td>
                                                                <td><?php echo htmlspecialchars($v->name); ?></td>
                                                                <td><?php echo htmlspecialchars($v->link); ?></td>
                                                                <td><?php echo htmlspecialchars($v->size); ?></td>
                                                                <td><?php echo htmlspecialchars($v->platform); ?></td>
                                                                <td><?php echo htmlspecialchars($v->requirement); ?></td>
                                                                <td><input disabled type="checkbox" <?php checked( htmlspecialchars($v->published) ); ?></td>
                                                                <td><?php echo date('Y-m-d', strtotime($v->cdate)); ?></td>
                                                                <td><a href="<?php echo $url . '&action=download&mode=edit&id=' . (int)$v->id; ?>"><?php _e('Edit', HPJ_CMLABS_I18N_DOMAIN) ?></a></td>
                                                                <td><a href="<?php echo $url . '&action=download&mode=delete&id=' . (int)$v->id; ?>" class="hpj_cmlabs_setting_delete_download"><?php _e('Delete', HPJ_CMLABS_I18N_DOMAIN) ?></a></td>
                                                            </tr>
                                                        <?php } ?>
                                                </tbody>
                                            </table>
                                        <?php } ?>       
                                    <?php } ?>        
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
