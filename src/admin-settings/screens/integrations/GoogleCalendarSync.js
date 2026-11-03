/**
 * Static preview of the Google Calendar Sync screen of the Pro version,
 * shown under the Upgrade to Pro modal.
 */
import { __ } from '@wordpress/i18n';
import {
	Accordion,
	PageHead,
	Panel,
	Row,
	Switch,
} from '../../../shared/components/Preview';

function SwitchRow( { label } ) {
	return (
		<Row label={ label }>
			<Switch />
		</Row>
	);
}

export default function GoogleCalendarSync() {
	return (
		<div className="prdd-content-area">
			<div className="container fas-page-wrap">
				<div className="row">
					<div className="col-md-12">
						<PageHead
							title={ __(
								'Google Calendar Sync',
								'product-delivery-date-for-woocommerce-lite'
							) }
							text={ __(
								'Stay connected with your store by syncing it with the Google Calendar. Your customers can have their own fair share by adding the delivery info to their own GC.',
								'product-delivery-date-for-woocommerce-lite'
							) }
						/>
						<Accordion>
							<Panel
								id="collapseOne"
								headingTag="h2"
								title={ __(
									'General Settings',
									'product-delivery-date-for-woocommerce-lite'
								) }
							>
								<Row
									className="tm1-row flx-center"
									label={ __(
										'Event Location:',
										'product-delivery-date-for-woocommerce-lite'
									) }
								>
									<input className="ib-xl" type="text" />
								</Row>
								<Row
									className="tm1-row flx-center"
									label={ __(
										'Event Summary(name):',
										'product-delivery-date-for-woocommerce-lite'
									) }
								>
									<input className="ib-xl" type="text" />
								</Row>
								<Row
									label={ __(
										'Event Description:',
										'product-delivery-date-for-woocommerce-lite'
									) }
									wrapClass="rc-flx-wrap"
								>
									<textarea className="ta-sm"></textarea>
								</Row>
							</Panel>
							<Panel
								id="collapseTwo"
								headingTag="h2"
								title={ __(
									'Customer Add to Calendar Button Settings',
									'product-delivery-date-for-woocommerce-lite'
								) }
							>
								<SwitchRow
									label={ __(
										'Show Add to Calendar Button on Order Received Page:',
										'product-delivery-date-for-woocommerce-lite'
									) }
								/>
								<SwitchRow
									label={ __(
										'Show Add to Calendar Button in The Customer Notification Email:',
										'product-delivery-date-for-woocommerce-lite'
									) }
								/>
								<SwitchRow
									label={ __(
										'Show Add to Calendar Button on My Account:',
										'product-delivery-date-for-woocommerce-lite'
									) }
								/>
							</Panel>
						</Accordion>
					</div>
				</div>
			</div>
		</div>
	);
}
