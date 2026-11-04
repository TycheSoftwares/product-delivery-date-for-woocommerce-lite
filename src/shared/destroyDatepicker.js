/**
 * Removes a jQuery UI datepicker from an element when React unmounts it.
 *
 * Errors are only logged: an exception thrown by a vendor script while
 * tearing down would otherwise unmount the whole React tree.
 *
 * @param {Object} $el jQuery element.
 */
export default function destroyDatepicker( $el ) {
	if ( ! $el.hasClass( 'hasDatepicker' ) ) {
		return;
	}
	try {
		$el.datepicker( 'destroy' );
	} catch ( e ) {
		// eslint-disable-next-line no-console
		console.error( e );
	}
}
