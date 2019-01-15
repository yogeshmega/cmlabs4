jQuery(function() {

    var licenseFound = false;
    
    // Fill Acivation Key form
    jQuery('.hpj_cmlabs_register_activation_key').on('paste', function() {
        element = this;
        setTimeout(function () {
            var text = jQuery(element).val();
            var activationKeys = text.split(/ |-/g);
            if (activationKeys.length) {
                var length = activationKeys.length;
                var start = parseInt(jQuery(element).attr('index'));
                var i = start;
                length += i;
                
                for (i; i < length; i++) {
                    if (jQuery('#hpj_cmlabs_register_activation_key_' + i).length) {
                        jQuery('#hpj_cmlabs_register_activation_key_' + i).val(activationKeys[i - start]);
                    }
                }
            }
        }, 100);
            
    });
    
    jQuery('#form_hpj_cmlabs_manual_activation').on('show.bs.modal', function(event) {
        var button = jQuery(event.relatedTarget);
        akey = button.attr('akey');
        if (akey != null && akey != '') {
            jQuery('#hpj_cmlabs_manual_activation_akey_label').html(akey);
            jQuery('#hpj_cmlabs_manual_activation_akey').val(akey);
        }
    });
    
    function clearForm() {
        jQuery('#hpj_cmlabs_manual_activation_akey_label').html('');
        jQuery('#hpj_cmlabs_manual_activation_akey').val('');
        jQuery('#hpj_cmlabs_manual_activation_host_id').val('');
        jQuery('#hpj_cmlabs_manual_activation_response').html('');    
    }
    
    jQuery('#form_hpj_cmlabs_manual_activation').on('hidden.bs.modal', function(event) {
        clearForm();
        if (licenseFound) {
            location.reload();
        }    
    });
    
    jQuery('#hpj_cmlabs_manual_activation_submit').on('click', function(event) {
        jQuery('#hpj_cmlabs_manual_activation').submit();    
    });
    
    jQuery('#hpj_cmlabs_manual_activation').on('submit', function(event) {
        event.preventDefault();
        var akey = jQuery(this).find('#hpj_cmlabs_manual_activation_akey');
        var hostId = jQuery(this).find('#hpj_cmlabs_manual_activation_host_id');
        var errors = [];
        if (!(akey != null && akey != '')) {
            errors.push('Activation key is required');    
        }
        if (!(hostId != null && hostId != '')) {
            errors.push('Host ID is required');    
        }
        if (!errors.length) {
            jQuery.ajax({
                type: 'POST',
                url: jQuery(this).attr('action'),
                data: jQuery(this).serialize(),
                dataType: 'json'
            }).done(function(data) {
                var html = '';
                if (data.status) {
                    html = '<pre>' + data.license + '</pre>';
                    licenseFound = true;    
                } else {
                    html = 'Activation failed';    
                }                 
                jQuery('#hpj_cmlabs_manual_activation_response').html(html);
            });    
        } else {
            
        }
            
    });

}); 