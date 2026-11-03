/**
 * Static preview of the Field Labels screen of the Pro version, shown under
 * the Upgrade to Pro modal.
 */
import { __ } from '@wordpress/i18n';
import {
	Accordion,
	PageHead,
	Panel,
	Row,
} from '../../../shared/components/Preview';

function LabelRow( { label } ) {
	return (
		<Row label={ label }>
			<input className="ib-md" type="text" />
		</Row>
	);
}

export default function FieldLabels() {
	return (
		<div className="prdd-content-area">
			<div className="container fas-page-wrap">
				<div className="row">
					<div className="col-md-12">
						<PageHead
							textClass="float-left"
							title={ __(
								'Field Labels',
								'product-delivery-date-for-woocommerce-lite'
							) }
							text={ __(
								'Set up the field labels of delivery fields the way you want it!',
								'product-delivery-date-for-woocommerce-lite'
							) }
						/>
						<Accordion>
							<Panel
								id="collapseOne"
								title={ __(
									'Labels on Product Page',
									'product-delivery-date-for-woocommerce-lite'
								) }
							>
								<LabelRow
									label={ __(
										'Delivery Date:',
										'product-delivery-date-for-woocommerce-lite'
									) }
								/>
								<LabelRow
									label={ __(
										'Delivery Time:',
										'product-delivery-date-for-woocommerce-lite'
									) }
								/>
								<LabelRow
									label={ __(
										'Choose Time Text:',
										'product-delivery-date-for-woocommerce-lite'
									) }
								/>
								<LabelRow
									label={ __(
										'Delivery Date Placeholder:',
										'product-delivery-date-for-woocommerce-lite'
									) }
								/>
							</Panel>
							<Panel
								id="collapseTwo"
								title={ __(
									'Labels on Order Received page & in email notification',
									'product-delivery-date-for-woocommerce-lite'
								) }
							>
								<LabelRow
									label={ __(
										'Delivery Date:',
										'product-delivery-date-for-woocommerce-lite'
									) }
								/>
								<LabelRow
									label={ __(
										'Delivery Time:',
										'product-delivery-date-for-woocommerce-lite'
									) }
								/>
								<LabelRow
									label={ __(
										'ICS File Name:',
										'product-delivery-date-for-woocommerce-lite'
									) }
								/>
								<LabelRow
									label={ __(
										'Delivery Charges:',
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
