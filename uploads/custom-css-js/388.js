<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function( $ ){
	 function check_new_order_recurrsive() {
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
	
	
	 jQuery('.first-back-btn').click(function() {
        // Replace 'https://example.com' with your desired URL
        window.location.href = 'https://myleads.fr/';
    });
	
	jQuery('.step-1').addClass('active');
	jQuery('.progress-bar').css('width', '33.333%');
	
    jQuery('.next-step').click(function() {
				   var currentStep = jQuery(this).closest('.step');
        var nextStep = currentStep.next('.step');
		var stepNumber = jQuery('.step').data('step');
		var error = false;
		if(stepNumber == 1){
			
			if(jQuery("#lead-category").val() == "Sélectionnez une catégorie"){
				error = true;
				jQuery(".category-error").show();
			}else{
				jQuery(".category-error").hide();
// 				 currentStep.hide();
//         nextStep.show();
//         updateProgressBar(nextStep);
			}
			
			 if (jQuery('input[type="checkbox"]:checked').length > 0) {
				 jQuery(".dept-error").hide();
//               currentStep.hide();
//         nextStep.show();
//         updateProgressBar(nextStep);
        } else {
			error = true;
            jQuery(".dept-error").show();
        }
			if(!error){
			    currentStep.hide();
        nextStep.show();
        updateProgressBar(nextStep);
			   }
			
		   }
			else{
			    currentStep.hide();
        nextStep.show();
        updateProgressBar(nextStep);
			
		   }
         
 
    });
jQuery('.second-btn').click(function(e) {
		e.preventDefault();
	 var currentStep = jQuery(this).closest('.step');
	 var nextStep = currentStep.next('.step');
	var fielderror = false;
	var lead_qty = jQuery("#leads-qty").val();
	
	if(lead_qty == ""){
	   fielderror = true;
		jQuery(".lead-no-error").show();
	   }else{
		   jQuery(".lead-no-error").hide();
	   }
	
	if(!fielderror){
		jQuery(".logged-in-user-leads-buy").css('display', 'flex');
	   var lead_dept = jQuery('.lead-dept-checkbox:checked').map(function() {
          return this.value;
        }).get();
		var lead_cat = jQuery("#lead-category").val();
		
		 var userdata = {
                    lead_dept : lead_dept,
	 				lead_cat : lead_cat,
					num_of_leads : lead_qty,
                };
		
		   jQuery.ajax({
                    type : "post",
                    dataType : "json",
                    url : "https://myleads.fr/wp-admin/admin-ajax.php",
                    data : {action: 'user_buy_leads', data: userdata},
                    success: function(response) {
				  var windowWidth = 600;
            var windowHeight = 400;
            var screenWidth = window.screen.width;
            var screenHeight = window.screen.height;

            var left = (screenWidth - windowWidth) / 2;
            var top = (screenHeight - windowHeight) / 2;
					
				window.open(response.data, '_blank', '_blank', 'width=' + windowWidth + ', height=' + windowHeight + ', left=' + left + ', top=' + top);
						setInterval(check_new_order_recurrsive, 3000);
                },
            });
		
	   }
// 		if(jQuery("#lead-numbers-total").val() == "Sélectionner un nombre"){
// 		   jQuery(".lead-no-error").show();
// 		   }else{
// 			    jQuery(".lead-no-error").hide();
// 			    currentStep.hide();
//         nextStep.show();
//         updateProgressBar(nextStep);
// 		   }
	  });
	
    jQuery('.prev-step').click(function() {
        var currentStep = jQuery(this).closest('.step');
        var prevStep = currentStep.prev('.step');
        currentStep.hide();
        prevStep.show();
        updateProgressBar(prevStep);
    });

    // Update progress bar
    function updateProgressBar(step) {
        var stepNumber = step.index() - 1;
        var totalSteps = jQuery('.step').length;
	
        var progressPercentage = (stepNumber / totalSteps) * 100;
        jQuery('.progress-bar').css('width', progressPercentage + '%');
    }
	
	jQuery('#leads-qty').on('keyup', function(){
        var totalleads = jQuery(this).val();
		var lead_price = totalleads * 50;
        jQuery('.lead_price').html(lead_price+"€");
		jQuery(".total-order").show();
    });
});
</script>
<!-- end Simple Custom CSS and JS -->
