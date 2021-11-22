<script type="text/javascript">
$(document).ready(function() {
	// SELECT2
	$('.select-ctype').select2();
	// DATATABLE
	$("#dyTable").DataTable({
	    select: {
	        style: 'single'
	    }
	});

	// CREATE DAMAGE TYPE
	$("#saveDamagetype").click(function(e){
		e.preventDefault();
		var formData = "dycode=" + $("#dycode").val();
		formData += "&dydesc=" + $("#dydesc").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('damagetype/add'); ?>",
			type: "POST",
			data: formData,
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('damagetype'); ?>";
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});						
				}
			}
		});
	});

	// EDIT DAMAGE TYPE
	$("#updateDamagetype").click(function(e){
		e.preventDefault();
		var formData = "dycode=" + $("#dycode").val();
		formData += "&dydesc=" + $("#dydesc").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('damagetype/edit/'); ?>"+$("#dycode").val(),
			type: "POST",
			data: formData,
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('damagetype'); ?>";
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});						
				}
			}
		});
	});	
});
</script>