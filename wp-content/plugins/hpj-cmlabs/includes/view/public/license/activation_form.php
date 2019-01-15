<?php
    defined( 'ABSPATH' ) or die( 'No direct access!' );
?>

<div id="form_hpj_cmlabs_manual_activation" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <?php _e('Manual activation', HPJ_CMLABS_I18N_DOMAIN); ?>
            </div>
            <div class="modal-body">
				<div>
					<h3>How to Manually Activate Your License</h3>
					<p>You are about to manually activate the activation key <span id="hpj_cmlabs_manual_activation_akey_label" style="font-weight:bold"></span>. To proceed, please enter the value provided by the Vortex Studio License Manager in the box below.</p>
					<p>The License Manager can be accessed by starting the Vortex Studio Editor application on the computer you wish to activate your license for. If no license is active on your computer, the License Manager will automatically appear. If you have an active license, the License Manager can be accessed under the About section, by clicking on the License Status button.</p>
					<p>From the License Manager's home page, select the Manual Activation option to obtain your computer's Host ID.</p> 
					<p>To learn more about your Vortex Studio license activation options, <a href=https://www.cm-labs.com/vortex-studio/activate-license target="_blank"> click here</a>.</p>

					</p>
				</div>
                
                <form method="post" id="hpj_cmlabs_manual_activation" action="<?php echo get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_LICENSES_ACTIVATION)); ?>">
                    <input type="hidden" name="akey" value="" id="hpj_cmlabs_manual_activation_akey"/>
                    <div>
                        <label><?php _e('Host ID', HPJ_CMLABS_I18N_DOMAIN); ?> : </label>
                        <input type="text" name="host_id" id="hpj_cmlabs_manual_activation_host_id" />
                    </div>
                    <div>
                        <?php wp_nonce_field( 'hpl-cmlabs-manual-activation' ) ?>
                        <input name="action" type="hidden" id="hpj_cmlabs_manual_activation_action" value="hpj-cmlabs-manual-activation" />
                    </div>
                </form>
                <div id="hpj_cmlabs_manual_activation_response"></div>
            </div>
            <div class="modal-footer">
                <button name='hpj_cmlabs_manual_activation'  data-dismiss="modal" type='button' class="btn btn-default"><?php _e('Close', HPJ_CMLABS_I18N_DOMAIN); ?></button>
                <button name='hpj_cmlabs_manual_activation' type='button' class="btn btn-primary" id="hpj_cmlabs_manual_activation_submit"><?php _e('Activate', HPJ_CMLABS_I18N_DOMAIN); ?></button>
            </div>
        </div>
    </div>
</div>
