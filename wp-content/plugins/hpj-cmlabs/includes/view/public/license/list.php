<div class="licence-list">

<?php
    defined( 'ABSPATH' ) or die( 'No direct access!' );

    const ADDITIONAL_FIELD_EXPIRATION = 'expiration';
    const ADDITIONAL_FIELD_APPLICATION_SIGNATURE = 'application_signature';
    const ADDITIONAL_FIELD_M_S_END_DATE = 'm_s_end_date';

    function displayGrid($list, $title, $additionalFieldList, $link = null, $emailLink = null, $activationLink = null, $essentials_licenses = false) {
        if (!empty($list)) {
            $todayDate = strtotime(date('Y-m-d'));
                                             
        ?>
            <?php if (!empty($link)) { ?>
                <a class="btn btn-default pull-right" href="<?php echo $link['href'] ?>"><?php echo $link['name'] ?></a>
            <?php } ?>
            <h3><?= $title; ?></h3>
            <div class="licences">
                <table class="white-table responsive-table">
                    <thead>
                        <tr class="compare-body__features">
                            <th><?php _e('Activation Key', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                            <th><?php _e('Product Name', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                            <th><?php _e('Status', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                            <th><?php _e('Host ID', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                            <?php if (in_array(ADDITIONAL_FIELD_APPLICATION_SIGNATURE, $additionalFieldList)) { ?>
                                <th><?php _e('Application Signature', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                            <?php } ?>
                            <?php if (in_array(ADDITIONAL_FIELD_EXPIRATION, $additionalFieldList)) { ?>
                                <th><?php _e('Expiration', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                            <?php } ?>
                            <?php if (in_array(ADDITIONAL_FIELD_M_S_END_DATE, $additionalFieldList)) { ?>
                                <th><?php _e('M&S End Date', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                            <?php } ?>
                            <th><?php _e('License File', HPJ_CMLABS_I18N_DOMAIN); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($list as $item) {
                                
                                $expDate = null;
                                $cDate = strtotime($item->kcreate);
                                $isPermanent = false;
                                $isExpired = false;
                                /*
                                if (!empty($item->lasthate)) {
                                    $expDate = strtotime($item->lastdate);
                                    if ($expDate < $todayDate) {
                                        $isExpired = true;
                                    }
                                } else if (isset($item->exp) ) {
                                    if ((int)$item->exp === 0) {
                                        $isPermanent = true;
                                    }
                                    $expDate = $cDate + ((int)$item->exp * 60 * 60 * 24);
                                    if (!$isPermanent) {
                                        if ($expDate < $todayDate) {
                                            $isExpired = true;
                                        }
                                    }
                                } else {
                                    $isExpired = true;
                                }
                                $expiration = ($isPermanent) ? 'Never' : (($expDate < $todayDate) ? 'Expired'  : date('Y-m-d', $expDate));
                                */
                                $countFulFillment = count($item->fulfillments);

                                if (!empty($item->fulfillments)) {
                                    foreach ($item->fulfillments as $fulfillment) {
                                        $isActive = false;
                                        $isPermanent = false;
                                        $isExpired = false;
                                        $expDate = $fulfillment->expdate;
                                        $msDate = null;
                                        if (!empty($fulfillment->license_hostid) && trim($fulfillment->license_hostid) != '') {
                                            if ($expDate == 'permanent') {
                                                $isPermanent = true;
                                            } else {
                                                if (strtotime($expDate) < time()) {
                                                    $isExpired = true;    
                                                }    
                                            }
                                            if (!$isExpired) {
                                                $isActive = true;
                                            }   
                                        }
                                        $expiration = ($isPermanent) ? 'Never' : (($isExpired) ? 'Expired'  : $expDate);
                                        
                                        if (in_array(ADDITIONAL_FIELD_M_S_END_DATE, $additionalFieldList)) {
                                            
                                            if ($isActive) {
                                                if (!empty($fulfillment->license) && !empty($fulfillment->time)) {
                                                    if (preg_match_all('/LICENSE.*/', $fulfillment->license, $matches)) {
                                                        $date = null;
                                                        if (!empty($matches)) {
                                                            foreach ($matches[0] as $match) {
                                                                $newDate = explode(' ', $match);
                                                                if (!empty($newDate[3])) {
                                                                    $newDate = str_replace('.', '-', $newDate[3]);
                                                                    if (empty($date) || strtotime($date) < strtotime($newDate)) {
                                                                        $date = $newDate;
                                                                    }
                                                                }   
                                                            }   
                                                        }
                                                        if (!empty($date)) {               
                                                            $day = date('d', $fulfillment->time);
                                                            if (!empty($day)) {  
                                                                $msDate = $date . '-' . $day; 
                                                            }
                                                            
                                                        }
                                                    }
                                                }
                                            } else if (!$isExpired) {
                                                if (!empty($item->kver)) {
                                                    $date = time();
                                                    $msDate = date('Y-m-d', strtotime('+' . $item->kver . ' months', $date));
                                                }    
                                            }        
                                        }
                                
                                        ?>
                                            <tr class="compare-body__features">
                                            <td class="prod-key"><?php echo $item->akey; ?></td>
                                            <td class="prod-name"><?php echo (!empty($item->product)) ? $item->product->name : '' ; ?></td>
                                            <td>
                                                <?php
                                                    if ($isExpired) {
                                                        echo __('Expired', HPJ_CMLABS_I18N_DOMAIN);
                                                    } else {
                                                        echo (($isActive) ? __('Active', HPJ_CMLABS_I18N_DOMAIN) : __('Inactive', HPJ_CMLABS_I18N_DOMAIN));
                                                    }
                                                  ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($fulfillment)) { ?>
                                                    <?php if ($isActive || $isExpired) { ?>
                                                        <?php  echo $fulfillment->license_hostid; ?>
                                                     <?php } else if (!isActive && !$isExpired) { ?>
                                                        <a href="#form_hpj_cmlabs_manual_activation" data-toggle="modal" akey="<?php echo $item->akey; ?>"><?php _e('Manual Activation', HPJ_CMLABS_I18N_DOMAIN); ?></a>
                                                       <?php } ?>
                                                <?php } ?>
                                            </td>
                                            <?php if (in_array(ADDITIONAL_FIELD_APPLICATION_SIGNATURE, $additionalFieldList)) { ?>
                                                <td></td>
                                            <?php } ?>
                                            <?php if (in_array(ADDITIONAL_FIELD_EXPIRATION, $additionalFieldList)) { ?>
                                                <td><?php echo $expiration; ?></td>
                                            <?php } ?>
                                            <?php if (in_array(ADDITIONAL_FIELD_M_S_END_DATE, $additionalFieldList)) { ?>
                                                <td><?php echo (!empty($msDate)) ? $msDate : '' ?></td>
                                            <?php } ?>
                                            <td class="dld-btn">
                                                <?php if (!empty($fulfillment)) {						
						    $isExpiringSoon = false;
						    if ( !$isExpired && $essentials_licenses ) {
							$date1 = new DateTime( date( 'Y-m-d', time() ) );
							$date2 = new DateTime( $expiration );
							
							$interval = $date1->diff($date2);
							if ( !$interval->invert && $interval->days < 14 ) {
							    $isExpiringSoon = true;
							}
						    }
						    
						    if ( ( $isExpired || $isExpiringSoon ) && !empty( $item->product ) && FALSE !== strpos( $item->product->name, 'Essentials' ) ) {
							echo '<form method="POST"><input type="hidden" name="activation_key" value="' . $item->akey . '"><input type="hidden" name="host_id" value="' . $fulfillment->license_hostid . '"><input type="hidden" name="action" value="delete_rlm_fulfillment"><input class="btn btn-default" style="padding: 2px 15px;" type="submit" value="Renew"></form>';
						    } elseif ($isExpired) { ?>
                                                        <form action="<?php echo get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_CONTACT_RENEW)); ?>" method="post">
                                                            <input type="hidden" name="type" value="renewal" />
                                                            <input type="hidden" name="prodname" value="<?php echo (!empty($item->product)) ? $item->product->name : '' ; ?>" />
                                                            <input type="hidden" name="akey" value="<?php echo $item->akey; ?>" />
                                                            <input type="hidden" name="host_id" value="<?php echo $fulfillment->license_hostid; ?>" />
                                                            <input type="hidden" name="action" value="hpj-cmlabs-send-renewal-email" />
                                                            <?php wp_nonce_field( 'hpj-cmlabs-send-renewal-email' ) ?>
                                                            <button type="submit" class="btn-link"><?php _e('Renew', HPJ_CMLABS_I18N_DOMAIN); ?></button>
                                                        </form>
                                                    <?php } else { ?>
                                                        <a href="<?php echo get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_LICENSES_DOWNLOAD)); ?>?akey=<?php echo urlencode($item->akey); ?>&hostid=<?php echo urlencode($item->fulfillments[0]->license_hostid); ?>"><?php _e('Download', HPJ_CMLABS_I18N_DOMAIN); ?></a>
                                                    <?php } ?>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr class="compare-body__features">
                                        <td class="prod-key"><?php echo $item->akey; ?></td>
                                        <td><?php echo (!empty($item->product)) ? $item->product->name : '' ; ?></td>
                                        <td></td>
                                        <td>
                                            <a href="#form_hpj_cmlabs_manual_activation" data-toggle="modal" akey="<?php echo $item->akey; ?>"><?php _e('Manual Activation', HPJ_CMLABS_I18N_DOMAIN); ?></a>
                                        </td>
                                        <?php if (in_array(ADDITIONAL_FIELD_APPLICATION_SIGNATURE, $additionalFieldList)) { ?>
                                            <td></td>
                                        <?php } ?>
                                        <?php if (in_array(ADDITIONAL_FIELD_EXPIRATION, $additionalFieldList)) { ?>
                                            <td></td>
                                        <?php } ?>
                                        <?php if (in_array(ADDITIONAL_FIELD_M_S_END_DATE, $additionalFieldList)) { ?>
                                            <td></td>
                                        <?php } ?>
                                        <td></td>
                                    </tr>
                                    <?php
                                }
                                if ($countFulFillment > 1 && $countFulFillment < $item->count && !$isExpired) {
                                ?>
                                    <tr class="compare-body__features">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <a href="#form_hpj_cmlabs_manual_activation" data-toggle="modal" akey="<?php echo $item->akey; ?>"><?php _e('Manual Activation', HPJ_CMLABS_I18N_DOMAIN); ?></a>
                                        </td>
                                        <?php if (in_array(ADDITIONAL_FIELD_APPLICATION_SIGNATURE, $additionalFieldList)) { ?>
                                            <td></td>
                                        <?php } ?>
                                        <?php if (in_array(ADDITIONAL_FIELD_EXPIRATION, $additionalFieldList)) { ?>
                                            <td></td>
                                        <?php } ?>
                                        <?php if (in_array(ADDITIONAL_FIELD_M_S_END_DATE, $additionalFieldList)) { ?>
                                            <td></td>
                                        <?php } ?>
                                        <td></td>
                                    </tr>
                                <?php
                                }
                            }
                        ?>
                    </tbody>
                </table>
				
                <?php if (!empty($emailLink) || !empty($activationLink)) { ?>
                    <div class="text-right request-link">
						<?php if (!empty($activationLink)) { ?>
							<a href="<?php echo $activationLink['href']; ?>" target="_blank"><?php echo $activationLink['name']; ?></a>
						<?php 
							if (!empty($emailLink)) {
								echo ' | ';
							}						
						}							
						if (!empty($emailLink)) { ?>
                        <a href="<?php echo $emailLink['href']; ?>"><?php echo $emailLink['name']; ?></a>
						<?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php
        }
    }

    ?>
    <?php      

    // Display Activation Key Form
    Hpj_CMLabs_Dispatcher::dispatch('license', 'registrationActivationKeyForm');             

    // Display Essentials edition list
    if (empty($essential_list) ) {     
    ?>
    <div>
		<h3>Vortex Studio Essentials</h3>
        <p><?php _e('You do not have any Vortex Studio Essentials license attached to your account at this time. Add a free Vortex Studio Essentials license to your account by pressing the button below. To learn more about Vortex Studio Essentials, ', HPJ_CMLABS_I18N_DOMAIN); ?><a href="/essentials"><?php _e( 'click here', HPJ_CMLABS_I18N_DOMAIN ); ?>.</a></p>
        <div>
            <form action="<?php echo get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_LICENSES)); ?>" method="post">
                <input type="hidden" name="action" value="hpj-cmlabs-generate-activation-key" />
                <?php wp_nonce_field( 'hpj-cmlabs-generate-activation-key' ) ?>
                <button type="submit" class="btn btn-default"><?php _e('Get Vortex Studio Essentials', HPJ_CMLABS_I18N_DOMAIN); ?></button>
            </form>
        </div>
    </div>
    <?php
    } else {
		$fieldList = array(ADDITIONAL_FIELD_EXPIRATION);
		$link = array(
			'href' => get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_DOWNLOADS)),
			'class' => 'btn',
			'name' => __('Download Vortex Studio Essentials', HPJ_CMLABS_I18N_DOMAIN),
		);
		$email = array(
			'href' => get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_CONTACT_SUPPORT)),
			'name' => __('Request a new license', HPJ_CMLABS_I18N_DOMAIN)
		);
		$activation = array(
			'href' => get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_ACTIVATE_LICENSE)),
			'name' => __('How to Activate your License', HPJ_CMLABS_I18N_DOMAIN)
		);
		
		if ( $_GET['renewed'] ) { ?>
		    <p class="hpj_cmlabs_messages">Your license has been renewed.</p>
		<?php }
				
		displayGrid( $essential_list, __('Vortex Studio Essentials Edition', HPJ_CMLABS_I18N_DOMAIN), $fieldList, $link, $email, $activation, true );
	} ?>
	
	<br />
	
	<?php if ( empty($premium_list) && empty($runtime_list) && empty($other_list) ) { ?>
	<div>
		<h3><?php _e( 'Vortex Studio Academic, Solo and Team Editions', HPJ_CMLABS_I18N_DOMAIN ); ?></h3>
		<p><?php _e( 'If you previously purchased older versions of Vortex, click below to upgrade your existing licenses to the latest version of Vortex Studio.', HPJ_CMLABS_I18N_DOMAIN ); ?></p>
        <div>                                                                                
            <a class="btn btn-default" href="<?php echo get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_CONTACT_LICENSING)); ?>"><?php _e('Upgrade a Vortex Software Solution license', HPJ_CMLABS_I18N_DOMAIN); ?></a>
        </div>
		<br />
		<h3><?php _e( 'Older Versions of Vortex Software Solution', HPJ_CMLABS_I18N_DOMAIN ); ?></h3>
		<p><?php _e( 'To access licenses of Vortex Software Solution 6.8 and earlier, visit', HPJ_CMLABS_I18N_DOMAIN ); ?> <a href="https://my.vxsim.com">my.vxsim.com</a></p>
	</div>	
	<?php } else {
		// Display Premium list
		if (!empty($premium_list)) {
			$fieldList = array(ADDITIONAL_FIELD_EXPIRATION, ADDITIONAL_FIELD_M_S_END_DATE);
			$link = array(
				'href' => get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_DOWNLOADS)),
				'name' => __('Download Vortex Studio', HPJ_CMLABS_I18N_DOMAIN),
			);
			$email = array(
				'href' => get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_CONTACT_SALE)),
				'name' => __('Request a new license', HPJ_CMLABS_I18N_DOMAIN)
			);
			$activation = array(
				'href' => get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_ACTIVATE_LICENSE)),
				'name' => __('How to Activate your License', HPJ_CMLABS_I18N_DOMAIN)
			);
			displayGrid($premium_list, __('Vortex Studio Academic, Solo and Team Edition', HPJ_CMLABS_I18N_DOMAIN), $fieldList, $link, $email, $activation, false);
		}

		// Display Runtime list
		if (!empty($runtime_list)) {
			$fieldList = array(ADDITIONAL_FIELD_EXPIRATION, ADDITIONAL_FIELD_APPLICATION_SIGNATURE);
			displayGrid($runtime_list, __('Runtimes', HPJ_CMLABS_I18N_DOMAIN), $fieldList, '', '', '', false);
		}

		// Display Other list
		if (!empty($other_list)) {
			$fieldList = array(ADDITIONAL_FIELD_EXPIRATION, ADDITIONAL_FIELD_M_S_END_DATE);
			displayGrid($other_list, __('Other', HPJ_CMLABS_I18N_DOMAIN), $fieldList, '', '', '', false);
		} ?>

		
		<br />
		<a class="pull-right" href="https://my.vxsim.com/" target="_blank"><?php _e('Looking for Vortex Software Solution 6.8 and earlier? Click here', HPJ_CMLABS_I18N_DOMAIN);?></a>

    <?php } ?>
    </div>