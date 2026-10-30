/**
 * "Settings" tab of the delivery settings form.
 */
import { __ } from '@wordpress/i18n';
import InfoTip from '../../shared/components/InfoTip';
import DatePicker from '../../shared/components/DatePicker';
import MultiDatesPicker from '../../shared/components/MultiDatesPicker';
import { Switch, isOn } from '../components/fields';
import { ProOnly } from '../../shared/components/ProNotice';

function SwitchRow( {
	label,
	tip,
	id,
	form,
	className = 'tm1-row align-items-center',
	children,
} ) {
	return (
		<div className={ className }>
			<div className="col-left">
				<label>{ label }</label>
			</div>
			<div className="col-right">
				<div className="rc-flx-wrap flx-aln-center">
					<InfoTip text={ tip } />
					<Switch
						id={ id }
						checked={ isOn( form.values[ id ] ) }
						onChange={ ( value ) => form.set( id, value ) }
					>
						{ children }
					</Switch>
				</div>
			</div>
		</div>
	);
}

function TextRow( { label, tip, id, form } ) {
	return (
		<div className="tm1-row align-items-center">
			<div className="col-left">
				<label>{ label }</label>
			</div>
			<div className="col-right">
				<div className="rc-flx-wrap flx-aln-center">
					<InfoTip text={ tip } />
					<input
						className="ib-lg"
						type="text"
						id={ id }
						name={ id }
						value={ form.values[ id ] }
						onChange={ ( event ) =>
							form.set( id, event.target.value )
						}
					/>
				</div>
			</div>
		</div>
	);
}

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

export default function SettingsTab( { form } ) {
	const { values, set } = form;
	const holidays = splitDates( values.prdd_product_holiday );

	return (
		<>
			<div className="d-flex justify-content-between">
				<h4>
					{ __(
						'Settings',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</h4>
			</div>
			<ProOnly>
				<SwitchRow
					form={ form }
					className="tm1-row align-items-center d-b-color"
					id="prdd_enable_inline_calendar"
					label={ __(
						'Show calendar always visible:',
						'product-delivery-date-for-woocommerce-lite'
					) }
					tip={ __(
						'Enable Inline Calendar on Products Page.',
						'product-delivery-date-for-woocommerce-lite'
					) }
				/>
			</ProOnly>
			<SwitchRow
				form={ form }
				id="prdd_delivery_field_mandatory"
				label={ __(
					'Mandatory Fields:',
					'product-delivery-date-for-woocommerce-lite'
				) }
				tip={ __(
					'Make the selection of a delivery date mandatory on the Product page',
					'product-delivery-date-for-woocommerce-lite'
				) }
			/>
			<ProOnly>
				<SwitchRow
					form={ form }
					id="prdd_hide_add_to_cart_button"
					label={ __(
						'Hide Add to Cart button:',
						'product-delivery-date-for-woocommerce-lite'
					) }
					tip={ __(
						'Hide Add to Cart button on front end product details page until a delivery date and/or time slot is selected.',
						'product-delivery-date-for-woocommerce-lite'
					) }
				/>
				<TextRow
					form={ form }
					id="prdd_deliery_date_label"
					label={ __(
						'Delivery date label:',
						'product-delivery-date-for-woocommerce-lite'
					) }
					tip={ __(
						'Custom label for delivery date field for this product.',
						'product-delivery-date-for-woocommerce-lite'
					) }
				/>
				<TextRow
					form={ form }
					id="prdd_time_date_label"
					label={ __(
						'Time slot label:',
						'product-delivery-date-for-woocommerce-lite'
					) }
					tip={ __(
						'Custom label for delivery time field for this product.',
						'product-delivery-date-for-woocommerce-lite'
					) }
				/>
			</ProOnly>
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
					<div className="rc-flx-wrap">
						<InfoTip
							text={ __(
								'Select dates for which the delivery will be completely disabled only for this product. Please click on the date in calendar to add or delete the date from the holiday list.',
								'product-delivery-date-for-woocommerce-lite'
							) }
						/>
						<MultiDatesPicker
							tag="input"
							type="text"
							className="ib-lg"
							name="prdd_product_holiday"
							id="prdd_product_holiday"
							value={ holidays }
							onChange={ ( dates ) =>
								set( 'prdd_product_holiday', dates.join( ',' ) )
							}
							options={ { showButtonPanel: true } }
						/>
					</div>
				</div>
			</div>
			<ProOnly>
				<SwitchRow
					form={ form }
					className="tm1-row"
					id="prdd_hide_delivery_fields_on_shop"
					label={ __(
						'Hide Delivery date picker & time slots on Shop/archives page:',
						'product-delivery-date-for-woocommerce-lite'
					) }
					tip={ __(
						"If you don't want to show delivery date & time only for this product on Shop/archives page, then please check this setting. The delivery date & time fields will continue to show for other products",
						'product-delivery-date-for-woocommerce-lite'
					) }
				>
					<p>
						{ __(
							'Works only for Simple products.',
							'product-delivery-date-for-woocommerce-lite'
						) }
					</p>
				</SwitchRow>
				<div className="tm1-row align-items-center">
					<div className="col-left">
						<label>
							{ __(
								'Default date:',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</label>
					</div>
					<div className="col-right">
						<div className="rc-flx-wrap flx-aln-center">
							<InfoTip
								text={ __(
									'Default date to add in the order meta data even if delivery is disabled for this product.',
									'product-delivery-date-for-woocommerce-lite'
								) }
							/>
							<DatePicker
								className="ib-lg datepicker"
								name="prdd_default_date_for_delivery_disabled_product"
								id="prdd_default_date_for_delivery_disabled_product"
								value={
									values.prdd_default_date_for_delivery_disabled_product
								}
								onChange={ ( value ) =>
									set(
										'prdd_default_date_for_delivery_disabled_product',
										value
									)
								}
								options={ {
									dateFormat: 'yy-mm-d',
									minDate: 0,
									changeMonth: true,
									changeYear: true,
									showButtonPanel: true,
								} }
							/>
						</div>
					</div>
				</div>
			</ProOnly>
		</>
	);
}
