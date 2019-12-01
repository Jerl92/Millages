function remove_millage_place($) {
    
    $.fn.ready();
	'use strict';


	/**
	 * Remove All from Saved for Later
	 */
	$('.remove_place_in_post').on('click', function(event) {
		event.preventDefault();
		
		if (confirm("Confirme before delete!")) {
			txt = "You pressed OK!";
		} else {
			txt = "You pressed Cancel!";
			return null;
		}
                        
        var $this = $(this),
            object_id = {id:$this.attr('data-id'), postid:getUrlParameter('postid')};

		$.ajax({
			type: 'post',
			url: remove_post_millage_ajax_url,
			data: {
				'object_id': object_id,
				'action': 'remove_post_millage'
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
	remove_millage_place($);
});