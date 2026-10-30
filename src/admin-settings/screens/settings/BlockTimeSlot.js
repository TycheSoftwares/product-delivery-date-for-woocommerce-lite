/* eslint-disable jsx-a11y/no-redundant-roles -- Markup kept from the earlier template. */
/**
 * Block Time Slot tab, markup of the former includes/admin/views/settings/block-time-slot.php.
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import config, { pluginUrl } from '../../../shared/config';
import { apiDelete, apiGet, apiPost, errorMessage } from '../../../shared/api';
import InfoTip from '../../../shared/components/InfoTip';
import ErrorMessage from '../../../shared/components/ErrorMessage';
import ChoicesSelect from '../../../shared/components/ChoicesSelect';
import MultiDatesPicker from '../../../shared/components/MultiDatesPicker';
import { scrollToMessage, vLoading, vShow } from '../../hooks/useSettingsForm';
import Spinner from '../../../shared/components/Spinner';

const HOURS = Array.from( { length: 24 }, ( v, i ) => String( i ) );
const MINUTES = Array.from( { length: 60 }, ( v, i ) =>
	i < 10 ? '0' + i : String( i )
);
const EMPTY_SLOT = { from_hrs: '', from_min: '00', to_hrs: '0', to_min: '00' };

function weekdayLabel( key ) {
	const day = ( config.weekdays || [] ).find(
		( item ) => item.value === key
	);
	return day ? day.label : key;
}

export default function BlockTimeSlot() {
	const [ rows, setRows ] = useState( [] );
	const [ checked, setChecked ] = useState( [] );
	const [ blockFor, setBlockFor ] = useState( 'dates' );
	const [ dates, setDates ] = useState( [] );
	const [ weekdays, setWeekdays ] = useState( [] );
	const [ slots, setSlots ] = useState( [] );
	const [ formOpen, setFormOpen ] = useState( false );
	const [ formKey, setFormKey ] = useState( 0 );
	const [ bulkAction, setBulkAction ] = useState( 'select' );
	const [ loading, setLoading ] = useState( true );
	const [ saving, setSaving ] = useState( false );
	const [ savedMessage, setSavedMessage ] = useState( false );
	const [ error, setError ] = useState( '' );

	const weekdayOptions = [
		{
			value: 'all',
			label: __( 'All', 'product-delivery-date-for-woocommerce-lite' ),
		},
		...( config.weekdays || [] ),
	];

	const setData = ( data ) => {
		setRows( Array.isArray( data ) ? data : [] );
		setChecked( [] );
	};

	useEffect( () => {
		apiGet( 'settings/block-time-slots' )
			.then( ( response ) => setData( response.data ) )
			.catch( ( e ) => setError( errorMessage( e ) ) )
			.finally( () => setLoading( false ) );
	}, [] );

	const unblock = ( items ) => {
		if ( 0 === items.length ) {
			return;
		}
		setSavedMessage( false );
		setSaving( true );
		apiDelete( 'settings/block-time-slots', {
			items: items.map( ( row ) => ( {
				time_slot: row.time_slot,
				delivery_days: row.delivery_days,
			} ) ),
		} )
			.then( ( response ) => setData( response.data ) )
			.catch( ( e ) => setError( errorMessage( e ) ) )
			.finally( () => setSaving( false ) );
	};

	const addSlot = () => setSlots( [ ...slots, { ...EMPTY_SLOT } ] );

	const updateSlot = ( index, key ) => ( event ) => {
		const next = [ ...slots ];
		next[ index ] = { ...next[ index ], [ key ]: event.target.value };
		setSlots( next );
	};

	const saveSlots = ( event ) => {
		event.preventDefault();
		setSavedMessage( false );
		setError( '' );
		setSaving( true );
		apiPost( 'settings/block-time-slots', {
			block_time_slot_for: blockFor,
			weekdays,
			dates,
			time_slots: slots,
		} )
			.then( ( response ) => {
				setData( response.data );
				setSlots( [] );
				setDates( [] );
				setWeekdays( [] );
				setFormKey( formKey + 1 );
				setSavedMessage( true );
				scrollToMessage();
			} )
			.catch( ( e ) => setError( errorMessage( e ) ) )
			.finally( () => setSaving( false ) );
	};

	const toggleAll = () => {
		// Same as before, the header checkbox inverts the selection of every row.
		setChecked( rows.map( ( row, index ) => ! checked[ index ] ) );
	};

	const applyBulk = () => {
		if ( 'unblock' === bulkAction ) {
			unblock( rows.filter( ( row, index ) => checked[ index ] ) );
		}
	};

	const slotSelect = (
		index,
		key,
		placeholder,
		placeholderValue,
		values
	) => {
		const name =
			'prdd_' +
			key.replace( '_hrs', '_slot_hrs' ).replace( '_min', '_slot_min' ) +
			'[' +
			index +
			']';
		return (
			<select
				name={ name }
				id={ name }
				value={ slots[ index ][ key ] }
				onChange={ updateSlot( index, key ) }
			>
				<option value={ placeholderValue }>{ placeholder }</option>
				{ values.map( ( item ) => (
					<option key={ item } value={ item }>
						{ item }
					</option>
				) ) }
			</select>
		);
	};

	return (
		<div className="prdd-content-area">
			<div className="container bts-page-wrap">
				<div
					className="pl-info-wrap"
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
				<div className="prdd_events_loader" style={ vShow( saving ) }>
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
									'Block Time Slots',
									'product-delivery-date-for-woocommerce-lite'
								) }
							</h1>
							<p>
								{ __(
									"Taking some time off is a must. That's why you can block or hide time slots for particular dates or weekdays and get that groove on!",
									'product-delivery-date-for-woocommerce-lite'
								) }
							</p>
						</div>
					</div>
					<div className="col-md-12">
						<div className="bts-content">
							<div className="tbl-mod-2 flx-100">
								<div className="tm2-inner-wrap">
									<table className="table">
										<thead>
											<tr>
												<th width="5%">
													<div className="el-checkbox el-checkbox-green d-uni-col">
														<input
															type="checkbox"
															className="ckbCheckAll"
															id="cb_opt_nm_9A"
															value="cb_opt_val_9"
															onChange={
																toggleAll
															}
														/>
														<label
															className="el-checkbox-style mb-0"
															htmlFor="cb_opt_nm_9A"
														></label>
													</div>
												</th>
												<th>
													{ __(
														'Delivery Days/Dates',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</th>
												<th>
													{ __(
														'Time Slot',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</th>
												<th>
													{ __(
														'Actions',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</th>
											</tr>
										</thead>
										<tbody>
											{ rows.map( ( row, index ) => (
												<tr
													className="cloned-row"
													key={
														row.delivery_days +
														'|' +
														row.time_slot
													}
												>
													<td>
														<div className="el-checkbox el-checkbox-green d-uni-col">
															<input
																type="checkbox"
																className="checkBoxClass"
																id={ index }
																checked={
																	!! checked[
																		index
																	]
																}
																onChange={ () => {
																	const next =
																		[
																			...checked,
																		];
																	next[
																		index
																	] =
																		! next[
																			index
																		];
																	setChecked(
																		next
																	);
																} }
															/>
															<label
																className="el-checkbox-style mb-0"
																htmlFor={
																	index
																}
															></label>
														</div>
													</td>
													{ 'weekdays' ===
														row.block_time_slot_for && (
														<td>
															{ weekdayLabel(
																row.delivery_days
															) }
														</td>
													) }
													{ 'dates' ===
														row.block_time_slot_for && (
														<td>
															{
																row.delivery_days
															}
														</td>
													) }
													<td>{ row.time_slot }</td>
													<td>
														<a
															className="delete"
															title="Delete"
															href="#!"
															onClick={ ( e ) => {
																e.preventDefault();
																unblock( [
																	row,
																] );
															} }
														>
															{ __(
																'Unblock',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</a>
													</td>
												</tr>
											) ) }
										</tbody>
									</table>

									<div
										id="col_opt_id_1"
										className={
											'collapse tbl-mod-3 bdr-btm-1' +
											( formOpen ? ' show' : '' )
										}
									>
										{ /* Add Time Slots */ }
										<form
											method="post"
											onSubmit={ saveSlots }
											key={ formKey }
										>
											<div className="tbl-mod-1">
												<div className="tm3-header">
													<h3>
														{ __(
															'Add Time Slots',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</h3>
												</div>
												<div className="tm1-row">
													<div className="col-left">
														<label>
															{ __(
																'Block Time Slot For:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="rc-flx-wrap flx-aln-center ro-wrap">
															<InfoTip
																text={ __(
																	'Select "Dates" option to block time slots for individual dates. Select "Weekdays" option to block the time slots for a weekday or multiple weekdays.',
																	'product-delivery-date-for-woocommerce-lite'
																) }
															/>
															<div className="el-radio el-radio-green">
																<input
																	type="radio"
																	name="prdd_recurring_chk"
																	id="time_slot_3_1"
																	value="dates"
																	checked={
																		'dates' ===
																		blockFor
																	}
																	onChange={ () =>
																		setBlockFor(
																			'dates'
																		)
																	}
																/>
																<label
																	className="el-radio-style"
																	htmlFor="time_slot_3_1"
																></label>
																<span>
																	{ __(
																		'Dates',
																		'product-delivery-date-for-woocommerce-lite'
																	) }
																</span>
															</div>
															<div className="el-radio el-radio-green">
																<input
																	type="radio"
																	name="prdd_recurring_chk"
																	id="time_slot_3_2"
																	value="weekdays"
																	checked={
																		'weekdays' ===
																		blockFor
																	}
																	onChange={ () =>
																		setBlockFor(
																			'weekdays'
																		)
																	}
																/>
																<label
																	className="el-radio-style"
																	htmlFor="time_slot_3_2"
																></label>
																<span>
																	{ __(
																		'Weekdays',
																		'product-delivery-date-for-woocommerce-lite'
																	) }
																</span>
															</div>
														</div>
													</div>
												</div>

												<div
													className="tm1-row ind_tm_slote_weekday"
													style={ vShow(
														'dates' === blockFor
													) }
												>
													<div className="col-left">
														<label>
															{ __(
																'Delivery Date:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="row-box-1">
															<div className="rb1-left">
																<InfoTip
																	text={ __(
																		'Block time slots for individual dates.',
																		'product-delivery-date-for-woocommerce-lite'
																	) }
																/>
															</div>
															<div className="rb1-right">
																<div className="rb1-row flx-center">
																	<div className="rb-col w-50">
																		<label>
																			{ __(
																				'Select Dates',
																				'product-delivery-date-for-woocommerce-lite'
																			) }
																		</label>
																		<MultiDatesPicker
																			tag="input"
																			type="text"
																			id="multidatepicker"
																			name="prdd_specific_date"
																			value={ [] }
																			onChange={
																				setDates
																			}
																			options={ {
																				dateFormat:
																					'm-d-y',
																				minDate: 0,
																			} }
																		/>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div
													className="tm1-row ind_tm_slote_spec_date"
													style={ vShow(
														'weekdays' === blockFor
													) }
												>
													<div className="col-left">
														<label>
															{ __(
																'Weekdays:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="row-box-1">
															<div className="rb1-left">
																<InfoTip
																	text={ __(
																		'Block time slots for weekdays.',
																		'product-delivery-date-for-woocommerce-lite'
																	) }
																/>
															</div>
															<div className="rb1-right">
																<div className="rb1-row flx-center">
																	<div className="rb-col w-50">
																		<label>
																			{ __(
																				'Select Weekdays:',
																				'product-delivery-date-for-woocommerce-lite'
																			) }
																		</label>
																		<ChoicesSelect
																			name="prdd_weekdays[]"
																			options={
																				weekdayOptions
																			}
																			value={
																				weekdays
																			}
																			onChange={
																				setWeekdays
																			}
																		/>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div className="tm1-row">
													<div className="col-left">
														<label>
															{ __(
																'Select Time Slots to Block:',
																'product-delivery-date-for-woocommerce-lite'
															) }
														</label>
													</div>
													<div className="col-right">
														<div className="row-box-1">
															<div className="rb1-left">
																<InfoTip
																	text={ __(
																		'This will list all the time slots which are created in General Settings or in Custom Delivery Settings.',
																		'product-delivery-date-for-woocommerce-lite'
																	) }
																/>
															</div>
															<div className="rb1-right">
																<div className="rb1-row flx-center">
																	<div
																		id="time_slot"
																		name="time_slot"
																	>
																		{ slots.map(
																			(
																				slot,
																				index
																			) => (
																				<table
																					className="prdd-form-table"
																					key={
																						index
																					}
																				>
																					<tbody>
																						<tr>
																							<td
																								style={ {
																									width: '380px',
																								} }
																							>
																								<table>
																									<tbody>
																										<tr>
																											<td>
																												{ __(
																													'From:',
																													'product-delivery-date-for-woocommerce-lite'
																												) }
																											</td>
																											<td>
																												{ slotSelect(
																													index,
																													'from_hrs',
																													__(
																														'Hours',
																														'product-delivery-date-for-woocommerce-lite'
																													),
																													'',
																													HOURS
																												) }
																											</td>
																											<td>
																												{ slotSelect(
																													index,
																													'from_min',
																													__(
																														'Minutes',
																														'product-delivery-date-for-woocommerce-lite'
																													),
																													'00',
																													MINUTES
																												) }
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
																												{ slotSelect(
																													index,
																													'to_hrs',
																													__(
																														'Hours',
																														'product-delivery-date-for-woocommerce-lite'
																													),
																													'0',
																													HOURS
																												) }
																											</td>
																											<td>
																												{ slotSelect(
																													index,
																													'to_min',
																													__(
																														'Minutes',
																														'product-delivery-date-for-woocommerce-lite'
																													),
																													'00',
																													MINUTES
																												) }
																											</td>
																										</tr>
																									</tbody>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			)
																		) }
																	</div>
																	<div
																		id="add_button"
																		name="add_button"
																	>
																		<input
																			type="button"
																			className="secondary-btn"
																			value={ __(
																				'Add Time Slot',
																				'product-delivery-date-for-woocommerce-lite'
																			) }
																			id="add_another"
																			onClick={
																				addSlot
																			}
																		/>
																	</div>
																</div>
																<div
																	className="rb1-row flx-center time-save-btn"
																	style={ vShow(
																		slots.length >
																			0
																	) }
																>
																	<div className="rb-col">
																		<input
																			type="submit"
																			role="button"
																			className="secondary-btn"
																			value={ __(
																				'Save Settings',
																				'product-delivery-date-for-woocommerce-lite'
																			) }
																		/>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</form>
										{ /* Add Time Slots - End */ }
									</div>

									<div className="add-more-link link-flx">
										<a
											className={
												'al-link' +
												( formOpen ? '' : ' collapsed' )
											}
											href="#col_opt_id_1"
											role="button"
											aria-expanded={
												formOpen ? 'true' : 'false'
											}
											aria-controls="col_opt_id_1"
											onClick={ ( e ) => {
												e.preventDefault();
												setFormOpen( ! formOpen );
											} }
										>
											<img
												src={
													pluginUrl +
													'/assets/images/icon-plus.svg'
												}
												alt="Icon"
											/>{ ' ' }
											{ __(
												'Add More',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</a>
									</div>
								</div>
								<div className="abulk-box">
									<select
										className="ib-small"
										value={ bulkAction }
										onChange={ ( e ) =>
											setBulkAction( e.target.value )
										}
									>
										<option value="select">
											{ __(
												'Bulk Action',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</option>
										<option value="unblock">
											{ __(
												'Unblock',
												'product-delivery-date-for-woocommerce-lite'
											) }
										</option>
									</select>
									<button
										className="trietary-btn reverse"
										type="button"
										onClick={ applyBulk }
									>
										{ __(
											'Apply',
											'product-delivery-date-for-woocommerce-lite'
										) }
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}
