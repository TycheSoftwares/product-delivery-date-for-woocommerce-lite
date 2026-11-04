/**
 * Static preview of the Bulk Product Settings screen of the Pro version,
 * shown under the Upgrade to Pro modal: the product selectors and the first
 * tab of the delivery settings.
 */
import { __ } from '@wordpress/i18n';
import {
	Checkbox,
	PageHead,
	Radio,
	Row,
	Switch,
} from '../../../shared/components/Preview';
import {
	DeliveryOptionsIcon,
	CalendarIcon,
	ClockIcon,
	CardIcon,
	GearIcon,
} from '../../../product-metabox/components/TabIcons';

const FIELD_STYLE = { width: '300px' };

const TABS = [
	{
		icon: <DeliveryOptionsIcon />,
		title: __(
			'Delivery Options',
			'product-delivery-date-for-woocommerce-lite'
		),
	},
	{
		icon: <CalendarIcon />,
		title: __(
			'Manage Availability',
			'product-delivery-date-for-woocommerce-lite'
		),
	},
	{
		icon: <ClockIcon />,
		title: __(
			'Delivery Time Period',
			'product-delivery-date-for-woocommerce-lite'
		),
	},
	{
		icon: <CardIcon />,
		title: __(
			'Delivery Charges',
			'product-delivery-date-for-woocommerce-lite'
		),
	},
	{
		icon: <GearIcon />,
		title: __( 'Settings', 'product-delivery-date-for-woocommerce-lite' ),
	},
];

export default function BulkProductSettings() {
	return (
		<div id="prdd-bulk-settings-page" className="prdd-content-area">
			<div className="container-fluid bd-page-wrap as-page">
				<div className="row">
					<div className="col-md-12">
						<PageHead
							className="prdd-page-head phw-btn justify-content-between"
							title={ __(
								'Bulk Product Settings',
								'product-delivery-date-for-woocommerce-lite'
							) }
							text={ __(
								'Add delivery settings for multiple products together. Once the settings are saved, you will be able to see them & edit them from the Edit product page of the products that you have selected.',
								'product-delivery-date-for-woocommerce-lite'
							) }
						/>
					</div>
					<div className="col-md-12">
						<div className="wbc-box">
							<div className="wbc-head d-flex justify-content-between align-items-center">
								<h4>
									{ __(
										'Multiple Products Delivery Settings',
										'product-delivery-date-for-woocommerce-lite'
									) }
								</h4>
							</div>
							<div className="wbc-content">
								<div className="tbl-mod-1">
									<Row
										label={ __(
											'Products:',
											'product-delivery-date-for-woocommerce-lite'
										) }
										wrapClass="rc-flx-wrap"
									>
										<input
											type="text"
											className="ib-small"
											style={ FIELD_STYLE }
										/>
									</Row>
									<Row
										label={ __(
											'Products Categories And Tags:',
											'product-delivery-date-for-woocommerce-lite'
										) }
										wrapClass="rc-flx-wrap"
									>
										<input
											type="text"
											className="ib-small"
											style={ FIELD_STYLE }
										/>
									</Row>
									<Row
										label={ __(
											'Override Existing product Settings?',
											'product-delivery-date-for-woocommerce-lite'
										) }
									>
										<Switch />
									</Row>
								</div>
							</div>
							<div
								id="prdd-product-page"
								className="wbc-content product-setting-section prdd-content-area"
							>
								<div className="tbl-mod-1">
									<div className="tm1-row p-0">
										<div className="col-left">
											<div className="col-sidebar">
												<ul>
													{ TABS.map( ( tab, i ) => (
														<li key={ tab.title }>
															<a
																className={
																	0 === i
																		? 'prdd_tabs active'
																		: 'prdd_tabs'
																}
																href="#"
															>
																{ tab.icon }{ ' ' }
																{ tab.title }{ ' ' }
																<i
																	className={
																		0 === i
																			? 'dashicons dashicons-arrow-right-alt2'
																			: 'dashicons dashicons-arrow-down-alt2'
																	}
																></i>
															</a>
														</li>
													) ) }
												</ul>
											</div>
										</div>
										<div className="col-right">
											<div className="tbl-mod-1">
												<div className="prdd-tab-content tab-delivery-option">
													<div className="d-flex justify-content-between">
														<h4>
															{ __(
																'Enable Delivery Date',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</h4>
													</div>
													<Row
														className="tm1-row align-items-center d-b-color"
														label={ __(
															'Enable Delivery Date:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													>
														<Switch />
													</Row>
													<Row
														label={ __(
															'Delivery Option:',
															'product-delivery-date-for-woocommerce-lite'
														) }
														wrapClass="rc-flx-wrap flx-aln-center ro-wrap"
													>
														<Radio
															checked
															label={ __(
																'Calendar',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<Radio
															label={ __(
																'Text Block',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
													</Row>
													<Row
														label={ __(
															'Delivery On:',
															'product-delivery-date-for-woocommerce-lite'
														) }
														wrapClass="rc-flx-wrap flx-aln-center ro-wrap"
													>
														<Checkbox
															className="mr-3"
															label={ __(
																'Specific Dates',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<Checkbox
															checked
															label={ __(
																'Recurring Weekdays',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
													</Row>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}
