/**
 * Text field with a jQuery UI datepicker.
 */
import { useEffect, useRef } from '@wordpress/element';
import destroyDatepicker from '../destroyDatepicker';

/**
 * @param {Object}   props
 * @param {string}   props.value    Date.
 * @param {Function} props.onChange Receives the date text.
 * @param {Function} props.onSelect Called when a date is picked in the calendar.
 * @param {Object}   props.options  Datepicker options.
 */
export default function DatePicker( {
	value = '',
	onChange,
	onSelect,
	options = {},
	...props
} ) {
	const ref = useRef( null );
	const changeRef = useRef( onChange );
	changeRef.current = onChange;
	const selectRef = useRef( onSelect );
	selectRef.current = onSelect;

	useEffect( () => {
		const $el = jQuery( ref.current );
		$el.datepicker( {
			...options,
			onSelect( dateText ) {
				if ( changeRef.current ) {
					changeRef.current( dateText );
				}
				if ( selectRef.current ) {
					selectRef.current( dateText );
				}
			},
		} );
		$el.on(
			'prdd:clear',
			() => changeRef.current && changeRef.current( '' )
		);

		return () => {
			$el.off( 'prdd:clear' );
			destroyDatepicker( $el );
		};
	}, [] ); // eslint-disable-line react-hooks/exhaustive-deps

	return (
		<input
			type="text"
			ref={ ref }
			value={ value }
			onChange={ ( event ) => onChange && onChange( event.target.value ) }
			{ ...props }
		/>
	);
}
