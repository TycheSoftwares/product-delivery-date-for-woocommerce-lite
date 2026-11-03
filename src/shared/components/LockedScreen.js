/**
 * Static, non interactive preview of a Pro screen under the Upgrade to Pro
 * modal, like the earlier Lite teaser pages.
 */
import UpgradeModal from './UpgradeModal';

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
	return (
		<div className={ `prddd-shipping-based ${ className }`.trim() }>
			<div
				className="prddd-lite-settings-inner-section"
				ref={ setInert }
				aria-hidden="true"
			>
				{ children }
			</div>
			<UpgradeModal />
		</div>
	);
}
