<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function( $ ){
	
		 function check_new_order_recurrsive33() {
      jQuery.ajax({
                type: 'POST',
                url:"https://myleads.fr/wp-admin/admin-ajax.php",
              data: {
                action: 'new_order_check',
            },
                success: function(response) {
					
		if (response.success) {
			 if (response.message !== "") {
			 jQuery(".redirecttext").html("redirection");
			window.location.href = 'https://myleads.fr/my-leads/';
			 }			
		}
                },
            });
    }
    jQuery('.not-logged-in-second-btn').click(function(e) {
		e.preventDefault();
		var lead_qty = jQuery("#leads-qty").val();
		var fielderror = false;
	if(lead_qty == ""){
	   fielderror = true;
		jQuery(".lead-no-error").show();
	   }else{
		   jQuery(".lead-no-error").hide();
	   }
		
		if(!fielderror){
			 var lead_dept = jQuery('.lead-dept-checkbox:checked').map(function() {
          return this.value;
        }).get();
		var lead_cat = jQuery("#lead-category").val();
			 var data = {
                    lead_dept : lead_dept,
	 				lead_cat : lead_cat,
					num_of_leads : lead_qty,
                };
			 var encodedParams = encodeURIComponent(JSON.stringify(data));
				 var baseURL = 'https://myleads.fr/sign-in/';
				 var redirectURL = baseURL + '?data=' + encodedParams;
				 window.location.href = redirectURL;
		}
		
		
		});
	
	  jQuery('.user-sign-in-button-logout').click(function(e) {
		  e.preventDefault();
		  var lead_category = jQuery(".lead-cat-hidden").val();
		   var lead_department = jQuery(".lead-dept-hidden").val();
		   var lead_qty = jQuery(".lead-qty-hidden").val();
		   var user_email = jQuery("#user_login").val();
		   var user_password = jQuery("#user_pass").val();
		  var remember_me = "";
		  
		   if (jQuery("#rememberme").is(':checked')) {
          remember_me = "yes";
        } else {
         remember_me = "no"
        }
		   var data_login_pay = {
			   		user_email : user_email,
			   		user_password : user_password,
			   		remember_me : remember_me,
                };
		    jQuery.ajax({
                    type : "post",
                    dataType : "json",
                    url : "https://myleads.fr/wp-admin/admin-ajax.php",
                    data : {action: 'user_another_login', data: data_login_pay},
                    success: function(response) {
				if(response.success == false){
				  jQuery(".error-message-section").html(response.message);
					jQuery(".error-message-section").show();
				   }else{
					   jQuery(".logged-out-user-page").css('dispaly', 'flex');
					    var userdata = {
                     lead_dept : lead_department,
	 				lead_cat : lead_category,
					num_of_leads : lead_qty,			
                };
					       jQuery.ajax({
                type: 'POST',
                url:"https://myleads.fr/wp-admin/admin-ajax.php",
              data: {
                action: 'user_buy_leads',
                data: userdata
            },
                success: function(response) {
						jQuery(".spinner").css('display', 'none');
					  var windowWidth = 600;
            var windowHeight = 400;

            // Calculate the center position
            var screenWidth = window.screen.width;
            var screenHeight = window.screen.height;

            var left = (screenWidth - windowWidth) / 2;
            var top = (screenHeight - windowHeight) / 2;
					
				window.open(response.data, '_blank', '_blank', 'width=' + windowWidth + ', height=' + windowHeight + ', left=' + left + ', top=' + top);
					jQuery(".buy-follower-overlay").css('display', 'flex');
				setInterval(check_new_order_recurrsive33, 3000);
		
                }

            });
				   }
                },
            });
		
		  });
});

</script>
<!-- end Simple Custom CSS and JS -->
