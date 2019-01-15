/**
 * 	Plugin Name: User Last Visit
 *
 *  User Last Visit plugin for WordPress, Copyright (C) 2015 Rija Rajaonah
 *  User Last Visit plugin for WordPress is licensed under the GPL License version 2 or later.
 *  [http://www.gnu.org/licenses/gpl-2.0.txt]
 */
;(function( $ ) {
    "use strict";
    
    // preload a .gif image for AJAX loading purpose
    var preloader = $( '<p class="preloader" style="text-align:center;"><img src="/wp-admin/images/spinner.gif"/></p>' );
    
    // Add a role to the excluded role list
    function excludeRole( slug, name ) {
        var excludedRoleCode = 
        '<li>' + name +
            ' (<code>' + slug + '</code>)' +
            '<span class="ulv-action ulv-rem-role">' + ulvSettingsText.cancel + '</span>' +
            '<input type="hidden" name="exclude-by-role[]" value="' + slug + '" />' +
        '</li>';
        $( '#excluded-roles' ).append( $( excludedRoleCode ) );
        $( '#all-roles' ).find( 'li[data-slug="' + slug + '"]' ).find( '.ulv-add-role' ).addClass( 'muted' );
    }
    
    // On DOM ready
    $(function() {
        
        // Add an user role to the exclusion list
        $( document ).on( 'click', '.ulv-add-role', function( ev ) {
            ev.stopPropagation();
            if ( $( this ).hasClass( 'muted' ) ) {
                return;
            }
            var slug = $( this ).parents( 'li' ).attr( 'data-slug' );
            var name = $( this ).parents( 'li' ).attr( 'data-name' );
            excludeRole( slug, name );            
        } );
        
        // Remove a role from the role exclusion list
        $( document ).on( 'click', '.ulv-rem-role', function( ev ) {
            ev.stopPropagation();
            var slug = $( this ).siblings( 'input[type="hidden"]' ).val();
            $( '#all-roles li[data-slug="' + slug + '"]' ).find( '.ulv-add-role' ).removeClass( 'muted' );
            $( this ).parents( 'li' ).remove();
        } );
        
        // Add a particular user to the exclusion list
        $( document ).on( 'click', '#add-by-id', function( ev ) {
            ev.preventDefault();
            ev.stopPropagation();
            
            var userId = $( this ).data( 'userid' );
            var userLogin = $( this ).data( 'userlogin' );
            if ( 0 == $( '#excluded-users li[data-userid="' + userId + '"]' ).length ) {
                $( '#excluded-users' ).append(
                    $(
                        '<li data-userid="' + userId + '">' + 
                            userLogin + 
                            '<span class="ulv-action ulv-rem-user">' + ulvSettingsText.cancel + '</span>' +
                            '<input type="hidden" name="exclude-by-id[]" value="' + userId + '" />' +
                        '</li>'
                    )
                );
            }
        } );
        
        // Remove a particular user from the exclusion
        $( document ).on( 'click', '.ulv-rem-user', function( ev ) {
            ev.stopPropagation();
            ev.preventDefault();
            $( this ).parents( 'li' ).remove();
        } );
        
        // Search user by login
        $( '#pick-user' ).autocomplete({
            source: ulvAllLogins,
            select: function( event, ui ) {
                if ( ui.item.label ) {
                    var recip = $( '#user-preview' );
                    recip.empty().append( preloader );
                    var login = ui.item.value;
                    var formData = {
                        nonce : ulvSettingsText.ajaxNonce,
                        login : login,
                        action : 'ulv_user_preview',
                    }
                    
                    $.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: formData,
                        success: function ( data, textStatus, XMLHttpRequest ) {
                            recip.html( data );
                            
                        },
                        error: function ( MLHttpRequest, textStatus, errorThrown ) {
                            
                        }
                    });
                }
            },
            response: function ( ev, ui ) {
                if ( ! ui.content.length ) {
                    $( '#ulv-settings #no-result' ).show();
                } else {
                    $( '#ulv-settings #no-result' ).hide();
                }
            },
        });
        
    });
    
})( jQuery );
