function edit_millage_place($) {
    
    $.fn.ready();
	'use strict';


	/**
	 * Remove All from Saved for Later
	 */
	$('.edit_place_in_post').on('click', function(event) {
        event.preventDefault();
                        
        var $this = $(this),
            object_id = {id:$this.attr('data-id'), postid:getUrlParameter('postid')};

		$.ajax({
			type: 'post',
			url: edit_post_millage_ajax_url,
			data: {
				'object_id': object_id,
				'action': 'edit_post_millage'
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
		});
    });
}

jQuery(document).ready(function($) {
	edit_millage_place($);
});