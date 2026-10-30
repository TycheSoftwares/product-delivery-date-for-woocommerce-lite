/**
 * Calendar Appearance tab, markup of the former includes/admin/views/appearance/calendar-appearance.php.
 */
import { __ } from '@wordpress/i18n';
import CollapsePanel from '../../../shared/components/CollapsePanel';
import { ProOnly } from '../../../shared/components/ProNotice';
import InfoTip from '../../../shared/components/InfoTip';
import ErrorMessage from '../../../shared/components/ErrorMessage';
import ThemePreview, { setCalendarTheme } from '../../components/ThemePreview';
import useSettingsForm, { vLoading, vShow } from '../../hooks/useSettingsForm';
import Spinner from '../../../shared/components/Spinner';

// The option lists come with the settings and are not saved back.
const prepare = ( { options, ...data } ) => data; // eslint-disable-line no-unused-vars

export default function CalendarAppearance() {
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
	} = useSettingsForm( 'settings/appearance', { prepare } );
	const options = settings.options || {};

	const changeTheme = ( event ) => {
		update( 'prdd_themes', event.target.value );
		setCalendarTheme( event.target.value );
	};

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
										'Calendar Appearance Settings',
										'product-delivery-date-for-woocommerce-lite'
									) }
								</h1>
								<p>
									{ __(
										'Setup the delivery calendar as per your needs. How? Check the settings below and adjust them based on your requirements.',
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
										'Calendar Appearance',
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
																'Enable Availability Display on the Product page:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="rc-flx-wrap flx-aln-center">
															<InfoTip
																text={ __(
																	'Please select this checkbox if you want to display the number of deliveries available for a given product on a given date and time.',
																	'product-delivery-date-for-woocommerce-lite'
																) }
															/>
															<label className="el-switch el-switch-green">
																<input
																	type="checkbox"
																	name="prdd_availability_display"
																	id="prdd_availability_display"
																	checked={
																		'on' ===
																		settings.prdd_availability_display
																	}
																	onChange={ toggle(
																		'prdd_availability_display'
																	) }
																/>
																<span className="el-switch-style"></span>
															</label>
														</div>
													</div>
												</div>
											</ProOnly>
											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Calendar Language:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Choose the language for your delivery calendar.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<select
															className="ib-md"
															id="prdd_language"
															name="prdd_language"
															value={
																settings.prdd_language ??
																''
															}
															onChange={ change(
																'prdd_language'
															) }
														>
															{ (
																options.languages ||
																[]
															).map(
																( option ) => (
																	<option
																		key={
																			option.value
																		}
																		value={
																			option.value
																		}
																	>
																		{
																			option.label
																		}
																	</option>
																)
															) }
														</select>
													</div>
												</div>
											</div>
											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Date Format:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'The format in which the delivery date appears to the customers on the product page once the date is selected.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<select
															className="ib-md-3"
															id="prdd_date_format"
															name="prdd_date_format"
															value={
																settings.prdd_date_format ??
																''
															}
															onChange={ change(
																'prdd_date_format'
															) }
														>
															{ (
																options.date_formats ||
																[]
															).map(
																( option ) => (
																	<option
																		key={
																			option.value
																		}
																		value={
																			option.value
																		}
																	>
																		{
																			option.label
																		}
																	</option>
																)
															) }
														</select>
													</div>
												</div>
											</div>
											<ProOnly>
												<div className="tm1-row flx-center">
													<div className="col-left">
														<label>
															{ __(
																'Time Format:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="rc-flx-wrap flx-aln-center">
															<InfoTip
																text={ __(
																	'The format in which the delivery time appears to the customers on the product page once the date is selected.',
																	'product-delivery-date-for-woocommerce-lite'
																) }
															/>
															<select
																className="ib-md-3"
																id="prdd_time_format"
																name="prdd_time_format"
																value={
																	settings.prdd_time_format ??
																	''
																}
																onChange={ change(
																	'prdd_time_format'
																) }
															>
																{ (
																	options.time_formats ||
																	[]
																).map(
																	(
																		option
																	) => (
																		<option
																			key={
																				option.value
																			}
																			value={
																				option.value
																			}
																		>
																			{
																				option.label
																			}
																		</option>
																	)
																) }
															</select>
														</div>
													</div>
												</div>
											</ProOnly>
											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'First Day of Week:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Choose the first day of week displayed on the Delivery Date calendar.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<select
															className="ib-md-3"
															id="prdd_calendar_day"
															name="prdd_calendar_day"
															value={
																settings.prdd_calendar_day ??
																''
															}
															onChange={ change(
																'prdd_calendar_day'
															) }
														>
															{ (
																options.days ||
																[]
															).map(
																( option ) => (
																	<option
																		key={
																			option.value
																		}
																		value={
																			option.value
																		}
																	>
																		{
																			option.label
																		}
																	</option>
																)
															) }
														</select>
													</div>
												</div>
											</div>

											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Number of Months:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'The number of months to be shown on the calendar. If the delivery dates spans across 2 months, then dates of 2 months can be shown simultaneously without the need to press Next or Back buttons.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<select
															className="ib-md-3"
															id="prdd_months"
															name="prdd_months"
															value={
																settings.prdd_months ??
																''
															}
															onChange={ change(
																'prdd_months'
															) }
														>
															<option value="1">
																1
															</option>
															<option value="2">
																2
															</option>
														</select>
													</div>
												</div>
											</div>
											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Theme:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="row-box-1">
														<div className="rb1-left">
															<InfoTip
																text={ __(
																	'Select the theme for the calendar. You can choose a theme which blends with the design of your website.',
																	'product-delivery-date-for-woocommerce-lite'
																) }
															/>
														</div>
														<div className="rb1-right">
															<div className="rb1-row">
																<select
																	className="ib-md"
																	id="prdd_themes"
																	name="prdd_themes"
																	value={
																		settings.prdd_themes ??
																		''
																	}
																	onChange={
																		changeTheme
																	}
																>
																	{ (
																		options.themes ||
																		[]
																	).map(
																		(
																			option
																		) => (
																			<option
																				key={
																					option.value
																				}
																				value={
																					option.value
																				}
																			>
																				{
																					option.label
																				}
																			</option>
																		)
																	) }
																</select>
															</div>
															<div className="rb1-row adj-img">
																<p>
																	{ __(
																		'Preview Theme',
																		'product-delivery-date-for-woocommerce-lite'
																	) }
																</p>
															</div>
															<div className="rb1-row adj-img cal-theme-image">
																{ loaded && (
																	<ThemePreview
																		start={
																			settings.prdd_calendar_day
																		}
																		language={
																			settings.prdd_language
																		}
																	/>
																) }
															</div>
														</div>
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
