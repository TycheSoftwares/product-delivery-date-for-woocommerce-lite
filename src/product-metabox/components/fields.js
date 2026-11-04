/**
 * Small pieces of markup repeated through the delivery settings form.
 */
import { __ } from '@wordpress/i18n';

const HOURS = Array.from( { length: 24 }, ( v, i ) => String( i ) );
const MINUTES = Array.from( { length: 60 }, ( v, i ) =>
	i < 10 ? '0' + i : String( i )
);

/**
 * Inline style of the elements shown / hidden by the former jQuery code.
 *
 * @param {boolean} visible Whether the element is visible.
 * @param {string}  value   Display value when visible, none to keep the CSS one.
 * @return {Object} Style.
 */
export const display = ( visible, value ) => {
	if ( ! visible ) {
		return { display: 'none' };
	}
	return value ? { display: value } : undefined;
};

/**
 * Checkbox stored as 'on' / ''.
 *
 * @param {string} value Stored value.
 * @return {boolean} Whether it is checked.
 */
export const isOn = ( value ) => 'on' === value || true === value;

export function Switch( { id, name = id, checked, onChange, children } ) {
	return (
		<label className="el-switch el-switch-green">
			<input
				type="checkbox"
				id={ id }
				name={ name }
				checked={ checked }
				onChange={ ( event ) =>
					onChange( event.target.checked ? 'on' : '' )
				}
			/>
			<span className="el-switch-style"></span>
			{ children }
		</label>
	);
}

/**
 * Hours select.
 *
 * @param {Object}   props
 * @param {string}   props.placeholder Value of the "Hours" option.
 * @param {string}   props.value       Selected value.
 * @param {Function} props.onChange    Receives the selected value.
 */
export function HourSelect( { placeholder = '', value, onChange, ...props } ) {
	return (
		<select
			value={ value }
			onChange={ ( event ) => onChange( event.target.value ) }
			{ ...props }
		>
			<option value={ placeholder }>
				{ __( 'Hours', 'product-delivery-date-for-woocommerce-lite' ) }
			</option>
			{ HOURS.map( ( hour ) => (
				<option key={ hour } value={ hour }>
					{ hour }
				</option>
			) ) }
		</select>
	);
}

/**
 * Minutes select.
 *
 * @param {Object}   props
 * @param {string}   props.placeholder Value of the "Minutes" option.
 * @param {string}   props.value       Selected value.
 * @param {Function} props.onChange    Receives the selected value.
 */
export function MinuteSelect( {
	placeholder = '',
	value,
	onChange,
	...props
} ) {
	return (
		<select
			value={ value }
			onChange={ ( event ) => onChange( event.target.value ) }
			{ ...props }
		>
			<option value={ placeholder }>
				{ __(
					'Minutes',
					'product-delivery-date-for-woocommerce-lite'
				) }
			</option>
			{ MINUTES.map( ( minute ) => (
				<option key={ minute } value={ minute }>
					{ minute }
				</option>
			) ) }
		</select>
	);
}

/**
 * Hours and minutes selects in the nested tables of the PHP template.
 *
 * @param {Object}  props
 * @param {Element} props.hours   Hours select.
 * @param {Element} props.minutes Minutes select.
 */
export function CutOffTime( { hours, minutes } ) {
	return (
		<div>
			<table className="prdd-form-table transparent-table">
				<tbody>
					<tr>
						<td>
							<table>
								<tbody>
									<tr className="mb-1">
										<td>
											<HourSelect { ...hours } />
											<MinuteSelect { ...minutes } />
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	);
}
