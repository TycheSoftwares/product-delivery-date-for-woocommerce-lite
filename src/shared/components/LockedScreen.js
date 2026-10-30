/**
 * Static, non interactive preview of a Pro screen under the Upgrade to Pro
 * modal, like the earlier Lite teaser pages.
 */
import { createContext, useContext } from '@wordpress/element';
import UpgradeModal from './UpgradeModal';

/**
 * Whether the content is already inside a locked preview, where nested
 * locked parts and Pro notices are not repeated.
 */
export const LockedContext = createContext( false );

export const useInLockedPreview = () => useContext( LockedContext );

/**
 * Sets the inert attribute. Set through a ref, as React 18 has no inert prop
 * and React 19 reads an empty string as false.
 *
 * @param {HTMLElement|null} element Preview element.
 */
const setInert = ( element ) => {
	if ( element ) {
		element.setAttribute( 'inert', '' );
	}
};

export default function LockedScreen( { className = '', children } ) {
	const nested = useInLockedPreview();
	if ( nested ) {
		return children;
	}

	return (
		<div className={ `prddd-shipping-based ${ className }`.trim() }>
			<div
				className="prddd-lite-settings-inner-section"
				ref={ setInert }
				aria-hidden="true"
			>
				<LockedContext.Provider value={ true }>
					{ children }
				</LockedContext.Provider>
			</div>
			<UpgradeModal />
		</div>
	);
}
