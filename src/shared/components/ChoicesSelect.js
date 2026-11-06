/**
 * Multiple select enhanced with Choices.js, as the earlier "choices" component.
 */
import { useEffect, useRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Unique values as strings, in their order.
 *
 * @param {Array} values Values.
 * @return {Array} Values.
 */
const unique = ( values ) => [ ...new Set( ( values || [] ).map( String ) ) ];

/**
 * Comparable signature of the values, whatever their order.
 *
 * @param {Array} values Values.
 * @return {string} Signature.
 */
const signatureOf = ( values ) => JSON.stringify( unique( values ).sort() );

export default function ChoicesSelect( {
	options = [],
	value = [],
	onChange,
	...props
} ) {
	const ref = useRef( null );
	const instance = useRef( null );
	// Set while the items are updated from the props, Choices fires its
	// addItem / removeItem events for these too.
	const syncing = useRef( false );
	const changeRef = useRef( onChange );
	changeRef.current = onChange;
	const valueRef = useRef( value );
	valueRef.current = value;

	const selectedValues = () => {
		const current = instance.current.getValue( true );
		return unique( Array.isArray( current ) ? current : [ current ] );
	};

	const sync = ( callback ) => {
		syncing.current = true;
		try {
			callback();
		} finally {
			syncing.current = false;
		}
	};

	useEffect( () => {
		const select = ref.current;
		instance.current = new Choices( select, {
			removeItemButton: true,
			searchResultLimit: -1,
			shouldSort: false,
			loadingText: __(
				'Loading…',
				'product-delivery-date-for-woocommerce-lite'
			),
			noResultsText: __(
				'No results found',
				'product-delivery-date-for-woocommerce-lite'
			),
			noChoicesText: __(
				'No choices to choose from',
				'product-delivery-date-for-woocommerce-lite'
			),
			itemSelectText: __(
				'Press to select',
				'product-delivery-date-for-woocommerce-lite'
			),
		} );

		const handle = () => {
			if ( syncing.current || ! changeRef.current ) {
				return;
			}
			changeRef.current( selectedValues() );
		};

		select.addEventListener( 'addItem', handle );
		select.addEventListener( 'removeItem', handle );

		return () => {
			select.removeEventListener( 'addItem', handle );
			select.removeEventListener( 'removeItem', handle );
			instance.current.destroy();
			instance.current = null;
		};
	}, [] ); // eslint-disable-line react-hooks/exhaustive-deps

	// Replaces the choices when the options change.
	const optionsSignature = JSON.stringify( options );
	useEffect( () => {
		if ( ! instance.current ) {
			return;
		}
		const selected = unique( valueRef.current );
		sync( () => {
			// The selected items are added again with their choices.
			instance.current.removeActiveItems();
			instance.current.setChoices(
				options.map( ( option ) => ( {
					value: String( option.value ),
					label: option.label,
					selected: selected.includes( String( option.value ) ),
				} ) ),
				'value',
				'label',
				true
			);
		} );
	}, [ optionsSignature ] ); // eslint-disable-line react-hooks/exhaustive-deps

	// Keeps the selected items in sync when the value changes from outside.
	const valueSignature = signatureOf( value );
	useEffect( () => {
		if (
			! instance.current ||
			signatureOf( selectedValues() ) === valueSignature
		) {
			return;
		}
		sync( () => {
			instance.current.removeActiveItems();
			unique( value ).forEach( ( item ) =>
				instance.current.setChoiceByValue( item )
			);
		} );
	}, [ valueSignature ] ); // eslint-disable-line react-hooks/exhaustive-deps

	return <select ref={ ref } multiple="multiple" { ...props } />;
}
