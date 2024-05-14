<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function( $ ){
jQuery(".end-step").click(function() {

	var user_available_credits = jQuery(".user-credits").val();
	var user_wants_credits = jQuery("#lead-numbers-total").val()
	 var userdata = {
                    lead_dept : jQuery("#lead-department").val(),
	 				lead_cat : jQuery("#lead-category").val(),
					num_of_leads : jQuery("#lead-numbers-total").val(),
					 user_id : jQuery(".user-id-hidden").val(),
                };

	
		   jQuery.ajax({
                    type : "post",
                    dataType : "json",
                    url : "https://myleads.fr/wp-admin/admin-ajax.php",
                    data : {action: 'user_buy_leads', userdata: userdata},
                    success: function(response) {
					if(response.hasOwnProperty('sucess') && response.sucess === true){
					   jQuery(".step-2").hide();
						jQuery(".step-3").show();
						jQuery(".user-purchased-credits").html(user_wants_credits);
						jQuery(".form-top-heading").html("Félicitations");
					   }
                },
            });
	 

});
});

</script>
<!-- end Simple Custom CSS and JS -->
