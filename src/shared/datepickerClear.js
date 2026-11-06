/**
 * Adds the "Clear" button to the button panel of the jQuery UI datepickers,
 * as cleanDatepicker() of the former custom.js did.
 *
 * Only the pickers of the delivery settings form get it, the button triggers
 * `prdd:clear` on the input so the React components can update their state.
 */
import { __ } from '@wordpress/i18n';

let patched = false;

export default function addDatepickerClearButton() {
	if ( patched || ! window.jQuery || ! window.jQuery.datepicker ) {
		return;
	}
	patched = true;

	const $ = window.jQuery;
	const original = $.datepicker._updateDatepicker;

	$.datepicker._updateDatepicker = function ( inst ) {
		original.call( this, inst );

		if (
			! inst.input ||
			! inst.input.closest( '#prdd-product-page' ).length
		) {
			return;
		}

		const buttonPane = $( this )
			.datepicker( 'widget' )
			.find( '.ui-datepicker-buttonpane' );

		$(
			"<button type='button' class='ui-datepicker-clean ui-state-default ui-priority-primary ui-corner-all'></button>"
		)
			.text( __( 'Clear', 'product-delivery-date-for-woocommerce-lite' ) )
			.appendTo( buttonPane )
			.on( 'click', () => {
				if ( inst.input.data( 'prdd-multi' ) ) {
					inst.input.multiDatesPicker( 'resetDates' );
				} else {
					inst.input.val( '' );
				}
				inst.input.trigger( 'prdd:clear' );
			} );
	};
}
