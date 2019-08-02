jQuery(document).ready(function() {
    var min_date = jQuery( "#prdd_lite_hidden_minimum_delivery_time" ).val();
    var split_date = min_date.split( "-" );
    var min_date_to_set = new Date ( split_date[1] + "/" + split_date[0] + "/" + split_date[2] );
    jQuery.extend( jQuery.datepicker, { afterShow: function(event) {
        jQuery.datepicker._getInst( event.target ).dpDiv.css( "z-index", 9999 );
    }});

    jQuery( "#delivery_calender_lite" ).datepicker({
        dateFormat: prdd_lite_params.date_format,
        firstDay: prdd_lite_params.first_day,
        numberOfMonths: parseInt(prdd_lite_params.prdd_months),
        minDate: min_date_to_set,
        maxDate: parseInt(prdd_lite_params.prdd_maximum_number_days)-1,
        beforeShowDay: function( date ) {
            var weekday = [ "Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday" ];
            var a = new Date( date );
            var m = a.getMonth(), d = a.getDate(), y = a.getFullYear();
            var datejny = d + "-" + (m+1) + "-" + y;

            var enable_days = prdd_lite_params.prdd_lite_delivery_days;

            var additional_data = JSON.parse( prdd_lite_params.additional_data );
            var holidays = JSON.parse( "[" + additional_data.holidays + "]" );

            for ( ii = 0; ii < holidays.length; ii++ ) {
                if( jQuery.inArray( datejny, holidays ) != -1 ) {
                    return [ false, "", "Holiday" ];
                }
            }	

            if(enable_days){
                if( jQuery.inArray( weekday[a.getDay()], enable_days ) > -1 ) {
                    return [ true ];
                }else{
                    return [ false ];
                }
            }else{
                
                return [ true ];
            }

            
        },
        onClose:function( dateStr, inst ) {
            if ( dateStr != "" ) {
                var monthValue = inst.selectedMonth+1;
                var dayValue = inst.selectedDay;
                var yearValue = inst.selectedYear;
                var all = yearValue + "-" + monthValue + "-" + dayValue;
                jQuery( "#prdd_lite_hidden_date" ).val( all );
            }
        }
    }).focus(function (event){
        jQuery.datepicker.afterShow(event);
    });
    jQuery( "#delivery_cal_lite" ).click(function() {
        jQuery( "#delivery_calender_lite" ).datepicker( "show" );
    });

    jQuery( "#datepicker" ).datepicker( jQuery.datepicker.regional[prdd_lite_params.language_selected] );
});