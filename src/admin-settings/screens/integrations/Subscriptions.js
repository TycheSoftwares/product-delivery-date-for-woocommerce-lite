/**
 * Subscriptions tab, markup of the former includes/admin/views/integrations/subscriptions.php.
 */
import { __ } from '@wordpress/i18n';
import CollapsePanel from '../../../shared/components/CollapsePanel';
import InfoTip from '../../../shared/components/InfoTip';
import useSettingsForm, { vLoading, vShow } from '../../hooks/useSettingsForm';
import Spinner from '../../../shared/components/Spinner';

// The status of WooCommerce Subscriptions is not a setting.
const prepare = ( { prdd_wc_subscriptions_active: active, ...data } ) => data; // eslint-disable-line no-unused-vars

export default function Subscriptions() {
	const {
		settings,
		update,
		loaded,
		loading,
		saving,
		savedMessage,
		setSavedMessage,
		error,
		setError,
		save,
	} = useSettingsForm( 'settings/subscriptions', { prepare } );

	// Checkboxes stored as 'yes' / 'no'.
	const yesNo = ( key ) => ( event ) =>
		update( key, event.target.checked ? 'yes' : 'no' );

	return (
		<div className="prdd-content-area">
			<div className="container fas-page-wrap">
				{ /* Saved message */ }
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
				{ /* Error message */ }
				<div
					className="container-fluid pl-info-wrap"
					style={ vShow( '' !== error ) }
				>
					<div className="row">
						<div className="col-md-12">
							<div
								className="alert alert-danger alert-dismissible fade show"
								role="alert"
							>
								{ error }
								<button
									type="button"
									className="close"
									data-dismiss="alert"
									aria-label="Close"
									onClick={ () => setError( '' ) }
								>
									<span aria-hidden="true">×</span>
								</button>
							</div>
						</div>
					</div>
				</div>
				{ /* Saving loader */ }
				<div id="prdd_events_loader" style={ vShow( saving ) }>
					<div className="prdd_events_loader_wrapper">
						{ __(
							'Saving Changes…',
							'product-delivery-date-for-woocommerce-lite'
						) }
						<Spinner />
					</div>
				</div>
				{ /* Loading loader */ }
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
						{ /* Page header */ }
						<div className="prdd-page-head phw-btn">
							<div className="float-left prdd-container">
								<h1>
									{ __(
										'WooCommerce Subscriptions Compatibility',
										'product-delivery-date-for-woocommerce-lite'
									) }
								</h1>
								<p>
									{ __(
										'Configure how Product Delivery Date Pro behaves with WooCommerce Subscriptions. Requires WooCommerce Subscriptions (by WooCommerce) to be installed and active.',
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

						{ /* WooCommerce Subscriptions not active notice */ }
						<div
							className="container-fluid pl-info-wrap"
							style={ vShow(
								loaded &&
									! settings.prdd_wc_subscriptions_active
							) }
						>
							<div className="row">
								<div className="col-md-12">
									<div
										className="alert alert-warning alert-dismissible fade show"
										role="alert"
									>
										<span
											dangerouslySetInnerHTML={ {
												__html: __(
													'<strong>WooCommerce Subscriptions is not active.</strong> Please install and activate <a href="https://woocommerce.com/products/woocommerce-subscriptions/" target="_blank">WooCommerce Subscriptions</a> to use these settings.',
													'product-delivery-date-for-woocommerce-lite'
												),
											} }
										/>
									</div>
								</div>
							</div>
						</div>

						<div className="wbc-accordion">
							<div
								className="panel-group prdd-accordian"
								id="wbc-accordion"
							>
								{ /* ─── Panel 1: Subscription Compatibility Settings ─────────────────── */ }
								<CollapsePanel
									id="collapseSubOne"
									defaultOpen
									headingTag="h2"
									ariaExpanded
									title={ __(
										'Subscription Compatibility Settings',
										'product-delivery-date-for-woocommerce-lite'
									) }
								>
									<div className="panel-body">
										<div className="tbl-mod-1">
											{ /* Enable Subscription Compatibility (master toggle) */ }
											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Enable Subscription Compatibility:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Master toggle. Activates all subscription-aware behaviour in the plugin. All settings below require this to be enabled.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_subscription_compatibility"
																id="prdd_subscription_compatibility"
																checked={
																	'yes' ===
																	settings.prdd_subscription_compatibility
																}
																onChange={ yesNo(
																	'prdd_subscription_compatibility'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											{ /* Sub-settings: only interactive when master is enabled */ }
											<div
												style={ vShow(
													'yes' ===
														settings.prdd_subscription_compatibility
												) }
											>
												{ /* Delivery Date & Time as per Subscription Period */ }
												<div className="tm1-row">
													<div className="col-left">
														<label>
															{ __(
																'Delivery Date & Time as per Subscription Period:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="rc-flx-wrap flx-aln-center">
															<InfoTip
																text={ __(
																	'The delivery calendar on the product page will display only dates within the current billing period. Weekly subscription → next 7 days. Monthly → next 30 days. Yearly → next 365 days. All existing rules (blackout dates, lockout, cut-off times) still apply within the window.',
																	'product-delivery-date-for-woocommerce-lite'
																) }
															/>
															<label className="el-switch el-switch-green">
																<input
																	type="checkbox"
																	name="prdd_subscription_calendar_period"
																	id="prdd_subscription_calendar_period"
																	checked={
																		'yes' ===
																		settings.prdd_subscription_calendar_period
																	}
																	onChange={ yesNo(
																		'prdd_subscription_calendar_period'
																	) }
																/>
																<span className="el-switch-style"></span>
															</label>
														</div>
													</div>
												</div>

												{ /* Update Next Payment Date */ }
												<div className="tm1-row">
													<div className="col-left">
														<label>
															{ __(
																'Update Next Payment Date Based on Delivery Date & Cut-off:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="rc-flx-wrap flx-aln-center">
															<InfoTip
																text={ __(
																	"After checkout, the subscription's next payment date will be automatically recalculated as: selected delivery date + 1 billing period, adjusted for the cut-off time buffer to ensure fulfilment is always achievable.",
																	'product-delivery-date-for-woocommerce-lite'
																) }
															/>
															<label className="el-switch el-switch-green">
																<input
																	type="checkbox"
																	name="prdd_subscription_update_next_payment"
																	id="prdd_subscription_update_next_payment"
																	checked={
																		'yes' ===
																		settings.prdd_subscription_update_next_payment
																	}
																	onChange={ yesNo(
																		'prdd_subscription_update_next_payment'
																	) }
																/>
																<span className="el-switch-style"></span>
															</label>
														</div>
													</div>
												</div>

												{ /* Auto renewal notice row (no toggle — always active) */ }
												<div className="tm1-row">
													<div className="col-left">
														<label>
															{ __(
																'Update Delivery Date & Time on Renewal:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="rc-flx-wrap flx-aln-center">
															<InfoTip
																text={ __(
																	'On every renewal order, the plugin automatically calculates and assigns the next valid delivery date based on: previous delivery date, billing schedule, minimum delivery time, cut-off times, blackout dates, and non-working days. Delivery lockout & charges are also updated on the renewal order. This is always active when Subscription Compatibility is enabled — no separate toggle is required.',
																	'product-delivery-date-for-woocommerce-lite'
																) }
															/>
															<span className="prdd-sub-auto-badge">
																<span className="dashicons dashicons-yes-alt"></span>
																{ __(
																	'Always active when compatibility is enabled',
																	'product-delivery-date-for-woocommerce-lite'
																) }
															</span>
														</div>
													</div>
												</div>
											</div>
											{ /* /sub-settings */ }

											{ /* Hint row shown when master is off */ }
											<div
												className="tm1-row"
												style={ vShow(
													'yes' !==
														settings.prdd_subscription_compatibility
												) }
											>
												<div className="col-full">
													<p className="description">
														{ __(
															'Enable the master toggle above to configure subscription delivery options.',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</p>
												</div>
											</div>
										</div>
									</div>
								</CollapsePanel>
								{ /* /Panel 1 */ }

								{ /* ─── Panel 2: How Renewal Dates Are Calculated ─────────────────────── */ }
								<CollapsePanel
									id="collapseSubTwo"
									defaultOpen
									headingTag="h2"
									style={ vShow(
										'yes' ===
											settings.prdd_subscription_compatibility
									) }
									title={ __(
										'How Renewal Delivery Dates Are Calculated',
										'product-delivery-date-for-woocommerce-lite'
									) }
								>
									<div className="panel-body">
										<div className="tbl-mod-1">
											<div className="tm1-row">
												<div className="col-full">
													<p className="description">
														{ __(
															'When a renewal order is generated, the plugin automatically determines the next delivery date using the following rules in order:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</p>
													<ol className="prdd-sub-info-list">
														<li>
															{ __(
																'Start from: last delivery date + subscription billing interval (e.g. +7 days for weekly).',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Apply minimum delivery time configured for the product or globally.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Apply cut-off time — if the deadline for the candidate date has already passed, advance by one day.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Skip blackout / holiday dates.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Skip non-working days (weekdays disabled in product or global settings).',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Skip dates / time slots that have reached their maximum delivery lockout count.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Assign the resulting date to the renewal order and update lockout counts and delivery charges accordingly.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
													</ol>
													<p
														className="description"
														style={ {
															marginTop: '10px',
														} }
														dangerouslySetInnerHTML={ {
															__html: __(
																'<strong>Note:</strong> If no valid date is found within 90 days (e.g. all dates are blocked), the renewal order is flagged and an email notification is sent to the store admin.',
																'product-delivery-date-for-woocommerce-lite'
															),
														} }
													/>
												</div>
											</div>
										</div>
									</div>
								</CollapsePanel>
								{ /* /Panel 2 */ }

								{ /* ─── Panel 3: Edge Case Behaviour ─────────────────────────────────── */ }
								<CollapsePanel
									id="collapseSubThree"
									headingTag="h2"
									style={ vShow(
										'yes' ===
											settings.prdd_subscription_compatibility
									) }
									title={ __(
										'Edge Case Behaviour',
										'product-delivery-date-for-woocommerce-lite'
									) }
								>
									<div className="panel-body">
										<div className="tbl-mod-1">
											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Subscription Schedule Change:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<p className="description">
															{ __(
																'If a subscription billing schedule is changed (e.g. weekly → monthly), the new billing interval is automatically used from the next renewal onwards. No manual action is needed.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</p>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Paused Subscriptions:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<p className="description">
															{ __(
																'No delivery date is assigned during a pause. When a subscription is resumed, the delivery date baseline is cleared and recalculated fresh from the resumption date, so missed weeks are not backfilled.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</p>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Manual Renewals:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<p className="description">
															{ __(
																'Manual renewals triggered from the WooCommerce admin follow the same automatic delivery date calculation logic as scheduled renewals.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</CollapsePanel>
								{ /* /Panel 3 */ }
							</div>
						</div>
						{ /* /accordion */ }

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
