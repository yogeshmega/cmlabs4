jQuery(function() {
    
    jQuery('#hpj_cmlabs_setting_new_application, #hpj_cmlabs_setting_application_id').on('change', function() {
        if (jQuery(this).attr('id') == 'hpj_cmlabs_setting_new_application') {
            jQuery('#hpj_cmlabs_setting_application_id').val('');        
        } else {
            jQuery('#hpj_cmlabs_setting_new_application').val('');
            jQuery('#hpj_cmlabs_setting_new_application_version').val('');    
        }    
    });
    
    jQuery('.hpj_cmlabs_setting_delete_download').on('click', function(event) {
        if (!confirm('This element will be deleted. Are you sure ?')) {
            event.preventDefault();
        }
    });
    
    jQuery('.hpj_cmlabs_setting_delete_application').on('click', function(event) {
        if (!confirm('All the downloads items of this application will be deleted too. Are you sure ?')) {
            event.preventDefault();
        }
    });                                                     
    
});