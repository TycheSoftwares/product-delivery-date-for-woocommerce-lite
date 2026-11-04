/**
 * Upgrade to Pro modal, same markup as the earlier
 * includes/component/upgrade-to-pro/templates/upgrade-to-pro-page/upgrade-to-pro-modal.php.
 */
import { __ } from '@wordpress/i18n';
import config from '../config';

export const TRIAL_URL =
	'https://www.tychesoftwares.com/products/woocommerce-product-delivery-date-pro-plugin-trial/';

const upsellImage = ( file ) => `${ config.upsell_images_url || '' }/${ file }`;

export default function UpgradeModal() {
	const wasPro = !! config.pro_installed;

	const heading = wasPro
		? __(
				'Your store was thriving with PRO - Why stop now?',
				'product-delivery-date-for-woocommerce-lite'
		  )
		: __(
				'Product Delivery Date PRO for WooCommerce is CHEAPER than you think',
				'product-delivery-date-for-woocommerce-lite'
		  );
	const bodyHeading = wasPro
		? __(
				'Upgrade to Product Delivery Date PRO for WooCommerce',
				'product-delivery-date-for-woocommerce-lite'
		  )
		: __(
				'Upgrade from Lite to PRO with our Super Saver PRO Access Deal!',
				'product-delivery-date-for-woocommerce-lite'
		  );
	const bodyDesc = wasPro
		? __(
				"Reclaim the 20+ powerful features that were driving your growth. Upgrade to PRO again and continue unlocking your store's full potential. Without PRO you are missing out on -",
				'product-delivery-date-for-woocommerce-lite'
		  )
		: __(
				"Unlock all premium features of Product Delivery Date PRO for WooCommerce without restrictions — all while staying within budget. Your store's success is one click away, so don't miss the Super Saver PRO Access Deal that can skyrocket your sales today!",
				'product-delivery-date-for-woocommerce-lite'
		  );
	const footer = wasPro ? (
		<b>
			<i>
				{ __(
					"Don't miss out on what you were achieving - take action today! Click the button below to Upgrade to PRO.",
					'product-delivery-date-for-woocommerce-lite'
				) }
			</i>
		</b>
	) : (
		<>
			{ __(
				'Upgrade from lite to PRO at special pricing. Click the button below to',
				'product-delivery-date-for-woocommerce-lite'
			) }{ ' ' }
			<i>
				{ __(
					'access the latest deals.',
					'product-delivery-date-for-woocommerce-lite'
				) }
			</i>
		</>
	);
	const bullet = upsellImage( wasPro ? 'delete.png' : 'add.png' );

	const features = [
		__(
			'Access all the premium features ( Delivery per product, Same Day & Next Day delivery, Google Calendar Sync and much more )',
			'product-delivery-date-for-woocommerce-lite'
		),
		__(
			'Early access to new features',
			'product-delivery-date-for-woocommerce-lite'
		),
		__(
			'Dedicated Customer Support',
			'product-delivery-date-for-woocommerce-lite'
		),
		__(
			'Priority access to our other offers/services',
			'product-delivery-date-for-woocommerce-lite'
		),
	];

	return (
		<div className="ts-upsell-overlay">
			<div className="ts-upsell-top">
				<h2 style={ { fontSize: '20px' } }>{ heading }</h2>
			</div>
			<div className="ts-upsell-content">
				<div className="ts-upsell-content__features">
					<h3>{ bodyHeading }</h3>
					<h4>{ bodyDesc }</h4>
					<ul className="ts-columns-2">
						{ features.map( ( feature ) => (
							<li key={ feature }>
								<img src={ bullet } alt="" />
								{ feature }
							</li>
						) ) }
					</ul>
					<div className="ts-upsell-content__features-cliff">
						<p>
							{ __(
								'And more!',
								'product-delivery-date-for-woocommerce-lite'
							) }
						</p>
					</div>
					<p>{ footer }</p>
					<a
						href={ TRIAL_URL }
						target="_blank"
						rel="noopener noreferrer"
						className="ts-upgrade-button"
					>
						{ __(
							'Upgrade and Unlock',
							'product-delivery-date-for-woocommerce-lite'
						) }
						<img
							height="24"
							width="24"
							src={ upsellImage( 'icon-new-tab.png' ) }
							alt=""
							style={ { marginLeft: '10px' } }
						/>
					</a>
				</div>
			</div>
		</div>
	);
}
