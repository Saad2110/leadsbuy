<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function( $ ){
                     
                        jQuery(".generatePdf").click(function() {
                            var pdf = new jsPDF();
                            pdf.text('Hello, jsPDF!', 10, 10);
                            pdf.save('mypdf.pdf');
                        });
          
});



</script>
<!-- end Simple Custom CSS and JS -->
