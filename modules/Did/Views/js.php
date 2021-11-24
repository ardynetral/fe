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
		var formData = "prcode=" + $("#prcode").val();
		formData += "&cucode=" + $("#cucode").val();
		formData += "&prname=" + $("#prname").val();
		formData += "&praddr=" + $("#praddr").val();
		formData += "&prremark=" + $("#prremark").val();
		formData += "&cncode=" + $("#cncode").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('principal/add'); ?>",
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
					window.location.href = "<?php echo site_url('principal'); ?>";
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
		var formData = "prcode=" + $("#prcode").val();
		formData += "&cucode=" + $("#cucode").val();
		formData += "&prname=" + $("#prname").val();
		formData += "&praddr=" + $("#praddr").val();
		formData += "&prremark=" + $("#prremark").val();
		formData += "&cncode=" + $("#cncode").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('principal/edit/'); ?>"+$("#prcode").val(),
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
					window.location.href = "<?php echo site_url('principal'); ?>";
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

	$("#cancel").on("click", function(){
		window.location.href = "<?php echo site_url('principal'); ?>";
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
			url: "<?php echo site_url('principal/delete/'); ?>"+code,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('principal'); ?>";
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
	// print PDF
	$("#printPdf").on("click", function(e) {
		window.open("<?php echo site_url('did/reportPdf'); ?>", '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');		
		e.preventDefault();
	});
});
</script>