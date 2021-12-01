<script type="text/javascript">
$(document).ready(function() {
	// SELECT2
	$('.select-ctype').select2();
	// DATATABLE
	$("#ctTable").DataTable({
	    select: {
	        style: 'single'
	    }
	});

	$("#saveCCode").click(function(e){
		e.preventDefault();
		var formData = "cccode=" + $("#cccode").val();
		formData += "&ctype=" + $("#ctype").val();
		formData += "&height=" + $("#height").val();
		formData += "&length=" + $("#length").val();
		formData += "&alias1=" + $("#alias1").val();
		formData += "&alias2=" + $("#alias2").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('containercode/add'); ?>",
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
					window.location.href = "<?php echo site_url('containercode'); ?>";
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

	// $("#cccode").on("keyup", function(){

	// 	$.ajax({
	// 		url:"<?php echo site_url('containercode/cek_cccode');?>",
	// 		type:'POST',
	// 		dataType:'json',
	// 		data:$(this).val(),
	// 		success: function(json) {
	// 			if(json.status==true) {
	// 				alert("Container code exists.");
	// 				return false;
	// 			} else if (json.status==false) {
	// 				$("#ctype").prop("disabled",false);
	// 				$("#length").prop("disabled",false);
	// 				$("#height").prop("disabled",false);
	// 				$("#saveCCode").prop("disabled",false);
	// 			} else {
	// 				alert("Error: "+ json.message);
	// 				return false;
	// 			}
	// 			// console.log(json);
	// 		}
	// 	});
	// });

	// EDIT DATA
	$("#updateCCode").click(function(e){
		e.preventDefault();
		var formData = "cccode=" + $("#cccode").val();
		formData += "&ctype=" + $("#ctype").val();
		formData += "&height=" + $("#height").val();
		formData += "&length=" + $("#length").val();
		formData += "&alias1=" + $("#alias1").val();
		formData += "&alias2=" + $("#alias2").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('containercode/edit/'); ?>"+$("#cccode").val(),
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
					window.location.href = "<?php echo site_url('containercode'); ?>";
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
			url: "<?php echo site_url('containercode/delete/'); ?>"+code,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('containercode'); ?>";
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