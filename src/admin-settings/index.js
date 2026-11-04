/**
 * Entry point of the Product Delivery Date admin pages.
 */
import { createRoot } from '@wordpress/element';
import App from './App';
import mapLegacyUrl from './legacy';

const root = document.getElementById( 'prdd-admin-settings-root' );

if ( root ) {
	mapLegacyUrl();
	createRoot( root ).render( <App /> );
}
