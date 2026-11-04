/* eslint-disable jsx-a11y/img-redundant-alt -- Alt text kept from the earlier markup. */
/**
 * Welcome tab, Pro markup with the content of the Lite plugin.
 */
import { __ } from '@wordpress/i18n';
import CollapsePanel from '../../../shared/components/CollapsePanel';
import { pluginUrl } from '../../../shared/config';

export default function Welcome() {
	return (
		<>
			{ /*content area*/ }
			<div className="prdd-content-area">
				<div className="container prdd-welcome-page">
					<div className="row">
						<div className="col-md-12">
							<div className="wbc-box">
								<div className="wbc-head">
									<div className="col-left">
										<img
											src={
												pluginUrl +
												'/assets/images/welcome-logo.svg'
											}
											alt="Image"
										/>
									</div>
									<div className="col-right">
										<h2>
											{ __(
												'Welcome!',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</h2>
										<p>
											{ __(
												'Thank you for choosing Product Delivery Date for WooCommerce - Lite plugin.',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</p>
									</div>
								</div>
								<div className="wbc-content">
									<div className="prdd-video-wrap">
										<embed
											src="https://www.youtube.com/embed/WsjhAAxaLs4"
											wmode="transparent"
											type="video/mp4"
											width="100%"
											height="500"
											allow="autoplay; encrypted-media; picture-in-picture"
											// eslint-disable-next-line react/no-unknown-property -- Kept from the earlier markup.
											allowFullScreen
										/>
									</div>
									<div className="prdd-wc-accordian">
										<h2 className="h1">
											{ __(
												'What do you want to do?',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</h2>
										<div
											className="panel-group prdd-accordian"
											id="accordion"
										>
											{ /* First Panel */ }
											<CollapsePanel
												id="collapseOne"
												title={ __(
													'Can I exclude the weekends for delivery as we do not deliver on weekends?',
													'product-delivery-date-for-woocommerce-lite'
												) }
											>
												<div className="panel-body">
													<p>
														{ __(
															'Yes, you can exclude the weekends by not selecting Saturday & Sunday (or any weekdays) in the Delivery Days field on the Product delivery date meta box on WooCommerce edit product page.',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</p>
													<p>
														<a
															target="_blank"
															className="link-wul"
															href="https://www.tychesoftwares.com/docs/docs/product-delivery-date-for-woocommerce-lite/setup-delivery-date-calendar/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite"
															rel="noreferrer"
														>
															{ __(
																'Documentation',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</a>
													</p>
												</div>
											</CollapsePanel>
											{ /* Second Panel */ }
											<CollapsePanel
												id="collapseTwo"
												title={ __(
													'I need some lead preparation time before I can make a delivery. Can I set a minimum delivery time for my products?',
													'product-delivery-date-for-woocommerce-lite'
												) }
											>
												<div className="panel-body">
													<p>
														{ __(
															'Yes, you can set a minimum delivery period in hours, which will be taken into consideration before showing the earliest available delivery date to your customers. This can be done under the "Minimum Delivery preparation time (in hours):" field under the Product Delivery Date -> Delivery Time Period tab on WooCommerce edit product page.',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</p>
													<p>
														<a
															target="_blank"
															className="link-wul"
															href="https://www.tychesoftwares.com/docs/docs/product-delivery-date-for-woocommerce-lite/setup-delivery-date-calendar/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite"
															rel="noreferrer"
														>
															{ __(
																'Documentation',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</a>
													</p>
												</div>
											</CollapsePanel>
											{ /* Third Panel */ }
											<CollapsePanel
												id="collapseThree"
												title={ __(
													'Can I disable product delivery on some public holidays?',
													'product-delivery-date-for-woocommerce-lite'
												) }
											>
												<div className="panel-body">
													<p>
														{ __(
															'Yes. Add the dates for all products under Product Delivery Date -> Settings -> Holidays, or for a single product with the "No delivery on these dates" setting under the Product Delivery Date -> Settings tab on WooCommerce edit product page. These dates will be disabled in the calendar.',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</p>
													<p>
														<a
															target="_blank"
															className="link-wul"
															href="https://www.tychesoftwares.com/docs/docs/product-delivery-date-for-woocommerce-lite/setup-delivery-date-calendar/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite"
															rel="noreferrer"
														>
															{ __(
																'Documentation',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</a>
													</p>
												</div>
											</CollapsePanel>
											{ /* Forth Panel */ }
											<CollapsePanel
												id="collapseFour"
												title={ __(
													'Can I make the delivery date selection mandatory?',
													'product-delivery-date-for-woocommerce-lite'
												) }
											>
												<div className="panel-body">
													<p>
														{ __(
															'Yes, enable the "Mandatory Fields" option under the Product Delivery Date -> Settings tab on WooCommerce edit product page. Customers will then have to select a delivery date before adding the product to the cart.',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</p>
													<p>
														<a
															target="_blank"
															className="link-wul"
															href="https://www.tychesoftwares.com/docs/docs/product-delivery-date-for-woocommerce-lite/setup-delivery-date-calendar/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite"
															rel="noreferrer"
														>
															{ __(
																'Documentation',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</a>
													</p>
												</div>
											</CollapsePanel>
											{ /* Fifth Panel */ }
											<CollapsePanel
												id="collapseFive"
												title={ __(
													'Can I create fixed timeslots or add extra charges for delivery dates?',
													'product-delivery-date-for-woocommerce-lite'
												) }
											>
												<div className="panel-body">
													<p>
														{ __(
															'Delivery time slots, delivery charges for weekdays or specific dates, same day & next day cut-off and many more features are available in the Product Delivery Date Pro for WooCommerce plugin.',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</p>
													<p>
														<a
															target="_blank"
															className="link-wul"
															href="https://www.tychesoftwares.com/products/woocommerce-product-delivery-date-pro-plugin-trial/"
															rel="noreferrer"
														>
															{ __(
																'Upgrade to Pro',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</a>
													</p>
												</div>
											</CollapsePanel>
										</div>
									</div>
									<div className="prdd-wc-related">
										<h2 className="h1">
											{ __(
												'Your next game changers are here…',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</h2>
										<p>
											{ __(
												'Thank us later.',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</p>
										<ul>
											<li>
												<a
													target="_blank"
													className="link-wul"
													href="https://www.tychesoftwares.com/products/woocommerce-abandoned-cart-pro-plugin/?utm_source=prddlitewelcome&utm_medium=link&utm_campaign=ProductDeliveryDateLite"
													rel="noreferrer"
												>
													{ __(
														'Recover lost sales with Abandon Cart Pro for WooCommerce',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</a>
											</li>
											<li>
												<a
													target="_blank"
													className="link-wul"
													href="https://www.tychesoftwares.com/products/woocommerce-deposit-plugin/?utm_source=prddlitewelcome&utm_medium=link&utm_campaign=ProductDeliveryDateLite"
													rel="noreferrer"
												>
													{ __(
														'Deposits For WooCommerce',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</a>
											</li>
											<li>
												<a
													target="_blank"
													className="link-wul"
													href="https://www.tychesoftwares.com/products/woocommerce-booking-and-appointment-plugin/?utm_source=prddlitewelcome&utm_medium=link&utm_campaign=ProductDeliveryDateLite"
													rel="noreferrer"
												>
													{ __(
														'Booking & Appointment Plugin for WooCommerce',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</a>
											</li>
											<li>
												<a
													target="_blank"
													className="link-wul"
													href="https://www.tychesoftwares.com/products/woocommerce-order-delivery-date-pro-plugin/?utm_source=prddlitewelcome&utm_medium=link&utm_campaign=ProductDeliveryDateLite"
													rel="noreferrer"
												>
													{ __(
														'Order Delivery Date Pro for WooCommerce',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</a>
											</li>
										</ul>
									</div>
									<div className="prdd-wc-3col-mod">
										<div className="wc3-mod-box">
											<div className="wc3-col">
												<h3>
													{ __(
														'Join our Community',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</h3>
												<p>
													{ __(
														'Want to hang out with us? Our Facebook group is the perfect place to meet like-minded users and learn more about our plugins.',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</p>
												<p>
													<a
														target="_blank"
														className="link-wul"
														href="https://www.tychesoftwares.com/docs/docs/product-delivery-date-for-woocommerce-lite/"
														rel="noreferrer"
													>
														{ __(
															'Read Documentation',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</a>
												</p>
											</div>

											<div className="wc3-col">
												<h3>
													{ __(
														'Knowledgebase',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</h3>
												<p>
													{ __(
														'Maximize your Product Delivery Date performance! Get all the info you need from our Knowledge base.',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</p>
												<p>
													<a
														target="_blank"
														className="link-wul"
														href="https://www.tychesoftwares.com/docs/woocommerce-product-delivery-date-lite/"
														rel="noreferrer"
													>
														{ __(
															'Check it out',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</a>
												</p>
											</div>

											<div className="wc3-col">
												<h3>Get Support</h3>
												<p>
													{ __(
														'Tyche Softwares support hours are from 10 am to 6 pm (GMT +5:30 timezone), Monday through Friday. Our response time is approximately 24 hours during working days.',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</p>
												<p>
													<a
														target="_blank"
														className="link-wul"
														href="https://support.tychesoftwares.com/help/2285384554"
														rel="noreferrer"
													>
														{ __(
															'Submit a Ticket',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</a>
												</p>
											</div>
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
