<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">

      jQuery('.first-step-btn').click(function(event) {
        event.preventDefault();
	var lead_dept = jQuery("#lead-department").val();
		  var lead_cat = jQuery("#lead-category").val();
		  
		  if(lead_dept === ''){
			 console.log("came");
			 }

      });
</script>
<!-- end Simple Custom CSS and JS -->
