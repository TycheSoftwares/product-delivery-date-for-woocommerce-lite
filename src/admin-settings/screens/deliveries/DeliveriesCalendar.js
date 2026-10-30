/**
 * Calendar view of the View Deliveries screen, the FullCalendar setup of
 * the earlier js/prdd-calender.js.
 */
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import config from '../../../shared/config';
import Spinner from '../../../shared/components/Spinner';

const settings = config.view_deliveries || {};

export default function DeliveriesCalendar() {
	const ref = useRef( null );
	const [ loading, setLoading ] = useState( false );

	useEffect( () => {
		if ( ! window.FullCalendar || ! ref.current ) {
			return;
		}
		const holidays = settings.holidays || [];

		const calendar = new window.FullCalendar.Calendar( ref.current, {
			headerToolbar: {
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay',
				ignoreTimezone: false,
			},
			selectable: false,
			navLinks: true,
			locale: settings.language,
			events: settings.events_url,

			eventDidMount( info ) {
				jQuery( info.el ).qtip( {
					content: {
						text: 'Loading...',
						button: 'Close',
						ajax: {
							url: settings.ajax_url,
							type: 'POST',
							data: {
								action: 'prdd_calender_content',
								order_id: info.event.id,
								event_value: info.event.extendedProps.value,
								nonce: settings.nonce,
							},
						},
					},
					show: {
						event: 'click',
						solo: true,
					},
					position: {
						my: 'bottom right',
						at: 'top right',
					},
					hide: 'unfocus',
					style: {
						classes: 'qtip-light qtip-shadow',
					},
				} );
			},
			eventWillUnmount( info ) {
				// The tooltips are appended to the body, remove them with their event.
				const $el = jQuery( info.el );
				if ( $el.data( 'qtip' ) ) {
					$el.qtip( 'destroy', true );
				}
			},
			dayCellDidMount( args ) {
				const date = args.date;
				const key =
					date.getDate() +
					'-' +
					( date.getMonth() + 1 ) +
					'-' +
					date.getFullYear();
				if ( holidays.includes( key ) ) {
					args.el.style.background = '#ff0000';
				}
			},
			loading( isLoading ) {
				setLoading( isLoading );
			},
		} );
		calendar.render();

		return () => calendar.destroy();
	}, [] );

	return (
		<>
			<h2>
				{ __(
					'Calendar View',
					'product-delivery-date-for-woocommerce-lite'
				) }
			</h2>
			<div id="calendar" ref={ ref }></div>
			<div
				id="prddd_events_loader"
				style={ { display: loading ? 'block' : 'none' } }
			>
				<div className="prddd_events_loader_wrapper">
					{
						// Existing translated string.
						// eslint-disable-next-line @wordpress/i18n-ellipsis
						__(
							'Loading delivery events...',
							'product-delivery-date-for-woocommerce-lite'
						)
					}
					<Spinner />
				</div>
			</div>
		</>
	);
}
