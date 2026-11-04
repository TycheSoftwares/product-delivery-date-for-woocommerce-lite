/**
 * Static preview of the Block Time Slots screen of the Pro version, shown
 * under the Upgrade to Pro modal.
 */
import { __ } from '@wordpress/i18n';
import { imageUrl } from '../../../shared/config';

export default function BlockTimeSlot() {
	return (
		<div className="prdd-content-area">
			<div className="container bts-page-wrap">
				<div className="row">
					<div className="col-md-12">
						<div className="prdd-page-head">
							<h1>
								{ __(
									'Block Time Slots',
									'product-delivery-date-for-woocommerce-lite'
								) }
							</h1>
							<p>
								{ __(
									"Taking some time off is a must. That's why you can block or hide time slots for particular dates or weekdays and get that groove on!",
									'product-delivery-date-for-woocommerce-lite'
								) }
							</p>
						</div>
					</div>
					<div className="col-md-12">
						<div className="bts-content">
							<div className="tbl-mod-2 flx-100">
								<div className="tm2-inner-wrap">
									<table className="table">
										<thead>
											<tr>
												<th width="5%">
													<div className="el-checkbox el-checkbox-green d-uni-col">
														<input type="checkbox" />
														<label className="el-checkbox-style mb-0"></label>
													</div>
												</th>
												<th>
													{ __(
														'Delivery Days/Dates',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</th>
												<th>
													{ __(
														'Time Slot',
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
										</thead>
										<tbody></tbody>
									</table>
									<div className="add-more-link link-flx">
										<a
											className="al-link collapsed"
											href="#"
										>
											<img
												src={ imageUrl(
													'icon-plus.svg'
												) }
												alt=""
											/>{ ' ' }
											{ __(
												'Add More',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</a>
									</div>
								</div>
								<div className="abulk-box">
									<select className="ib-small">
										<option>
											{ __(
												'Bulk Action',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</option>
									</select>
									<button
										className="trietary-btn reverse"
										type="button"
									>
										{ __(
											'Apply',
											'product-delivery-date-for-woocommerce-lite'
										) }
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}
