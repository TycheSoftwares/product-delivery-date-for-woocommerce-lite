/**
 * Info icon with the help text in its title, as rendered by the PHP views.
 */
import { imageUrl } from '../config';

/**
 * Some help texts keep the line break entities of the PHP views in their
 * msgid, they are turned into real line breaks as the browser did before.
 *
 * @param {string} text Help text.
 * @return {string} Text.
 */
function decodeBreaks( text ) {
	return 'string' === typeof text
		? text.replace( /&#13;/g, '\r' ).replace( /&#10;/g, '\n' )
		: text;
}

export default function InfoTip( { text, className = 'tt-info' } ) {
	return (
		<img
			className={ className }
			src={ imageUrl( 'icon-info.svg' ) }
			alt="Info"
			data-toggle="tooltip"
			data-placement="top"
			title={ decodeBreaks( text ) }
		/>
	);
}
