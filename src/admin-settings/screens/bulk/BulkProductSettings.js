/* eslint-disable no-alert -- Same alert as the earlier jQuery code. */
/**
 * Bulk Product Settings page, markup of the former
 * includes/admin/views/bulk-product-settings/main.php.
 */
import { __, sprintf } from '@wordpress/i18n';
import { useEffect, useRef, useState } from '@wordpress/element';
import { apiGet, apiPostBatches, errorMessage } from '../../../shared/api';
import InfoTip from '../../../shared/components/InfoTip';
import ErrorMessage from '../../../shared/components/ErrorMessage';
import SavingLoader from '../../../shared/components/SavingLoader';
import Select2Search from '../../../shared/components/Select2Search';
import DeliverySettingsForm from '../../../product-metabox/DeliverySettingsForm';
import { vShow } from '../../hooks/useSettingsForm';

const ALL_PRODUCTS = {
	id: 'all_products',
	text: __( 'All Products', 'product-delivery-date-for-woocommerce-lite' ),
};
const ALL_CATEGORIES = {
	id: 'all_categories',
	text: __( 'All categories', 'product-delivery-date-for-woocommerce-lite' ),
};

const ids = ( items ) => items.map( ( item ) => item.id );

export default function BulkProductSettings() {
	const [ data, setData ] = useState( null );
	const [ products, setProducts ] = useState( [] );
	const [ categories, setCategories ] = useState( [] );
	const [ override, setOverride ] = useState( false );
	const [ saving, setSaving ] = useState( false );
	// Products saved so far, shown while a large selection is saved in batches.
	const [ progress, setProgress ] = useState( '' );
	const [ saved, setSaved ] = useState( false );
	const [ error, setError ] = useState( '' );
	const settings = useRef( {} );

	useEffect( () => {
		apiGet( 'bulk-product-settings' )
			.then( ( response ) => setData( response.data ) )
			.catch( ( e ) => setError( errorMessage( e ) ) );
	}, [] );

	const save = () => {
		if ( ! products.length && ! categories.length ) {
			window.alert(
				__(
					'Please select the products or categories and tags.',
					'product-delivery-date-for-woocommerce-lite'
				)
			);
			return;
		}

		setSaving( true );
		setSaved( false );
		setError( '' );
		apiPostBatches(
			'bulk-product-settings',
			{
				prdd_products: ids( products ),
				prdd_products_categories: ids( categories ),
				prdd_bulk_override: override ? 'on' : '',
				settings: settings.current,
			},
			( done, total ) =>
				done < total &&
				setProgress(
					sprintf(
						/* translators: 1: Products saved so far, 2: Number of products. */
						__(
							'Saving Changes… %1$d / %2$d',
							'product-delivery-date-for-woocommerce-lite'
						),
						done,
						total
					)
				)
		)
			.then( () => setSaved( true ) )
			.catch( ( e ) => setError( errorMessage( e ) ) )
			.finally( () => {
				setSaving( false );
				setProgress( '' );
				window.scrollTo( 0, 0 );
			} );
	};

	const saveButton = (
		<button type="button" className="prdd-bulk-setting" onClick={ save }>
			{ __(
				'Save Settings',
				'product-delivery-date-for-woocommerce-lite'
			) }
		</button>
	);

	return (
		<div id="prdd-bulk-settings-page" className="prdd-content-area">
			<SavingLoader show={ saving } text={ progress } />
			<div className="container-fluid bd-page-wrap as-page">
				<div className="pl-info-wrap" style={ vShow( saved ) }>
					<div className="row">
						<div className="col-md-12">
							<div
								className="alert alert-success alert-dismissible fade show"
								role="alert"
							>
								{ __(
									'The product delivery settings have been saved. You can view & edit them from the Edit product page of the products that you had selected.',
									'product-delivery-date-for-woocommerce-lite'
								) }
								<button
									type="button"
									className="close shown-in-middle"
									data-dismiss="alert"
									aria-label="Close"
									onClick={ () => setSaved( false ) }
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
				<div className="row">
					<form
						id="bulk_product_form_settings"
						onSubmit={ ( event ) => event.preventDefault() }
					>
						<div className="col-md-12">
							<div className="prdd-page-head phw-btn justify-content-between">
								<div className="float-left prdd-container">
									<h1>
										{ __(
											'Bulk Product Settings',
											'product-delivery-date-for-woocommerce-lite'
										) }
									</h1>
									<p>
										{ __(
											'Add delivery settings for multiple products together. Once the settings are saved, you will be able to see them & edit them from the Edit product page of the products that you have selected.',
											'product-delivery-date-for-woocommerce-lite'
										) }
									</p>
								</div>
								<div className="float-right">
									{ saveButton }
								</div>
							</div>
						</div>

						<div className="col-md-12">
							<div className="wbc-box">
								<div className="wbc-head d-flex justify-content-between align-items-center">
									<h4>
										{ __(
											'Multiple Products Delivery Settings',
											'product-delivery-date-for-woocommerce-lite'
										) }
									</h4>
								</div>
								<div className="wbc-content">
									<div className="tbl-mod-1">
										<div className="tm1-row">
											<div className="col-left">
												<label>
													{ __(
														'Products:',
														'product-delivery-date-for-woocommerce-lite'
													) }
												</label>
											</div>
											<div className="col-right">
												<div className="rc-flx-wrap">
													<InfoTip
														text={ __(
															'Please select product to apply the below settings to',
															'product-delivery-date-for-woocommerce-lite'
														) }
													/>
													<div className="bs-example invisible-field">
														<Select2Search
															type="products"
															id="prdd_products"
															name="prdd_products[]"
															style={ {
																width: '300px',
															} }
															className="ib-small chosen_select"
															placeholder={ __(
																'Select Products and/or Categories',
																'product-delivery-date-for-woocommerce-lite'
															) }
															allOption={
																ALL_PRODUCTS
															}
															onChange={
																setProducts
															}
														/>
													</div>
												</div>
											</div>
										</div>
										<div className="tbl-mod-1">
											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Products Categories And Tags:',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right">
													<div className="rc-flx-wrap">
														<InfoTip
															text={ __(
																'Please select product categories and tags to apply the below settings to',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<div className="bs-example invisible-field">
															<Select2Search
																type="categories"
																id="prdd_products_categories"
																name="prdd_products_categories[]"
																style={ {
																	width: '300px',
																} }
																className="ib-small chosen_category"
																placeholder={ __(
																	'Select Products and/or Categories',
																	'product-delivery-date-for-woocommerce-lite'
																) }
																allOption={
																	ALL_CATEGORIES
																}
																onChange={
																	setCategories
																}
															/>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div className="tbl-mod-1">
											<div className="tm1-row">
												<div className="col-left">
													<label>
														{ __(
															'Override Existing product Settings?',
															'product-delivery-date-for-woocommerce-lite'
														) }
													</label>
												</div>
												<div className="col-right d-flex">
													<div className="rc-flx-wrap flx-aln-center">
														<InfoTip
															text={ __(
																'Please select this checkbox if you want to replace the existing delivery settings with the new ones. If this checkbox is not selected, then it will retain the existing delivery settings on the products & update the new ones in it.',
																'product-delivery-date-for-woocommerce-lite'
															) }
														/>
														<label className="el-switch el-switch-green">
															<input
																type="checkbox"
																id="prdd_bulk_override"
																name="prdd_bulk_override"
																checked={
																	override
																}
																onChange={ (
																	event
																) =>
																	setOverride(
																		event
																			.target
																			.checked
																	)
																}
															/>
															<span className="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								{ data ? (
									<DeliverySettingsForm
										initialData={ data }
										isBulk
										bulkSelection={ {
											products: ids( products ),
											categories: ids( categories ),
											override,
										} }
										onChange={ ( payload ) => {
											settings.current = payload;
										} }
									/>
								) : null }
							</div>
						</div>
						<div className="ss-foot">
							<div className="col-md-12">{ saveButton }</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	);
}
