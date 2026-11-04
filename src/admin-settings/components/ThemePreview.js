/**
 * Calendar theme helpers shared by the Appearance and the onboarding screens.
 */
import { useEffect, useRef } from '@wordpress/element';
import config from '../../shared/config';
import destroyDatepicker from '../../shared/destroyDatepicker';

/**
 * Swaps the stylesheet of the jQuery UI theme, as the earlier pages did.
 *
 * @param {string} theme Theme slug.
 */
export function setCalendarTheme( theme ) {
	if ( ! theme ) {
		return;
	}
	const sheet = document.getElementById( 'jquery-ui-style-prdd-css' );
	if ( null !== sheet ) {
		sheet.disabled = true;
		sheet.parentNode.removeChild( sheet );
	}
	const link = document.createElement( 'link' );
	link.rel = 'stylesheet';
	link.type = 'text/css';
	link.id = 'jquery-ui-style-prdd-css';
	link.href = config.themes_url + theme + '/jquery-ui.css';
	document.getElementsByTagName( 'HEAD' )[ 0 ].appendChild( link );
}

/**
 * Inline datepicker previewing the theme, first day and language.
 *
 * @param {Object} props
 * @param {string} props.start    First day of the week.
 * @param {string} props.language Calendar language.
 */
export default function ThemePreview( { start, language } ) {
	const ref = useRef( null );

	useEffect( () => {
		const $el = jQuery( ref.current );
		const regional =
			language && jQuery.datepicker.regional[ language ]
				? jQuery.datepicker.regional[ language ]
				: {};
		$el.datepicker( { ...regional, firstDay: parseInt( start, 10 ) || 0 } );
		return () => destroyDatepicker( $el );
	}, [ start, language ] );

	return <div id="datepicker" ref={ ref } />;
}
