/**
 * Delivery settings metabox of the product edit screen. The settings are
 * saved with the product: every change is written as JSON to the hidden
 * #prdd_metabox_data field read by PRDD_Lite_Product_Metabox_React::process_product_meta().
 */
import { useCallback } from '@wordpress/element';
import DeliverySettingsForm from './DeliverySettingsForm';

export default function ProductMetabox( { initialData } ) {
	const onChange = useCallback( ( payload ) => {
		const field = document.getElementById( 'prdd_metabox_data' );
		if ( field ) {
			field.value = JSON.stringify( payload );
		}
	}, [] );

	return (
		<DeliverySettingsForm
			initialData={ initialData }
			onChange={ onChange }
		/>
	);
}
