<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function($) {
         jQuery(".generatePdfButton").click(function() {
			
        jQuery.ajax({
            type: 'POST',
            url: "https://myleads.fr/wp-admin/admin-ajax.php",  
            data: {
                action: 'generate_pdf', // Define the PHP function to execute
            },
     success: function(response) {
        if (response.pdf_url !== undefined) {
            window.location.href = response.pdf_url;
        } else {
            console.log('PDF URL is undefined.');
        }
    },
    error: function(xhr, textStatus, errorThrown) {
        console.log('Error:', textStatus);
    }
    });
});
	});</script>
<!-- end Simple Custom CSS and JS -->
