/**
 * Static preview of the WooCommerce Subscriptions Compatibility screen of
 * the Pro version, shown under the Upgrade to Pro modal.
 */
import { __ } from '@wordpress/i18n';
import {
	Accordion,
	PageHead,
	Panel,
	Row,
	SaveButton,
	Switch,
} from '../../../shared/components/Preview';

export default function Subscriptions() {
	return (
		<div className="prdd-content-area">
			<div className="container fas-page-wrap">
				<div className="row">
					<div className="col-md-12">
						<PageHead
							title={ __(
								'WooCommerce Subscriptions Compatibility',
								'product-delivery-date-for-woocommerce-lite'
							) }
							text={ __(
								'Configure how Product Delivery Date Pro behaves with WooCommerce Subscriptions. Requires WooCommerce Subscriptions (by WooCommerce) to be installed and active.',
								'product-delivery-date-for-woocommerce-lite'
							) }
						/>
						<Accordion>
							<Panel
								id="collapseSubOne"
								headingTag="h2"
								title={ __(
									'Subscription Compatibility Settings',
									'product-delivery-date-for-woocommerce-lite'
								) }
							>
								<Row
									label={ __(
										'Enable Subscription Compatibility:',
										'product-delivery-date-for-woocommerce-lite'
									) }
								>
									<Switch />
								</Row>
								<div className="tm1-row">
									<div className="col-full">
										<p className="description">
											{ __(
												'Enable the master toggle above to configure subscription delivery options.',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</p>
									</div>
								</div>
							</Panel>
						</Accordion>
						<div className="ss-foot">
							<SaveButton />
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}
