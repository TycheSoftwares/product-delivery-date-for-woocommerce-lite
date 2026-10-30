/**
 * Notice of the fields that are available in the Pro version only.
 */
import { __ } from '@wordpress/i18n';
import { TRIAL_URL } from './UpgradeModal';
import { useInLockedPreview } from './LockedScreen';

export default function ProNotice() {
	return (
		<span className="prdd-lite-pro-notice">
			<span className="dashicons dashicons-lock"></span>{ ' ' }
			{ __(
				'Available in the Pro version.',
				'product-delivery-date-for-woocommerce-lite'
			) }{ ' ' }
			<a href={ TRIAL_URL } target="_blank" rel="noopener noreferrer">
				{ __(
					'Upgrade to Pro',
					'product-delivery-date-for-woocommerce-lite'
				) }
			</a>
		</span>
	);
}

/**
 * Pro only rows of a .tbl-mod-1 table: shown disabled, followed by the
 * upgrade notice. The fieldset is display: contents, so the rows keep the
 * layout of the Pro screen.
 *
 * @param {Object} props          Props.
 * @param {*}      props.children Rows.
 */
export function ProOnly( { children } ) {
	const inPreview = useInLockedPreview();
	if ( inPreview ) {
		return children;
	}

	return (
		<>
			<fieldset disabled className="prdd-lite-pro-only">
				{ children }
			</fieldset>
			<div className="tm1-row prdd-lite-pro-row">
				<div className="col-left"></div>
				<div className="col-right">
					<ProNotice />
				</div>
			</div>
		</>
	);
}
