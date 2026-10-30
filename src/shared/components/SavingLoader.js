/**
 * "Saving Changes..." overlay shown while a request is running.
 */
import { __ } from '@wordpress/i18n';
import Spinner from './Spinner';

export default function SavingLoader( { show, text } ) {
	return (
		<div
			id="prdd_events_loader"
			style={ { display: show ? 'block' : 'none' } }
		>
			<div className="prdd_events_loader_wrapper">
				{ text ||
					__(
						'Saving Changes…',
						'product-delivery-date-for-woocommerce-lite'
					) }
				<Spinner />
			</div>
		</div>
	);
}
