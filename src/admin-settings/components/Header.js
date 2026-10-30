/**
 * Plugin header, same markup as the earlier includes/admin/views/header.php.
 */
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Link, useLocation } from 'react-router-dom';
import config, { imageUrl } from '../../shared/config';

/**
 * Top level section of a route path.
 *
 * @param {string} pathname Route path.
 * @return {string} Section.
 */
export function sectionOf( pathname ) {
	const first = pathname.split( '/' ).filter( Boolean )[ 0 ] || '';
	return 'faq' === first ? '' : first;
}

export default function Header() {
	const { pathname } = useLocation();
	const [ open, setOpen ] = useState( false );
	const section = sectionOf( pathname );

	const active = ( name ) => ( name === section ? 'active' : '' );

	return (
		<div className="prdd-header">
			<div className="container-fluid">
				<div className="row">
					<div className="col-md-12">
						<div className="header-wrap">
							<div className="branding">
								<Link to="/">
									<img
										src={ imageUrl( 'prdd-logo.svg' ) }
										alt="Logo"
									/>
								</Link>
							</div>

							<nav className="navbar navbar-expand-lg navbar-light prdd-navigation">
								<button
									className={
										'navbar-toggler' +
										( open ? '' : ' collapsed' )
									}
									type="button"
									data-toggle="collapse"
									data-target="#navbarNav"
									aria-controls="navbarNav"
									aria-expanded={ open ? 'true' : 'false' }
									aria-label={ __(
										'Toggle navigation',
										'product-delivery-date-for-woocommerce-lite'
									) }
									onClick={ () => setOpen( ! open ) }
								>
									<span className="navbar-toggler-icon">
										<svg
											xmlns="http://www.w3.org/2000/svg"
											viewBox="0 0 24 24"
											width="24"
											height="24"
										>
											<path
												fill="none"
												d="M0 0h24v24H0z"
											/>
											<path
												d="M3 4h18v2H3V4zm0 7h12v2H3v-2zm0 7h18v2H3v-2z"
												fill="rgba(255,255,255,1)"
											/>
										</svg>
									</span>
								</button>
								<div
									className={
										'collapse navbar-collapse' +
										( open ? ' show' : '' )
									}
									id="navbarNav"
								>
									<ul className="navbar-nav menu nav-menu">
										<li
											className={
												'nav-link ' + active( '' )
											}
										>
											<Link className="nav-link" to="/">
												{ __(
													'Home',
													'product-delivery-date-for-woocommerce-lite'
												) }
											</Link>
										</li>
										<li
											className={
												'nav-item ' +
												active( 'settings' )
											}
										>
											<Link
												className="nav-link"
												to="/settings"
											>
												{ __(
													'Settings',
													'product-delivery-date-for-woocommerce-lite'
												) }
											</Link>
										</li>
										<li
											className={
												'nav-item ' +
												active( 'appearance' )
											}
										>
											<Link
												className="nav-link"
												to="/appearance"
											>
												{ __(
													'Appearance',
													'product-delivery-date-for-woocommerce-lite'
												) }
											</Link>
										</li>
										<li
											className={
												'nav-item ' +
												active( 'view-deliveries' )
											}
										>
											<Link
												className="nav-link"
												to="/view-deliveries"
											>
												{ __(
													'View Deliveries',
													'product-delivery-date-for-woocommerce-lite'
												) }
											</Link>
										</li>
										<li
											className={
												'nav-item ' +
												active(
													'bulk-product-settings'
												)
											}
										>
											<Link
												className="nav-link"
												to="/bulk-product-settings"
											>
												{ __(
													'Bulk Product Settings',
													'product-delivery-date-for-woocommerce-lite'
												) }
											</Link>
										</li>
										<li
											className={
												'nav-item ' +
												active( 'integrations' )
											}
										>
											<Link
												className="nav-link"
												to="/integrations"
											>
												{ __(
													'Integrations',
													'product-delivery-date-for-woocommerce-lite'
												) }
											</Link>
										</li>
									</ul>
								</div>
							</nav>
							<div className="prdd-version">
								<p className="ver-fig">
									{ __(
										'Version',
										'product-delivery-date-for-woocommerce-lite'
									) }{ ' ' }
									{ config.version }
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}
