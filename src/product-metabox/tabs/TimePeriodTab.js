/**
 * "Delivery Time Period" tab of the delivery settings form.
 */
import { __ } from '@wordpress/i18n';
import InfoTip from '../../shared/components/InfoTip';
import DatePicker from '../../shared/components/DatePicker';
import { CutOffTime } from '../components/fields';
import { ProOnly } from '../../shared/components/ProNotice';

const RANGE_OPTIONS = { dateFormat: 'd-m-yy', monthsToShow: 1 };

function TextRow( {
	label,
	tip,
	id,
	form,
	className = 'tm1-row align-items-center',
} ) {
	return (
		<div className={ className }>
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

function CutOffRow( {
	label,
	tip,
	hours,
	minutes,
	form,
	minutesPlaceholder = '',
} ) {
	const select = ( id, placeholder = '' ) => ( {
		id,
		name: id,
		placeholder,
		value: form.values[ id ],
		onChange: ( value ) => form.set( id, value ),
	} );

	return (
		<div className="tm1-row">
			<div className="col-left">
				<label>{ label }</label>
			</div>
			<div className="col-right">
				<div className="rc-flx-wrap">
					<InfoTip text={ tip } />
					<CutOffTime
						hours={ select( hours ) }
						minutes={ select( minutes, minutesPlaceholder ) }
					/>
				</div>
			</div>
		</div>
	);
}

function OrRow() {
	return (
		<div className="tm1-row align-items-center">
			<div className="col-left"></div>
			<div className="col-right">
				<div className="rc-flx-wrap flx-aln-center">
					{ __( 'OR', 'product-delivery-date-for-woocommerce-lite' ) }
				</div>
			</div>
		</div>
	);
}

function DateRow( { label, tip, id, form } ) {
	return (
		<div className="tm1-row align-items-center">
			<div className="col-left">
				<label>{ label }</label>
			</div>
			<div className="col-right">
				<div className="rc-flx-wrap flx-aln-center">
					<InfoTip text={ tip } />
					<DatePicker
						className="ib-lg"
						name={ id }
						id={ id }
						value={ form.values[ id ] }
						onChange={ ( value ) => form.set( id, value ) }
						options={ RANGE_OPTIONS }
					/>
				</div>
			</div>
		</div>
	);
}

export default function TimePeriodTab( { form } ) {
	const { values, set, slot } = form;
	const minimumTip = __(
		'Enable Delivery after X number of hours from current time. The customer can select a delivery date that is available only after the minimum hours that are entered here. For example, if you need 1 day advance notice for a delivery, enter 24 here.',
		'product-delivery-date-for-woocommerce-lite'
	);

	return (
		<>
			<div className="d-flex justify-content-between">
				<h4>
					{ __(
						'Delivery Time Period',
						'product-delivery-date-for-woocommerce-lite'
					) }
				</h4>
			</div>
			<TextRow
				form={ form }
				className="tm1-row align-items-center d-b-color"
				id="prdd_minimum_number_days"
				label={ __(
					'Minimum Delivery preparation time (in hours):',
					'product-delivery-date-for-woocommerce-lite'
				) }
				tip={ minimumTip }
			/>
			<ProOnly>
				<OrRow />
				<CutOffRow
					form={ form }
					label={ __(
						'Same day delivery cut-off time:',
						'product-delivery-date-for-woocommerce-lite'
					) }
					tip={ minimumTip }
					hours="prdd_same_day_cutoff_hrs"
					minutes="prdd_same_day_cutoff_mins"
				/>
				<CutOffRow
					form={ form }
					label={ __(
						'Same day delivery cut-off time for free delivery:',
						'product-delivery-date-for-woocommerce-lite'
					) }
					tip={ __(
						'Allow free delivery on the same day if the order is placed before the set time.',
						'product-delivery-date-for-woocommerce-lite'
					) }
					hours="prdd_same_day_cutoff_hrs_free"
					minutes="prdd_same_day_cutoff_mins_free"
				/>
				<div className="tm1-row">
					<div className="col-left">
						<label>
							{ __(
								'Same day delivery cut-off charges:',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</label>
					</div>
					<div className="col-right">
						<div className="rc-flx-wrap">
							<InfoTip
								text={ __(
									'Same day delivery charges.',
									'product-delivery-date-for-woocommerce-lite'
								) }
							/>
							<input
								className="ib-lg"
								type="text"
								id="prdd_same_day_cut_off_fee"
								name="prdd_same_day_cut_off_fee"
								value={ values.prdd_same_day_cut_off_fee }
								onChange={ ( event ) =>
									set(
										'prdd_same_day_cut_off_fee',
										event.target.value
									)
								}
							/>
						</div>
					</div>
				</div>
				<CutOffRow
					form={ form }
					label={ __(
						'Next day delivery cut-off time:',
						'product-delivery-date-for-woocommerce-lite'
					) }
					tip={ __(
						'Disable next day for delivery after the set cut-off time. The cut-off time will be calculated based on the WordPress timezone.',
						'product-delivery-date-for-woocommerce-lite'
					) }
					hours="prdd_next_day_cutoff_hrs"
					minutes="prdd_next_day_cutoff_mins"
					minutesPlaceholder="00"
				/>
				<TextRow
					form={ form }
					id="nextday_charge"
					label={ __(
						'Next day Delivery charge:',
						'product-delivery-date-for-woocommerce-lite'
					) }
					tip={ __(
						'Charges for next day delivery',
						'product-delivery-date-for-woocommerce-lite'
					) }
				/>
				{ slot( 'before_range_selection_radio' ) }
				<div className="tm1-row flx-center">
					<div className="col-left">
						<label>
							{ __(
								'Delivery Type:',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</label>
					</div>
					<div className="col-right">
						<span className="el-radio el-radio-green">
							<input
								type="radio"
								name="prdd_date_range_type"
								id="prdd_date_range_type"
								value="fixed_range"
								checked={
									'fixed_range' ===
									values.prdd_date_range_type
								}
								onChange={ () =>
									set( 'prdd_date_range_type', 'fixed_range' )
								}
							/>
							<label
								className="el-radio-style"
								htmlFor="prdd_date_range_type"
							></label>
						</span>
						<label
							htmlFor="prdd_date_range_type"
							style={ { display: 'inline' } }
						>
							{ __(
								'Fixed delivery period by dates ( e.g. March 1 to October 31)',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</label>
						<div style={ { marginBottom: '5px' } }></div>
						<span className="el-radio el-radio-green">
							<input
								type="radio"
								name="prdd_date_range_type"
								id="prdd_date_range_type_2"
								value="all_year"
								checked={
									'fixed_range' !==
									values.prdd_date_range_type
								}
								onChange={ () =>
									set( 'prdd_date_range_type', 'all_year' )
								}
							/>
							<label
								className="el-radio-style"
								htmlFor="prdd_date_range_type_2"
							></label>
						</span>
						<label htmlFor="prdd_date_range_type_2">
							{ __(
								'Deliver all year round',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</label>
					</div>
				</div>
			</ProOnly>
			{ slot( 'before_number_of_dates' ) }
			<TextRow
				form={ form }
				id="prdd_maximum_number_days"
				label={ __(
					'Number of Dates to choose:',
					'product-delivery-date-for-woocommerce-lite'
				) }
				tip={ __(
					'The maximum number of delivery dates you want to be available for your customers to choose from. For example, if you take only 2 months delivery in advance, enter 60 here.',
					'product-delivery-date-for-woocommerce-lite'
				) }
			/>
			{ slot( 'before_start_date_range' ) }
			<ProOnly>
				<OrRow />
				<DateRow
					form={ form }
					id="prdd_start_date_range"
					label={ __(
						'Deliveries start on:',
						'product-delivery-date-for-woocommerce-lite'
					) }
					tip={ __(
						'The start date of the deliverable block. For e.g. if you want to take deliveries for the period of March to october, then the date here should be March 1',
						'product-delivery-date-for-woocommerce-lite'
					) }
				/>
				{ slot( 'before_end_date_range' ) }
				<DateRow
					form={ form }
					id="prdd_end_date_range"
					label={ __(
						'Deliveries end on:',
						'product-delivery-date-for-woocommerce-lite'
					) }
					tip={ __(
						'The end date of the deliverable block. For e.g. if you want to take deliveries for the period of March to october, then the date here should be October 31.',
						'product-delivery-date-for-woocommerce-lite'
					) }
				/>
			</ProOnly>
		</>
	);
}
