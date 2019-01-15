<?php global $sim_tools; 
$selected_tool = false;
foreach ( $sim_tools as $sim_tool ) {
    if ( $form_datas[sanitize_title( $sim_tool )] ) {
	$selected_tool = true;
    }
}

?>
<div id="post-<?php the_ID(); ?>">
    <div class="entry-content entry">
        <?php if ( !is_user_logged_in() ) : ?>
            <p class="warning">
                <?php _e('You must be logged in to edit your profile.', 'profile'); ?>
            </p><!-- .warning -->
        <?php else : ?>
			
		<?php if ( $_GET['incomplete_profile'] ) { ?>
		<p class="warning">You have been redirected here from the Licenses page since you are a Vortex Essential user and your profile is incomplete.</p>
		<?php } ?>
		
			<p><?php _e( 'Please complete all mandatory fields (*) in all tabs of your account details. Vortex Studio Essentials users must complete these fields before they can renew their licenses.', HPJ_CMLABS_I18N_DOMAIN ); ?></p>
            <?php if ( count($error) > 0 ) echo '<p class="error">' . implode("<br />", $error) . '</p>'; ?>
            <form class="form-inline edit-account row" method="post" id="adduser" action="<?php echo get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_ACCOUNT)); ?>">
			
			<div>
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item active">
				<a class="nav-link<?php if ( isset( $_GET['missing_items'] ) && ( empty( $form_datas['title'] ) || empty( $form_datas['first_name'] ) || empty( $form_datas['last_name'] ) || empty( $form_datas['email'] ) || empty( $form_datas['knowledge_background'] ) || ( 'Other (Specify below)' == $form_datas['knowledge_background'] && empty( $form_datas['other_knowledge_background'] ) ) || empty( $form_datas['vortex_source'] ) || ( 'Other (Specify below)' == $form_datas['vortex_source'] && empty( $form_datas['other_vortex_source'] ) ) ) ) { echo ' missing'; } ?>" id="personal-info-tab" data-toggle="tab" href="#personal-info" role="tab" aria-controls="home" aria-selected="true">Personal Info</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link<?php if ( isset( $_GET['missing_items'] ) && ( empty( $form_datas['company'] ) || empty( $form_datas['city'] ) || empty( $form_datas['state'] ) || empty( $form_datas['country'] ) || empty( $form_datas['company_industry'] ) || ( 'Other (Specify below)' == $form_datas['company_industry'] && empty( $form_datas['other_company_industry'] ) ) || empty( $form_datas['company_size'] ) ) ) { echo ' missing'; } ?>" id="company-tab" data-toggle="tab" href="#company" role="tab" aria-controls="company" aria-selected="false">Company Info</a>
			  </li>
  			  <li class="nav-item">
				<a class="nav-link<?php if ( isset( $_GET['missing_items'] ) && ( ( !$selected_tool && empty( $form_datas['other_sim_tools'] ) && !$form_datas['no_sim_tools'] ) || empty( $form_datas['primary_use'] ) || empty( $form_datas['type_equipment_simulated'] ) || empty( $form_datas['type_equipment_simulated'] ) || empty( $form_datas['client_industry'] ) || ( 'Other (Specify below)' == $form_datas['client_industry'] && empty( $form_datas['other_client_industry'] ) ) || empty( $form_datas['use_type'] ) || ( 'Other (Specify below)' == $form_datas['use_type'] && empty( $form_datas['other_use_type'] ) )) ) { echo ' missing'; } ?>" id="vortex-studio-tab" data-toggle="tab" href="#vortex-studio" role="tab" aria-controls="vortex-studio" aria-selected="false">Tell us how you use Vortex Studio</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">Reset Password</a>
			  </li>
			</ul>
			</div>
			
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade active in" id="personal-info" role="tabpanel" aria-labelledby="personal-info-tab">
					<p class="form-username form-group">
                        <label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['title'] ) ) { echo 'class="missing-data-label"'; } ?> for="title"><?php _e('Title *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
			<input class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['title'] ) ) { echo ' missing'; } ?>" name="title" maxlength="256" type="text" id="title" value="<?php echo (!empty($form_datas) && !empty($form_datas['title'])) ? $form_datas['title'] : ''; ?>" />
                    </p><!-- .form-username -->

                    <p class="form-username form-group">
                        <label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['first_name'] ) ) { echo 'class="missing-data-label"'; } ?> for="first-name"><?php _e('First Name *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
			<input class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && empty ( $form_datas['first_name'] ) ) { echo ' missing'; }?>" name="first_name" maxlength="256" type="text" id="first_name" value="<?php echo (!empty($form_datas) && !empty($form_datas['first_name'])) ? $form_datas['first_name'] : ''; ?>" />
                    </p><!-- .form-username -->

                    <p class="form-username form-group">
                        <label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['last_name'] ) ) { echo 'class="missing-data-label"'; } ?> for="last-name"><?php _e('Last Name *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
			<input class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['last_name'] ) ) { echo ' missing'; } ?>" name="last_name" maxlength="256" type="text" id="last_name" value="<?php echo (!empty($form_datas) && !empty($form_datas['last_name'])) ? $form_datas['last_name'] : ''; ?>" />
                    </p><!-- .form-username -->
					
					<p class="form-email form-group">
                        <label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['email'] ) ) { echo 'class="missing-data-label"'; } ?> for="email"><?php _e('E-mail *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
			<input class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['email'] ) ) { echo ' missing'; } ?>" name="email" maxlength="256" type="text" id="email" value="<?php echo (!empty($form_datas) && !empty($form_datas['email'])) ? $form_datas['email'] : ''; ?>" />
                    </p><!-- .form-email -->
					
		    <p class="form-username form-group">
                        <label for="phone"><?php _e('Phone', HPJ_CMLABS_I18N_DOMAIN); ?></label>
			<input class="text-input form-control" name="phone" maxlength="256" type="text" id="phone" value="<?php echo (!empty($form_datas) && !empty($form_datas['phone'])) ? $form_datas['phone'] : ''; ?>" />
                    </p><!-- .form-email -->
		    
		    <p class="form-company form-group">
			<label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['knowledge_background'] ) ) { echo 'class="missing-data-label"'; } ?> for="type_equipment_simulated"><?php _e('Knowledge Background *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                        <?php
                            $knowledgelist = array(
                                __( 'Aerospace Engineering', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Civil Engineering', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Hardware Engineering', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Mechanical Engineering', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Mechatronics Engineering', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Robotics Engineering', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Software Engineering', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Software Development', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Other (Specify below)', HPJ_CMLABS_I18N_DOMAIN ),
                            );
                            $selectedValue = (!empty($form_datas) && !empty($form_datas['knowledge_background'])) ? $form_datas['knowledge_background'] : '';
                        ?>
                        <select class="form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $selectedValue ) ) { echo ' missing'; } ?>" name="knowledge_background" id="knowledge_background">
                            <option value="">Please make a selection</option>
                            <?php foreach ($knowledgelist as $value) { ?>
                                <option value="<?php echo $value; ?>" <?php if (!empty($selectedValue) && $value == $selectedValue) { echo 'selected'; } ?>><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
		    </p>
		    <p class="form-company form-group">
			<label for="other_knowledge_background"></label>
			<input class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && ( 'Other (Specify below)' == $form_datas['knowledge_background'] && empty( $form_datas['other_knowledge_background'] ) ) ) { echo ' missing'; } ?>" name="other_knowledge_background" maxlength="256" type="text" id="phone" placeholder="Other knowledge background" value="<?php echo (!empty($form_datas) && !empty($form_datas['other_knowledge_background'])) ? $form_datas['other_knowledge_background'] : ''; ?>" />
		    </p>
		    
		    <p class="form-company form-group">
			<label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['vortex_source'] ) ) { echo 'class="missing-data-label"'; } ?> for="type_equipment_simulated"><?php _e('How did you hear about Vortex Studio Essentials? *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                        <?php
                            $sourcelist = array(
                                __( 'Google Search', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Online Ads', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Print Magazine', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'LinkedIn', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Facebook', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Twitter', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'TradeShow', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Colleague', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Other (Specify below)', HPJ_CMLABS_I18N_DOMAIN ),
                            );
                            $selectedValue = (!empty($form_datas) && !empty($form_datas['vortex_source'])) ? $form_datas['vortex_source'] : '';
                        ?>
                        <select class="form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $selectedValue ) ) { echo ' missing'; } ?>" name="vortex_source" id="vortex_source">
                            <option value="">Please make a selection</option>
                            <?php foreach ($sourcelist as $value) { ?>
                                <option value="<?php echo $value; ?>" <?php if (!empty($selectedValue) && $value == $selectedValue) { echo 'selected'; } ?>><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
		    </p>
		    <p class="form-company form-group">
			<label for="other_vortex_source"></label>
			<input class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && ( 'Other (Specify below)' == $form_datas['vortex_source'] && empty( $form_datas['other_vortex_source'] ) ) ) { echo ' missing'; } ?>" name="other_vortex_source" maxlength="256" type="text" id="other_vortex_source" placeholder="Other Vortex Studio Essentials source" value="<?php echo (!empty($form_datas) && !empty($form_datas['other_vortex_source'])) ? $form_datas['other_vortex_source'] : ''; ?>" />
		    </p>
					
		    <p class="form-company form-group">
                        <label for="twitter"><?php _e('Twitter profile', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                        <input class="text-input form-control" name="twitter" maxlength="256" type="text" id="twitter" value="<?php echo (!empty($form_datas) && !empty($form_datas['twitter'])) ? $form_datas['twitter'] : ''; ?>" />
                    </p><!-- .form-url -->

                    <p class="form-company form-group">
                        <label for="linkedin"><?php _e('Linkedin profile', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                        <input class="text-input form-control" name="linkedin" maxlength="256" type="text" id="linkedin" value="<?php echo (!empty($form_datas) && !empty($form_datas['linkedin'])) ? $form_datas['linkedin'] : ''; ?>" />
                    </p><!-- .form-url -->
				</div>
				<div class="tab-pane fade" id="company" role="tabpanel" aria-labelledby="company-tab">
					<p class="form-company form-group">
                        <label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['company'] ) ) { echo 'class="missing-data-label"'; } ?> for="company"><?php _e('Company Name *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
			<input class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['company'] ) ) { echo ' missing'; } ?>" name="company" maxlength="256" type="text" id="company" value="<?php echo (!empty($form_datas) && !empty($form_datas['company'])) ? $form_datas['company'] : ''; ?>" />
                    </p><!-- .form-url -->

                    <p class="form-company form-group">
                        <label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['city'] ) ) { echo 'class="missing-data-label"'; } ?> for="city"><?php _e('City *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
			<input class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['city'] ) ) { echo ' missing'; } ?>" name="city" maxlength="256" type="text" id="city" value="<?php echo (!empty($form_datas) && !empty($form_datas['city'])) ? $form_datas['city'] : ''; ?>" />
                    </p><!-- .form-url -->

                    <p class="form-company form-group">
                        <label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['state'] ) ) { echo 'class="missing-data-label"'; } ?> for="state"><?php _e('State/Province *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
			<input class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['state'] ) ) { echo ' missing'; } ?>" name="state" maxlength="256" type="text" id="state" value="<?php echo (!empty($form_datas) && !empty($form_datas['state'])) ? $form_datas['state'] : ''; ?>" />
                    </p><!-- .form-url -->

                    <p class="form-company form-group">
                        <label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['country'] ) ) { echo 'class="missing-data-label"'; } ?> for="country"><?php _e('Country *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
			<input class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['country'] ) ) { echo ' missing'; } ?>" name="country" maxlength="256" type="text" id="country" value="<?php echo (!empty($form_datas) && !empty($form_datas['country'])) ? $form_datas['country'] : ''; ?>" />
                    </p><!-- .form-url -->
	
		    <p class="form-company form-group">
			<label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['company_industry'] ) ) { echo 'class="missing-data-label"'; } ?> for="type_equipment_simulated"><?php _e('Industry *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                        <?php
                            $company_industry_list = array(
                                __( 'Construction', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Education Services', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Entertainment/Games', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Government Agency', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Industrial Materials', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Off Highway Equipment', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Robotics Civil', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Simulators Defense', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Military', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Transportation - Port', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Transportation Rail and Ground', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Utilities', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Mining', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Forestry-Agriculture', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Public Works', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Corrections', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Academia', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Energy - Offshore', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Energy - Onshore', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Other (Specify below)', HPJ_CMLABS_I18N_DOMAIN ),
                            );
                            $selectedValue = (!empty($form_datas) && !empty($form_datas['company_industry'])) ? $form_datas['company_industry'] : '';
                        ?>
                        <select class="form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $selectedValue ) ) { echo ' missing'; } ?>" name="company_industry" id="company_industry">
                            <option value="">Please make a selection</option>
                            <?php foreach ($company_industry_list as $value) { ?>
                                <option value="<?php echo $value; ?>" <?php if (!empty($selectedValue) && $value == $selectedValue) { echo 'selected'; } ?>><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
		    </p>
		    
		    <p class="form-company form-group">
			<label for="other_company_industry"></label>
			<input class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && ( 'Other (Specify below)' == $form_datas['company_industry'] && empty( $form_datas['other_company_industry'] ) ) ) { echo ' missing'; } ?>" name="other_company_industry" maxlength="256" type="text" id="other_company_industry" placeholder="Other company industry" value="<?php echo (!empty($form_datas) && !empty($form_datas['other_company_industry'])) ? $form_datas['other_company_industry'] : ''; ?>" />
		    </p>
		    
		    <p class="form-company form-group">
			<label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['company_size'] ) ) { echo 'class="missing-data-label"'; } ?> for="type_equipment_simulated"><?php _e('Company Size *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                        <?php			
                            $company_size_list = array(
                                __( 'More than 5,000 employees', HPJ_CMLABS_I18N_DOMAIN ),
                                __( '1,000 to 5,000 employees', HPJ_CMLABS_I18N_DOMAIN ),
				__( '200 to 1,000 employees', HPJ_CMLABS_I18N_DOMAIN ),
                                __( '50 to 200 employees', HPJ_CMLABS_I18N_DOMAIN ),
                                __( '1 to 50 employees', HPJ_CMLABS_I18N_DOMAIN ),
                            );
                            $selectedValue = (!empty($form_datas) && !empty($form_datas['company_size'])) ? $form_datas['company_size'] : '';
                        ?>
                        <select class="form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $selectedValue ) ) { echo ' missing'; } ?>" name="company_size" id="company_size">
                            <option value="">Please make a selection</option>
                            <?php foreach ($company_size_list as $value) { ?>
                                <option value="<?php echo $value; ?>" <?php if (!empty($selectedValue) && $value == $selectedValue) { echo 'selected'; } ?>><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
		    </p>
		    
					<p class="form-company form-group">
                        <label for="website"><?php _e('Website', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                        <input class="text-input form-control" name="website" maxlength="256" type="text" id="website" value="<?php echo (!empty($form_datas) && !empty($form_datas['website'])) ? $form_datas['website'] : ''; ?>" />
                    </p><!-- .form-url -->
				</div>
				<div class="tab-pane fade" id="vortex-studio" role="tabpanel" aria-labelledby="vortex-studio-tab">
				    
		    <p class="form-company form-group">
                        <label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['use_type'] ) ) { echo 'class="missing-data-label"'; } ?> for="primary_use"><?php _e('What do you use it for? *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                        <?php
                            $useList = array(
                                __('Work', HPJ_CMLABS_I18N_DOMAIN),
                                __('School', HPJ_CMLABS_I18N_DOMAIN),
				__('Personal', HPJ_CMLABS_I18N_DOMAIN),
                                __('Other (Specify below)', HPJ_CMLABS_I18N_DOMAIN)
                            );
                            $selectedValue = (!empty($form_datas) && !empty($form_datas['use_type'])) ? $form_datas['use_type'] : '';
                        ?>
                        <select class="form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $selectedValue ) ) { echo ' missing'; } ?>" name="use_type" id="use_type">
                            <option value="">Please make a selection</option>
                            <?php foreach ($useList as $value) { ?>
                                <option value="<?php echo $value; ?>" <?php if (!empty($selectedValue) && $value == $selectedValue) { echo 'selected'; } ?>><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                    </p><!-- .form-url -->
		    
		    <p class="form-company form-group">
			<label for="other_use_type"></label>
			<input class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && ( 'Other (Specify below)' == $form_datas['use_type'] && empty( $form_datas['other_use_type'] ) ) ) { echo ' missing'; } ?>" name="other_use_type" maxlength="256" type="text" id="other_use_type" placeholder="Other uses" value="<?php echo (!empty($form_datas) && !empty($form_datas['other_use_type'])) ? $form_datas['other_use_type'] : ''; ?>" />
		    </p>
		    
		    <p class="form-company form-group">
                        <label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['primary_use'] ) ) { echo 'class="missing-data-label"'; } ?> for="primary_use"><?php _e('Primary Vortex Studio use *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                        <?php
                            $useList = array(
                                __('Training Solution Development', HPJ_CMLABS_I18N_DOMAIN),
                                __('Virtual Prototyping', HPJ_CMLABS_I18N_DOMAIN),
                                __('Other', HPJ_CMLABS_I18N_DOMAIN)
                            );
                            $selectedValue = (!empty($form_datas) && !empty($form_datas['primary_use'])) ? $form_datas['primary_use'] : '';
                        ?>
                        <select class="form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $selectedValue ) ) { echo ' missing'; } ?>" name="primary_use" id="primary_use">
                            <option value="">Please make a selection</option>
                            <?php foreach ($useList as $value) { ?>
                                <option value="<?php echo $value; ?>" <?php if (!empty($selectedValue) && $value == $selectedValue) { echo 'selected'; } ?>><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                    </p><!-- .form-url -->

                    <p class="form-company form-group">
                        <label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['type_equipment_simulated'] ) ) { echo 'class="missing-data-label"'; } ?> for="type_equipment_simulated"><?php _e('Type of Equipment Simulated *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                        <?php
                            $useList = array(
                                __( 'Wheeled Vehicle', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Tracked Vehicle', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Robotic System', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Maritime Equipment', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Subsea Equipment', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Other', HPJ_CMLABS_I18N_DOMAIN ),
                            );
                            $selectedValue = (!empty($form_datas) && !empty($form_datas['type_equipment_simulated'])) ? $form_datas['type_equipment_simulated'] : '';
                        ?>
                        <select class="form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $selectedValue ) ) { echo ' missing'; } ?>" name="type_equipment_simulated" id="type_equipment_simulated">
                            <option value="">Please make a selection</option>
                            <?php foreach ($useList as $value) { ?>
                                <option value="<?php echo $value; ?>" <?php if (!empty($selectedValue) && $value == $selectedValue) { echo 'selected'; } ?>><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                    </p><!-- .form-url -->
					
		    <?php
			if ( !$selected_tool && empty( $otherSimTools ) && !$noSimTools ) {
			    echo '<p class="form-company form-group form-group-no-background missing-data-label"><label for="simulation_software_used">' . __('Please select one or more tools below, specify Other software used, or indicate that No other simulation software is used', HPJ_CMLABS_I18N_DOMAIN) . '</label><p>' . "\n";
			}
		    
			sort( $sim_tools );
			$nb_sim_tools = sizeof( $sim_tools );
			$nb_rows = ceil( $nb_sim_tools / 3 );

			for ( $row = 0; $row < $nb_rows; $row++ ) {
			    $start_value = 0 + $row;
			    echo '<p class="form-company form-group form-group-no-background">' . "\n";
			    if ( 0 == $row ) {
				echo '<label for="simulation_software_used">' . __('Other software used *', HPJ_CMLABS_I18N_DOMAIN) . '</label>' . "\n";
			    } else {
				echo '<label for="simulation_software_used"></label>' . "\n";
			    }			    
			    
			    $item = 0;
			    for( $i = $start_value; $i < $nb_sim_tools; $i+= $nb_rows ) {
				echo '<input type="checkbox" ';
				checked( $form_datas[sanitize_title( $sim_tools[$i] )] );
				echo 'name="' . sanitize_title( $sim_tools[$i] ) . '" id="' . sanitize_title( $sim_tools[$i] )  . '" /><label class="shortlabel" for="' . sanitize_title( $sim_tools[$i] )  . '">' . $sim_tools[$i] . '</label>' . "\n";						    
				$item++;
			    }
			    if ( $item < 3 ) {
				echo '<label></label>' . "\n";						    
			    }
			    echo '</p>';
			}
		    ?>
					
		    <p>
			    <p class="form-company form-group">
			    <label for="simulation_software_used"></label>						
			    <label for="other">Other (specify)</label><input class="text-input form-control" name="other_sim_tools" maxlength="256" type="text" id="other_sim_tools" value="<?php echo (!empty($form_datas) && !empty($form_datas['other_sim_tools'])) ? $form_datas['other_sim_tools'] : ''; ?>" />
		    </p>
		    <p>
			    <p class="form-company form-group form-group-no-background">
			    <label for="simulation_software_used"></label>						
			    <input type="checkbox" <?php checked( $form_datas[no_sim_tools] ); ?> name="no_sim_tools" id="no_sim_tools" /><label style="width: 70%" for="other">No other simulation software used</label>
		    </p>
		    <p class="form-company form-group form-group-no-background">
                        <label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['proj_description'] ) ) { echo 'class="missing-data-label"'; } ?> for="proj_description"><?php _e('What type of simulation do you plan to do with Vortex Studio *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
<textarea class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['proj_description'] ) ) { echo ' missing'; } ?>" name="proj_description" rows="5" cols="100" id="proj_description"><?php echo (!empty($form_datas) && !empty($form_datas['proj_description'])) ? $form_datas['proj_description'] : ''; ?></textarea>
                    </p><!-- .form-url -->
		    
		    <p class="form-company form-group">
                        <label <?php if ( isset( $_GET['missing_items'] ) && empty( $form_datas['client_industry'] ) ) { echo 'class="missing-data-label"'; } ?> for="type_equipment_simulated"><?php _e('If work is done for a client, indicate client industry *', HPJ_CMLABS_I18N_DOMAIN); ?></label>
                        <?php
                            $company_industry_list = array(
                                __( 'Construction', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Education Services', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Entertainment/Games', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Government Agency', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Industrial Materials', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Off Highway Equipment', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Robotics Civil', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Simulators Defense', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Military', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Transportation - Port', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Transportation Rail and Ground', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Utilities', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Mining', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Forestry-Agriculture', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Public Works', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Corrections', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Academia', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Energy - Offshore', HPJ_CMLABS_I18N_DOMAIN ),
				__( 'Energy - Onshore', HPJ_CMLABS_I18N_DOMAIN ),
                                __( 'Other (Specify below)', HPJ_CMLABS_I18N_DOMAIN ),
                            );
                            $selectedValue = (!empty($form_datas) && !empty($form_datas['client_industry'])) ? $form_datas['client_industry'] : '';
                        ?>
                        <select class="form-control<?php if ( isset( $_GET['missing_items'] ) && empty( $selectedValue ) ) { echo ' missing'; } ?>" name="client_industry" id="client_industry">
                            <option value="">Please make a selection</option>
			    <option disabled>__________________</option>
			    <option value="Internal work / no client">Internal work / no client</option>
			    <option disabled>__________________</option>
                            <?php foreach ($company_industry_list as $value) { ?>
                                <option value="<?php echo $value; ?>" <?php if (!empty($selectedValue) && $value == $selectedValue) { echo 'selected'; } ?>><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                    </p><!-- .form-url -->
		    
		    <p class="form-company form-group">
			<label for="other_client_industry"></label>
			<input class="text-input form-control<?php if ( isset( $_GET['missing_items'] ) && ( 'Other (Specify below)' == $form_datas['client_industry'] && empty( $form_datas['other_client_industry'] ) ) ) { echo ' missing'; } ?>" name="other_client_industry" maxlength="256" type="text" id="other_client_industry" placeholder="Other client industry" value="<?php echo (!empty($form_datas) && !empty($form_datas['other_client_industry'])) ? $form_datas['other_client_industry'] : ''; ?>" />
		    </p>
		    
		    </div>
		    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
			    <p class="form-password form-group">
				    <label for="pass1"><?php _e('New Password', HPJ_CMLABS_I18N_DOMAIN); ?> </label>
				    <input class="text-input form-control" name="newpass1" maxlength="256" type="password" id="pass1" />
			    </p><!-- .form-password -->

			    <p class="form-password form-group">
				    <label for="pass2"><?php _e('Repeat New Password', HPJ_CMLABS_I18N_DOMAIN); ?></label>
				    <input class="text-input form-control" name="newpass2" maxlength="256" type="password" id="pass2" />
			    </p>
		    </div>
		</div>
			
                <input type="submit" class="submit btn btn-default" value="<?php _e('save', 'profile'); ?>" />
                <!-- .form-textarea -->
                <div class="col-sm-12">
                     <?php
                        //action hook for plugin and extra fields
                        do_action('edit_user_profile',$current_user);
                    ?>
                    <p class="form-submit">
                        <?php echo $referer; ?>
                        <!--<input name="updateuser" type="submit" id="updateuser" class="submit btn btn-default" value="<?php _e('Update', 'profile'); ?>" />-->
                        <?php wp_nonce_field( 'hpj-cmlabs-update-profile' ) ?>
                        <input name="action" type="hidden" id="action" value="hpj-cmlabs-update-profile" />
                    </p><!-- .form-submit -->
                </div>
            </form><!-- #adduser -->
        <?php endif; ?>
    </div><!-- .entry-content -->
</div><!-- .hentry .post -->
<script>
jQuery(document).ready(function($) {
 $(".form-control").focus(function(){
   $(this).parent().addClass("active");
  }).blur(function(){
    $(this).parent().not( ".form-password" ).removeClass("active");
  })
});
</script>