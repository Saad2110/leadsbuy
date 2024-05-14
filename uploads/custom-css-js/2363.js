<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function( $ ){
    jQuery("#stripe-card-expiry").on("input", function() {
                var val = jQuery(this).val().trim();
                
                if (val.length === 2 && val.indexOf('/') === -1) {
                    jQuery(this).val(val + '/');
                }
            });
});

</script>
<!-- end Simple Custom CSS and JS -->
