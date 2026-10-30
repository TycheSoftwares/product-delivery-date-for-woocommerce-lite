/**
 * Dismissible bootstrap alert used to report the result of a save.
 */
import { __ } from '@wordpress/i18n';

export default function AlertMessage( {
	notice,
	onDismiss,
	id = 'save_message',
	className = 'prdd-alert-message',
} ) {
	if ( ! notice || ! notice.message ) {
		return null;
	}

	const type = 'error' === notice.type ? 'alert-danger' : 'alert-success';

	return (
		<div
			className={ `alert ${ type } alert-dismissible fade show ${ className }` }
			role="alert"
			id={ id }
		>
			{ notice.message }
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
	);
}
