/**
 * Maps the URLs of the earlier admin pages ( ?action=settings#/holidays ) to
 * the hash routes of the app, so bookmarks and links in other plugins keep
 * working.
 */
const ACTIONS = {
	settings: '/settings',
	block_timeslot_settings: '/settings/block-time-slot',
	'block-time-slot': '/settings/block-time-slot',
	holidays: '/settings/holidays',
	appearance: '/appearance',
	integrations: '/integrations',
	calendar_sync_settings: '/integrations',
	'bulk-settings': '/bulk-product-settings',
	bulk_product_settings: '/bulk-product-settings',
	'bulk-product-settings': '/bulk-product-settings',
	'view-deliveries': '/view-deliveries',
	// Tabs of the earlier Lite settings page.
	labels: '/appearance/field-labels',
	prdd_google_calendar_sync: '/integrations',
	upgrade_to_pro_page: '/integrations',
};

/**
 * Query args of the View Deliveries page, kept in the query of its route.
 */
const DELIVERIES_ARGS = [
	'prdd_view',
	'status',
	'paged',
	'orderby',
	'order',
	'start-date',
	'end-date',
	'order-status',
];

/**
 * Rewrites the current URL when it uses a legacy `action` query arg.
 */
export default function mapLegacyUrl() {
	const url = new URL( window.location.href );
	const action = url.searchParams.get( 'action' );

	if ( ! action || ! ACTIONS[ action ] ) {
		return;
	}

	const base = ACTIONS[ action ];
	const sub = url.hash.replace( /^#\/?/, '' ).replace( /\/$/, '' );
	let route = base;

	if ( sub && ! base.endsWith( sub ) ) {
		route = `${ base }/${ sub }`;
	}

	let search = '';
	if ( '/view-deliveries' === base ) {
		const args = new URLSearchParams();
		DELIVERIES_ARGS.forEach( ( key ) => {
			const value = url.searchParams.get( key );
			if ( value ) {
				args.set( key, value );
			}
			url.searchParams.delete( key );
		} );
		search = args.toString() ? '?' + args.toString() : '';
		route = base;
	}

	url.searchParams.delete( 'action' );
	url.searchParams.delete( 'action_type' );
	url.hash = '#' + route + search;
	window.history.replaceState( null, '', url.toString() );
}
