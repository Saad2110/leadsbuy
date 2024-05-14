<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.buy-credit-field').change(function() {
        // Get the selected option value
        const selectedOptionValue = jQuery(this).val();

        // Set the selected option value inside the div
        jQuery('.selected-price').text(selectedOptionValue+"$");
    });
});
</script>
<!-- end Simple Custom CSS and JS -->
