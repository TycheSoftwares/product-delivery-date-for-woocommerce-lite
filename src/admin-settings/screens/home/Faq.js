/**
 * FAQ & Support tab, Pro markup with the questions of the Lite plugin.
 */
import { __ } from '@wordpress/i18n';
import CollapsePanel from '../../../shared/components/CollapsePanel';

const PRO_URL =
	'https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=wprepo&utm_medium=faqlink&utm_campaign=ProductDeliveryDateLite';

/**
 * Questions shown in the two columns. Answers may hold links, so they are
 * rendered as HTML.
 *
 * @return {Array[]} Columns of { id, question, answer }.
 */
const getColumns = () => [
	[
		{
			id: 'collapseOne',
			question: __(
				'How to make Product delivery date field a required field on the Product Page?',
				'product-delivery-date-for-woocommerce-lite'
			),
			answer: __(
				'Enable the "Mandatory Fields" option under the Product Delivery Date -> Settings tab on the WooCommerce edit product page. Customers will then have to select a delivery date before adding the product to the cart.',
				'product-delivery-date-for-woocommerce-lite'
			),
		},
		{
			id: 'collapseSecond',
			question: __(
				'Is it possible to add delivery date calendar on checkout page instead of for each product?',
				'product-delivery-date-for-woocommerce-lite'
			),
			answer: __(
				'No, it is not possible to add Delivery date calendar on checkout page from Product Delivery Date plugin. However, we do have a plugin named <a href="https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/?utm_source=wprepo&amp;utm_medium=demolink&amp;utm_campaign=ProductDeliveryDateLite" rel="nofollow" target="_blank">Order Delivery Date for WooCommerce Pro</a> and <a href="https://wordpress.org/plugins/order-delivery-date-for-woocommerce/" target="_blank">Lite</a> version which you can use to add a Delivery Date on the WooCommerce checkout page.',
				'product-delivery-date-for-woocommerce-lite'
			),
		},
		{
			id: 'collapseThird',
			question: __(
				'Can the customer enter the preferred delivery time for the product?',
				'product-delivery-date-for-woocommerce-lite'
			),
			answer: `${ __(
				'Currently, there is no provision for entering the delivery time in the free version. This is possible in the Pro version.',
				'product-delivery-date-for-woocommerce-lite'
			) } <a href="${ PRO_URL }" rel="nofollow" target="_blank">${ __(
				'View Demo',
				'product-delivery-date-for-woocommerce-lite'
			) }</a>`,
		},
		{
			id: 'collapseFourth',
			question: __(
				'Can we change the language of the delivery date calendar?',
				'product-delivery-date-for-woocommerce-lite'
			),
			answer: __(
				'Yes, you can choose a different language for the delivery date calendar under Product Delivery Date -> Settings -> Appearance -> Calendar Appearance.',
				'product-delivery-date-for-woocommerce-lite'
			),
		},
		{
			id: 'collapseFifth',
			question: __(
				'Is it possible to add extra charges for weekdays or specific dates?',
				'product-delivery-date-for-woocommerce-lite'
			),
			answer: `${ __(
				'Currently, it is not possible to add the extra charges for deliveries on weekdays or for specific dates in the free version. However, this feature is available in the',
				'product-delivery-date-for-woocommerce-lite'
			) } <a href="${ PRO_URL }" rel="nofollow" target="_blank">${ __(
				'Product Delivery Date Pro for WooCommerce plugin',
				'product-delivery-date-for-woocommerce-lite'
			) }</a>.`,
		},
	],
	[
		{
			id: 'collapseSix',
			question: __(
				'Can we translate Delivery Date label on product page?',
				'product-delivery-date-for-woocommerce-lite'
			),
			answer: __(
				'Yes, you can translate the Delivery Date label for the product page. To translate the strings, you need to generate ".po" and ".mo" files in your respective language. These files then need to be added to the following path: "product-delivery-date-for-woocommerce-lite/languages"',
				'product-delivery-date-for-woocommerce-lite'
			),
		},
		{
			id: 'collapseSeven',
			question: __(
				'Is it possible to edit the selected delivery date for the already placed WooCommerce orders?',
				'product-delivery-date-for-woocommerce-lite'
			),
			answer: `${ __(
				'Currently, it is not possible to edit the selected delivery date for the WooCommerce orders in the free version. However, this feature is available in the',
				'product-delivery-date-for-woocommerce-lite'
			) } <a href="${ PRO_URL }" rel="nofollow" target="_blank">${ __(
				'Product Delivery Date Pro for WooCommerce plugin',
				'product-delivery-date-for-woocommerce-lite'
			) }</a>. ${ __(
				'The admin, as well as the customers, can edit the delivery date for the already placed WooCommerce orders.',
				'product-delivery-date-for-woocommerce-lite'
			) }`,
		},
		{
			id: 'collapseEight',
			question: __(
				'Can we change the "Delivery Date" label to something else, such as "Choose a date" or "Date to deliver"?',
				'product-delivery-date-for-woocommerce-lite'
			),
			answer: `${ __(
				'Currently, it is not possible to change the Delivery Date label in the free version. However, this feature is available in the',
				'product-delivery-date-for-woocommerce-lite'
			) } <a href="${ PRO_URL }" rel="nofollow" target="_blank">${ __(
				'Product Delivery Date Pro for WooCommerce plugin',
				'product-delivery-date-for-woocommerce-lite'
			) }</a>.`,
		},
		{
			id: 'collapseNinth',
			question: __(
				'Will Delivery Date Calendar on the product page work on the mobile devices?',
				'product-delivery-date-for-woocommerce-lite'
			),
			answer: __(
				'Yes, Delivery Date Calendar on the product page will work on the mobile devices.',
				'product-delivery-date-for-woocommerce-lite'
			),
		},
		{
			id: 'collapseTenth',
			question: __(
				'Difference between Lite and Pro version of the plugin.',
				'product-delivery-date-for-woocommerce-lite'
			),
			answer: `${ __(
				'You can refer',
				'product-delivery-date-for-woocommerce-lite'
			) } <strong><a href="https://www.tychesoftwares.com/differences-pro-lite-versions-product-delivery-date-woocommerce-plugin/?utm_source=wprepo&utm_medium=faqlink&utm_campaign=ProductDeliveryDateLite" rel="nofollow" target="_blank">${ __(
				'here',
				'product-delivery-date-for-woocommerce-lite'
			) }</a></strong>.`,
		},
	],
];

export default function Faq() {
	return (
		<>
			<div className="prdd-content-area">
				<div className="container prdd-faq-page">
					<div className="row">
						<div className="col-md-12">
							<div className="wbc-box">
								<div className="wbc-head">
									<h4>
										{ __(
											'Frequently Asked Questions',
											'product-delivery-date-for-woocommerce-lite'
										) }
									</h4>
								</div>
								<div className="wbc-content">
									<div className="prdd-faq-wrap">
										<div className="row">
											{ getColumns().map(
												( items, column ) => (
													<div
														className="col-md-6"
														key={ column }
													>
														<div className="prdd-wc-accordion">
															<div
																className="panel-group prdd-accordian"
																id="accordion"
															>
																{ items.map(
																	(
																		item
																	) => (
																		<CollapsePanel
																			key={
																				item.id
																			}
																			id={
																				item.id
																			}
																			title={
																				item.question
																			}
																		>
																			<div className="panel-body">
																				<p
																					dangerouslySetInnerHTML={ {
																						__html: item.answer,
																					} }
																				/>
																			</div>
																		</CollapsePanel>
																	)
																) }
															</div>
														</div>
													</div>
												)
											) }
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</>
	);
}
