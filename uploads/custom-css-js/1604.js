<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function( $ ){
    jQuery('.table-data-row').click(function() {
        var selected_lead_id = jQuery(this).data('lead-id');
  jQuery('.modal').toggleClass('is-visible');

	
	 jQuery.ajax({
            url: "https://myleads.fr/wp-admin/admin-ajax.php", 
            method: 'GET',
            data: { action: 'my_leads_details', selected_lead_id: selected_lead_id },
            success: function(response) {
              jQuery(".ajax-result-data").html(response);
				jQuery(".toggle-lead-hidden-post").val(selected_lead_id);
            },

        });
});	 
	});

function closemodal(){
		jQuery('.modal').toggleClass('is-visible');
}

</script>
<!-- end Simple Custom CSS and JS -->
