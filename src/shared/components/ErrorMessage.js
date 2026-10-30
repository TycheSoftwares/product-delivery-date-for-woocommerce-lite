/**
 * Error counterpart of the "Settings Saved." message, shown when a request fails.
 */
import { __ } from '@wordpress/i18n';

export default function ErrorMessage( { message, onDismiss } ) {
	if ( ! message ) {
		return null;
	}
	return (
		<div className="container-fluid pl-info-wrap">
			<div className="row">
				<div className="col-md-12">
					<div
						className="alert alert-danger alert-dismissible fade show"
						role="alert"
					>
						{ message }
						<button
							type="button"
							className="close"
							data-dismiss="alert"
							aria-label={ __(
								'Close',
								'product-delivery-date-for-woocommerce-lite'
							) }
							onClick={ onDismiss }
						>
							<span aria-hidden="true">×</span>
						</button>
					</div>
				</div>
			</div>
		</div>
	);
}
