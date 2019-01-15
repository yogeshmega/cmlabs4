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
                    <option value=''><?php _e('Please select an item', HPJ_CMLABS_I18N_DOMAIN); ?></option>
                    <?php display_list_page($pages, 0, 0, get_option($field)); ?>
                </select>

            </td>
        </tr>
        <?php
    }  
?>
<h2><?php _e('HPJ RSS', HPJ_RSS_I18N_DOMAIN); ?></h2>
<div>
    
    <form method='post' action='options.php'>
        <?php wp_nonce_field('update-options'); ?>
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <table>
                    <tr>
                        <td>
                            <div class="stuffbox" id="namediv">
                                <h3>
                                    <label class="wp-neworks-label"><?php _e('Select Items to fill the RSS feed', HPJ_RSS_I18N_DOMAIN); ?></label>
                                </h3>
                                <div class="inside">
                                    <table class="form-table">
                                        <tbody>
                                            <?php
                                                display_field('hpj_rss_setting_posts_ids', __('Posts', HPJ_RSS_I18N_DOMAIN), __('Posts to be visible in feed', HPJ_RSS_I18N_DOMAIN), $posts, true, 'size="15"');
                                            ?>
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
        <input type="hidden" name="page_options" value="hpj_rss_setting_pages_ids, hpj_rss_setting_posts_ids" />
        <p class="submit">
            <input type="submit" name="Submit" value="Save Changes" class="button button-primary">
        </p>
    </form>
    
</div>