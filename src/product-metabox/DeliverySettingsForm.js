/**
 * Delivery settings form of the product page metabox, the former
 * prdd-delivery-settings-meta-box.php.
 */
import { __ } from '@wordpress/i18n';
import { applyFilters } from '@wordpress/hooks';
import { useEffect, useMemo, useState } from '@wordpress/element';
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
const LockedDeliveryChargesTab = locked( DeliveryChargesTab );

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
 * @param {Object}   props
 * @param {Object}   props.initialData Data of PRDD_Lite_Product_Metabox_React::get_form_data().
 * @param {Function} props.onChange    Receives the payload to save on every change.
 */
export default function DeliverySettingsForm( { initialData, onChange } ) {
	const [ values, setValues ] = useState( initialData.values || {} );
	const [ newSlots, setNewSlots ] = useState( [ { ...NEW_SLOT } ] );
	const [ extra, setExtra ] = useState( {} );
	const [ activeTab, setActiveTab ] = useState( 'delivery-option' );

	useEffect( () => {
		addDatepickerClearButton();
	}, [] );

	useEffect( () => {
		if ( onChange ) {
			onChange( {
				...values,
				new_timeslots: newSlots,
				extra,
			} );
		}
	}, [ values, newSlots, extra ] ); // eslint-disable-line react-hooks/exhaustive-deps

	const set = ( key, value ) =>
		setValues( ( current ) => ( { ...current, [ key ]: value } ) );
	const specificDates = useMemo(
		() => splitDates( values.prdd_specific_date ),
		[ values.prdd_specific_date ]
	);

	const form = {
		data: initialData,
		values,
		set,
		newSlots,
		setNewSlots,
		specificDates,
		setSpecificDates: ( dates ) =>
			set( 'prdd_specific_date', dates.join( ',' ) ),
		extra,
		setExtra,
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
			{
				name: 'manage-dates',
				title: __(
					'Manage Availability',
					'product-delivery-date-for-woocommerce-lite'
				),
				icon: <CalendarIcon />,
				Component: LockedManageAvailabilityTab,
			},
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
			className="wbc-content p-0 product-setting-section prdd-content-area"
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
