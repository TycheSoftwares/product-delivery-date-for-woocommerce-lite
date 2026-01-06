/**
 * File for configuring Full Calendar
 * @namespace prdd_calendar
 * @since 1.0
 */
jQuery(document).ready(function($) {
	
	var calendarEl = document.getElementById('prdd-calendar');
	if ( ! calendarEl ) {
		return;
	}
    var calendar = new FullCalendar.Calendar(calendarEl, {
    	headerToolbar: {
    		left: 'prev,next today',
    		center: 'title',
    		right: 'dayGridMonth,timeGridWeek,timeGridDay',
    		ignoreTimezone: false    		
    	},
    	selectable: false,
		navLinks: true,
		locale: prdd_lite_ajax_data.prdd_language,
		events: function(info, successCallback, failureCallback) {
			$.ajax({
				url: ajaxurl,
				type: 'GET',
				data: {
					action: 'prdd-adminend-events-jsons',
					start: info.startStr,
					end: info.endStr,
					nonce: prdd_lite_ajax_data.prdd_nonce
				},
				success: function(response) {
					var events = [];
					try {
						events = typeof response === 'string' ? JSON.parse(response) : response;
					} catch(e) {
						console.error('Error parsing events:', e);
					}
					successCallback(events);
				},
				error: function(xhr, status, error) {
					console.error('Error loading events:', error);
					failureCallback();
				}
			});
		},

		eventDidMount: function( info ) {
			var prdd_event_data = { action : 'prdd_calender_content', order_id : info.event.id, event_value : info.event.extendedProps.value, nonce: prdd_lite_ajax_data.prdd_nonce };
			jQuery(info.el).qtip({
				content:{
					text : 'Loading...',
					button: 'Close',
					ajax : {
						url : prdd_lite_ajax_data.ajax_url,
						type : "POST",
						data : prdd_event_data
					}
				},
				show: {
                    event: 'click',
                    solo: true
                },
				position: {
					my: 'bottom right',
					at: 'top right'

				},

				hide: 'unfocus',
				
				style: {
				  classes: 'qtip-light qtip-shadow'
				}
			});
		},
		loading: function(bool) {
			if( bool == true ) {
				jQuery( "#prdd-calendar-loader" ).show();
			} else if( bool == false ) {
				jQuery( "#prdd-calendar-loader" ).hide();
			}
		},
	});
	calendar.render();
});