/**
 * Keeps the page usable when a screen throws: an error message is shown
 * instead of an empty page. Remounted on route change, so the other tabs
 * keep working.
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default class ErrorBoundary extends Component {
	constructor( props ) {
		super( props );
		this.state = { failed: false };
	}

	static getDerivedStateFromError() {
		return { failed: true };
	}

	componentDidCatch( error ) {
		// eslint-disable-next-line no-console
		console.error( error );
	}

	render() {
		if ( this.state.failed ) {
			return (
				<div className="notice notice-error">
					<p>
						{ __(
							'Something went wrong while loading this page. Please reload the page and try again.',
							'product-delivery-date-for-woocommerce-lite'
						) }
					</p>
				</div>
			);
		}
		return this.props.children;
	}
}
