/**
 * Holidays tab, markup of the former includes/admin/views/settings/holidays.php.
 */
import { __, sprintf } from '@wordpress/i18n';
import { useRef, useState } from '@wordpress/element';
import CollapsePanel from '../../../shared/components/CollapsePanel';
import InfoTip from '../../../shared/components/InfoTip';
import ErrorMessage from '../../../shared/components/ErrorMessage';
import MultiDatesPicker from '../../../shared/components/MultiDatesPicker';
import useSettingsForm, { vLoading, vShow } from '../../hooks/useSettingsForm';
import Spinner from '../../../shared/components/Spinner';

export default function Holidays() {
	const {
		settings,
		loaded,
		loading,
		saving,
		savedMessage,
		setSavedMessage,
		error,
		setError,
		save,
		setSettings,
	} = useSettingsForm( 'settings/holidays' );
	const textarea = useRef( null );
	// Dates the last save dropped as not valid.
	const [ invalidDates, setInvalidDates ] = useState( [] );
	// Remounts the textarea and the calendar with the saved dates.
	const [ revision, setRevision ] = useState( 0 );

	const holidays =
		'string' === typeof settings.prdd_global_holidays &&
		'' !== settings.prdd_global_holidays
			? settings.prdd_global_holidays.split( ',' )
			: [];

	// The textarea is the altField of the calendar and can be edited by hand, as before.
	const saveHolidays = () => {
		setInvalidDates( [] );
		return save( {
			prdd_global_holidays: textarea.current
				? textarea.current.value
				: '',
		} ).then( ( response ) => {
			const data = ( response && response.data ) || {};
			if ( 'string' !== typeof data.prdd_global_holidays ) {
				return;
			}
			setInvalidDates( data.invalid_dates || [] );
			setSettings( { prdd_global_holidays: data.prdd_global_holidays } );
			setRevision( ( current ) => current + 1 );
		} );
	};

	const dismissMessage = () => {
		setSavedMessage( false );
		setInvalidDates( [] );
	};

	return (
		<div className="container holidays-page-wrap">
			<div
				className="container-fluid pl-info-wrap"
				id="save_message"
				style={ vShow( savedMessage ) }
			>
				<div className="row">
					<div className="col-md-12">
						<div
							className={ `alert ${
								invalidDates.length > 0
									? 'alert-warning'
									: 'alert-success'
							} alert-dismissible fade show` }
							role="alert"
						>
							{ invalidDates.length > 0
								? sprintf(
										/* translators: %s: Comma separated dates which were not saved. */
										__(
											'Settings Saved. These dates are not valid and were removed: %s',
											'product-delivery-date-for-woocommerce-lite'
										),
										invalidDates.join( ', ' )
								  )
								: __(
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
								onClick={ dismissMessage }
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
					<div className="prdd-page-head">
						<h1>
							{ __(
								'Holidays',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</h1>
						<p>
							{ __(
								'If you want to add holiday for all products then you can add them from here. You have to select dates for holidays and those will not available to select for all products.',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</p>
					</div>
				</div>

				<div className="col-md-12">
					<div className="holiday-content-area">
						<div
							className="panel-group prdd-accordian"
							id="wbc-accordion"
						>
							<CollapsePanel
								id="collapseSix"
								defaultOpen
								title={ __(
									'Holidays',
									'product-delivery-date-for-woocommerce-lite'
								) }
								after={
									<div className="ss-foot">
										<button
											type="button"
											onClick={ saveHolidays }
											disabled={ ! loaded || saving }
										>
											{ __(
												'Save Settings',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</button>
									</div>
								}
							>
								<div className="panel-body">
									<div className="tbl-mod-1">
										<div className="tm1-row">
											<div className="col-left">
												<label>
													{ __(
														'No delivery on these dates:',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</label>
											</div>
											<div className="col-right">
												<div className="row-box-1">
													<div className="rb1-left">
														<InfoTip
															text={ __(
																'Select dates for which the delivery will be completely disabled for all the products in your WooCommerce store.&#13;&#10; The dates selected here will be unavailable for all products. Please click on the date in calendar to add or delete the date from the list.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
													</div>
													<div className="col-right">
														<textarea
															className="ta-sm"
															id="prdd_global_holidays"
															ref={ textarea }
															defaultValue={ holidays.join(
																','
															) }
															key={
																loaded
																	? 'loaded-' +
																	  revision
																	: 'loading'
															}
														></textarea>
														{ loaded && (
															<MultiDatesPicker
																key={ revision }
																type="text"
																id="multidatepicker"
																value={
																	holidays
																}
																options={ {
																	altField:
																		'#prdd_global_holidays',
																} }
															/>
														) }
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</CollapsePanel>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}
