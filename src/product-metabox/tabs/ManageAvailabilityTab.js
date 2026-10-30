/**
 * "Manage Availability" tab of the product page: saved dates, weekdays and
 * time slots with their inline edit / delete actions.
 */
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { HourSelect, MinuteSelect } from '../components/fields';

const hidden = { display: 'none' };
const TRASH_STYLE = { color: 'rgb(220, 53, 69)' };

function SectionHead( { title, className, onDeleteAll } ) {
	return (
		<>
			<div className="float-left">
				<h4>{ title }</h4>
			</div>
			<div className="float-right">
				<a
					href="#"
					className={ `${ className } trietary-btn reverse` }
					onClick={ ( event ) => {
						event.preventDefault();
						onDeleteAll();
					} }
				>
					{ __(
						'Delete All',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</a>
			</div>
		</>
	);
}

function DayRow( { row, label, rowKey, form } ) {
	const [ editing, setEditing ] = useState( false );
	const [ lockout, setLockout ] = useState( String( row.lockout ) );

	const toggle = () => {
		if ( ! editing ) {
			setLockout( String( row.lockout ) );
			setEditing( true );
			return;
		}
		setEditing( false );
		form.request( 'days/lockout', { key: rowKey, lockout } );
	};

	return (
		<tr id={ `row_${ rowKey }` }>
			<td> { label } </td>
			<td
				className="view_field lockout_slot"
				style={ editing ? hidden : undefined }
			>
				{ ' ' }
				{ row.lockout }
			</td>
			<td className="edit_field" style={ editing ? undefined : hidden }>
				<input
					type="number"
					min="0"
					style={ { width: '60px' } }
					value={ lockout }
					onChange={ ( event ) => setLockout( event.target.value ) }
				/>{ ' ' }
			</td>
			<td className="tbl-action-col">
				{ ' ' }
				<a
					href="#"
					className="remove_day_data"
					style={ editing ? hidden : undefined }
					onClick={ ( event ) => {
						event.preventDefault();
						form.deleteDay( rowKey );
					} }
				>
					{ ' ' }
					<span
						className="dashicons dashicons-trash"
						style={ TRASH_STYLE }
						title={ __(
							'Remove Date',
							'product-delivery-date-for-woocommerce-lite'
						) }
					></span>
				</a>
				<span
					className="recure_edit_days"
					onClick={ toggle }
					role="button"
					tabIndex={ 0 }
					onKeyDown={ ( event ) => 'Enter' === event.key && toggle() }
				>
					{ editing ? (
						__(
							'Update',
							'product-delivery-date-for-woocommerce-lite'
						)
					) : (
						<span className="dashicons dashicons-edit"></span>
					) }
				</span>
			</td>
		</tr>
	);
}

function SlotRow( { row, form, recurring } ) {
	const [ editing, setEditing ] = useState( false );
	const [ slot, setSlot ] = useState( row );
	const set = ( key ) => ( value ) => setSlot( { ...slot, [ key ]: value } );

	const toggle = () => {
		if ( ! editing ) {
			setSlot( row );
			setEditing( true );
			return;
		}
		setEditing( false );
		form.request( 'timeslots/edit', {
			group: row.group,
			index: row.index,
			from_hrs: slot.from_slot_hrs,
			from_min: slot.from_slot_min,
			to_hrs: slot.to_slot_hrs,
			to_min: slot.to_slot_min,
			lockout: slot.lockout_slot,
			price: slot.slot_price,
		} );
	};

	const view = editing ? hidden : undefined;
	const edit = editing ? undefined : hidden;

	return (
		<tr
			id={ `row_${ row.index }_${ row.group }` }
			data-post-id={ form.data.post_id }
		>
			<td> { row.label } </td>
			<td className="view_field prdd_form_slot" style={ view }>
				{ ' ' }
				{ `${ row.from_slot_hrs }:${ row.from_slot_min }` }{ ' ' }
			</td>
			<td className="edit_field" style={ edit }>
				<HourSelect
					value={ slot.from_slot_hrs }
					onChange={ set( 'from_slot_hrs' ) }
				/>
				<MinuteSelect
					value={ slot.from_slot_min }
					onChange={ set( 'from_slot_min' ) }
				/>
			</td>
			<td className="view_field prdd_to_slot" style={ view }>
				{ ' ' }
				{ `${ row.to_slot_hrs }:${ row.to_slot_min }` }{ ' ' }
			</td>
			<td className="edit_field" style={ edit }>
				<HourSelect
					value={ slot.to_slot_hrs }
					onChange={ set( 'to_slot_hrs' ) }
				/>
				<MinuteSelect
					value={ slot.to_slot_min }
					onChange={ set( 'to_slot_min' ) }
				/>
			</td>
			<td className="view_field lockout_slot" style={ view }>
				{ ' ' }
				{ row.lockout_slot }{ ' ' }
			</td>
			<td className="edit_field" style={ edit }>
				<input
					type="number"
					min="0"
					style={ { width: '60px' } }
					value={ slot.lockout_slot }
					onChange={ ( event ) =>
						set( 'lockout_slot' )( event.target.value )
					}
				/>{ ' ' }
			</td>
			<td
				className="view_field slot_price"
				style={ view }
				dangerouslySetInnerHTML={ { __html: ` ${ row.price_html } ` } }
			/>
			<td className="edit_field" style={ edit }>
				<input
					type="number"
					value={ slot.slot_price }
					style={ { width: '60px' } }
					onChange={ ( event ) =>
						set( 'slot_price' )( event.target.value )
					}
				/>
			</td>
			<td className="tbl-action-col">
				{ ' ' }
				<a
					href="#"
					className="remove_time_data view_field"
					style={ view }
					onClick={ ( event ) => {
						event.preventDefault();
						form.deleteSlot( row );
					} }
				>
					{ ' ' }
					<span
						className="dashicons dashicons-trash"
						style={ TRASH_STYLE }
						title={ __(
							'Remove Time Slot',
							'product-delivery-date-for-woocommerce-lite'
						) }
					></span>
				</a>
				<span
					className="recure_edit"
					onClick={ toggle }
					role="button"
					tabIndex={ 0 }
					onKeyDown={ ( event ) => 'Enter' === event.key && toggle() }
				>
					{ editing &&
						__(
							'Update',
							'product-delivery-date-for-woocommerce-lite'
						) }
					{ ! editing &&
						( recurring ? (
							<span
								title={ __(
									'Edt Time Slot',
									'product-delivery-date-for-woocommerce-lite'
								) }
								className="dashicons dashicons-edit"
							></span>
						) : (
							<span className="dashicons dashicons-edit"></span>
						) ) }
				</span>
			</td>
		</tr>
	);
}

function SlotTable( { id, rows, form, recurring } ) {
	return (
		<table
			className="wp-list-table widefat fixed posts"
			cellSpacing="0"
			id={ id }
			data-post-id={ form.data.post_id }
		>
			<tbody>
				<tr>
					<th>
						{ ' ' }
						{ __(
							'Day',
							'product-delivery-date-for-woocommerce-lite'
						) }{ ' ' }
					</th>
					<th>
						{ ' ' }
						{ __(
							'Start Time',
							'product-delivery-date-for-woocommerce-lite'
						) }{ ' ' }
					</th>
					<th>
						{ ' ' }
						{ __(
							'End Time',
							'product-delivery-date-for-woocommerce-lite'
						) }{ ' ' }
					</th>
					<th>
						{ ' ' }
						{ __(
							'Maximum Deliveries',
							'product-delivery-date-for-woocommerce-lite'
						) }{ ' ' }
					</th>
					<th>
						{ ' ' }
						{ __(
							'Time Slot Price',
							'product-delivery-date-for-woocommerce-lite'
						) }{ ' ' }
					</th>
					<th>
						{ ' ' }
						{ __(
							'Actions',
							'product-delivery-date-for-woocommerce-lite'
						) }{ ' ' }
					</th>
				</tr>
				{ rows.map( ( row ) => (
					<SlotRow
						key={ `${ row.group }_${ row.index }_${ row.from_slot_hrs }_${ row.from_slot_min }_${ row.to_slot_hrs }_${ row.to_slot_min }_${ row.lockout_slot }_${ row.slot_price }` }
						row={ row }
						form={ form }
						recurring={ recurring }
					/>
				) ) }
			</tbody>
		</table>
	);
}

function DayTable( { id, children, form } ) {
	return (
		<table
			className="wp-list-table widefat fixed posts"
			cellSpacing="0"
			id={ id }
			data-post-id={ form.data.post_id }
		>
			<tbody>
				<tr>
					<th>
						{ ' ' }
						{ __(
							'Day',
							'product-delivery-date-for-woocommerce-lite'
						) }{ ' ' }
					</th>
					<th>
						{ ' ' }
						{ __(
							'Maximum Deliveries',
							'product-delivery-date-for-woocommerce-lite'
						) }{ ' ' }
					</th>
					<th>
						{ ' ' }
						{ __(
							'Actions',
							'product-delivery-date-for-woocommerce-lite'
						) }{ ' ' }
					</th>
				</tr>
				{ children }
			</tbody>
		</table>
	);
}

export default function ManageAvailabilityTab( { form } ) {
	const { data } = form;
	const specificDates = data.specific_dates || [];
	const recurringDays = data.recurring_days || [];
	const specificSlots = data.specific_timeslots || [];
	const recurringSlots = data.recurring_timeslots || [];

	return (
		<>
			{ specificDates.length > 0 && (
				<>
					<SectionHead
						title={ __(
							'Specific Dates',
							'product-delivery-date-for-woocommerce-lite'
						) }
						className="remove_specific_data"
						onDeleteAll={ () => form.deleteAll( 'specific' ) }
					/>
					<DayTable id="list_prdd_specific" form={ form }>
						{ specificDates.map( ( row ) => (
							<DayRow
								key={ `${ row.value }_${ row.lockout }` }
								row={ row }
								label={ row.value }
								rowKey={ row.value }
								form={ form }
							/>
						) ) }
					</DayTable>
				</>
			) }
			{ recurringDays.length > 0 && (
				<>
					<SectionHead
						title={ __(
							'Recurring Days',
							'product-delivery-date-for-woocommerce-lite'
						) }
						className="remove_recurring_data"
						onDeleteAll={ () => form.deleteAll( 'recurring' ) }
					/>
					<DayTable id="list_prdd_recurring" form={ form }>
						{ recurringDays.map( ( row ) => (
							<DayRow
								key={ `${ row.key }_${ row.lockout }` }
								row={ row }
								label={ row.label }
								rowKey={ row.key }
								form={ form }
							/>
						) ) }
					</DayTable>
				</>
			) }
			{ specificSlots.length > 0 && (
				<>
					<SectionHead
						title={ __(
							'Specific Date Time Slots',
							'product-delivery-date-for-woocommerce-lite'
						) }
						className="remove_specific_data_timeslots"
						onDeleteAll={ () =>
							form.deleteAll( 'specific_timeslots' )
						}
					/>
					<SlotTable
						id="list_prdd_specific_timeslots"
						rows={ specificSlots }
						form={ form }
					/>
				</>
			) }
			{ recurringSlots.length > 0 && (
				<>
					<SectionHead
						title={ __(
							'Recurring Days Time Slots',
							'product-delivery-date-for-woocommerce-lite'
						) }
						className="remove_recurring_data_timeslots"
						onDeleteAll={ () =>
							form.deleteAll( 'recurring_timeslots' )
						}
					/>
					<SlotTable
						id="list_prdd_recurring_timeslots"
						rows={ recurringSlots }
						form={ form }
						recurring
					/>
				</>
			) }
			<input
				type="hidden"
				name="prdd_product_id"
				id="prdd_product_id"
				value={ data.post_id || '' }
			/>
		</>
	);
}
