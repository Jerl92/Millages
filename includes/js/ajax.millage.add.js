function add_millage_place($) {
    
    $.fn.ready();
	'use strict';


	/**
	 * Remove All from Saved for Later
	 */
	$('#add_place_in_post').on('click', function(event) {
        event.preventDefault();
                        
        var $this = $(this),
				object_id = getUrlParameter('postid');

		$.ajax({
			type: 'post',
			url: edit_post_millage_ajax_url,
			data: {
				'object_id': object_id,
				'action': 'update_post_millage'
            },
            dataType: 'JSON',
			success: function(data) {
                console.log(data);
                $('#post_millage_table').empty();
                $('#post_millage_table').append(data);
                save_millage_place($);
				edit_millage_place($);
				remove_millage_place($);
				ajax_get_map($);
			},
			error: function(error) {
				console.log(error);
			}
        })
	});
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

jQuery(document).ready(function($) {
	add_millage_place($);
});