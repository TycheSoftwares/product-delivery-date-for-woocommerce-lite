/**
 * Settings tab, markup of the former includes/admin/views/settings/settings.php.
 */
import { __ } from '@wordpress/i18n';
import config from '../../../shared/config';
import CollapsePanel from '../../../shared/components/CollapsePanel';
import InfoTip from '../../../shared/components/InfoTip';
import ErrorMessage from '../../../shared/components/ErrorMessage';
import { ProOnly } from '../../../shared/components/ProNotice';
import useSettingsForm, { vLoading, vShow } from '../../hooks/useSettingsForm';
import Spinner from '../../../shared/components/Spinner';

export default function Settings() {
	const {
		settings,
		update,
		toggle,
		change,
		loaded,
		loading,
		saving,
		savedMessage,
		setSavedMessage,
		error,
		setError,
		save,
	} = useSettingsForm( 'settings/general' );

	return (
		<div className="container setting-page-wrap">
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
								aria-label={ __(
									'Close',
									'product-delivery-date-for-woocommerce-lite'
								) }
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
			<div className="prdd_events_loader" style={ vShow( saving ) }>
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
									'Settings',
									'product-delivery-date-for-woocommerce-lite'
								) }
							</h1>
							<p>
								{ __(
									'Here are some general settings for your store. We have set up some defaults to help you get started!',
									'product-delivery-date-for-woocommerce-lite'
								) }
							</p>
						</div>
						<div className="float-right">
							<button
								type="button"
								onClick={ () => save() }
								disabled={ ! loaded || saving }
							>
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
									'General Settings',
									'product-delivery-date-for-woocommerce-lite'
								) }
							>
								<div className="panel-body">
									<div className="tbl-mod-1">
										<div className="tm1-row">
											<div className="col-left">
												<label>
													{ __(
														'Enable Rounding of Prices:',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</label>
											</div>
											<div className="col-right">
												<div className="rc-flx-wrap flx-aln-center">
													<InfoTip
														text={ __(
															'Rounds the Price to the nearest Integer value.',
															'product-delivery-date-for-woocommerce-lite'
														) }
													/>
													<label className="el-switch el-switch-green">
														<input
															type="checkbox"
															name="prdd_enable_rounding"
															id="prdd_enable_rounding"
															checked={
																'on' ===
																settings.prdd_enable_rounding
															}
															onChange={ toggle(
																'prdd_enable_rounding'
															) }
														/>
														<span className="el-switch-style"></span>
													</label>
												</div>
											</div>
										</div>

										<ProOnly>
											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Allow Deliveries to be Editable:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Enabling this option will allow Deliveries to be editable from Cart and Checkout page',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_enable_delivery_edit"
																id="prdd_enable_delivery_edit"
																checked={
																	'on' ===
																	settings.prdd_enable_delivery_edit
																}
																onChange={ toggle(
																	'prdd_enable_delivery_edit'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Allow Deliveries to be Reschedulable:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Enabling this option will allow Deliveries to be reschedulable from My Account page',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_enable_delivery_reschedule"
																id="prdd_enable_delivery_reschedule"
																checked={
																	'on' ===
																	settings.prdd_enable_delivery_reschedule
																}
																onChange={ toggle(
																	'prdd_enable_delivery_reschedule'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Minimum Number of Days For Rescheduling:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Minimum number of days before the delivery date, after which Delivery cannot be rescheduled.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<input
															className="ib-md"
															type="text"
															name="prdd_delivery_reschedule_days"
															id="prdd_delivery_reschedule_days"
															value={
																settings.prdd_delivery_reschedule_days ??
																''
															}
															onChange={ change(
																'prdd_delivery_reschedule_days'
															) }
														/>
													</div>
												</div>
											</div>
										</ProOnly>

										<div className="tm1-row flx-center">
											<div className="col-left">
												<label>
													{ __(
														'Keep the data when deleting the plugin?',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</label>
											</div>
											<div className="col-right">
												<div className="rc-flx-wrap flx-aln-center">
													<InfoTip
														text={ __(
															'Enabling this setting will not delete the data when you delete the plugin from your website.',
															'product-delivery-date-for-woocommerce-lite'
														) }
													/>
													<label className="el-switch el-switch-green">
														<input
															type="checkbox"
															name="prdd_keep_data_on_delete_plugin"
															id="prdd_keep_data_on_delete_plugin"
															checked={
																'on' ===
																settings.prdd_keep_data_on_delete_plugin
															}
															onChange={ toggle(
																'prdd_keep_data_on_delete_plugin'
															) }
														/>
														<span className="el-switch-style"></span>
													</label>
												</div>
											</div>
										</div>

										{ config.reset_tracking_url && (
											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Reset usage tracking:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'This will reset your usage tracking statistics, causing it to show the opt-in banner again and not send any data.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<a
															href={
																config.reset_tracking_url
															}
															className="trietary-btn reverse"
														>
															{ __(
																'Reset',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</a>
													</div>
												</div>
											</div>
										) }
									</div>
								</div>
							</CollapsePanel>
							{ /*Second Panel*/ }
							<CollapsePanel
								id="collapseTwo"
								defaultOpen
								title={ __(
									'Product Page Settings',
									'product-delivery-date-for-woocommerce-lite'
								) }
							>
								<div className="panel-body">
									<div className="tbl-mod-1">
										<ProOnly>
											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Duplicate Dates from First Product In the Cart To Other Products:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'The plugin will duplicate the delivery date selected for the first product in the cart for all other products. In case if the user changes the date for a product added later, the same will be applied to existing products in the cart.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_global_selection"
																id="prdd_global_selection"
																checked={
																	'on' ===
																	settings.prdd_global_selection
																}
																onChange={ toggle(
																	'prdd_global_selection'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div
												className="tm1-row flx-center"
												style={ vShow(
													'on' ===
														settings.prdd_global_selection
												) }
											>
												<div className="col-left">
													<label>
														{ __(
															'Select products to apply Duplicate Dates:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<span className="el-radio el-radio-green">
														<input
															type="radio"
															id="prdd_for_all"
															value="prdd_for_all"
															checked={
																'prdd_for_all' ===
																settings.prdd_for_all
															}
															onChange={ () =>
																update(
																	'prdd_for_all',
																	'prdd_for_all'
																)
															}
														/>
														<label
															className="el-radio-style"
															htmlFor="prdd_for_all"
														></label>
													</span>
													<label htmlFor="prdd_for_all">
														{ __(
															'Select if you want to apply for all product',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
													<div></div>
													<span className="el-radio el-radio-green">
														<input
															type="radio"
															id="prdd_for_category"
															value="prdd_for_category"
															checked={
																'prdd_for_category' ===
																settings.prdd_for_all
															}
															onChange={ () =>
																update(
																	'prdd_for_all',
																	'prdd_for_category'
																)
															}
														/>
														<label
															className="el-radio-style"
															htmlFor="prdd_for_category"
														></label>
													</span>
													<label htmlFor="prdd_for_category">
														{ __(
															'Select if you want to apply for particular category product',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
											</div>

											<div
												className="tm1-row"
												style={ vShow(
													'on' ===
														settings.prdd_global_selection &&
														'prdd_for_category' ===
															settings.prdd_for_all
												) }
											>
												<div className="col-left">
													<label>
														{ __(
															'Select categories:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap invisible-field">
														<InfoTip
															text={ __(
																'Select categories that you want to apply duplicates date.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<select
															className="chosen_category"
															id="prdd_products_categories"
															multiple
															style={ {
																width: '300px',
															} }
														/>
													</div>
												</div>
											</div>

											<div
												className="tm1-row flx-center"
												style={ vShow(
													'on' ===
														settings.prdd_global_selection
												) }
											>
												<div className="col-left">
													<label>
														{ __(
															'Enable this if you want that the delivery date chosen for a product should be automatically applied on all other existing products in the cart:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Enable this if you want that the delivery date chosen for a product should be automatically applied on all other existing products in the cart.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_globally_same_date"
																id="prdd_globally_same_date"
																checked={
																	'on' ===
																	settings.prdd_globally_same_date
																}
																onChange={ toggle(
																	'prdd_globally_same_date'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div
												className="tm1-row"
												style={ vShow(
													'on' ===
														settings.prdd_global_selection &&
														'on' ===
															settings.prdd_globally_same_date
												) }
											>
												<div className="col-left">
													<label>
														{ __(
															'Change duplicate date message which is displaying on the product page:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap">
														<InfoTip
															text={ __(
																'Change duplicate date message which is displaying on the product page.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<textarea
															className="ta-sm"
															name="prdd_duplicates_date_note_text"
															id="prdd_duplicates_date_note_text"
															value={
																settings.prdd_duplicates_date_note_text ??
																''
															}
															onChange={ change(
																'prdd_duplicates_date_note_text'
															) }
														></textarea>
													</div>
												</div>
											</div>

											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Apply One-Time Delivery Charges For Multiple Products:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Select this checkbox to restrict delivery charges to apply only once for multiple products with the same delivery date in the cart.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_disable_price_calculation_on_dates"
																id="prdd_disable_price_calculation_on_dates"
																checked={
																	'on' ===
																	settings.prdd_disable_price_calculation_on_dates
																}
																onChange={ toggle(
																	'prdd_disable_price_calculation_on_dates'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Disable extra charge when user chooses local pickup shipping',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Select this checkbox to disable the extra charges when user place order to the local pickup shipping method.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_enable_local_pickup"
																id="prdd_enable_local_pickup"
																checked={
																	'on' ===
																	settings.prdd_enable_local_pickup
																}
																onChange={ toggle(
																	'prdd_enable_local_pickup'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Auto-select the first available timeslot',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Enabling this option will pre-select the first available time slot.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="pre_selected_timeslot"
																id="pre_selected_timeslot"
																checked={
																	'on' ===
																	settings.pre_selected_timeslot
																}
																onChange={ toggle(
																	'pre_selected_timeslot'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Auto-populate first available delivery date',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																"Enabling this option will auto populate the first available delivery date in the calendar. This setting will be overridden when 'Duplicate Dates from First Product In the Cart To Other Products' setting is enabled.",
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="pre_select_next_date"
																id="pre_select_next_date"
																checked={
																	'on' ===
																	settings.pre_select_next_date
																}
																onChange={ toggle(
																	'pre_select_next_date'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Minimum Order Amount for Waiving off Delivery Charges',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																"When Order Subtotal reaches this minimum amount, all delivery charges from the plugin will be removed. Keep it 0 if you don't want to use this option.",
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<input
															className="ib-md"
															type="text"
															name="prdd_discard_delivery_charges"
															id="prdd_discard_delivery_charges"
															value={
																settings.prdd_discard_delivery_charges ??
																''
															}
															onChange={ change(
																'prdd_discard_delivery_charges'
															) }
														/>
													</div>
												</div>
											</div>
										</ProOnly>
									</div>
								</div>
							</CollapsePanel>
							{ /*Third Panel*/ }
							<CollapsePanel
								id="collapseThree"
								defaultOpen
								title={ __(
									'Shop Page Settings',
									'product-delivery-date-for-woocommerce-lite'
								) }
							>
								<div className="panel-body">
									<div className="tbl-mod-1">
										<ProOnly>
											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Show Delivery Date/Time On Shop/Archives Page:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Enabling this setting will show delivery date picker & time slots dropdown on the Shop page & give customers a smooth single page experience. Works only for Simple products.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_show_delivery_fields_on_shop"
																id="prdd_show_delivery_fields_on_shop"
																checked={
																	'on' ===
																	settings.prdd_show_delivery_fields_on_shop
																}
																onChange={ toggle(
																	'prdd_show_delivery_fields_on_shop'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div
												className="tm1-row flx-center"
												style={ vShow(
													'on' ===
														settings.prdd_show_delivery_fields_on_shop
												) }
											>
												<div className="col-left">
													<label>
														{ __(
															'Enable Availability Display On The Shop/Archives Page:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Enabling this setting will show the availability message on the Shop/archives page when page loads & on selection of a date or time slot.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_enable_availability_on_shop"
																id="prdd_enable_availability_on_shop"
																checked={
																	'on' ===
																	settings.prdd_enable_availability_on_shop
																}
																onChange={ toggle(
																	'prdd_enable_availability_on_shop'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>
										</ProOnly>
									</div>
								</div>
							</CollapsePanel>
						</div>
					</div>
					<div className="ss-foot">
						<button
							type="button"
							onClick={ () => save() }
							disabled={ ! loaded || saving }
						>
							{ __(
								'Save Settings',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</button>
					</div>
				</div>
			</div>
		</div>
	);
}
