/**
 * Static preview of the "Delivery Charges" tab of the Pro version, shown
 * under the Upgrade to Pro modal.
 */
import { __ } from '@wordpress/i18n';
import InfoTip from '../../shared/components/InfoTip';

const TABLE_STYLE = { borderRadius: '5px' };

export default function DeliveryChargesTab() {
	return (
		<div className="row">
			<div className="col-md-12">
				<div className="d-flex justify-content-between heading-border mb-20">
					<h4>
						{ __(
							'Delivery Charges',
							'product-delivery-date-for-woocommerce-lite'
						) }
					</h4>
				</div>
			</div>
			<div className="col-md-5">
				<label>
					{ __(
						'Select Days:',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</label>
				<div className="rc-flx-wrap flx-aln-center">
					<select>
						<option>
							{ __(
								'Select Weekday',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</option>
					</select>
					<InfoTip className="tt-info ml-2" />
				</div>
			</div>
			<div className="col-md-4">
				<label>
					{ __(
						'Select Date:',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</label>
				<div className="rc-flx-wrap flx-aln-center">
					<input className="ib-sm" type="text" />
					<InfoTip className="tt-info ml-2" />
				</div>
			</div>
			<div className="col-md-3">
				<label>
					{ __(
						'Delivery Charges:',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</label>
				<div className="rc-flx-wrap flx-aln-center">
					<input
						className="ib-sm"
						type="text"
						id="special_delivery_price"
					/>
					<InfoTip className="tt-info ml-2" />
				</div>
			</div>
			<div className="col-md-12 mt-3">
				<input
					type="button"
					className="trietary-btn reverse"
					value={ __(
						'Add Delivery Charges',
						'product-delivery-date-for-woocommerce-lite'
					) }
				/>
			</div>
			<div className="col-md-12 mt-3">
				<p>
					{ __(
						'Delivery Charges',
						'product-delivery-date-for-woocommerce-lite'
					) }{ ' ' }
					<a style={ { float: 'right' } } href="#">
						{ __(
							'Delete All Delivery Charges',
							'product-delivery-date-for-woocommerce-lite'
						) }
					</a>
				</p>
				<table
					className="wp-list-table widefat fixed posts"
					style={ TABLE_STYLE }
				>
					<tbody>
						<tr>
							<th>
								{ __(
									'Day / Date',
									'product-delivery-date-for-woocommerce-lite'
								) }
							</th>
							<th>
								{ __(
									'Delivery Charges',
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
					</tbody>
				</table>
			</div>
		</div>
	);
}
