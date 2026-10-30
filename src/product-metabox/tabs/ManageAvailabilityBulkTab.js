/**
 * "Manage Availability" tab of the Bulk Product Settings page: maximum
 * deliveries of the selected specific dates and of each weekday.
 */
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

const hidden = { display: 'none' };

function LockoutRow( {
	id,
	label,
	group,
	rowKey,
	value,
	onChange,
	actionClass,
} ) {
	const [ editing, setEditing ] = useState( false );
	const name = `delivery_date_lockout[${ group }][${ rowKey }]`;
	const toggle = () => setEditing( ! editing );

	const input = (
		<td className="edit_field" style={ editing ? undefined : hidden }>
			<input
				id={ name }
				name={ name }
				type="number"
				min="0"
				style={ { width: '60px' } }
				value={ value }
				onChange={ ( event ) => onChange( event.target.value ) }
			/>
		</td>
	);
	const view = (
		<td
			className="view_field lockout_slot"
			style={ editing ? hidden : undefined }
		>
			{ '' === value ? '0' : value }
		</td>
	);

	return (
		<tr id={ id }>
			<td>{ label }</td>
			{ 'specific_dates' === group ? (
				<>
					{ input }
					{ view }
				</>
			) : (
				<>
					{ view }
					{ input }
				</>
			) }
			<td className={ actionClass }>
				<span
					className="recure_edit_bulk_days"
					data-key={ rowKey }
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
						<span
							className="dashicons dashicons-edit"
							title={
								'weekdays' === group
									? __(
											'Edit',
											'product-delivery-date-for-woocommerce-lite'
									  )
									: undefined
							}
						></span>
					) }
				</span>
			</td>
		</tr>
	);
}

export default function ManageAvailabilityBulkTab( { form } ) {
	const { data, specificDates, bulkLockouts, setBulkLockout } = form;
	const lockout = ( group, key ) =>
		bulkLockouts[ group ] && undefined !== bulkLockouts[ group ][ key ]
			? String( bulkLockouts[ group ][ key ] )
			: '';

	return (
		<>
			<div className="d-flex justify-content-between heading-border mb-20">
				<h4>
					{ __(
						'Manage Availability',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</h4>
			</div>
			<div className="float-left">
				<h4>
					{ __(
						'Specific Dates',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</h4>
			</div>
			<table
				className="wp-list-table widefat fixed posts"
				cellSpacing="0"
				id="prdd-specific-dates-lockout-table"
			>
				<thead>
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
				</thead>
				<tbody>
					{ specificDates.map( ( date ) => (
						<LockoutRow
							key={ date }
							label={ date }
							group="specific_dates"
							rowKey={ date }
							value={
								'' === lockout( 'specific_dates', date )
									? '0'
									: lockout( 'specific_dates', date )
							}
							onChange={ ( value ) =>
								setBulkLockout( 'specific_dates', date, value )
							}
						/>
					) ) }
				</tbody>
			</table>
			<div className="float-left">
				<h4>
					{ __(
						'Recurring Days',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</h4>
			</div>
			<table
				className="wp-list-table widefat fixed posts"
				cellSpacing="0"
				id="prdd-weekdays-lockout-table"
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
					{ ( data.weekdays || [] ).map( ( day ) => (
						<LockoutRow
							key={ day.value }
							id={ `row_${ day.value }` }
							label={ day.label }
							group="weekdays"
							rowKey={ day.value }
							value={ lockout( 'weekdays', day.value ) }
							onChange={ ( value ) =>
								setBulkLockout( 'weekdays', day.value, value )
							}
							actionClass="tbl-action-col"
						/>
					) ) }
				</tbody>
			</table>
		</>
	);
}
