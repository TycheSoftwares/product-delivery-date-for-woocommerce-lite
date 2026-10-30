/**
 * View Deliveries screen, markup of the Pro screen.
 *
 * The list is rendered by the PRDD_Lite_View_Deliveries_Table list table
 * through the prdd-lite/admin/v1/view-deliveries endpoint. Its links ( views,
 * pagination, sorting ) update the query of the route instead of reloading
 * the page. The filters, Order View and exports are Pro features and are shown
 * disabled.
 */
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';

import config from '../../../shared/config';
import { apiGet, errorMessage } from '../../../shared/api';
import DatePicker from '../../../shared/components/DatePicker';
import ProNotice, { ProOnly } from '../../../shared/components/ProNotice';
import SavingLoader from '../../../shared/components/SavingLoader';
import DeliveriesCalendar from './DeliveriesCalendar';

const settings = config.view_deliveries || {};
const PAGE = 'woocommerce_prdd_lite_page';
const ROUTE = '/view-deliveries';

/**
 * Query args of the page, the ones the list table reads.
 */
const KEYS = [ 'prdd_view', 'status', 'paged', 'orderby', 'order' ];

/**
 * Known, non empty query args of a URLSearchParams / FormData.
 *
 * @param {Object} source Object with a get() method.
 * @return {Object} Query.
 */
function pickQuery( source ) {
	const query = {};
	KEYS.forEach( ( key ) => {
		const value = source.get( key );
		if ( value ) {
			query[ key ] = String( value );
		}
	} );
	return query;
}

/**
 * Route location of a query.
 *
 * @param {Object} query Query args.
 * @return {Object} Location.
 */
function locationOf( query ) {
	const search = new URLSearchParams( query ).toString();
	return { pathname: ROUTE, search: search ? '?' + search : '' };
}

export default function ViewDeliveries() {
	const [ searchParams ] = useSearchParams();
	const navigate = useNavigate();
	const query = pickQuery( searchParams );
	const queryKey = new URLSearchParams( query ).toString();
	const view = query.prdd_view || '';
	const isCalendar = 'prdd_calender' === view;

	const [ result, setResult ] = useState( null );
	const [ loading, setLoading ] = useState( false );
	const [ error, setError ] = useState( '' );
	const [ startDate, setStartDate ] = useState( '' );
	const [ endDate, setEndDate ] = useState( '' );
	const [ orderStatus, setOrderStatus ] = useState( '' );

	useEffect( () => {
		if ( isCalendar ) {
			setResult( null );
			return;
		}
		let active = true;
		setLoading( true );
		setError( '' );
		apiGet( 'view-deliveries' + ( queryKey ? '?' + queryKey : '' ) )
			.then( ( response ) => {
				if ( active ) {
					setResult( response.data || {} );
				}
			} )
			.catch( ( e ) => active && setError( errorMessage( e ) ) )
			.finally( () => active && setLoading( false ) );

		return () => {
			active = false;
		};
	}, [ queryKey ] ); // eslint-disable-line react-hooks/exhaustive-deps

	const go = ( next ) => navigate( locationOf( next ) );

	// Same request the GET form sent: the filters, the current page field of the table and the view.
	const onSubmit = ( event ) => {
		event.preventDefault();
		go( pickQuery( new FormData( event.currentTarget ) ) );
	};

	// Views, pagination and sorting links of the list table.
	const onTableClick = ( event ) => {
		const link = event.target.closest( 'a' );
		if (
			! link ||
			event.defaultPrevented ||
			0 !== event.button ||
			event.metaKey ||
			event.ctrlKey ||
			event.shiftKey ||
			event.altKey ||
			'_blank' === link.getAttribute( 'target' )
		) {
			return;
		}
		let url;
		try {
			url = new URL(
				link.getAttribute( 'href' ) || '',
				window.location.href
			);
		} catch ( e ) {
			return;
		}
		if (
			PAGE !== url.searchParams.get( 'page' ) ||
			'view-deliveries' !== url.searchParams.get( 'action' )
		) {
			return;
		}
		event.preventDefault();
		go( pickQuery( url.searchParams ) );
	};

	const calendarToggle = isCalendar
		? locationOf( {} )
		: locationOf( { ...query, prdd_view: 'prdd_calender' } );

	return (
		<div className="prdd-area prdd-page-head prdd-content-area">
			<div className="container-fluid bd-page-wrap as-page">
				<div className="row">
					<div className="col-md-12">
						<div className="phw-btn mb-3 d-flex">
							<h1 className="mr-3">
								{ __(
									'All Deliveries',
									'product-delivery-date-for-woocommerce-lite'
								) }
							</h1>
						</div>
					</div>
					<div className="col-md-12">
						<form
							id="prdd-view-deliveries"
							method="get"
							className="row"
							onSubmit={ onSubmit }
						>
							<div className="col-md-12">
								<div className="tm1-row bdr-0 pt-0 delvry-sch-bottom mb-4">
									<div className="abulk-box pt-0 ">
										<Link
											to={ calendarToggle }
											className="trietary-btn reverse"
										>
											{ isCalendar
												? __(
														'Product View',
														'product-delivery-date-for-woocommerce-lite'
												  )
												: __(
														'Calendar View',
														'product-delivery-date-for-woocommerce-lite'
												  ) }
										</Link>{ ' ' }
										<span
											className="trietary-btn reverse prdd-lite-disabled-btn"
											aria-disabled="true"
										>
											{ __(
												'Order View',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</span>
									</div>
									<div className="action-url">
										{ ! isCalendar && (
											<>
												<span
													className="trietary-btn reverse prdd-lite-disabled-btn"
													aria-disabled="true"
												>
													<i className="dashicons dashicons-media-text"></i>{ ' ' }
													{ __(
														'CSV',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</span>{ ' ' }
												<span
													className="trietary-btn reverse prdd-lite-disabled-btn mr-1"
													aria-disabled="true"
												>
													<i className="dashicons dashicons-printer"></i>{ ' ' }
													{ __(
														'Print',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</span>
											</>
										) }
										<ProNotice />
									</div>
								</div>
							</div>
							{ ! isCalendar && (
								<div className="col-md-12 prdd-lite-filters">
									<ProOnly>
										<div className="rc-flx-wrap d-flex align-items-end flx-aln-center mb-4">
											<div className="select-box mr-3">
												<label htmlFor="prdd_delivery_datefrom_cal">
													{ __(
														'Start Date:',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</label>
												<DatePicker
													id="prdd_delivery_datefrom_cal"
													name="start-date"
													className="prdd_delivery_date_filter"
													value={ startDate }
													onChange={ setStartDate }
													onSelect={ ( selected ) => {
														jQuery(
															'#prdd_delivery_dateto_cal'
														).datepicker(
															'option',
															'minDate',
															new Date( selected )
														);
														setEndDate(
															( current ) =>
																'' === current
																	? selected
																	: current
														);
													} }
													options={ {
														dateFormat: 'yy-mm-dd',
													} }
												/>
											</div>
											<div className="select-box mr-3">
												<label htmlFor="prdd_delivery_dateto_cal">
													{ __(
														'End Date:',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</label>
												<DatePicker
													id="prdd_delivery_dateto_cal"
													name="end-date"
													className="prdd_delivery_date_filter"
													value={ endDate }
													onChange={ setEndDate }
													onSelect={ ( selected ) =>
														setStartDate(
															( current ) =>
																'' === current
																	? selected
																	: current
														)
													}
													options={ {
														dateFormat: 'yy-mm-dd',
													} }
												/>
											</div>
											<div className="select-box mr-3">
												<label htmlFor="prdd_delivery_status_filter">
													{ __(
														'Order Status:',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</label>
												<select
													id="prdd_delivery_status_filter"
													name="order-status"
													value={ orderStatus }
													onChange={ ( event ) =>
														setOrderStatus(
															event.target.value
														)
													}
												>
													<option value="">
														{ __(
															'All Statuses',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</option>
													{ (
														settings.order_statuses ||
														[]
													).map( ( status ) => (
														<option
															key={ status.value }
															value={
																status.value
															}
														>
															{ status.label }
														</option>
													) ) }
												</select>
											</div>
											<div className="select-box mr-3">
												<button
													className="secondary-btn"
													type="submit"
												>
													{ __(
														'Apply filters',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</button>
											</div>
										</div>
									</ProOnly>
								</div>
							) }
							<div className="col-md-12 deliveries-page-wrapper">
								<div className="wbc-box">
									<div className="tm2-inner-wrap">
										<div className="booking-table">
											{ isCalendar && (
												<DeliveriesCalendar />
											) }
											{ ! isCalendar && error && (
												<div className="notice notice-error">
													<p>{ error }</p>
												</div>
											) }
											{ ! isCalendar && result && (
												// The list table markup is generated and escaped by WP_List_Table.
												// eslint-disable-next-line jsx-a11y/click-events-have-key-events, jsx-a11y/no-static-element-interactions
												<div
													onClick={ onTableClick }
													dangerouslySetInnerHTML={ {
														__html: result.html,
													} }
												/>
											) }
										</div>
									</div>
								</div>
							</div>
							{ view && (
								<input
									type="hidden"
									name="prdd_view"
									value={ view }
								/>
							) }
						</form>
					</div>
				</div>
			</div>
			<SavingLoader
				show={ loading }
				text={ __(
					'Loading…',
					'product-delivery-date-for-woocommerce-lite'
				) }
			/>
		</div>
	);
}
