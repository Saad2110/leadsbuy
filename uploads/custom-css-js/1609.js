<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
function show_reporting_area(){
	jQuery(".report-dropdown").toggle();
}
jQuery(document).ready(function( $ ){
    jQuery('.report-span').click(function() {
        var message = "SPAM";
		var user_id = jQuery(".toggle-user-hidden-field-modal").val();
		var post_id = jQuery(".toggle-lead-hidden-post").val();
	
	 jQuery.ajax({
            url: "https://myleads.fr/wp-admin/admin-ajax.php", 
            method: 'POST',
            data: { action: 'lead_report', message: message, user_id : user_id, post_id : post_id },
            success: function(response) {
				jQuery(".report-dropdown").toggle();
				jQuery(".email-message").html(response);
            },

        });
});	 
	
	    jQuery('.misleading-info').click(function() {
        var message = "Misleading Information";
		var user_id = jQuery(".toggle-user-hidden-field-modal").val();
		var post_id = jQuery(".toggle-lead-hidden-post").val();
	
	 jQuery.ajax({
            url: "https://myleads.fr/wp-admin/admin-ajax.php", 
            method: 'POST',
            data: { action: 'lead_report', message: message, user_id : user_id, post_id : post_id },
            success: function(response) {
				jQuery(".report-dropdown").toggle();
				jQuery(".email-message").html(response);
            },

        });
});
	
		    jQuery('.click-icon').click(function() {
        var message = jQuery(".report-other-field").val();;
		var user_id = jQuery(".toggle-user-hidden-field-modal").val();
		var post_id = jQuery(".toggle-lead-hidden-post").val();
	
	 jQuery.ajax({
            url: "https://myleads.fr/wp-admin/admin-ajax.php", 
            method: 'POST',
            data: { action: 'lead_report', message: message, user_id : user_id, post_id : post_id },
            success: function(response) {
				jQuery(".report-dropdown").toggle();
				jQuery(".email-message").html(response);
            },

        });
});
	
	});</script>
<!-- end Simple Custom CSS and JS -->
