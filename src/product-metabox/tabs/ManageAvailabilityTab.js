/**
 * Static preview of the "Manage Availability" tab of the Pro version, shown
 * under the Upgrade to Pro modal.
 */
import { __ } from '@wordpress/i18n';

const TRASH_STYLE = { color: 'rgb(220, 53, 69)' };

const WEEKDAYS = [
	__( 'Sunday', 'product-delivery-date-for-woocommerce-lite' ),
	__( 'Monday', 'product-delivery-date-for-woocommerce-lite' ),
	__( 'Tuesday', 'product-delivery-date-for-woocommerce-lite' ),
	__( 'Wednesday', 'product-delivery-date-for-woocommerce-lite' ),
	__( 'Thursday', 'product-delivery-date-for-woocommerce-lite' ),
	__( 'Friday', 'product-delivery-date-for-woocommerce-lite' ),
	__( 'Saturday', 'product-delivery-date-for-woocommerce-lite' ),
];

export default function ManageAvailabilityTab() {
	return (
		<>
			<div className="float-left">
				<h4>
					{ __(
						'Recurring Days',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</h4>
			</div>
			<div className="float-right">
				<a href="#" className="trietary-btn reverse">
					{ __(
						'Delete All',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</a>
			</div>
			<table className="wp-list-table widefat fixed posts">
				<tbody>
					<tr>
						<th>
							{ __(
								'Day',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</th>
						<th>
							{ __(
								'Maximum Deliveries',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</th>
						<th>
							{ __(
								'Actions',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</th>
					</tr>
					{ WEEKDAYS.map( ( day ) => (
						<tr key={ day }>
							<td>{ day }</td>
							<td>0</td>
							<td className="tbl-action-col">
								<a href="#" className="remove_day_data">
									{ ' ' }
									<span
										className="dashicons dashicons-trash"
										style={ TRASH_STYLE }
									></span>
								</a>
								<span className="recure_edit_days">
									<span className="dashicons dashicons-edit"></span>
								</span>
							</td>
						</tr>
					) ) }
				</tbody>
			</table>
		</>
	);
}
