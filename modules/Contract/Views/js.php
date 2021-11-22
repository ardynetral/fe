<script type="text/javascript">
$(document).ready(function() {
	// SELECT2
	// $('.select-cncode').select2();
	$('.select-pr').select2();
	// DATATABLE
	$("#ctTable").DataTable({
	    select: {
	        style: 'single'
	    }
	});
	
	// datePicker
	// $.fn.datepicker.defaults.format = "dd/mm/yyyy";
	$(".tanggal").datepicker({
		// format: 'dd/mm/yyyy',
		autoclose:true,
	});

	$("#saveData").click(function(e){
		e.preventDefault();
		var forms = $("form#fContract").serialize();
		// console.log(forms);
		$.ajax({
			url: "<?php echo site_url('contract/add'); ?>",
			type: "POST",
			data: forms,
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});	
					console.log(json.post);						
					window.location.href = "<?php echo site_url('contract'); ?>";
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

	$("#coadmm").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
		console.log(val);
	});	

	$("#cofreedn").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});		

	$("#cofreedmg").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});	
	// EDIT DATA
	$("#updateData").click(function(e){
		e.preventDefault();
		var formData = "code=" + $("#cityId").val();
		formData += "&name=" + $("#name").val();
		formData += "&cncode=" + $("#cncode").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('city/edit/'); ?>"+$("#code").val(),
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
					window.location.href = "<?php echo site_url('city'); ?>";
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

	$("#cancel").on("click", function(e){
		e.preventDefault();
		window.location.href = "<?php echo site_url('contract'); ?>";
	})

	function delete_data(code) {
		$.ajax({
			url: "<?php echo site_url('contract/delete/'); ?>"+code,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('contract'); ?>";
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