<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function( $ ){
	jQuery(document).on('click','.close-box',function(){
		  jQuery(".close-box").addClass("open-box");
            jQuery(".open-box").removeClass("close-box");
		jQuery(".box-open-div").css('border-radius', '12px 12px 0 0');
		 jQuery(".checkbox-dept-box").css('display', 'flex');
        });
	
	jQuery(document).on('click','.open-box',function(){
   jQuery(".open-box").addClass("close-box");
            jQuery(".close-box").removeClass("open-box");
		jQuery(".box-open-div").css('border-radius', '12px 12px 12px 12px');
		 jQuery(".checkbox-dept-box").hide();
});
	
	jQuery('.lead-dept-checkbox').click(function() {
    var checkedCount = jQuery('.lead-dept-checkbox:checked').length;
    if (checkedCount === 0) {
       jQuery(".dept-valid-btn").hide();
    }else{
		 jQuery(".dept-valid-btn").show();
	} 
  });
	jQuery(document).on('click','.dept-valid-btn',function(){
			  jQuery(".open-box").addClass("close-box");
            jQuery(".close-box").removeClass("open-box");
		jQuery(".box-open-div").css('border-radius', '12px 12px 12px 12px');
		 jQuery(".checkbox-dept-box").hide();
	});
// 	   jQuery('.lead-dept-checkbox').on('change', function(){
//         if(jQuery(this).is(':checked')) {
//           jQuery(".dept-valid-btn").show();
//         }
//     });
	
// 	jQuery('.lead-dept-checkbox').on('change', function(){
// 		var uncheckboxValue = jQuery(this).val();
//         if(!jQuery(this).is(':checked')) {
//             jQuery("."+uncheckboxValue).remove();
//         }
//     });

});


</script>
<!-- end Simple Custom CSS and JS -->
