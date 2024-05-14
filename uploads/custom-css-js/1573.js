<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function( $ ){
   jQuery('#lead-numbers-total').change(function() {
        var number_of_leads = jQuery("#lead-numbers-total").val();
        jQuery('#leads-credits').val(number_of_leads);
      });
});
</script>
<!-- end Simple Custom CSS and JS -->
