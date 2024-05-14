<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
function statusprogress(){
	jQuery(".modal-lead-status-call").removeClass("selectedstatus");
	jQuery(".modal-lead-status-sold").removeClass("selectedstatus");
	jQuery(".modal-lead-status-progress").addClass("selectedstatus");
}
function statuscall(){
	jQuery(".modal-lead-status-call").addClass("selectedstatus");
	jQuery(".modal-lead-status-sold").removeClass("selectedstatus");
	jQuery(".modal-lead-status-progress").removeClass("selectedstatus");
}
function statussold(){
	jQuery(".modal-lead-status-call").removeClass("selectedstatus");
	jQuery(".modal-lead-status-sold").addClass("selectedstatus");
	jQuery(".modal-lead-status-progress").removeClass("selectedstatus");
}</script>
<!-- end Simple Custom CSS and JS -->
