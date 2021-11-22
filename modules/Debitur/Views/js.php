<script type="text/javascript">
$(document).ready(function() {
	// SELECT2
	$('.select-cncode').select2();
	// DATATABLE
	$("#ctTable").DataTable({
	    select: {
	        style: 'single'
	    }
	});

	$("#saveData").click(function(e){
		e.preventDefault();
		var formData = "cucode=" + $("#cucode").val();
		formData += "&cuname=" + $("#cuname").val();
		formData += "&cuaddr=" + $("#cuaddr").val();
		formData += "&cuzip=" + $("#cuzip").val();
		formData += "&cncode=" + $("#cncode").val();
		formData += "&cuphone=" + $("#cuphone").val();
		formData += "&cufax=" + $("#cufax").val();
		formData += "&cucontact=" + $("#cucontact").val();
		formData += "&cuemail=" + $("#cuemail").val();
		formData += "&cunpwp=" + $("#cunpwp").val();
		formData += "&cuskada=" + $("#cuskada").val();
		formData += "&cudebtur=" + $("#cudebtur").val();
		formData += "&cutype=" + $("#cutype").val();
		formData += "&cunppkp=" + $("#cunppkp").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('debitur/add'); ?>",
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
					window.location.href = "<?php echo site_url('debitur'); ?>";
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

	// EDIT DATA
	$("#updateData").click(function(e){
		e.preventDefault();
		var formData = "cucode=" + $("#cucode").val();
		formData += "&cuname=" + $("#cuname").val();
		formData += "&cuaddr=" + $("#cuaddr").val();
		formData += "&cuzip=" + $("#cuzip").val();
		formData += "&cncode=" + $("#cncode").val();
		formData += "&cuphone=" + $("#cuphone").val();
		formData += "&cufax=" + $("#cufax").val();
		formData += "&cucontact=" + $("#cucontact").val();
		formData += "&cuemail=" + $("#cuemail").val();
		formData += "&cunpwp=" + $("#cunpwp").val();
		formData += "&cuskada=" + $("#cuskada").val();
		formData += "&cudebtur=" + $("#cudebtur").val();
		formData += "&cutype=" + $("#cutype").val();
		formData += "&cunppkp=" + $("#cunppkp").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('debitur/edit/'); ?>"+$("#cucode").val(),
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
					window.location.href = "<?php echo site_url('debitur'); ?>";
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

	$('#ctTable tbody').on('click', '.delete', function(e){
		e.preventDefault();	
		var code = $(this).data('kode');
		Swal.fire({
		  title: 'Are you sure?',
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		  if (result.isConfirmed) {
		  	delete_data(code);
		  }
		});		
		
	});

	function delete_data(code) {
		$.ajax({
			url: "<?php echo site_url('debitur/delete/'); ?>"+code,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('debitur'); ?>";
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});						
				}
			}
		});		
	}
});
</script>