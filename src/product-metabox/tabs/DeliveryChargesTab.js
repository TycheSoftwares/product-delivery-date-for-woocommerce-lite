/* eslint-disable no-alert -- Same alert / confirm dialogs as the earlier jQuery code. */
/**
 * "Delivery Charges" tab, markup of the former prdd-special-delivery-price.php
 * tab and behaviour of prdd_save_special_delivery() of custom.js.
 */
import { __ } from '@wordpress/i18n';
import { useRef, useState } from '@wordpress/element';
import InfoTip from '../../shared/components/InfoTip';
import DatePicker from '../../shared/components/DatePicker';

const TRASH_STYLE = { color: 'rgb(220, 53, 69)' };
const EMPTY = { weekday: '', date: '', price: '', id: '' };

/**
 * Swaps the first and last parts of a dash separated date
 * ( d-m-Y <=> Y-m-d ).
 *
 * @param {string} value Date.
 * @return {string} Date.
 */
const swapDate = ( value ) => {
	const parts = ( value || '' ).split( '-' );
	return 3 === parts.length
		? `${ parts[ 2 ] }-${ parts[ 1 ] }-${ parts[ 0 ] }`
		: value || '';
};

export default function DeliveryChargesTab( { form } ) {
	const { data, isBulk, specialPrices, setSpecialPrices } = form;
	const [ fields, setFields ] = useState( EMPTY );
	const [ editing, setEditing ] = useState( null );
	const count = useRef( 0 );
	const weekdays = data.weekdays || [];

	const cancel = () => {
		setFields( EMPTY );
		setEditing( null );
	};

	const weekdayLabel = ( value ) => {
		const day = weekdays.find( ( item ) => item.value === value );
		return day ? day.label : '';
	};

	const save = () => {
		count.current++;
		if ( '' === fields.weekday && '' === fields.date ) {
			window.alert(
				__(
					'Please select weekday or date for delivery charges',
					'product-delivery-date-for-woocommerce-lite'
				)
			);
			return;
		}
		if ( '' === fields.price ) {
			window.alert(
				__(
					'Price cannot be blank.',
					'product-delivery-date-for-woocommerce-lite'
				)
			);
			return;
		}

		const body = {
			weekday: fields.weekday,
			date: fields.date,
			price: fields.price,
			id: fields.id,
		};

		if ( ! isBulk ) {
			form.request( 'special-prices', body, ( prices ) => {
				setSpecialPrices( prices );
				cancel();
			} );
			return;
		}

		form.bulkRequest(
			'bulk-product-settings/special-prices',
			{ ...body, count: count.current },
			() => {
				const row = {
					id: fields.id,
					weekday: fields.weekday,
					date: '' !== fields.weekday ? '' : swapDate( fields.date ),
					price: fields.price,
					label:
						'' !== fields.weekday
							? weekdayLabel( fields.weekday )
							: swapDate( fields.date ),
					price_text: ( data.currency_symbol || '' ) + fields.price,
				};
				if ( null !== editing ) {
					setSpecialPrices(
						specialPrices.map( ( item, index ) =>
							index === editing ? row : item
						)
					);
				} else {
					setSpecialPrices( [
						...specialPrices,
						{ ...row, id: String( specialPrices.length ) },
					] );
				}
				cancel();
			}
		);
	};

	const edit = ( row, index ) => {
		setEditing( index );
		setFields( {
			weekday: '' !== row.date ? '' : row.weekday,
			date: '' !== row.date ? swapDate( row.date ) : '',
			price: String( row.price ),
			id: isBulk ? String( index ) : String( row.id ),
		} );
	};

	const remove = ( row, index ) => {
		if (
			! window.confirm(
				__(
					'Are you sure you want to delete this delivery charge?',
					'product-delivery-date-for-woocommerce-lite'
				)
			)
		) {
			// eslint-disable-line no-alert
			return;
		}
		if ( isBulk ) {
			setSpecialPrices(
				specialPrices.filter( ( item, i ) => i !== index )
			);
			return;
		}
		form.request(
			'special-prices/delete',
			{ id: row.id },
			setSpecialPrices
		);
	};

	const removeAll = () => {
		if (
			! window.confirm(
				__(
					'Are you sure you want to delete all the delivery charges?',
					'product-delivery-date-for-woocommerce-lite'
				)
			)
		) {
			// eslint-disable-line no-alert
			return;
		}
		if ( isBulk ) {
			setSpecialPrices( [] );
			return;
		}
		form.request( 'special-prices/clear', {}, () =>
			setSpecialPrices( [] )
		);
	};

	return (
		<div className="row">
			<div className="col-md-12">
				<div className="d-flex justify-content-between heading-border mb-20">
					<h4>
						{ __(
							'Delivery Charges',
							'product-delivery-date-for-woocommerce-lite'
						) }
					</h4>
				</div>
			</div>
			<div className="col-md-5">
				<label>
					{ __(
						'Select Days:',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</label>
				<div className="rc-flx-wrap flx-aln-center">
					<select
						name="special_delivery_weekday"
						id="special_delivery_weekday"
						value={ fields.weekday }
						disabled={ '' !== fields.date }
						onChange={ ( event ) =>
							setFields( {
								...fields,
								weekday: event.target.value,
								date:
									'' !== event.target.value
										? ''
										: fields.date,
							} )
						}
					>
						<option value="">
							{ __(
								'Select Weekday',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</option>
						{ weekdays.map( ( day ) => (
							<option key={ day.value } value={ day.value }>
								{ day.label }
							</option>
						) ) }
					</select>
					<InfoTip
						className="tt-info ml-2"
						text={ __(
							'Select weekday to set delivery charge for',
							'product-delivery-date-for-woocommerce-lite'
						) }
					/>
				</div>
			</div>
			<div className="col-md-4">
				<label>
					{ __(
						'Select Date:',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</label>
				<div className="rc-flx-wrap flx-aln-center">
					<DatePicker
						className="ib-sm"
						name="special_delivery_date"
						id="special_delivery_date"
						value={ fields.date }
						disabled={ '' !== fields.weekday }
						onChange={ ( value ) =>
							setFields( {
								...fields,
								date: value,
								weekday: '' !== value ? '' : fields.weekday,
							} )
						}
						options={ { dateFormat: 'd-mm-yy', monthsToShow: 1 } }
					/>
					<InfoTip
						className="tt-info ml-2"
						text={ __(
							'Select date to set delivery charge for',
							'product-delivery-date-for-woocommerce-lite'
						) }
					/>
				</div>
			</div>
			<div className="col-md-3">
				<label>
					{ __(
						'Delivery Charges:',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</label>
				<div className="rc-flx-wrap flx-aln-center">
					<input
						className="ib-sm"
						type="text"
						name="special_delivery_price"
						id="special_delivery_price"
						value={ fields.price }
						onChange={ ( event ) =>
							setFields( {
								...fields,
								price: event.target.value,
							} )
						}
					/>
					<InfoTip
						className="tt-info ml-2"
						text={ __(
							'Set delivery charge for the selected day / date',
							'product-delivery-date-for-woocommerce-lite'
						) }
					/>
				</div>
			</div>
			<div className="col-md-12 mt-3">
				<input
					type="button"
					className="trietary-btn reverse"
					id="save_special_delivery"
					value={
						null !== editing
							? __(
									'Update Delivery Charge',
									'product-delivery-date-for-woocommerce-lite'
							  )
							: __(
									'Add Delivery Charges',
									'product-delivery-date-for-woocommerce-lite'
							  )
					}
					onClick={ save }
				/>{ ' ' }
				<input
					type="button"
					className="trietary-btn reverse"
					id="cancel_special_delivery"
					value={ __(
						'Cancel',
						'product-delivery-date-for-woocommerce-lite'
					) }
					style={ null !== editing ? undefined : { display: 'none' } }
					onClick={ cancel }
				/>
				<input
					type="hidden"
					name="prdd_special_delivery_id"
					id="prdd_special_delivery_id"
					value={ fields.id }
				/>
			</div>
			<div className="col-md-12 mt-3">
				<div id="special_delivery_price_table">
					<p>
						{ __(
							'Delivery Charges',
							'product-delivery-date-for-woocommerce-lite'
						) }{ ' ' }
						<a
							style={ { float: 'right' } }
							href="#"
							className="delete_all_special_delivery"
							onClick={ ( event ) => {
								event.preventDefault();
								removeAll();
							} }
						>
							{ ' ' }
							{ __(
								'Delete All Delivery Charges',
								'product-delivery-date-for-woocommerce-lite'
							) }{ ' ' }
						</a>
					</p>
					<table
						className="wp-list-table widefat fixed posts"
						cellSpacing="0"
						id="list_special_deliveries"
						style={ { borderRadius: '5px' } }
					>
						<tbody>
							<tr>
								<th>
									{ ' ' }
									{ __(
										'Day / Date',
										'product-delivery-date-for-woocommerce-lite'
									) }{ ' ' }
								</th>
								<th>
									{ ' ' }
									{ __(
										'Delivery Charges',
										'product-delivery-date-for-woocommerce-lite'
									) }
								</th>
								<th>
									{ ' ' }
									{ __(
										'Actions',
										'product-delivery-date-for-woocommerce-lite'
									) }{ ' ' }
								</th>
							</tr>
							{ specialPrices.map( ( row, index ) => (
								<tr
									key={ `${ row.id }_${ index }` }
									id={ `row_${ row.id }` }
									className={
										index === editing ? 'Edited' : undefined
									}
								>
									<td>
										{ '' !== row.label ? row.label : ' ' }
									</td>
									{ undefined !== row.price_text ? (
										<td>{ row.price_text }</td>
									) : (
										<td
											dangerouslySetInnerHTML={ {
												__html: row.price_html,
											} }
										/>
									) }
									<td className="tbl-action-col">
										{ ' ' }
										<a
											href="#"
											className="special_delivery_edit_block"
											onClick={ ( event ) => {
												event.preventDefault();
												edit( row, index );
											} }
										>
											{ ' ' }
											<span
												className="dashicons dashicons-edit"
												title={ __(
													'Edit Delivery Charges',
													'product-delivery-date-for-woocommerce-lite'
												) }
											></span>
										</a>{ ' ' }
										<a
											href="#"
											className="special_delivery_delete_block"
											onClick={ ( event ) => {
												event.preventDefault();
												remove( row, index );
											} }
										>
											{ ' ' }
											<span
												className="dashicons dashicons-trash"
												style={ TRASH_STYLE }
												title={ __(
													'Delete Delivery Charges',
													'product-delivery-date-for-woocommerce-lite'
												) }
											></span>
										</a>{ ' ' }
									</td>
								</tr>
							) ) }
						</tbody>
					</table>
				</div>
			</div>
		</div>
	);
}
