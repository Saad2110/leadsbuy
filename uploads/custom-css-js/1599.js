<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function( $ ){
    jQuery('.search-my-leads').keyup(function() {
    var searchValue = jQuery(this).val();
	if(searchValue.length > 0){
	       jQuery.ajax({
      url : "https://myleads.fr/wp-admin/admin-ajax.php",                  
      method: 'POST',
       data : {action: 'search_leads', searchValue: searchValue},
      success: function(response) {
        jQuery(".leads-data-table").hide();
		  jQuery(".search-table-data").remove();
		 jQuery('.table-data').after(response);
      }
    });
	   }else{
		   jQuery(".leads-data-table").show();
		  jQuery(".search-table-data").remove();
	   }

  });
});


</script>
<!-- end Simple Custom CSS and JS -->
