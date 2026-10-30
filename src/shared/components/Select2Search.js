/**
 * Multiple select with the select2 ajax search used for products and
 * categories. The search goes through the prdd-lite/admin/v1/search endpoint.
 *
 * select2 manages the <option> nodes itself, so the select is uncontrolled:
 * `value` is only read on mount, render the component once the data loaded.
 * `allOption` ( {id, text} ) is listed first in the results, as the "All
 * Products" / "All categories" options of the Bulk Product Settings page.
 */
import { useEffect, useRef } from '@wordpress/element';
import { prddApiFetch } from '../api';

export default function Select2Search( {
	type,
	value = [],
	onChange,
	minimumInputLength = 3,
	allOption = null,
	...props
} ) {
	const ref = useRef( null );
	const changeRef = useRef( onChange );
	changeRef.current = onChange;

	useEffect( () => {
		const $select = jQuery( ref.current );

		value.forEach( ( item ) => {
			$select.append(
				new window.Option( item.text, String( item.id ), true, true )
			);
		} );

		$select.select2( {
			ajax: {
				delay: 250,
				transport( params, success, failure ) {
					const term = ( params.data && params.data.term ) || '';
					const request = prddApiFetch(
						`search/${ type }?q=${ encodeURIComponent( term ) }`
					);
					request.then( success ).catch( failure );
					return { abort() {} };
				},
				processResults( data ) {
					const results = [];
					if ( allOption && data ) {
						results.push( {
							id: String( allOption.id ),
							text: allOption.text,
						} );
					}
					if ( Array.isArray( data ) ) {
						data.forEach( ( item ) =>
							results.push( {
								id: String( item[ 0 ] ),
								text: item[ 1 ],
							} )
						);
					}
					return { results };
				},
				cache: true,
			},
			minimumInputLength,
		} );

		$select.on( 'change.prdd', () => {
			const selected = $select.select2( 'data' ).map( ( item ) => ( {
				id: String( item.id ),
				text: item.text,
			} ) );
			if ( changeRef.current ) {
				changeRef.current( selected );
			}
		} );

		return () => {
			$select.off( 'change.prdd' );
			if ( $select.data( 'select2' ) ) {
				$select.select2( 'destroy' );
			}
		};
	}, [ type, minimumInputLength ] ); // eslint-disable-line react-hooks/exhaustive-deps

	return <select ref={ ref } multiple="multiple" { ...props } />;
}
