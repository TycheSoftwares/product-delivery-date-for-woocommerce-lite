/**
 * Google Calendar Sync tab, markup of the former includes/admin/views/integrations/google-calendar-sync.php.
 */
import { __, sprintf } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { pluginUrl } from '../../../shared/config';
import { apiPost, errorMessage } from '../../../shared/api';
import CollapsePanel from '../../../shared/components/CollapsePanel';
import InfoTip from '../../../shared/components/InfoTip';
import ErrorMessage from '../../../shared/components/ErrorMessage';
import useSettingsForm, { vLoading, vShow } from '../../hooks/useSettingsForm';
import Spinner from '../../../shared/components/Spinner';

/**
 * Reads the status of the OAuth connection the Google redirect adds to the
 * URL and removes it, so a reload does not show the message again.
 *
 * @return {string} 'success', 'fail' or ''.
 */
function takeConnectionStatus() {
	const url = new URL( window.location.href );
	const status = url.searchParams.get( 'ts_con_status' ) || '';
	if ( status ) {
		url.searchParams.delete( 'ts_con_status' );
		window.history.replaceState( {}, document.title, url.toString() );
	}
	return status;
}

// Values only used for display are not saved back.
const prepare = ( {
	calendars,
	connect_link: connectLink,
	redirect_uri: redirectUri,
	logout_url: logoutUrl,
	prdd_gcal_success: success,
	prdd_gcal_failure: failure,
	key_file_dir: keyFileDir,
	...data
} ) => data; // eslint-disable-line no-unused-vars

export default function GoogleCalendarSync() {
	const [ status ] = useState( takeConnectionStatus );
	const {
		settings,
		setSettings,
		update,
		toggle,
		change,
		loading,
		saving,
		savedMessage,
		setSavedMessage,
		error,
		setError,
		save,
	} = useSettingsForm(
		'integrations/google-calendar' +
			( status ? '?status=' + encodeURIComponent( status ) : '' ),
		{ savePath: 'integrations/google-calendar', prepare }
	);
	const [ showInfo, setShowInfo ] = useState( false );
	const [ testing, setTesting ] = useState( false );
	const [ testResult, setTestResult ] = useState( null );
	const [ loggingOut, setLoggingOut ] = useState( false );
	const [ notice, setNotice ] = useState( null );

	// Message of the OAuth redirect, the texts come with the settings.
	let gcalSuccess = notice && notice.success ? notice.message : '';
	let gcalError = notice && ! notice.success ? notice.message : '';
	if ( ! notice && 'success' === status ) {
		gcalSuccess = settings.prdd_gcal_success || '';
	} else if ( ! notice && 'fail' === status ) {
		gcalError = settings.prdd_gcal_failure || '';
	}

	const showInstructions = ( event ) => {
		event.preventDefault();
		setShowInfo( ! showInfo );
	};

	const saveSettings = () => {
		save().then( ( response ) => {
			// The response carries the new connect link and calendars.
			if ( response && response.data ) {
				setSettings( response.data );
			}
		} );
	};

	const testConnection = ( event ) => {
		event.preventDefault();
		setTesting( true );
		setTestResult( null );
		apiPost( 'integrations/google-calendar/test' )
			.then( ( response ) =>
				setTestResult( {
					message: response.message,
					className:
						response.data && response.data.success
							? 'success-msg'
							: 'error-msg',
				} )
			)
			.catch( () =>
				setTestResult( {
					message: __(
						'An error occurred. Please try again.',
						'product-delivery-date-for-woocommerce-lite'
					),
					className: 'error-msg',
				} )
			)
			.finally( () => setTesting( false ) );
	};

	const logout = ( event ) => {
		event.preventDefault();
		event.stopPropagation();
		setLoggingOut( true );
		apiPost( 'integrations/google-calendar/logout' )
			.then( ( response ) => {
				setNotice( { success: true, message: response.message } );
				update( 'logout_url', '' );
			} )
			.catch( ( e ) =>
				setNotice( { success: false, message: errorMessage( e ) } )
			)
			.finally( () => setLoggingOut( false ) );
	};

	const copyToClipboard = ( text, element ) => {
		window.navigator.clipboard.writeText( text ).then( () => {
			const originalTitle = element.title;
			// Temporarily changes the title, as before.
			element.title = __(
				'Redirect URI has been copied!',
				'product-delivery-date-for-woocommerce-lite'
			);
			element.setAttribute( 'data-tip', element.title );
			element.dispatchEvent( new window.Event( 'mouseenter' ) );
			setTimeout( () => {
				element.title = originalTitle;
			}, 2000 );
		} );
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
				<div
					className="container-fluid pl-info-wrap"
					style={ vShow( '' !== gcalSuccess ) }
				>
					<div className="row">
						<div className="col-md-12">
							<div
								className="alert alert-success alert-dismissible fade show"
								role="alert"
							>
								{ gcalSuccess }
								<button
									type="button"
									className="close"
									data-dismiss="alert"
									aria-label="Close"
									onClick={ () =>
										setNotice( {
											success: true,
											message: '',
										} )
									}
								>
									<span aria-hidden="true">×</span>
								</button>
							</div>
						</div>
					</div>
				</div>
				<div
					className="container-fluid pl-info-wrap"
					style={ vShow( '' !== gcalError ) }
				>
					<div className="row">
						<div className="col-md-12">
							<div
								className="alert alert-danger alert-dismissible fade show"
								role="alert"
							>
								{ gcalError }
								<button
									type="button"
									className="close"
									data-dismiss="alert"
									aria-label="Close"
									onClick={ () =>
										setNotice( {
											success: false,
											message: '',
										} )
									}
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
				<div
					className="prdd_events_loader"
					style={ vShow( loggingOut ) }
				>
					<div className="prdd_events_loader_wrapper">
						{ __(
							'Logging out from Google Calendar…',
							'product-delivery-date-for-woocommerce-lite'
						) }
						<Spinner />
					</div>
				</div>
				<div className="row" style={ vLoading( loading ) }>
					<div className="col-md-12">
						<div className="prdd-page-head phw-btn">
							<div className="float-left prdd-container">
								<h1>
									{ __(
										'Google Calendar Sync',
										'product-delivery-date-for-woocommerce-lite'
									) }
								</h1>
								<p>
									{ __(
										'Stay connected with your store by syncing it with the Google Calendar. Your customers can have their own fair share by adding the delivery info to their own GC.',
										'product-delivery-date-for-woocommerce-lite'
									) }
								</p>
							</div>
							<div className="float-right">
								<button type="button" onClick={ saveSettings }>
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
									headingTag="h2"
									title={ __(
										'General Settings',
										'product-delivery-date-for-woocommerce-lite'
									) }
								>
									<div className="panel-body">
										<div className="tbl-mod-1">
											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Event Location:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Enter the text that will be used as location field in the calendar event. If left empty, website description is sent instead.&#13;&#10;Note: You can use ADDRESS, FULL_ADDRESS and CITY placeholders which will be replaced by their real values.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<input
															className="ib-xl"
															type="text"
															name="ts_calendar_event_location"
															id="ts_calendar_event_location"
															value={
																settings.ts_calendar_event_location ??
																''
															}
															onChange={ change(
																'ts_calendar_event_location'
															) }
														/>
													</div>
												</div>
											</div>

											<div className="tm1-row flx-center">
												<div className="col-left">
													<label>
														{ __(
															'Event Summary(name):',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																"You can use the following placeholders which will be replaced by their real values: SITE_NAME, CLIENT, PRODUCTS, PRODUCT_WITH_QTY, ORDER_DATE_TIME, ORDER_DATE, ORDER_NUMBER, PRICE, PHONE, NOTE, ADDRESS, FULL_ADDRESS , EMAIL (Client's email)",
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<input
															className="ib-xl"
															type="text"
															name="ts_calendar_event_summary"
															id="ts_calendar_event_summary"
															value={
																settings.ts_calendar_event_summary ??
																''
															}
															onChange={ change(
																'ts_calendar_event_summary'
															) }
														/>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Event Description:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap">
														<img
															className="tt-info aw-text"
															src={
																pluginUrl +
																'/assets/images/icon-info.svg'
															}
															alt="Info"
															data-toggle="tooltip"
															data-placement="top"
															title={ __(
																"You can use the following placeholders which will be replaced by their real values: SITE_NAME, CLIENT, PRODUCTS, PRODUCT_WITH_QTY, ORDER_DATE_TIME, ORDER_DATE, ORDER_NUMBER, PRICE, PHONE, NOTE, ADDRESS, FULL_ADDRESS , EMAIL (Client's email)",
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<textarea
															className="ta-sm"
															name="ts_calendar_event_description"
															id="ts_calendar_event_description"
															value={
																settings.ts_calendar_event_description ??
																''
															}
															onChange={ change(
																'ts_calendar_event_description'
															) }
														></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
								</CollapsePanel>

								{ /* Second Panel */ }
								<CollapsePanel
									id="collapseTwo"
									defaultOpen
									headingTag="h2"
									title={ __(
										'Customer Add to Calendar Button Settings',
										'product-delivery-date-for-woocommerce-lite'
									) }
								>
									<div className="panel-body">
										<div className="tbl-mod-1">
											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Show Add to Calendar Button on Order Received Page:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Show Add to Calendar button on the Order Received page for the customers.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_add_to_calendar_order_received_page"
																id="prdd_add_to_calendar_order_received_page"
																checked={
																	'on' ===
																	settings.prdd_add_to_calendar_order_received_page
																}
																onChange={ toggle(
																	'prdd_add_to_calendar_order_received_page'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Show Add to Calendar Button in The Customer Notification Email:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Show Add to Calendar button in the Customer notification email.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_add_to_calendar_customer_email"
																id="prdd_add_to_calendar_customer_email"
																checked={
																	'on' ===
																	settings.prdd_add_to_calendar_customer_email
																}
																onChange={ toggle(
																	'prdd_add_to_calendar_customer_email'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Show Add to Calendar Button on My Account:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Show Add to Calendar button on My account page for the customers.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_add_to_calendar_my_account_page"
																id="prdd_add_to_calendar_my_account_page"
																checked={
																	'on' ===
																	settings.prdd_add_to_calendar_my_account_page
																}
																onChange={ toggle(
																	'prdd_add_to_calendar_my_account_page'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Show Add To Calendar Button on Delivery Calendar Page:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																"Shows the 'Add to Calendar' button on the Order Received page. On clicking the button, an ICS file will be downloaded.",
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_admin_add_to_calendar_delivery_calendar"
																id="prdd_admin_add_to_calendar_delivery_calendar"
																checked={
																	'on' ===
																	settings.prdd_admin_add_to_calendar_delivery_calendar
																}
																onChange={ toggle(
																	'prdd_admin_add_to_calendar_delivery_calendar'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Show Add To Calendar Button in New Order Email Notification:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																"Show 'Add to Calendar' button in the New Order email notification.",
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_admin_add_to_calendar_email_notification"
																id="prdd_admin_add_to_calendar_email_notification"
																checked={
																	'on' ===
																	settings.prdd_admin_add_to_calendar_email_notification
																}
																onChange={ toggle(
																	'prdd_admin_add_to_calendar_email_notification'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>

											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Send Delivery Information As Attachments (ICS Files) In Email Notifications:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Allow customers to export deliveries as ICS file after placing an order. Sends ICS files as attachments in email notifications.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																name="prdd_attachment"
																id="prdd_attachment"
																checked={
																	'on' ===
																	settings.prdd_attachment
																}
																onChange={ toggle(
																	'prdd_attachment'
																) }
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</CollapsePanel>

								{ /* Third Panel */ }
								<CollapsePanel
									id="collapseThree"
									defaultOpen
									headingTag="h2"
									className="google-calendar-sync"
									title={ __(
										'Admin Calendar Sync Settings',
										'product-delivery-date-for-woocommerce-lite'
									) }
								>
									<div className="panel-body">
										<div className="tbl-mod-1">
											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Integration Mode:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center ro-wrap">
														<InfoTip
															text={ __(
																"Select method of integration.&#13;&#10;'Sync Automatically' will add the events to the Google calendar, which is set in the 'Calendar to be used' field, automatically when a customer places an order.&#13;&#10; 'Sync Manually' will add an 'Add to Calendar' button in emails received by admin on New customer order and on the Delivery Calendar page.&#13;&#10;'Disabled' will disable the integration with Google Calendar.",
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<div className="rb-flx-style">
															<div className="el-radio el-radio-green">
																<input
																	type="radio"
																	id="7_0"
																	checked={
																		'oauth' ===
																		settings.ts_calendar_sync_integration_mode
																	}
																	onChange={ () =>
																		update(
																			'ts_calendar_sync_integration_mode',
																			'oauth'
																		)
																	}
																	value="oauth"
																/>
																<label
																	className="el-radio-style"
																	htmlFor="7_0"
																></label>
															</div>
															<label htmlFor="7_0">
																{ __(
																	'OAuth Sync (Recommended)',
																	'product-delivery-date-for-woocommerce-lite'
																) }
															</label>
														</div>
														<div className="rb-flx-style">
															<div className="el-radio el-radio-green">
																<input
																	type="radio"
																	id="7_1"
																	checked={
																		'directly' ===
																		settings.ts_calendar_sync_integration_mode
																	}
																	onChange={ () =>
																		update(
																			'ts_calendar_sync_integration_mode',
																			'directly'
																		)
																	}
																	value="directly"
																/>
																<label
																	className="el-radio-style"
																	htmlFor="7_1"
																></label>
															</div>
															<label htmlFor="7_1">
																{ __(
																	'Service Account Sync',
																	'product-delivery-date-for-woocommerce-lite'
																) }
															</label>
														</div>
														<div className="rb-flx-style">
															<div className="el-radio el-radio-green">
																<input
																	type="radio"
																	id="7_2"
																	checked={
																		'manually' ===
																		settings.ts_calendar_sync_integration_mode
																	}
																	onChange={ () =>
																		update(
																			'ts_calendar_sync_integration_mode',
																			'manually'
																		)
																	}
																	value="manually"
																/>
																<label
																	className="el-radio-style"
																	htmlFor="7_2"
																></label>
															</div>
															<label htmlFor="7_2">
																{ __(
																	'Sync Manually',
																	'product-delivery-date-for-woocommerce-lite'
																) }
															</label>
														</div>
														<div className="rb-flx-style">
															<div className="el-radio el-radio-green">
																<input
																	type="radio"
																	id="7_3"
																	checked={
																		'disabled' ===
																		settings.ts_calendar_sync_integration_mode
																	}
																	onChange={ () =>
																		update(
																			'ts_calendar_sync_integration_mode',
																			'disabled'
																		)
																	}
																	value="disabled"
																/>
																<label
																	className="el-radio-style"
																	htmlFor="7_3"
																></label>
															</div>
															<label htmlFor="7_3">
																{ __(
																	'Disabled',
																	'product-delivery-date-for-woocommerce-lite'
																) }
															</label>
														</div>
													</div>
												</div>
											</div>

											<div
												className="rb_opt_val_3"
												style={ vShow(
													'directly' ===
														settings.ts_calendar_sync_integration_mode
												) }
											>
												<div className="tm1-row">
													<div className="col-full">
														<label>
															{ __(
																'Instructions:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>{ ' ' }
														{ __(
															'To set up Google Calendar API, please click on "Show me how" link and carefully follow these steps:',
															'product-delivery-date-for-woocommerce-lite'
														) }{ ' ' }
														<a
															className="link-wul"
															href="#!"
															onClick={
																showInstructions
															}
														>
															{ ' ' }
															{ __(
																'Show me how',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</a>
													</div>
												</div>
												<div
													className="description ts-info_target api-instructions"
													style={ vShow( showInfo ) }
												>
													<ul
														style={ {
															listStyleType:
																'decimal',
														} }
													>
														<li>
															{ __(
																'Google Calendar API requires php V5.3+ and some php extensions.',
																'product-delivery-date-for-woocommerce-lite'
															) }{ ' ' }
														</li>
														<li
															dangerouslySetInnerHTML={ {
																__html: sprintf(
																	/* translators: %s: Link to the Google APIs console. */
																	__(
																		'Go to Google APIs console by clicking %s. Login to your Google account if you are not already logged in.',
																		'product-delivery-date-for-woocommerce-lite'
																	),
																	'<a href="https://code.google.com/apis/console/" target="_blank">https://code.google.com/apis/console/</a>'
																),
															} }
														/>
														<li>
															{ __(
																'Create a new project using the left side pane. Click on \'Home\' option. Name the project "Deliveries" (or use your chosen name instead).',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Click on API Manager from left side pane.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Click "Calendar API" under Google Apps APIs and Enable the API.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Go to "Credentials" menu in the left side pane and click on "New Credentials" dropdown.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Click on "OAuth client ID" option. Then click on Configure consent screen.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Enter a Product Name, e.g. Product Delivery Date, inside the opening pop-up. Click Save.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Select "Web Application" option, enter the Web client name and create the client ID.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Click on New Credentials dropdown and select "Service account key".',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Click "Service account" and select "New service account" and enter the name. Now select key type as "P12" and create the service account.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'A file with extension .p12 will be downloaded.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														{ /* translators: %s: Key file folder. */ }
														<li>
															{ sprintf(
																/* translators: %s: Key file folder. */
																__(
																	'Using your FTP client program, copy this key file to folder: %s . This file is required as you will grant access to your Google Calendar account even if you are not online. So this file serves as a proof of your consent to access to your Google calendar account. Note: This file cannot be uploaded in any other way. If you do not have FTP access, ask the website admin to do it for you.',
																	'product-delivery-date-for-woocommerce-lite'
																),
																settings.key_file_dir ||
																	''
															) }
														</li>
														<li>
															{ __(
																'Enter the name of the key file to "Key file name" setting of Order Delivery Date. Exclude the extention .p12.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Copy "Email address" setting from Manage service account of Google apis console and paste it to "Service account email address" setting of Product Delivery Date.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li
															dangerouslySetInnerHTML={ {
																__html: sprintf(
																	/* translators: %s: Link to the Google Calendar. */
																	__(
																		'Open your Google Calendar by clicking this link: %s',
																		'product-delivery-date-for-woocommerce-lite'
																	),
																	'<a href="https://www.google.com/calendar/render" target="_blank">https://www.google.com/calendar/render</a>'
																),
															} }
														/>
														<li
															dangerouslySetInnerHTML={ {
																__html: __(
																	'Create a new Calendar by selecting "my Calendars > Create new calendar" on left side pane. <b>Try NOT to use your primary calendar.</b>',
																	'product-delivery-date-for-woocommerce-lite'
																),
															} }
														/>
														<li
															dangerouslySetInnerHTML={ {
																__html: __(
																	'Give a name to the new calendar, e.g. Product Delivery Date calendar. <b>Check that Calendar Time Zone setting matches with time zone setting of your WordPress website.</b> Otherwise there will be a time shift.',
																	'product-delivery-date-for-woocommerce-lite'
																),
															} }
														/>
														<li>
															{ __(
																'Paste already copied "Email address" setting from Manage service account of Google apis console to "Person" field under "Share with specific person".',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Set "Permission Settings" of this person as "Make changes to events".',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Click "Add Person".',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Click "Create Calendar".',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Select the created calendar and click "Calendar settings".',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Copy "Calendar ID" value on Calendar Address row.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Paste this value to "Calendar to be used" field.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'Click "Save Settings".',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'After these stages, you have set up Google Calendar API. To test the connection, click the "Test Connection" link.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li>
															{ __(
																'If you get a success message, you should see a test event inserted to the Google Calendar and you are ready to go. If you get an error message, double check your settings.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
													</ul>
												</div>

												<div className="tm1-row flx-center">
													<div className="col-left">
														<label>
															{ __(
																'Key File Name:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="rc-flx-wrap flx-aln-center">
															<InfoTip
																text={ __(
																	'Enter key file name here without extention, e.g. ab12345678901234567890-privatekey.',
																	'product-delivery-date-for-woocommerce-lite'
																) }
															/>
															<input
																className="ib-xl"
																type="text"
																name="ts_calendar_key_file_name"
																id="ts_calendar_key_file_name"
																value={
																	settings.ts_calendar_key_file_name ??
																	''
																}
																onChange={ change(
																	'ts_calendar_key_file_name'
																) }
															/>
														</div>
													</div>
												</div>

												<div className="tm1-row flx-center">
													<div className="col-left">
														<label>
															{ __(
																'Service Account Email Address:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="rc-flx-wrap flx-aln-center">
															<InfoTip
																text={ __(
																	'Enter Service account email address here, e.g. 1234567890@developer.gserviceaccount.com.',
																	'product-delivery-date-for-woocommerce-lite'
																) }
															/>
															<input
																className="ib-xl"
																type="text"
																name="ts_calendar_service_acc_email_address"
																id="ts_calendar_service_acc_email_address"
																value={
																	settings.ts_calendar_service_acc_email_address ??
																	''
																}
																onChange={ change(
																	'ts_calendar_service_acc_email_address'
																) }
															/>
														</div>
													</div>
												</div>

												<div className="tm1-row">
													<div className="col-left">
														<label>
															{ __(
																'Calendar to Be Used',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="row-box-1">
															<div className="rb1-left">
																<InfoTip
																	text={ __(
																		'Enter the ID of the calendar in which your deliveries will be saved, e.g. abcdefg1234567890@group.calendar.google.com.',
																		'product-delivery-date-for-woocommerce-lite'
																	) }
																/>
															</div>
															<div className="rb1-right">
																<div className="rb1-row">
																	<input
																		className="ib-xl"
																		type="text"
																		name="ts_calendar_id"
																		id="ts_calendar_id"
																		value={
																			settings.ts_calendar_id ??
																			''
																		}
																		onChange={ change(
																			'ts_calendar_id'
																		) }
																	/>
																</div>
																<div className="rb1-row flx-center">
																	<a
																		className="link-wul"
																		onClick={
																			testConnection
																		}
																		href="#!"
																	>
																		{ __(
																			'Test Connection',
																			'product-delivery-date-for-woocommerce-lite'
																		) }{ ' ' }
																		<img
																			className="tt-info"
																			src={
																				pluginUrl +
																				'/assets/images/loader.png'
																			}
																			alt="Loader"
																			style={ vShow(
																				testing
																			) }
																		/>
																	</a>
																</div>
																<div className="rb1-row">
																	<span
																		className={
																			'response_message_text ' +
																			( testResult
																				? testResult.className
																				: 'error-msg' )
																		}
																		dangerouslySetInnerHTML={ {
																			__html: testResult
																				? testResult.message
																				: '',
																		} }
																	/>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											{ /* OAuth Sync Settings */ }

											<div
												className="rb_opt_val_3"
												style={ vShow(
													'oauth' ===
														settings.ts_calendar_sync_integration_mode
												) }
											>
												<div className="tm1-row">
													<div className="col-full">
														<label>
															{ __(
																'Instructions:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>{ ' ' }
														{ __(
															'To set up Google Calendar API, please click on "Show me how" link and carefully follow these steps:',
															'product-delivery-date-for-woocommerce-lite'
														) }{ ' ' }
														<a
															className="link-wul"
															href="#!"
															onClick={
																showInstructions
															}
														>
															{ ' ' }
															{ __(
																'Show me how',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</a>
													</div>
												</div>
												<div
													className="description ts-info_target api-instructions"
													style={ vShow( showInfo ) }
												>
													<ol>
														<li
															dangerouslySetInnerHTML={ {
																__html: sprintf(
																	/* translators: %s: Link to the Google API Console. */
																	__(
																		'Go to the <b>%s</b> and select a project, or create a new one. Login to your Google account if you are not already logged in.',
																		'product-delivery-date-for-woocommerce-lite'
																	),
																	'<a href="https://code.google.com/apis/console/" target="_blank">Google Developers Console</a>'
																),
															} }
														/>
														<li>
															{ __(
																"If creating a new project, give the Project name e.g 'My Delivery Project' and click on the Create button.",
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li
															dangerouslySetInnerHTML={ {
																__html: __(
																	'Once the project is created, the Calendar API needs to be enabled. To do so, click on <b>ENABLE API AND SERVICES</b> link and search for <b>Google Calendar API</b>, and enable it by clicking the ENABLE button.',
																	'product-delivery-date-for-woocommerce-lite'
																),
															} }
														/>
														<li
															dangerouslySetInnerHTML={ {
																__html: __(
																	"On the left, click <b>Credentials</b>. If this is your first time creating a client ID, you'll be prompted to configure the consent screen. Click on <b>Configure Consent Screen</b>.",
																	'product-delivery-date-for-woocommerce-lite'
																),
															} }
														/>
														<li
															dangerouslySetInnerHTML={ {
																__html: __(
																	'Go to the <b>OAuth consent screen</b>. Select User Type as <b>Internal</b> and click on the CREATE button. After that, set the <b>Application name</b> and click on the Create button.',
																	'product-delivery-date-for-woocommerce-lite'
																),
															} }
														/>
														<li
															dangerouslySetInnerHTML={ {
																__html: __(
																	'Go back to the <b>Credentials</b> tab, click <b>Create credentials</b>, then select <b>OAuth client ID</b>.',
																	'product-delivery-date-for-woocommerce-lite'
																),
															} }
														/>
														<li
															dangerouslySetInnerHTML={ {
																__html: __(
																	"Select <b>Web application</b> under Application type and provide the necessary information to create your project's credentials.",
																	'product-delivery-date-for-woocommerce-lite'
																),
															} }
														/>
														<li
															dangerouslySetInnerHTML={ {
																__html: __(
																	'For <b>Authorized redirect URIs</b> enter the Redirect URI (Can be found in Product Delivery Date menu > Settings > Integrations). Then click Create button.',
																	'product-delivery-date-for-woocommerce-lite'
																),
															} }
														/>
														<li
															dangerouslySetInnerHTML={ {
																__html: __(
																	"On the dialog that appears, you'll see your <b>Client ID</b> and <b>Client Secret</b>. Fill the details in below fields and click on Save Settings.",
																	'product-delivery-date-for-woocommerce-lite'
																),
															} }
														/>
														<li>
															{ __(
																'Once saved, Connect to Google Calendar button will appear. Click on it to get connected to the Google Calendar.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</li>
														<li
															dangerouslySetInnerHTML={ {
																__html: __(
																	'Once the Successful Connection to Google, <b>Calendar to be used</b> option will appear. Here select the calendar to which the event should get created for the delivery.',
																	'product-delivery-date-for-woocommerce-lite'
																),
															} }
														/>
													</ol>
												</div>

												<div className="tm1-row flx-center">
													<div className="col-left">
														<label>
															{ __(
																'Client ID:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="rc-flx-wrap flx-aln-center">
															<input
																className="ib-xl"
																type="text"
																name="ts_gcal_client_id"
																id="ts_gcal_client_id"
																value={
																	settings.ts_gcal_client_id ??
																	''
																}
																onChange={ change(
																	'ts_gcal_client_id'
																) }
																readOnly={
																	'' !==
																	( settings.logout_url ??
																		'' )
																}
															/>
														</div>
													</div>
												</div>

												<div className="tm1-row flx-center">
													<div className="col-left">
														<label>
															{ __(
																'Client Secret:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="rc-flx-wrap flx-aln-center">
															<input
																className="ib-xl"
																type="text"
																name="ts_gcal_client_secret"
																id="ts_gcal_client_secret"
																value={
																	settings.ts_gcal_client_secret ??
																	''
																}
																onChange={ change(
																	'ts_gcal_client_secret'
																) }
																readOnly={
																	'' !==
																	( settings.logout_url ??
																		'' )
																}
															/>
														</div>
													</div>
												</div>

												<div className="tm1-row">
													<div className="col-left">
														<label>
															{ __(
																'Redirect URI',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="row-box-1">
															<div className="rb1-right">
																<div className="rb1-row">
																	<input
																		className="ib-xl"
																		type="text"
																		name="redirectUri"
																		id="redirectUri"
																		value={
																			settings.redirect_uri ??
																			''
																		}
																		readOnly="readOnly"
																	/>
																	<a
																		href="#!"
																		title="Copy Redirect URI"
																		onClick={ (
																			e
																		) => {
																			// "#!" would change the hash route.
																			e.preventDefault();
																			copyToClipboard(
																				settings.redirect_uri,
																				e.currentTarget
																			);
																		} }
																		data-tip={ __(
																			'Redirect URI has been copied!',
																			'product-delivery-date-for-woocommerce-lite'
																		) }
																		className="dashicons dashicons-admin-page copy-to-clipboard"
																	>
																		{
																			'\u00a0'
																		}
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div
												className="tm1-row"
												style={ vShow(
													'oauth' ===
														settings.ts_calendar_sync_integration_mode &&
														Object.keys(
															settings.calendars ||
																{}
														).length > 1 &&
														'' !==
															settings.logout_url
												) }
											>
												<div className="col-left">
													<label>
														{ __(
															'Select Calendar',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap flx-aln-center">
														<img
															className="tt-info"
															src={
																pluginUrl +
																'/assets/images/icon-info.svg'
															}
															alt="Tooltip"
															data-toggle="tooltip"
															data-placement="top"
															title={ __(
																'The event for the delivery will be created in the Google Calendar selected here.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<select
															className="ib-md"
															value={
																settings.ts_gcal_calendar_id ??
																''
															}
															onChange={ change(
																'ts_gcal_calendar_id'
															) }
														>
															{ Object.keys(
																settings.calendars ||
																	{}
															).map( ( key ) => (
																<option
																	key={ key }
																	value={
																		key
																	}
																>
																	{
																		settings
																			.calendars[
																			key
																		]
																	}
																</option>
															) ) }
														</select>
													</div>
												</div>
											</div>

											<div
												className="tm1-row"
												style={ vShow(
													'' !==
														settings.connect_link &&
														'' !==
															settings.ts_gcal_client_secret &&
														'' !==
															settings.ts_gcal_client_id &&
														'oauth' ===
															settings.ts_calendar_sync_integration_mode &&
														'' !==
															settings.redirect_uri &&
														'' ===
															settings.logout_url
												) }
											>
												<div className="col-left"></div>
												<div className="col-right">
													<a
														href={
															settings.connect_link
														}
														className="secondary-btn pl-ss-btn"
													>
														{ __(
															'Connect to Google Calendar',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</a>
												</div>
											</div>

											<div
												className="tm1-row"
												style={ vShow(
													'oauth' ===
														settings.ts_calendar_sync_integration_mode &&
														'' !==
															settings.logout_url &&
														Object.keys(
															settings.calendars ||
																{}
														).length > 1
												) }
											>
												<div className="col-left"></div>
												<div className="col-right">
													<a
														href="#!"
														onClick={ logout }
														className="secondary-btn pl-ss-btn"
													>
														{ __(
															'Logout from Google Calendar',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</a>
												</div>
											</div>

											<div
												className="rb_opt_val_3"
												style={ vShow(
													'manually' ===
														settings.ts_calendar_sync_integration_mode
												) }
											>
												<div className="tm1-row">
													<div className="col-full">
														{ __(
															'It will add an "Add to Calendar" button in emails received by admin on New customer order and on the Delivery Calendar page.',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</div>
												</div>
											</div>
										</div>
									</div>
								</CollapsePanel>
							</div>
						</div>

						<div className="ss-foot">
							<button type="button" onClick={ saveSettings }>
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
