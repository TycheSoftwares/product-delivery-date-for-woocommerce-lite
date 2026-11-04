/**
 * Product Delivery Date admin app.
 */
import { useEffect } from '@wordpress/element';
import { applyFilters } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import {
	HashRouter,
	Navigate,
	Route,
	Routes,
	useLocation,
} from 'react-router-dom';

import config from '../shared/config';
import SectionLayout from './components/SectionLayout';
import ErrorBoundary from './components/ErrorBoundary';
import { hasUnsavedChanges } from './hooks/useSettingsForm';
import Welcome from './screens/home/Welcome';
import Faq from './screens/home/Faq';
import Settings from './screens/settings/Settings';
import BlockTimeSlot from './screens/settings/BlockTimeSlot';
import Holidays from './screens/settings/Holidays';
import CalendarAppearance from './screens/appearance/CalendarAppearance';
import FieldLabels from './screens/appearance/FieldLabels';
import GoogleCalendarSync from './screens/integrations/GoogleCalendarSync';
import Subscriptions from './screens/integrations/Subscriptions';
import BulkProductSettings from './screens/bulk/BulkProductSettings';
import ViewDeliveries from './screens/deliveries/ViewDeliveries';
import LockedScreen from '../shared/components/LockedScreen';

const submenu = config.submenu || {};
const text = ( section, key, fallback ) =>
	( submenu[ section ] && submenu[ section ][ key ] ) || fallback;

function RouteBoundary( { children } ) {
	const { pathname } = useLocation();
	// Keyed by route, so an error on one screen does not stick to the others.
	return <ErrorBoundary key={ pathname }>{ children }</ErrorBoundary>;
}

/**
 * Scrolls to the top when the route changes, like a full page load did.
 */
function ScrollTop() {
	const { pathname } = useLocation();
	useEffect( () => {
		window.scrollTo( 0, 0 );
	}, [ pathname ] );
	return null;
}

/**
 * Asks for a confirmation before leaving a screen with unsaved changes.
 */
function UnsavedChangesGuard() {
	useEffect( () => {
		const message = __(
			'You have unsaved changes. Are you sure you want to leave this page?',
			'product-delivery-date-for-woocommerce-lite'
		);

		// Links to the routes of the app, the other links unload the page.
		const onClick = ( event ) => {
			if ( event.defaultPrevented || ! hasUnsavedChanges() ) {
				return;
			}
			const link =
				event.target.closest && event.target.closest( 'a[href]' );
			if ( ! link || ( link.target && '_self' !== link.target ) ) {
				return;
			}
			const url = new URL( link.href, window.location.href );
			const current = window.location;
			if (
				url.pathname !== current.pathname ||
				url.search !== current.search ||
				! url.hash.startsWith( '#/' ) ||
				url.hash === current.hash
			) {
				return;
			}
			// eslint-disable-next-line no-alert
			if ( ! window.confirm( message ) ) {
				event.preventDefault();
				event.stopPropagation();
			}
		};

		const onUnload = ( event ) => {
			if ( hasUnsavedChanges() ) {
				event.preventDefault();
				event.returnValue = '';
			}
		};

		document.addEventListener( 'click', onClick, true );
		window.addEventListener( 'beforeunload', onUnload );
		return () => {
			document.removeEventListener( 'click', onClick, true );
			window.removeEventListener( 'beforeunload', onUnload );
		};
	}, [] );
	return null;
}

/**
 * Highlights the admin submenu of the current route, View Deliveries or
 * Settings, as the menu links point to the routes of the app.
 */
function SubmenuHighlight() {
	const { pathname } = useLocation();
	useEffect( () => {
		const route = pathname.startsWith( '/view-deliveries' )
			? '#/view-deliveries'
			: '#/settings';
		jQuery(
			'#toplevel_page_woocommerce_prdd_lite_page .wp-submenu a'
		).each( function () {
			const href = this.getAttribute( 'href' ) || '';
			if (
				! href.endsWith( '#/view-deliveries' ) &&
				! href.endsWith( '#/settings' )
			) {
				return;
			}
			const current = href.endsWith( route );
			jQuery( this )
				.toggleClass( 'current', current )
				.closest( 'li' )
				.toggleClass( 'current', current );
			if ( current ) {
				this.setAttribute( 'aria-current', 'page' );
			} else {
				this.removeAttribute( 'aria-current' );
			}
		} );
	}, [ pathname ] );
	return null;
}

export default function App() {
	const homeTabs = [
		{
			id: 'welcome',
			to: '/',
			text: text(
				'home',
				'welcome',
				__( 'Welcome', 'product-delivery-date-for-woocommerce-lite' )
			),
		},
		{
			id: 'faq',
			to: '/faq',
			text: text(
				'home',
				'faq',
				__( 'FAQ', 'product-delivery-date-for-woocommerce-lite' )
			),
		},
	];
	const settingsTabs = [
		{
			id: 'settings',
			to: '/settings',
			text: text(
				'settings',
				'settings',
				__( 'Settings', 'product-delivery-date-for-woocommerce-lite' )
			),
		},
		{
			id: 'block-time-slot',
			to: '/settings/block-time-slot',
			text: text(
				'settings',
				'block_time_slot',
				__(
					'Block Time Slot',
					'product-delivery-date-for-woocommerce-lite'
				)
			),
		},
		{
			id: 'holidays',
			to: '/settings/holidays',
			text: text(
				'settings',
				'holidays',
				__( 'Holidays', 'product-delivery-date-for-woocommerce-lite' )
			),
		},
	];
	const appearanceTabs = [
		{
			id: 'appearance',
			to: '/appearance',
			text: text(
				'appearance',
				'appearance',
				__(
					'Calendar Appearance',
					'product-delivery-date-for-woocommerce-lite'
				)
			),
		},
		{
			id: 'field-labels',
			to: '/appearance/field-labels',
			text: text(
				'appearance',
				'field_label',
				__(
					'Field Labels',
					'product-delivery-date-for-woocommerce-lite'
				)
			),
		},
	];
	const integrationsTabs = [
		{
			id: 'google',
			to: '/integrations',
			text: text(
				'integrations',
				'google',
				__(
					'Google Sync',
					'product-delivery-date-for-woocommerce-lite'
				)
			),
		},
		{
			id: 'subscriptions',
			to: '/integrations/subscriptions',
			text: text(
				'integrations',
				'subscriptions',
				__(
					'Subscriptions',
					'product-delivery-date-for-woocommerce-lite'
				)
			),
		},
	];

	/**
	 * Extra routes added by other plugins: array of { path, element }.
	 */
	const extraRoutes = applyFilters( 'prdd-lite.admin.routes', [] );

	return (
		<HashRouter>
			<ScrollTop />
			<SubmenuHighlight />
			<UnsavedChangesGuard />
			<RouteBoundary>
				<Routes>
					<Route element={ <SectionLayout tabs={ homeTabs } /> }>
						<Route index element={ <Welcome /> } />
						<Route path="faq" element={ <Faq /> } />
					</Route>
					<Route
						path="settings"
						element={ <SectionLayout tabs={ settingsTabs } /> }
					>
						<Route index element={ <Settings /> } />
						<Route
							path="block-time-slot"
							element={
								<LockedScreen>
									<BlockTimeSlot />
								</LockedScreen>
							}
						/>
						<Route path="holidays" element={ <Holidays /> } />
					</Route>
					<Route
						path="appearance"
						element={ <SectionLayout tabs={ appearanceTabs } /> }
					>
						<Route index element={ <CalendarAppearance /> } />
						<Route
							path="field-labels"
							element={
								<LockedScreen>
									<FieldLabels />
								</LockedScreen>
							}
						/>
						<Route
							path="field-label"
							element={
								<Navigate
									to="/appearance/field-labels"
									replace
								/>
							}
						/>
					</Route>
					<Route
						path="integrations"
						element={
							<SectionLayout
								tabs={ integrationsTabs }
								wrapperClass="ordd-content-area"
							/>
						}
					>
						<Route
							index
							element={
								<LockedScreen>
									<GoogleCalendarSync />
								</LockedScreen>
							}
						/>
						<Route
							path="subscriptions"
							element={
								<LockedScreen>
									<Subscriptions />
								</LockedScreen>
							}
						/>
					</Route>
					<Route
						path="bulk-product-settings"
						element={
							<SectionLayout>
								<LockedScreen>
									<BulkProductSettings />
								</LockedScreen>
							</SectionLayout>
						}
					/>
					<Route
						path="view-deliveries"
						element={
							<SectionLayout>
								<ViewDeliveries />
							</SectionLayout>
						}
					/>
					{ Array.isArray( extraRoutes ) &&
						extraRoutes.map( ( route ) => (
							<Route
								key={ route.path }
								path={ route.path }
								element={ route.element }
							/>
						) ) }
					<Route path="*" element={ <Navigate to="/" replace /> } />
				</Routes>
			</RouteBoundary>
		</HashRouter>
	);
}
