<?php
    defined( 'ABSPATH' ) or die( 'No direct access!' );
?> 
<p><?php _e( 'The license page lets you register Vortex Studio activation keys received from CM Labs, manually activate licenses and request addition licenses and runtimes. To learn more about Vortex Studio license activation,', HPJ_CMLABS_I18N_DOMAIN ); ?> <a href="/vortex-studio/activate-license"><?php _e( 'read our blog post', HPJ_CMLABS_I18N_DOMAIN ); ?></a>.</p>
<br />
<h3><?php _e( 'Register Activation Key', HPJ_CMLABS_I18N_DOMAIN ); ?></h3>
<p><?php _e( 'If you received an activation key from CM Labs, enter it below to associate it to your account.', HPJ_CMLABS_I18N_DOMAIN ); ?></p>                                                                                     
<div class="register-key-form" >
    <form method="post" id="hpj_cmlabs_register_activation_key" action="<?php echo get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_LICENSES)); ?>">
        <table>
            <tr>
                <td>
                    <input type='text' name='key[]' class='form-control hpj_cmlabs_register_activation_key' id='hpj_cmlabs_register_activation_key_1' index="1" />
                </td>
                <td>
                    <input type='text' name='key[]' class='form-control hpj_cmlabs_register_activation_key' id='hpj_cmlabs_register_activation_key_2' index="2" />
                </td>
                <td>
                    <input type='text' name='key[]' class='form-control hpj_cmlabs_register_activation_key' id='hpj_cmlabs_register_activation_key_3' index="3" />
                </td>
                <td>
                    <input type='text' name='key[]' class='form-control hpj_cmlabs_register_activation_key' id='hpj_cmlabs_register_activation_key_4' index="4" />
                </td>
                <td>
                    <?php wp_nonce_field( 'hpl-cmlabs-register-activation-key' ) ?>
                    <input name="action" type="hidden" id="action" value="hpj-cmlabs-register-activation-key" />
                    <button class="btn btn-default hpj_cmlabs_register_activation_key" name='hpj_cmlabs_register_activation_key' type='submit'><?php _e('Register Activation Key', HPJ_CMLABS_I18N_DOMAIN); ?></button>
                </td>
            </tr>
        </table>
    </form>
    <p>
        <hr class="color-black">
    </p>
</div>