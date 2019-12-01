function new_millage_post($) {
    
    $.fn.ready();
	'use strict';


	/**
	 * Remove All from Saved for Later
	 */
	$('#new_millage_btn').on('click', function(event) {
        event.preventDefault();
                        
        var $this = $(this),
                millage_var = {name:$("#placename").val(), geoloc:$("#geoloc").val(), km:$("#km").val(), arrivaltime:$("#arrivaltime").val(), departuretime:$("#departuretime").val()},
				object_id = millage_var;

		$.ajax({
			type: 'post',
			url: new_post_millage_ajax_url,
			data: {
				'object_id': object_id,
				'action': 'new_post_millage'
            },
            dataType: 'JSON',
			success: function(data) {
                console.log(data);
                window.location.href = '?post=edit&postid='+data;
			},
			error: function(error) {
				console.log(error);
			}
        })
	});
}

jQuery(document).ready(function($) {
	new_millage_post($);
});