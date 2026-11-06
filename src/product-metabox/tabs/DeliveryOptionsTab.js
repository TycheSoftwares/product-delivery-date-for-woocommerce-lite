/**
 * "Delivery Options" tab, markup of the former template, the estimate
 * delivery option ( prdd-estimate-delivery.php ) and the time slot charge
 * field ( prdd-timeslot-price.php ).
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import InfoTip from '../../shared/components/InfoTip';
import ChoicesSelect from '../../shared/components/ChoicesSelect';
import { ProOnly } from '../../shared/components/ProNotice';
import MultiDatesPicker from '../../shared/components/MultiDatesPicker';
import {
	Switch,
	HourSelect,
	MinuteSelect,
	display,
	isOn,
} from '../components/fields';

export const NEW_SLOT = {
	from_slot_hrs: '',
	from_slot_min: '00',
	to_slot_hrs: '0',
	to_slot_min: '00',
	lockout_slot: '0',
	slot_price: '',
};

function Radio( {
	name,
	id,
	value,
	current,
	onChange,
	label,
	wrapper = 'div',
} ) {
	const Wrapper = wrapper;
	return (
		<>
			<Wrapper className="el-radio el-radio-green">
				<input
					type="radio"
					name={ name }
					id={ id }
					value={ value }
					checked={ value === current }
					onChange={ () => onChange( value ) }
				/>
				<label className="el-radio-style" htmlFor={ id }></label>
			</Wrapper>
			{ label }
		</>
	);
}

/**
 * Text currently saved for the delivery methods, as printed by the template.
 *
 * @param {Object} saved Saved values.
 * @return {string} Text.
 */
function currentlyDelivering( saved ) {
	const specific = isOn( saved.prdd_specific_chk );
	const recurring = isOn( saved.prdd_recurring_chk );
	let text = __(
		'Currently Delivering on:',
		'product-delivery-date-for-woocommerce-lite'
	);
	if ( ! specific && ! recurring ) {
		text += __( 'None', 'product-delivery-date-for-woocommerce-lite' );
	} else if ( specific && recurring ) {
		text += __(
			'Specific Dates, Recurring Weekdays',
			'product-delivery-date-for-woocommerce-lite'
		);
	} else if ( specific ) {
		text += __(
			'Specific Dates',
			'product-delivery-date-for-woocommerce-lite'
		);
	} else {
		text += __(
			'Recurring Weekdays',
			'product-delivery-date-for-woocommerce-lite'
		);
	}
	return text;
}

function TimeSlotBlock( { slot, index, onChange } ) {
	const set = ( key ) => ( value ) => onChange( { ...slot, [ key ]: value } );
	const n = `[${ index }]`;

	return (
		<>
			<div className="tm1-row">
				<div className="col-left">
					<label>
						{ __(
							'Enter a Time Slot:',
							'product-delivery-date-for-woocommerce-lite'
						) }
					</label>
				</div>
				<div className="col-right">
					<table className="prdd-form-table transparent-table">
						<tbody>
							<tr>
								<td>
									<table>
										<tbody>
											<tr className="mb-1">
												<td>
													{ __(
														'From:',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</td>
												<td>
													<HourSelect
														name={ `prdd_from_slot_hrs${ n }` }
														id={ `prdd_from_slot_hrs${ n }` }
														value={
															slot.from_slot_hrs
														}
														onChange={ set(
															'from_slot_hrs'
														) }
													/>
													<MinuteSelect
														placeholder="00"
														name={ `prdd_from_slot_min${ n }` }
														id={ `prdd_from_slot_min${ n }` }
														value={
															slot.from_slot_min
														}
														onChange={ set(
															'from_slot_min'
														) }
													/>
												</td>
											</tr>
											<tr>
												<td>
													{ __(
														'To:',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</td>
												<td>
													<HourSelect
														placeholder="0"
														name={ `prdd_to_slot_hrs${ n }` }
														id={ `prdd_to_slot_hrs${ n }` }
														value={
															slot.to_slot_hrs
														}
														onChange={ set(
															'to_slot_hrs'
														) }
													/>
													<MinuteSelect
														placeholder="00"
														name={ `prdd_to_slot_min${ n }` }
														id={ `prdd_to_slot_min${ n }` }
														value={
															slot.to_slot_min
														}
														onChange={ set(
															'to_slot_min'
														) }
													/>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div className="tm1-row">
				<div className="col-left">
					<label>
						{ ' ' }
						{ __(
							'Maximum deliveries per time slot:',
							'product-delivery-date-for-woocommerce-lite'
						) }
					</label>
				</div>
				<div className="col-right">
					<InfoTip
						text={ __(
							'Please enter a number to limit the number of deliveries for this time slot. Setting it to blanks or 0 will ensure unlimited deliveries.',
							'product-delivery-date-for-woocommerce-lite'
						) }
					/>
					<input
						type="text"
						style={ { width: '50px' } }
						name={ `prdd_lockout_time${ n }` }
						id={ `prdd_lockout_time${ n }` }
						value={ slot.lockout_slot }
						onChange={ ( event ) =>
							set( 'lockout_slot' )( event.target.value )
						}
					/>
				</div>
			</div>
			<div className="tm1-row">
				<div className="col-left">
					<label>
						{ __(
							'Delivery charge added for the time slot:',
							'product-delivery-date-for-woocommerce-lite'
						) }
					</label>
				</div>
				<div className="col-right">
					<InfoTip
						text={ __(
							'Any additional delivery charges for the time slot can be added here.',
							'product-delivery-date-for-woocommerce-lite'
						) }
					/>
					<input
						type="text"
						style={ { width: '100px' } }
						name={ `prdd_price_time${ n }` }
						id={ `prdd_price_time${ n }` }
						value={ slot.slot_price }
						onChange={ ( event ) =>
							set( 'slot_price' )( event.target.value )
						}
					/>
				</div>
			</div>
		</>
	);
}

export default function DeliveryOptionsTab( { form } ) {
	const {
		values,
		set,
		data,
		newSlots,
		setNewSlots,
		specificDates,
		setSpecificDates,
		slot,
	} = form;
	const calendar = 'text_block' !== values.prdd_delivery_options;
	const specific = isOn( values.prdd_specific_chk );
	const recurring = isOn( values.prdd_recurring_chk );
	const time = isOn( values.prdd_enable_time );
	const text = ( key ) => ( event ) => set( key, event.target.value );

	return (
		<>
			<div className="d-flex justify-content-between">
				<h4>
					{ __(
						'Enable Delivery Date',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</h4>
			</div>
			{ slot( 'before_enable_delivery' ) }
			<div className="tm1-row align-items-center d-b-color">
				<div className="col-left">
					<label>
						{ __(
							'Enable Delivery Date:',
							'product-delivery-date-for-woocommerce-lite'
						) }
					</label>
				</div>
				<div className="col-right">
					<div className="rc-flx-wrap flx-aln-center">
						<InfoTip
							text={ __(
								'Enable the ability to capture delivery date on the Single Product Page.',
								'product-delivery-date-for-woocommerce-lite'
							) }
						/>
						<Switch
							id="prdd_enable_date"
							checked={ isOn( values.prdd_enable_date ) }
							onChange={ ( value ) =>
								set( 'prdd_enable_date', value )
							}
						/>
					</div>
				</div>
			</div>

			<ProOnly>
				<div className="tm1-row">
					<div className="col-left">
						<label>
							{ __(
								'Delivery Option:',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</label>
					</div>
					<div className="col-right">
						<div className="rc-flx-wrap flx-aln-center ro-wrap">
							<InfoTip
								text={ __(
									"The 'Calendar' option displays a datepicker at the front end, whereas the 'Text Block' option displays an estimated time period of delivery based on the setting",
									'product-delivery-date-for-woocommerce-lite'
								) }
							/>
							<div className="rb-flx-style">
								<Radio
									name="prdd_delivery_options"
									id="prdd_delivery_options_delivery_calendar"
									value="delivery_calendar"
									current={ values.prdd_delivery_options }
									onChange={ ( value ) =>
										set( 'prdd_delivery_options', value )
									}
									label={
										<label htmlFor="prdd_delivery_options_delivery_calendar">
											{ __(
												'Calendar',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</label>
									}
								/>
							</div>
							<div className="rb-flx-style">
								<Radio
									name="prdd_delivery_options"
									id="prdd_delivery_options_text_block"
									value="text_block"
									current={ values.prdd_delivery_options }
									onChange={ ( value ) =>
										set( 'prdd_delivery_options', value )
									}
									label={
										<label htmlFor="prdd_delivery_options_text_block">
											{ __(
												'Text Block',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</label>
									}
								/>
							</div>
						</div>
					</div>
				</div>
				<div
					className="text_box_sections"
					style={ display( ! calendar, 'block' ) }
				>
					<div className="tm1-row">
						<div className="col-left">
							<label>
								{ __(
									'Minimum Delivery Days required:',
									'product-delivery-date-for-woocommerce-lite'
								) }
							</label>
						</div>
						<div className="col-right">
							<div className="rc-flx-wrap flx-aln-center ro-wrap">
								<InfoTip
									text={ __(
										'Minimum number of days required to deliver an order after submitting it.',
										'product-delivery-date-for-woocommerce-lite'
									) }
								/>
								<input
									className="ib-lg"
									type="text"
									name="prdd_estimate_days"
									id="prdd_estimate_days"
									value={ values.prdd_estimate_days }
									onChange={ text( 'prdd_estimate_days' ) }
								/>
							</div>
						</div>
					</div>
				</div>
				<div
					className="text_box_sections"
					style={ display( ! calendar, 'block' ) }
				>
					<div className="tm1-row">
						<div className="col-left">
							<label>
								{ __(
									'Display Delivery details:',
									'product-delivery-date-for-woocommerce-lite'
								) }
							</label>
						</div>
						<div className="col-right">
							<div className="rc-flx-wrap flx-aln-center ro-wrap">
								<InfoTip
									text={ __(
										'Display estimated delivery details based on the number of business days or an estimated delivery date.',
										'product-delivery-date-for-woocommerce-lite'
									) }
								/>
								<div className="rb-flx-style">
									<Radio
										name="prdd_estimate_delivery_display"
										id="prdd_estimate_business_delivery_display"
										value="days"
										current={
											values.prdd_estimate_delivery_display
										}
										onChange={ ( value ) =>
											set(
												'prdd_estimate_delivery_display',
												value
											)
										}
										label={
											<label htmlFor="prdd_estimate_business_delivery_display">
												{ __(
													'Business Days',
													'product-delivery-date-for-woocommerce-lite'
												) }
											</label>
										}
									/>
								</div>
								<div className="rb-flx-style">
									<Radio
										name="prdd_estimate_delivery_display"
										id="prdd_estimate_specific_delivery_display"
										value="specific_date"
										current={
											values.prdd_estimate_delivery_display
										}
										onChange={ ( value ) =>
											set(
												'prdd_estimate_delivery_display',
												value
											)
										}
										label={
											<label htmlFor="prdd_estimate_specific_delivery_display">
												{ __(
													'Specific Date',
													'product-delivery-date-for-woocommerce-lite'
												) }
											</label>
										}
									/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</ProOnly>
			<div
				className="tm1-row delivery_calendar_sections"
				style={ display( calendar ) }
			>
				<div className="col-left"></div>{ ' ' }
				<div className="col-right">
					{ currentlyDelivering( data.values || {} ) }
				</div>
			</div>
			<ProOnly>
				<div
					className="tm1-row delivery_calendar_sections"
					style={ display( calendar ) }
				>
					<div className="col-left">
						<label>
							{ __(
								'Delivery On:',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</label>
					</div>
					<div className="col-right">
						<div className="rc-flx-wrap flx-aln-center ro-wrap">
							<InfoTip
								text={ __(
									'Please enable/disable the specific delivery dates and recurring weekdays using these checkboxes. Upon checking them, you shall be able to further select dates or weekdays.',
									'product-delivery-date-for-woocommerce-lite'
								) }
							/>
							<div className="custom-control custom-checkbox mr-3">
								<input
									type="checkbox"
									className="custom-control-input"
									name="prdd_specific_chk"
									id="prdd_specific_chk"
									checked={ specific }
									onChange={ ( event ) =>
										set(
											'prdd_specific_chk',
											event.target.checked ? 'on' : ''
										)
									}
								/>
								<label
									className="custom-control-label"
									htmlFor="prdd_specific_chk"
								>
									{ __(
										'Specific Dates',
										'product-delivery-date-for-woocommerce-lite'
									) }
								</label>
							</div>
							<div className="custom-control custom-checkbox">
								<input
									type="checkbox"
									className="custom-control-input"
									name="prdd_recurring_chk"
									id="prdd_recurring_chk"
									checked={ recurring }
									onChange={ ( event ) =>
										set(
											'prdd_recurring_chk',
											event.target.checked ? 'on' : ''
										)
									}
								/>
								<label
									className="custom-control-label"
									htmlFor="prdd_recurring_chk"
								>
									{ __(
										'Recurring Weekdays',
										'product-delivery-date-for-woocommerce-lite'
									) }
								</label>
							</div>
						</div>
					</div>
				</div>
			</ProOnly>
			<div
				className="delivery_calendar_sections"
				id="prdd_enable_weekday"
				style={ display( calendar && recurring, 'block' ) }
			>
				<div className="tm1-row">
					<div className="col-left">
						<label>
							{ __(
								'Delivery Days:',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</label>
					</div>
					<div className="col-right">
						<div className="rc-flx-wrap ro-wrap">
							<InfoTip
								text={ __(
									'Select recurring weekdays for delivery',
									'product-delivery-date-for-woocommerce-lite'
								) }
							/>
							<ChoicesSelect
								id="prdd_weekdays"
								className="prdd_weekdays prdd-multiple ib-small"
								name="prdd_weekdays[]"
								placeholder={ __(
									'Select Weekdays',
									'product-delivery-date-for-woocommerce-lite'
								) }
								options={ data.weekdays || [] }
								value={ values.prdd_weekdays || [] }
								onChange={ ( selected ) =>
									set( 'prdd_weekdays', selected )
								}
							/>
						</div>
					</div>
				</div>
			</div>
			<div
				className="delivery_calendar_sections"
				id="selective_delivery"
				style={ display( calendar && specific, 'block' ) }
			>
				<div className="tm1-row align-items-center">
					<div className="col-left">
						<label>
							{ __(
								'Specific Date Delivery:',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</label>
					</div>
					<div className="col-right">
						<InfoTip
							text={ __(
								'Select the specific dates that you want to enable for delivery.&#13;&#10;Specific dates can be managed from the "Manage Dates, Time Slots" tab on the left.',
								'product-delivery-date-for-woocommerce-lite'
							) }
						/>
						<MultiDatesPicker
							tag="input"
							className="ib-lg"
							type="text"
							name="prdd_specific_date"
							id="prdd_specific_date"
							value={ specificDates }
							onChange={ setSpecificDates }
							options={ {
								multiSelect: 999,
								monthsToShow: 1,
								showButtonPanel: true,
							} }
						/>
					</div>
				</div>
			</div>
			{ slot( 'before_enable_time' ) }
			<ProOnly>
				<div
					className="tm1-row align-items-center delivery_calendar_sections"
					style={ display( calendar ) }
				>
					<div className="col-left">
						<label>
							{ __(
								'Maximum deliveries per day:',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</label>
					</div>
					<div className="col-right">
						<div className="rc-flx-wrap flx-aln-center ro-wrap">
							<InfoTip
								text={ __(
									'Set this field if you want to place a limit on maximum deliveries on any given date. If you can manage up to 15 deliveries in a day, set this value to 15. Once 15 orders have been placed, then that date will not be available for further deliveries. Setting it to blanks or 0 will ensure unlimited deliveries.',
									'product-delivery-date-for-woocommerce-lite'
								) }
							/>
							<input
								className="ib-lg"
								type="text"
								id="prdd_lockout_date"
								name="prdd_lockout_date"
								value={ values.prdd_lockout_date }
								onChange={ text( 'prdd_lockout_date' ) }
							/>
						</div>
					</div>
				</div>
				<div
					className="tm1-row delivery_calendar_sections"
					style={ display( calendar ) }
				>
					<div className="col-left">
						<label>
							{ __(
								'Ask for Delivery Time:',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</label>
					</div>
					<div className="col-right">
						<div className="rc-flx-wrap flx-aln-center ro-wrap">
							<InfoTip
								text={ __(
									'Enable time (or time slots) on the product. Add any number of delivery time slots once you have checked this. You can manage the Time Slots using the Manage Dates, Time Slots tab',
									'product-delivery-date-for-woocommerce-lite'
								) }
							/>
							<Switch
								id="prdd_enable_time"
								checked={ time }
								onChange={ ( value ) =>
									set( 'prdd_enable_time', value )
								}
							/>
						</div>
					</div>
				</div>
				<div
					id="time_slot"
					className="delivery_calendar_sections"
					style={ display( calendar && time, 'block' ) }
				>
					{ newSlots.map( ( item, i ) => (
						<Fragment key={ i }>
							<TimeSlotBlock
								slot={ item }
								index={ i + 1 }
								onChange={ ( changed ) =>
									setNewSlots(
										newSlots.map( ( current, j ) =>
											j === i ? changed : current
										)
									)
								}
							/>
						</Fragment>
					) ) }
				</div>
				<div
					className="mt-4 delivery_calendar_sections"
					id="add_button"
					style={ display( calendar ) }
				>
					<input
						type="button"
						className="trietary-btn reverse"
						value={ __(
							'Add Time Slot',
							'product-delivery-date-for-woocommerce-lite'
						) }
						id="add_another"
						disabled={ ! time }
						onClick={ () =>
							setNewSlots( [ ...newSlots, { ...NEW_SLOT } ] )
						}
					/>
				</div>
			</ProOnly>
		</>
	);
}
