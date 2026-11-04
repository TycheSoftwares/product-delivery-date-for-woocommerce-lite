/**
 * Mounts the delivery settings metabox on the product edit screen.
 */
import { createRoot, render } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';
import ProductMetabox from './ProductMetabox';

domReady( () => {
	const root = document.getElementById( 'prdd-product-metabox-root' );
	const data = window.prddLiteProductMetabox;
	if ( ! root || ! data ) {
		return;
	}

	const app = <ProductMetabox initialData={ data } />;
	if ( createRoot ) {
		createRoot( root ).render( app );
	} else {
		render( app, root );
	}
} );
