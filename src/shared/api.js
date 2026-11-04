/**
 * REST client of the prdd-lite/admin/v1 namespace.
 *
 * The wp_rest nonce middleware is registered by WordPress core for the
 * wp-api-fetch script, so every request is cookie authenticated.
 */
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';

const NAMESPACE = '/prdd-lite/admin/v1/';

/**
 * Performs a request to the plugin REST API.
 *
 * @param {string} path    Route relative to the namespace.
 * @param {Object} options apiFetch options.
 * @return {Promise<Object>} Response body.
 */
export function prddApiFetch( path, options = {} ) {
	return apiFetch( {
		path: NAMESPACE + path.replace( /^\/+/, '' ),
		...options,
	} );
}

export const apiGet = ( path ) => prddApiFetch( path );

export const apiPost = ( path, data = {} ) =>
	prddApiFetch( path, { method: 'POST', data } );

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
