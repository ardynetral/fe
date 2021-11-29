<script type="text/javascript">
$(document).ready(function() {
	// SELECT2
	$('.select-ctype').select2();
	// SELECT2-multiple
	$('select.module_config').select2();
	// ICON Picker
	$('.module_icon').iconpicker();
	// DATATABLE
	$("#ctTable").DataTable({
	    select: {
	        style: 'single'
	    }
	});

	$("#saveData").click(function(e){
		e.preventDefault();
		var formData = "module_name=" + $("#module_name").val();
		formData += "&module_description=" + $("#module_description").val();		
		formData += "&module_parent=" + $("#module_parent").val();
		formData += "&module_config=" + $("#module_config").select2('val');		
		formData += "&module_type=" + $("#module_type").val();		
		formData += "&module_icon=" + $("#module_icon").val();		
		formData += "&module_var=" + $("#module_var").val();		
		formData += "&module_status=" + $("#module_status").val();		
		formData += "&module_url=" + $("#module_url").val();		
		formData += "&module_content=" + $("#module_content").val();		
		formData += "&sort_index=" + $("#sort_index").val();		
		$.ajax({
			url: "<?php echo site_url('module/add'); ?>",
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
					window.location.href = "<?php echo site_url('module'); ?>";
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
	$("#updateData").on("click", function(e){
		e.preventDefault();
		var formData = "module_id=" + $("#module_id").val();
		formData += "&module_name=" + $("#module_name").val();
		formData += "&module_description=" + $("#module_description").val();		
		formData += "&module_parent=" + $("#module_parent").val();
		formData += "&module_config=" + $("#module_config").select2('val');		
		formData += "&module_type=" + $("#module_type").val();		
		formData += "&module_icon=" + $("#module_icon").val();		
		formData += "&module_var=" + $("#module_var").val();		
		formData += "&module_status=" + $("#module_status").val();		
		formData += "&module_url=" + $("#module_url").val();		
		formData += "&module_content=" + $("#module_content").val();		
		formData += "&sort_index=" + $("#sort_index").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('module/edit/'); ?>"+$("#module_id").val(),
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
					window.location.href = "<?php echo site_url('module'); ?>";
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
			url: "<?php echo site_url('country/delete/'); ?>"+code,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('country'); ?>";
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