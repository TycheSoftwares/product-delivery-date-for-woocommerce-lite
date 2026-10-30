/**
 * Field Labels tab, markup of the former includes/admin/views/appearance/field-label.php.
 */
import { __ } from '@wordpress/i18n';
import CollapsePanel from '../../../shared/components/CollapsePanel';
import InfoTip from '../../../shared/components/InfoTip';
import ErrorMessage from '../../../shared/components/ErrorMessage';
import useSettingsForm, { vLoading, vShow } from '../../hooks/useSettingsForm';
import Spinner from '../../../shared/components/Spinner';

export default function FieldLabels() {
	const {
		settings,
		change,
		loading,
		saving,
		savedMessage,
		setSavedMessage,
		error,
		setError,
		save,
	} = useSettingsForm( 'settings/field-labels' );

	return (
		<div className="prdd-content-area">
			<div className="container fas-page-wrap">
				<div
					className="container-fluid pl-info-wrap"
					id="save_message"
					style={ vShow( savedMessage ) }
				>
					<div className="row">
						<div className="col-md-12">
							<div
								className="alert alert-success alert-dismissible fade show"
								role="alert"
							>
								{ __(
									'Settings Saved.',
									'product-delivery-date-for-woocommerce-lite'
								) }
								<button
									type="button"
									className="close"
									data-dismiss="alert"
									aria-label="Close"
									onClick={ () => setSavedMessage( false ) }
								>
									<span aria-hidden="true">×</span>
								</button>
							</div>
						</div>
					</div>
				</div>
				<ErrorMessage
					message={ error }
					onDismiss={ () => setError( '' ) }
				/>
				<div id="prdd_events_loader" style={ vShow( saving ) }>
					<div className="prdd_events_loader_wrapper">
						{ __(
							'Saving Changes…',
							'product-delivery-date-for-woocommerce-lite'
						) }
						<Spinner />
					</div>
				</div>
				<div className="prdd_events_loader" style={ vShow( loading ) }>
					<div className="prdd_events_loader_wrapper">
						{ __(
							'Loading…',
							'product-delivery-date-for-woocommerce-lite'
						) }
						<Spinner />
					</div>
				</div>
				<div className="row" style={ vLoading( loading ) }>
					<div className="col-md-12">
						<div className="prdd-page-head phw-btn">
							<div className="float-left">
								<h1>
									{ __(
										'Field Labels',
										'product-delivery-date-for-woocommerce-lite'
									) }
								</h1>
								<p>
									{ __(
										'Set up the field labels of delivery fields the way you want it!',
										'product-delivery-date-for-woocommerce-lite'
									) }
								</p>
							</div>
							<div className="float-right">
								<button type="button" onClick={ () => save() }>
									{ __(
										'Save Settings',
										'product-delivery-date-for-woocommerce-lite'
									) }
								</button>
							</div>
						</div>

						<div className="wbc-accordion">
							<div
								className="panel-group prdd-accordian"
								id="wbc-accordion"
							>
								{ /* First Panel */ }
								<CollapsePanel
									id="collapseOne"
									defaultOpen
									title={ __(
										'Labels on Product Page',
										'product-delivery-date-for-woocommerce-lite'
									) }
								>
									<div className="panel-body">
										<div className="tbl-mod-1">
											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Delivery Date:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Delivery Date label on product page.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<input
															className="ib-md"
															type="text"
															name="delivery_date_label"
															id="delivery_date_label"
															value={
																settings.delivery_date_label ??
																''
															}
															onChange={ change(
																'delivery_date_label'
															) }
														/>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Delivery Time:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Delivery Time label on the product page.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<input
															className="ib-md"
															type="text"
															name="delivery_time_label"
															id="delivery_time_label"
															value={
																settings.delivery_time_label ??
																''
															}
															onChange={ change(
																'delivery_time_label'
															) }
														/>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Choose Time Text:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Text for the 1st option of Time Slot dropdown field that instructs the customer to select a time slot.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<input
															className="ib-md"
															type="text"
															name="delivery_time_select_option"
															id="delivery_time_select_option"
															value={
																settings.delivery_time_select_option ??
																''
															}
															onChange={ change(
																'delivery_time_select_option'
															) }
														/>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Delivery Date Placeholder:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={
																__(
																	'Placeholder for delivery date field.',
																	'product-delivery-date-for-woocommerce-lite'
																) + ' '
															}
														/>
														<input
															className="ib-md"
															type="text"
															name="delivery_placeholder_option"
															id="delivery_placeholder_option"
															value={
																settings.delivery_placeholder_option ??
																''
															}
															onChange={ change(
																'delivery_placeholder_option'
															) }
														/>
													</div>
												</div>
											</div>
										</div>
									</div>
								</CollapsePanel>
								{ /*Second Panel*/ }
								<CollapsePanel
									id="collapseTwo"
									defaultOpen
									title={ __(
										'Labels on Order Received page & in email notification',
										'product-delivery-date-for-woocommerce-lite'
									) }
								>
									<div className="panel-body">
										<div className="tbl-mod-1">
											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Delivery Date:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Delivery Date label on the order received page and email notification.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<input
															className="ib-md"
															type="text"
															name="delivery_item_meta_date"
															id="delivery_item_meta_date"
															value={
																settings.delivery_item_meta_date ??
																''
															}
															onChange={ change(
																'delivery_item_meta_date'
															) }
														/>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Delivery Time:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Delivery Time label on the order received page and email notification.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<input
															className="ib-md"
															type="text"
															name="delivery_item_meta_time"
															id="delivery_item_meta_time"
															value={
																settings.delivery_item_meta_time ??
																''
															}
															onChange={ change(
																'delivery_item_meta_time'
															) }
														/>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'ICS File Name:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={
																__(
																	'ICS File name.',
																	'product-delivery-date-for-woocommerce-lite'
																) + ' '
															}
														/>
														<input
															className="ib-md"
															type="text"
															name="delivery_ics_file_name"
															id="delivery_ics_file_name"
															value={
																settings.delivery_ics_file_name ??
																''
															}
															onChange={ change(
																'delivery_ics_file_name'
															) }
														/>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Delivery Charges:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Delivery Charges label on the order received page and email notification.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<input
															className="ib-md"
															type="text"
															name="delivery_item_meta_charges"
															id="delivery_item_meta_charges"
															value={
																settings.delivery_item_meta_charges ??
																''
															}
															onChange={ change(
																'delivery_item_meta_charges'
															) }
														/>
													</div>
												</div>
											</div>
										</div>
									</div>
								</CollapsePanel>

								{ /*Third Panel*/ }
								<CollapsePanel
									id="collapseThree"
									defaultOpen
									title={ __(
										'Labels on Cart & Check-out Page',
										'product-delivery-date-for-woocommerce-lite'
									) }
								>
									<div className="panel-body">
										<div className="tbl-mod-1">
											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Delivery Date:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={
																__(
																	'Delivery Date label on the cart and checkout page.',
																	'product-delivery-date-for-woocommerce-lite'
																) + ' '
															}
														/>
														<input
															className="ib-md"
															type="text"
															name="delivery_item_cart_date"
															id="delivery_item_cart_date"
															value={
																settings.delivery_item_cart_date ??
																''
															}
															onChange={ change(
																'delivery_item_cart_date'
															) }
														/>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Delivery Time:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Delivery Time label on the cart and checkout page.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<input
															className="ib-md"
															type="text"
															name="delivery_item_cart_time"
															id="delivery_item_cart_time"
															value={
																settings.delivery_item_cart_time ??
																''
															}
															onChange={ change(
																'delivery_item_cart_time'
															) }
														/>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Delivery Charges:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={
																__(
																	'Delivery Charges label on the cart and checkout page.',
																	'product-delivery-date-for-woocommerce-lite'
																) + ' '
															}
														/>
														<input
															className="ib-md"
															type="text"
															name="delivery_item_cart_charges"
															id="delivery_item_cart_charges"
															value={
																settings.delivery_item_cart_charges ??
																''
															}
															onChange={ change(
																'delivery_item_cart_charges'
															) }
														/>
													</div>
												</div>
											</div>
										</div>
									</div>
								</CollapsePanel>

								{ /*Forth Panel*/ }
								<CollapsePanel
									id="collapseFour"
									defaultOpen
									title={ __(
										'Labels for Estimated Delivery Option',
										'product-delivery-date-for-woocommerce-lite'
									) }
								>
									<div className="panel-body">
										<div className="tbl-mod-1">
											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Estimated Delivery Section Heading:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Estimated Delivery Section Heading',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<input
															className="ib-md"
															type="text"
															name="prdd_estimate_delivery_header"
															id="prdd_estimate_delivery_header"
															value={
																settings.prdd_estimate_delivery_header ??
																''
															}
															onChange={ change(
																'prdd_estimate_delivery_header'
															) }
														/>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Estimated Delivery Display In Days Text:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap">
														<InfoTip
															text={ __(
																'Use {{business.days}} shortcode to replace it with the number of business days required for delivery',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<textarea
															className="ta-sm"
															name="prdd_estimate_delivery_days_text"
															id="prdd_estimate_delivery_days_text"
															value={
																settings.prdd_estimate_delivery_days_text ??
																''
															}
															onChange={ change(
																'prdd_estimate_delivery_days_text'
															) }
														></textarea>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Estimated Delivery Display with Specific Date Text:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap">
														<InfoTip
															text={ __(
																'Use {{expected.date}} shortcode to replace it with the expected date of delivery',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<textarea
															className="ta-sm"
															name="prdd_estimate_delivery_date_text"
															id="prdd_estimate_delivery_date_text"
															value={
																settings.prdd_estimate_delivery_date_text ??
																''
															}
															onChange={ change(
																'prdd_estimate_delivery_date_text'
															) }
														></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
								</CollapsePanel>
							</div>
						</div>

						<div className="ss-foot">
							<button type="button" onClick={ () => save() }>
								{ __(
									'Save Settings',
									'product-delivery-date-for-woocommerce-lite'
								) }
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}
