/**
 * This function is used to change meta_keys to make equivalent to pro plugin.
 *
 */
jQuery(document).ready(function($){

	$('#prdd-update-no').click(function(){
		$.post( prdd_lite_ajax_data.ajax_url, {
            action : 'prdd_lite_update_database',
            is_update : 'no',
            prdd_nonce: prdd_lite_ajax_data.prdd_nonce,
			
		}, function( response ) {
			window.location = 'admin.php?page=woocommerce_prdd_lite_history_page';
		});
	});

	$('#prdd-update-yes').click(function(){
        $('#prdd-update-status').html('Database update process is started. Please do not refresh page.').show();
        prdd_page = $('#prdd-update-status').data('page');
        if ( ! prdd_page ) {
            prdd_page = 1;
        }
        prdd_update_database_script( prdd_page );
	});
});

function prdd_update_database_script( page ) {
    jQuery.post( prdd_lite_ajax_data.ajax_url, {
        action : 'prdd_lite_update_database',
        is_update : 'yes',
        prdd_nonce: prdd_lite_ajax_data.prdd_nonce,
        max_product: prdd_lite_ajax_data.max_product,
        page: page,
    }, function( response ) {
        ajax_data = jQuery.parseJSON( response );
        page = parseInt( page ) + 1;
        console.log( page );
        if ( page > ajax_data.total_page ) {
            window.location.replace("admin.php?page=woocommerce_prdd_lite_history_page&message=updated");
        } else {
            jQuery('#prdd-update-status').html(page+' pages are updated from '+ajax_data.total_page ).show();
            prdd_update_database_script( page );
        }
    });
}
