/**
 * jQuery UI multiDatesPicker bound to a list of d-m-yy dates.
 */
import { useEffect, useRef } from '@wordpress/element';
import config from '../config';
import destroyDatepicker from '../destroyDatepicker';

/**
 * Converts a d-m-yy string to a timestamp.
 *
 * @param {string} value Date.
 * @return {number|null} Timestamp.
 */
export function parseDmy( value ) {
	if ( 'string' !== typeof value || '' === value.trim() ) {
		return null;
	}
	const parts = value.trim().split( '-' );
	if ( 3 !== parts.length ) {
		return null;
	}
	const date = new Date(
		parseInt( parts[ 2 ], 10 ),
		parseInt( parts[ 1 ], 10 ) - 1,
		parseInt( parts[ 0 ], 10 )
	);
	return isNaN( date.getTime() ) ? null : date.getTime();
}

/**
 * @param {Object}   props
 * @param {string}   props.tag      'div' for an inline calendar, 'input' for a field.
 * @param {string[]} props.value    Selected dates ( d-m-yy ).
 * @param {Function} props.onChange Receives the selected dates.
 * @param {Object}   props.options  Extra multiDatesPicker options.
 */
export default function MultiDatesPicker( {
	tag = 'div',
	value = [],
	onChange,
	options = {},
	...props
} ) {
	const ref = useRef( null );
	const changeRef = useRef( onChange );
	changeRef.current = onChange;

	useEffect( () => {
		const $el = jQuery( ref.current );
		const dates = value.map( parseDmy ).filter( Boolean );
		const settings = {
			dateFormat: 'd-m-yy',
			firstDay: parseInt( config.first_day, 10 ) || 0,
			onSelect() {
				if ( changeRef.current ) {
					changeRef.current( $el.multiDatesPicker( 'getDates' ) );
				}
			},
			...options,
		};
		if ( dates.length > 0 ) {
			settings.addDates = dates;
		}
		$el.multiDatesPicker( settings );
		$el.data( 'prdd-multi', true );
		$el.on(
			'prdd:clear',
			() => changeRef.current && changeRef.current( [] )
		);

		return () => {
			$el.off( 'prdd:clear' );
			destroyDatepicker( $el );
		};
	}, [] ); // eslint-disable-line react-hooks/exhaustive-deps

	const Tag = tag;
	return <Tag ref={ ref } { ...props } />;
}
