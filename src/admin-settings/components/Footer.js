/**
 * Plugin footer, same markup as the earlier includes/admin/views/footer.php.
 */
import { __ } from '@wordpress/i18n';
import config from '../../shared/config';

export default function Footer() {
	return (
		<div className="prdd-footer">
			<div className="container">
				<div className="row">
					<div className="col-md-12">
						<div className="footer-wrap">
							<div className="ft-text">
								<p>
									<a
										target="_blank"
										rel="noreferrer"
										href={ config.support_url }
									>
										{ __(
											'Need Support?',
											'product-delivery-date-for-woocommerce-lite'
										) }{ ' ' }
									</a>{ ' ' }
									<strong>
										{ __(
											"We're always happy to help you.",
											'product-delivery-date-for-woocommerce-lite'
										) }{ ' ' }
									</strong>
								</p>
								<p>
									<a
										target="_blank"
										rel="noreferrer"
										href={ config.review_url }
									>
										{ __(
											'Please rate this plugin',
											'product-delivery-date-for-woocommerce-lite'
										) }
									</a>{ ' ' }
									<span className="rating">★★★★★</span>{ ' ' }
									{ __(
										'if this product helped you.',
										'product-delivery-date-for-woocommerce-lite'
									) }{ ' ' }
									<br />
									{ __(
										'Thanks for your support',
										'product-delivery-date-for-woocommerce-lite'
									) }{ ' ' }
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}
