/**
 * Delivery settings form shared by the product page metabox and the Bulk
 * Product Settings page, the former prdd-delivery-settings-meta-box.php.
 */
import { __ } from '@wordpress/i18n';
import { applyFilters } from '@wordpress/hooks';
import { useEffect, useMemo, useState } from '@wordpress/element';
import { apiPost, apiPostBatches, errorMessage } from '../shared/api';
import addDatepickerClearButton from '../shared/datepickerClear';
import {
	DeliveryOptionsIcon,
	CalendarIcon,
	ClockIcon,
	CardIcon,
	GearIcon,
} from './components/TabIcons';
import DeliveryOptionsTab, { NEW_SLOT } from './tabs/DeliveryOptionsTab';
import SettingsTab from './tabs/SettingsTab';
import TimePeriodTab from './tabs/TimePeriodTab';
import ManageAvailabilityTab from './tabs/ManageAvailabilityTab';
import ManageAvailabilityBulkTab from './tabs/ManageAvailabilityBulkTab';
import DeliveryChargesTab from './tabs/DeliveryChargesTab';
import LockedScreen from '../shared/components/LockedScreen';

/**
 * Tab of the Pro version, shown as a preview under the upgrade modal.
 *
 * @param {Function} Component Tab component.
 * @return {Function} Locked tab component.
 */
const locked = ( Component ) =>
	function LockedTab( props ) {
		return (
			<LockedScreen className="prdd-lite-locked-tab">
				<Component { ...props } />
			</LockedScreen>
		);
	};

const LockedManageAvailabilityTab = locked( ManageAvailabilityTab );
const LockedManageAvailabilityBulkTab = locked( ManageAvailabilityBulkTab );
const LockedDeliveryChargesTab = locked( DeliveryChargesTab );

const DELETE_ALL_CONFIRM = {
	specific: __(
		'Are you sure you want to delete all the specific date records?',
		'product-delivery-date-for-woocommerce-lite'
	),
	specific_timeslots: __(
		'Are you sure you want to delete all the specific date records?',
		'product-delivery-date-for-woocommerce-lite'
	),
	recurring: __(
		'Are you sure you want to delete all the recurring weekday records?',
		'product-delivery-date-for-woocommerce-lite'
	),
	recurring_timeslots: __(
		'Are you sure you want to delete all the recurring weekday records?',
		'product-delivery-date-for-woocommerce-lite'
	),
};

const confirm = ( message ) => window.confirm( message ); // eslint-disable-line no-alert
const alert = ( message ) => window.alert( message ); // eslint-disable-line no-alert

/**
 * Splits a comma separated list of dates.
 *
 * @param {string} value Dates.
 * @return {string[]} Dates.
 */
const splitDates = ( value ) =>
	( value || '' )
		.split( ',' )
		.map( ( date ) => date.trim() )
		.filter( Boolean );

/**
 * Lockouts of the bulk page as { specific_dates: {}, weekdays: {} }.
 *
 * @return {Object} Lockouts.
 */
const emptyLockouts = () => ( { specific_dates: {}, weekdays: {} } );

/**
 * @param {Object}   props
 * @param {Object}   props.initialData   Data of PRDD_Lite_Product_Metabox_React::get_form_data().
 * @param {boolean}  props.isBulk        Whether the form is on the Bulk Product Settings page.
 * @param {Object}   props.bulkSelection { products, categories, override } of the bulk page.
 * @param {Function} props.onChange      Receives the payload to save on every change.
 */
export default function DeliverySettingsForm( {
	initialData,
	isBulk = false,
	bulkSelection = {},
	onChange,
} ) {
	const [ data, setData ] = useState( initialData );
	const [ values, setValues ] = useState( initialData.values || {} );
	const [ newSlots, setNewSlots ] = useState( [ { ...NEW_SLOT } ] );
	const [ specialPrices, setSpecialPrices ] = useState(
		initialData.special_prices || []
	);
	const [ bulkLockouts, setBulkLockouts ] = useState( emptyLockouts );
	const [ extra, setExtra ] = useState( {} );
	const [ activeTab, setActiveTab ] = useState( 'delivery-option' );
	const [ pickerKey, setPickerKey ] = useState( 0 );

	useEffect( () => {
		addDatepickerClearButton();
	}, [] );

	useEffect( () => {
		if ( onChange ) {
			onChange( {
				...values,
				new_timeslots: newSlots,
				...( isBulk ? { delivery_date_lockout: bulkLockouts } : {} ),
				extra,
			} );
		}
	}, [ values, newSlots, bulkLockouts, extra ] ); // eslint-disable-line react-hooks/exhaustive-deps

	const set = ( key, value ) =>
		setValues( ( current ) => ( { ...current, [ key ]: value } ) );
	const specificDates = useMemo(
		() => splitDates( values.prdd_specific_date ),
		[ values.prdd_specific_date ]
	);
	const remountPicker = () => setPickerKey( ( key ) => key + 1 );

	/**
	 * Calls a products/{id} route of the REST API. The routes editing the
	 * saved dates and time slots answer with the refreshed form data.
	 *
	 * @param {string}   path      Route relative to products/{id}.
	 * @param {Object}   body      Body.
	 * @param {Function} onSuccess Receives the response data, defaults to refreshing the lists.
	 * @param {string}   failure   Message shown when the request fails.
	 * @return {Promise} Request.
	 */
	const request = ( path, body, onSuccess, failure ) =>
		apiPost( `products/${ data.post_id }/${ path }`, body )
			.then( ( response ) => {
				if ( onSuccess ) {
					onSuccess( response.data );
					return true;
				}
				setData( ( current ) => ( { ...current, ...response.data } ) );
				return true;
			} )
			.catch( ( error ) => {
				alert(
					failure ||
						errorMessage( error ) ||
						__(
							'An error has occurred.',
							'product-delivery-date-for-woocommerce-lite'
						)
				);
				return false;
			} );

	const bulkRequest = ( path, body, onSuccess ) =>
		apiPostBatches( path, {
			...body,
			products: bulkSelection.products || [],
			categories: bulkSelection.categories || [],
			override: bulkSelection.override ? 'yes' : '',
		} )
			.then( ( response ) => onSuccess( response.data ) )
			.catch( ( error ) => alert( errorMessage( error ) ) );

	const deleteDay = ( key ) => {
		if (
			! confirm(
				__(
					'Are you sure you want to delete this day?',
					'product-delivery-date-for-woocommerce-lite'
				)
			)
		) {
			return;
		}
		request( 'days/delete', { key } ).then( ( done ) => {
			if ( ! done ) {
				return;
			}
			if ( 0 === key.indexOf( 'prdd_weekday' ) ) {
				setValues( ( current ) => ( {
					...current,
					prdd_weekdays: ( current.prdd_weekdays || [] ).filter(
						( day ) => day !== key
					),
				} ) );
				return;
			}
			setValues( ( current ) => ( {
				...current,
				prdd_specific_date: splitDates( current.prdd_specific_date )
					.filter( ( date ) => date !== key )
					.join( ',' ),
			} ) );
			remountPicker();
		} );
	};

	const deleteSlot = ( row ) => {
		if (
			! confirm(
				__(
					'Are you sure you want to delete this time slot?',
					'product-delivery-date-for-woocommerce-lite'
				)
			)
		) {
			return;
		}
		request(
			'timeslots/delete',
			{ group: row.group, index: row.index },
			null,
			__(
				'Failed to delete time slot. Please try again.',
				'product-delivery-date-for-woocommerce-lite'
			)
		);
	};

	const deleteAll = ( type ) => {
		if ( ! confirm( DELETE_ALL_CONFIRM[ type ] ) ) {
			return;
		}
		request( 'delete-all', { type } ).then( ( done ) => {
			if ( ! done ) {
				return;
			}
			if ( 'specific' === type ) {
				setValues( ( current ) => ( {
					...current,
					prdd_specific_date: '',
					prdd_specific_chk: '',
				} ) );
				remountPicker();
			} else if ( 'recurring' === type ) {
				set( 'prdd_weekdays', [] );
			}
		} );
	};

	const setBulkLockout = ( group, key, value ) =>
		setBulkLockouts( ( current ) => ( {
			...current,
			[ group ]: { ...current[ group ], [ key ]: value },
		} ) );

	const form = {
		data,
		values,
		set,
		isBulk,
		newSlots,
		setNewSlots,
		specificDates,
		setSpecificDates: ( dates ) =>
			set( 'prdd_specific_date', dates.join( ',' ) ),
		pickerKey,
		specialPrices,
		setSpecialPrices,
		bulkLockouts,
		setBulkLockout,
		extra,
		setExtra,
		request,
		bulkRequest,
		deleteDay,
		deleteSlot,
		deleteAll,
	};

	/**
	 * Extension point replacing the prdd_before_* / prdd_after_* actions of
	 * the PHP template.
	 *
	 * @param {string} name Position, e.g. before_enable_time.
	 * @return {*} Element.
	 */
	form.slot = ( name ) =>
		applyFilters( 'prdd-lite.productMetabox.fields', null, name, form );

	const manage = isBulk
		? {
				name: 'manage-dates-bulk',
				title: __(
					'Manage Availability',
					'product-delivery-date-for-woocommerce-lite'
				),
				icon: <CalendarIcon />,
				Component: LockedManageAvailabilityBulkTab,
		  }
		: {
				name: 'manage-dates',
				title: __(
					'Manage Availability',
					'product-delivery-date-for-woocommerce-lite'
				),
				icon: <CalendarIcon />,
				Component: LockedManageAvailabilityTab,
		  };

	const tabs = applyFilters(
		'prdd-lite.productMetabox.tabs',
		[
			{
				name: 'delivery-option',
				title: __(
					'Delivery Options',
					'product-delivery-date-for-woocommerce-lite'
				),
				icon: <DeliveryOptionsIcon />,
				Component: DeliveryOptionsTab,
			},
			manage,
			{
				name: 'time-period',
				title: __(
					'Delivery Time Period',
					'product-delivery-date-for-woocommerce-lite'
				),
				icon: <ClockIcon />,
				Component: TimePeriodTab,
			},
			{
				name: 'delivery-charges',
				title: __(
					'Delivery Charges',
					'product-delivery-date-for-woocommerce-lite'
				),
				icon: <CardIcon />,
				Component: LockedDeliveryChargesTab,
			},
			{
				name: 'settings',
				title: __(
					'Settings',
					'product-delivery-date-for-woocommerce-lite'
				),
				icon: <GearIcon />,
				Component: SettingsTab,
			},
		],
		form
	);

	return (
		<div
			id="prdd-product-page"
			className={ `wbc-content ${
				isBulk ? '' : 'p-0'
			} product-setting-section prdd-content-area` }
		>
			<div className="tbl-mod-1">
				<div className="tm1-row p-0">
					<div className="col-left">
						<div className="col-sidebar">
							<ul>
								{ tabs.map( ( tab ) => (
									<li key={ tab.name }>
										<a
											className={
												activeTab === tab.name
													? 'prdd_tabs active'
													: 'prdd_tabs'
											}
											href="#"
											onClick={ ( event ) => {
												event.preventDefault();
												setActiveTab( tab.name );
											} }
										>
											{ tab.icon } { tab.title }{ ' ' }
											<i
												className={
													activeTab === tab.name
														? 'dashicons dashicons-arrow-right-alt2'
														: 'dashicons dashicons-arrow-down-alt2'
												}
											></i>
										</a>
									</li>
								) ) }
							</ul>
						</div>
					</div>
					<div className="col-right">
						<div className="tbl-mod-1">
							{ tabs.map( ( tab ) => {
								const Component = tab.Component;
								return (
									<div
										key={ tab.name }
										className={ `prdd-tab-content tab-${ tab.name }` }
										style={
											activeTab === tab.name
												? undefined
												: { display: 'none' }
										}
									>
										{ Component ? (
											<Component form={ form } />
										) : null }
									</div>
								);
							} ) }
							{ form.slot( 'after_listing_enabled' ) }
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}
