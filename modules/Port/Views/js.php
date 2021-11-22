<script type="text/javascript">
$(document).ready(function() {
	// SELECT2
	$('.select-cncode').select2();	
	// DATATABLE
	var table = $("#ctTable").DataTable({
	    select: {
	        style: 'single'
	    }
	});

	$("#saveData").click(function(e){
		e.preventDefault();
		var formData = "code=" + $("#code").val();
		formData += "&poid=" + $("#poid").val();
		formData += "&cncode=" + $("#cncode").val();
		formData += "&desc=" + $("#desc").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('port/add'); ?>",
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
					window.location.href = "<?php echo site_url('port'); ?>";
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
		var formData = "code=" + $("#code").val();
		formData += "&poid=" + $("#poid").val();
		formData += "&cncode=" + $("#cncode").val();
		formData += "&desc=" + $("#desc").val();
		$.ajax({
			url: "<?php echo site_url('port/edit/'); ?>"+$("#code").val(),
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
					window.location.href = "<?php echo site_url('port'); ?>";
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


    // $('#ctTable tbody').on('click', '.delete', function () {
    //     var data = table.row( this ).data();
    //     alert( 'You clicked on '+data[4]+'\'s row' );
    // } );

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
			url: "<?php echo site_url('port/delete/'); ?>"+code,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('port'); ?>";
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