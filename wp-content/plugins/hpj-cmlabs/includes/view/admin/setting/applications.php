<?php
    defined( 'ABSPATH' ) or die( 'No direct access!' );
    
    $url = admin_url(HPJ_CMLABS_ADMIN_URL_APPLICATION);
    $versionLabels = array(
        1 => __('Latest', HPJ_CMLABS_I18N_DOMAIN),
        2 => __('Previous', HPJ_CMLABS_I18N_DOMAIN),
		3 => __('Staging', HPJ_CMLABS_I18N_DOMAIN),
    );
    $editionLabels = array(
        'essential' => __('Essential', HPJ_CMLABS_I18N_DOMAIN),
        'solo_team_academic' => __('Solo/Team/Academic', HPJ_CMLABS_I18N_DOMAIN),
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
                                <input type="hidden" name="hpj_cmlabs_setting_application_id" value="<?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_application_id'])) { echo $form_datas['hpj_cmlabs_setting_application_id']; } ?>" />
                                <div class="stuffbox">
                                    <h3>
                                        <label class="wp-neworks-label"><?php _e('Add application', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                                    </h3>
                                    <div class="inside">
                                        <table class="form-table">
                                            <tbody>
                                                <tr valign="top">
                                                    <th scope="row">
                                                        <label for="">
                                                            <?php _e('Name', HPJ_CMLABS_I18N_DOMAIN); ?>
                                                        </label>    
                                                    </th>
                                                    <td>
                                                        <input type="text" name="hpj_cmlabs_setting_application_name" value="<?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_application_name'])) { echo $form_datas['hpj_cmlabs_setting_application_name']; } ?>" />
                                                    </td>
                                                </tr>
												<tr valign="top">
                                                    <th scope="row">
                                                        <label for="">
                                                            <?php _e('Category', HPJ_CMLABS_I18N_DOMAIN); ?>
                                                        </label>    
                                                    </th>
                                                    <td>
                                                        <input type="text" name="hpj_cmlabs_setting_application_category" value="<?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_application_category'])) { echo $form_datas['hpj_cmlabs_setting_application_category']; } ?>" />
                                                    </td>
                                                </tr>
                                                <tr valign="top">
                                                    <th scope="row">
                                                        <label for="">
                                                            <?php _e('Version', HPJ_CMLABS_I18N_DOMAIN); ?>
                                                        </label>    
                                                    </th>
                                                    <td>
                                                        <?php
                                                            $platforms = array(
                                                                '1' => __('Latest', HPJ_CMLABS_I18N_DOMAIN),
                                                                '2' => __('Previous', HPJ_CMLABS_I18N_DOMAIN),
																'3' => __('Staging', HPJ_CMLABS_I18N_DOMAIN),
                                                            );
                                                        ?>
                                                        <select name="hpj_cmlabs_setting_application_version">
                                                            <option value=''><?php _e('Select a version', HPJ_CMLABS_I18N_DOMAIN); ?></option>
                                                            <?php foreach ($platforms as $key => $platform) { ?>
                                                                <option value='<?php echo $key; ?>' <?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_application_version']) && $key == $form_datas['hpj_cmlabs_setting_application_version']) { echo 'selected'; } ?>><?php echo $platform; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>        
                                                </tr>
                                                <tr valign="top">
                                                    <th scope="row">
                                                        <label for="">
                                                            <?php _e('Edition', HPJ_CMLABS_I18N_DOMAIN); ?>
                                                        </label>    
                                                    </th>
                                                    <td>
                                                        <?php
                                                            $editions = array(
                                                                'essential' => __('Essential', HPJ_CMLABS_I18N_DOMAIN),
                                                                'solo_team_academic' => __('Solo/Team/Academic', HPJ_CMLABS_I18N_DOMAIN)
                                                            );
                                                        ?>
                                                        <select name="hpj_cmlabs_setting_application_edition">
                                                            <option value=''><?php _e('Select an edition', HPJ_CMLABS_I18N_DOMAIN); ?></option>
                                                            <?php foreach ($editions as $key => $edition) { ?>
                                                                <option value='<?php echo $key; ?>' <?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_application_edition']) && $key == $form_datas['hpj_cmlabs_setting_application_edition']) { echo 'selected'; } ?>><?php echo $edition; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>
												<tr valign="top">
                                                    <th scope="row">
                                                        <label for="">
                                                            <?php _e('Display Order', HPJ_CMLABS_I18N_DOMAIN); ?>
                                                        </label>    
                                                    </th>
                                                    <td>
                                                        <input size="2" type="text" name="hpj_cmlabs_setting_application_displayorder" value="<?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_application_displayorder'])) { echo $form_datas['hpj_cmlabs_setting_application_displayorder']; } ?>" />
                                                    </td>
                                                </tr>
                                                <tr valign="top">
                                                    <th scope="row">
                                                        <label for="">
                                                            <?php _e('Published', HPJ_CMLABS_I18N_DOMAIN); ?>
                                                        </label>    
                                                    </th>
                                                    <td>
                                                        <input type="checkbox" name="hpj_cmlabs_setting_application_published" value="1" <?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_application_published']) && (int)$form_datas['hpj_cmlabs_setting_application_published']) { echo 'checked'; } ?> />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <input type="hidden" name="action" value="hpj_cmlabs_setting_application_save">
                                <p class="submit" style="float:left; padding-right:30px;">
                                    <input type="submit" name="Submit" value="<?php if (!empty($form_datas) && !empty($form_datas['hpj_cmlabs_setting_application_id'])) { _e('Update', HPJ_CMLABS_I18N_DOMAIN); } else { _e('Save', HPJ_CMLABS_I18N_DOMAIN); } ?>" class="button button-primary">
                                </p>
                            </form>
							<form method='post' action='<?php echo admin_url( 'admin-post.php' ); ?>'>
								<input type="hidden" name="action" value="hpj_cmlabs_staging_to_release">
                                <p class="submit" style="float:left;">
                                    <input type="submit" name="" value="<?php _e('Release new version', HPJ_CMLABS_I18N_DOMAIN ); ?>" class="button button-primary">
                                </p>
							</form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            
                        </td>
                    </tr>
                    <?php if (!empty($applications)) { ?>
                    <tr>
                        <td>
                            <div class="stuffbox" id="namediv">
                                <h3>
                                    <label class="wp-neworks-label"><?php _e('Applications', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                                </h3>
                                <div class="inside">
                                    <table class="wp-list-table widefat striped">
                                        <thead>
                                            <tr>
                                                <th><?php _e('ID', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                                                <th><?php _e('Name', HPJ_CMLABS_I18N_DOMAIN); ?></th>
												<th><?php _e('Category', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                                                <th><?php _e('Version', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                                                <th><?php _e('Edition', HPJ_CMLABS_I18N_DOMAIN); ?></th>
												<th><?php _e('Display Order', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                                                <th><?php _e('Published', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                                                <th><?php _e('Date', HPJ_CMLABS_I18N_DOMAIN); ?></th>     
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($applications as $key => $application) { ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($application->id); ?></td>
													<td><?php echo htmlspecialchars($application->name); ?></td>
													<td><?php echo htmlspecialchars($application->category); ?></td>
                                                    <td><?php echo htmlspecialchars($versionLabels[$application->version]); ?></td>
                                                    <td><?php echo htmlspecialchars($editionLabels[$application->edition]); ?></td>
													<td><?php echo htmlspecialchars($application->displayorder); ?></td>
                                                    <td><?php echo htmlspecialchars($application->published); ?></td>
                                                    <td><?php echo date('Y-m-d', strtotime($application->cdate)); ?></td>
                                                    <td><a href="<?php echo $url . '&mode=edit&id=' . (int)$application->id; ?>"><?php _e('Edit', HPJ_CMLABS_I18N_DOMAIN) ?></a></td>
                                                    <td><a href="<?php echo $url . '&mode=delete&id=' . (int)$application->id; ?>" class="hpj_cmlabs_setting_delete_application"><?php _e('Delete', HPJ_CMLABS_I18N_DOMAIN) ?></a></td>
                                                </tr>     
                                            <?php } ?>
                                        </tbody>
                                    </table>      
                                </div>
                            </div>
                        </td>
                    </tr>       
                    <?php } ?>   
                </table>
            </div>
        </div>
    </div>
</div>

