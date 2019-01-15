<div class="wrap" id="ulv-settings">
    <h2><?php _e( 'Last visit record settings', 'ulv' ); ?></h2>
    <div class="form-wrap">
        <form method="post">
            <input type="hidden" name="form-name" value="ulv-settings" />
            <input type="hidden" name="ulv_admin_nonce" value="<?php echo $this->nonce; ?>" />
            <?php $options = Ulv_Public::get_instance()->options(); ?>
            <h3><?php _e( 'General record exclusion (not user dependant) ', 'ulv' ); ?></h3>
            <div class="form-field">
                <label for="enable-record"><?php _e( 'Enable recording', 'ulv' ); ?></label>
                &nbsp;<input type="checkbox" name="enable-record" id="enable-record" <?php checked( $options['enabled'] ); ?> />
                <p><?php _e( 'Un-check to disable absolutely all recording.', 'ulv' ); ?></p>
            </div>
            <div class="form-field">
                <label for="exclude-backend"><?php _e( 'Exclude back office', 'ulv' ); ?></label>
                &nbsp;<input type="checkbox" name="exclude-backend" id="exclude-backend" <?php checked( $options['exclude_backend'] ); ?> />
                <p><?php printf(__( 'Check to disable recording for every administrative page ( anything under "%s" ).', 'ulv' ), admin_url()); ?></p>
            </div>
            <hr />
            <h3><?php _e( 'User role exclusion', 'ulv' ); ?></h3>
            <table class="widefat fixed">
                <thead>
                    <tr>
                        <th class="alt"><?php _e( 'Existing roles', 'ulv' ); ?></th>
                        <th><?php _e( 'Do not record for these roles', 'ulv' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="alt">
                            <?php
                                $all_roles = get_editable_roles();
                                $by_roles = $options['exclude_by_role'];
                            ?>
                            <ul id="all-roles">
                            <?php foreach ( $all_roles as $slug => $role ) : ?>
                                <li data-slug="<?php echo esc_attr( $slug ); ?>" data-name="<?php echo esc_attr( $role['name'] ); ?>">
                                    <?php echo $role['name'] . ' (<code>' . $slug . '</code>)'; ?><span class="ulv-action ulv-add-role <?php if ( in_array($slug, $by_roles ) ) echo 'muted '; ?>"><?php _e( 'exclude', 'ulv' ); ?></span>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            <ul id="excluded-roles">
                            <?php foreach ( $by_roles as $role_slug ) : ?>
                                <li>
                                    <?php echo $all_roles[$role_slug]['name'] . ' (<code>' . $role_slug . '</code>)'; ?><span class="ulv-action ulv-rem-role"><?php _e( 'cancel', 'ulv' ); ?></span>
                                    <input type="hidden" name="exclude-by-role[]" value="<?php echo esc_attr($role_slug); ?>" />
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr />
            <h3><?php _e( 'Individual user exclusion', 'ulv' ); ?></h3>
            <table class="widefat fixed">
                <thead>
                    <tr>
                        <th class="alt"><?php _e( 'Existing users', 'ulv' ); ?></th>
                        <th><?php _e( 'Do not record for these users (login)', 'ulv' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="alt">
                            <div class="form-field">
                                <label for="pick-user"><?php _e( 'Search user <em>(login)</em>', 'ulv' ); ?><span style="display:none;" id="no-result"><?php _e( 'No user found', 'ulv' ); ?></span></label>
                                <input id="pick-user" style="width:90%;"/>
                                <p><?php _e( 'Search for an user by typing its login', 'ulv' ); ?></p>
                            </div>
                            <div id="user-preview">
                            </div>
                        </td>
                        <td>
                            <?php $by_id = $options['exclude_by_id']; ?>
                            <ul id="excluded-users">
                            <?php foreach ( $by_id as $_id ) : ?>
                                <?php 
                                $user = get_user_by( 'id', stripslashes( $_id ) );
                                if ( ! $user ) continue;
                                ?>
                                <li data-userid="<?php echo $user->ID; ?>">
                                    <?php echo $user->user_login; ?>
                                    <span class="ulv-action ulv-rem-user"><?php _e( 'cancel', 'ulv' ); ?></span>
                                    <input type="hidden" name="exclude-by-id[]" value="<?php echo $user->ID; ?>" />
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr />
            <p class="submit">
                <input type="submit" name="save-settings" class="button button-primary" value="<?php echo esc_attr_e( 'Save settings', 'ulv' ); ?>" />
            </p>
        </form>
    </div><!-- .form-wrap -->
</div><!-- .wrap -->