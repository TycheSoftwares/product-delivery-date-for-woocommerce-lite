/**
 * Header + secondary navigation + screen + footer, the structure every
 * section page ( home, settings, appearance, integrations ) had in PHP.
 */
import {
	NavLink,
	Outlet,
	useResolvedPath,
	useLocation,
} from 'react-router-dom';
import Header from './Header';
import Footer from './Footer';

function Tab( { tab } ) {
	const { pathname } = useLocation();
	const resolved = useResolvedPath( tab.to );
	const current = resolved.pathname === pathname;

	return (
		<li className={ current ? 'current-menu-item' : '' }>
			<NavLink
				to={ tab.to }
				end
				className={ ( { isActive } ) =>
					isActive
						? 'router-link-exact-active router-link-active'
						: ''
				}
			>
				{ tab.text }{ ' ' }
			</NavLink>
		</li>
	);
}

export default function SectionLayout( {
	tabs = [],
	wrapperClass = 'prdd-content-area',
	children,
} ) {
	return (
		<div id="prdd-page" className="prdd-page">
			<Header />
			{ children || (
				<div className={ wrapperClass } id="secondary-nav-wrap">
					<div className="container cw-full secondary-nav">
						<div className="row">
							<div className="col-md-12">
								<div className="secondary-nav-wrap">
									<ul>
										{ tabs.map( ( tab ) => (
											<Tab key={ tab.id } tab={ tab } />
										) ) }
									</ul>
								</div>
							</div>
						</div>
					</div>

					<Outlet />
				</div>
			) }
			<Footer />
		</div>
	);
}
