/**
 * REST client of the prdd-lite/admin/v1 namespace.
 *
 * The wp_rest nonce middleware is registered by WordPress core for the
 * wp-api-fetch script, so every request is cookie authenticated.
 */
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import config from './config';

const NAMESPACE = '/prdd-lite/admin/v1/';

/**
 * Routes of the Pro version used by the screens shown as a locked preview.
 * They are answered here with empty data, without a request, and writing
 * to them is refused.
 */
const PRO_PREVIEW = [
	{ pattern: /^settings\/block-time-slots/, data: () => [] },
	{ pattern: /^settings\/field-labels/, data: () => ( {} ) },
	{ pattern: /^settings\/subscriptions/, data: () => ( {} ) },
	{ pattern: /^integrations\//, data: () => ( {} ) },
	{
		pattern: /^bulk-product-settings/,
		data: () => config.bulk_preview || { values: {} },
	},
	{ pattern: /^search\//, data: () => [] },
	{ pattern: /^products\/\d+\//, data: () => ( {} ) },
];

/**
 * Answers a request to a Pro route.
 *
 * @param {Object} preview Matching PRO_PREVIEW item.
 * @param {Object} options apiFetch options.
 * @return {Promise<Object>} Response body.
 */
function proPreview( preview, options ) {
	if ( options.method && 'GET' !== options.method.toUpperCase() ) {
		return Promise.reject( {
			code: 'prdd_lite_pro_feature',
			message: __(
				'This feature is available in the Pro version.',
				'product-delivery-date-for-woocommerce-lite'
			),
		} );
	}
	const data = preview.data();
	// The search routes answer with the bare list.
	return Promise.resolve(
		Array.isArray( data ) && preview.pattern.test( 'search/' )
			? data
			: { success: true, data, message: '' }
	);
}

/**
 * Performs a request to the plugin REST API.
 *
 * @param {string} path    Route relative to the namespace.
 * @param {Object} options apiFetch options.
 * @return {Promise<Object>} Response body.
 */
export function prddApiFetch( path, options = {} ) {
	const route = path.replace( /^\/+/, '' );
	const preview = PRO_PREVIEW.find( ( item ) => item.pattern.test( route ) );
	if ( preview ) {
		return proPreview( preview, options );
	}

	return apiFetch( {
		path: NAMESPACE + path.replace( /^\/+/, '' ),
		...options,
	} );
}

export const apiGet = ( path ) => prddApiFetch( path );

export const apiPost = ( path, data = {} ) =>
	prddApiFetch( path, { method: 'POST', data } );

export const apiDelete = ( path, data = {} ) =>
	prddApiFetch( path, { method: 'DELETE', data } );

/**
 * Posts a bulk save, one batch of products per request. Each response has
 * { total, processed, next }, next being 0 after the last batch.
 *
 * @param {string}   path       Route relative to the namespace.
 * @param {Object}   data       Body, sent with each batch.
 * @param {Function} onProgress Receives the products saved so far and the total.
 * @param {number}   after      Last id of the previous batch.
 * @param {number}   done       Products saved by the previous batches.
 * @return {Promise<Object>} Response body of the last batch.
 */
export function apiPostBatches( path, data, onProgress, after = 0, done = 0 ) {
	return apiPost( path, { ...data, after } ).then( ( response ) => {
		const result = response.data || {};
		const saved = done + ( result.processed || 0 );
		if ( onProgress ) {
			onProgress( saved, result.total || 0 );
		}
		return result.next
			? apiPostBatches( path, data, onProgress, result.next, saved )
			: response;
	} );
}

/**
 * Human readable message of a failed request.
 *
 * @param {Object} error apiFetch error.
 * @return {string} Message.
 */
export function errorMessage( error ) {
	if ( error && error.message ) {
		return error.message;
	}
	return __(
		'Something went wrong. Please try again.',
		'product-delivery-date-for-woocommerce-lite'
	);
}
