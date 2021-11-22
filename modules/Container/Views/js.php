<script type="text/javascript">
$(document).ready(function(){
	//containerCode dropdown
	$("#crno").focus();
	$('.select-ccode').select2();
	$('.select-material').select2();

	$("#ctTable").DataTable({
        "paging":   false,
        "info": false,		
	});

	$("#cccode").on("change", function(){
		var cccode = $(this).val();
		$.ajax({
			url:"<?=site_url('container/ajax_ccode/');?>"+cccode,
			type:"POST",
			dataType:"JSON",
			success: function(json) {
				$("#ctcode_view").val(json.ctcode);
				$("#ctcode").val(json.ctcode);
				$("#cclength").val(json.cclength);
				$("#ccheight").val(json.ccheight);
				console.log(json.cclength);
			}
		});
	});

	$("#saveContainer").click(function(e){
		e.preventDefault();
		// var crcdp$("#crcdp").val();
		var formData = "crno=" + $("#crno").val();
		formData += "&mtcode=" + $("#mtcode").val();
		formData += "&cccode=" + $("#cccode").val();
		formData += "&crowner=" + $("#crowner").val();
		formData += "&crcdp=" + $("#crcdp").val();
		formData += "&crcsc=" + $("#crcsc").val();
		formData += "&cracep=" + $("#cracep").val();
		formData += "&crmmyy=" + $("#crmmyy").val();
		formData += "&crweightk=" + $("#crweightk").val();
		formData += "&crweightl=" + $("#crweightl").val();
		formData += "&crtarak=" + $("#crtarak").val();
		formData += "&crtaral=" + $("#crtaral").val();
		formData += "&crnetk=" + $("#crnetk").val();
		formData += "&crnetl=" + $("#crnetl").val();
		formData += "&crvol=" + $("#crvol").val();
		formData += "&crmanuf=" + $("#crmanuf").val();
		formData += "&crmandat=" + $("#crmandat").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('container/add'); ?>",
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
					window.location.href = "<?php echo site_url('container'); ?>";
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

	$("#updateContainer").click(function(e){
		e.preventDefault();
		// var crcdp$("#crcdp").val();
		var formData = "crno=" + $("#crno").val();
		formData += "&mtcode=" + $("#mtcode").val();
		formData += "&cccode=" + $("#cccode").val();
		formData += "&crowner=" + $("#crowner").val();
		formData += "&crcdp=" + $("#crcdp").val();
		formData += "&crcsc=" + $("#crcsc").val();
		formData += "&cracep=" + $("#cracep").val();
		formData += "&crmmyy=" + $("#crmmyy").val();
		formData += "&crweightk=" + $("#crweightk").val();
		formData += "&crweightl=" + $("#crweightl").val();
		formData += "&crtarak=" + $("#crtarak").val();
		formData += "&crtaral=" + $("#crtaral").val();
		formData += "&crnetk=" + $("#crnetk").val();
		formData += "&crnetl=" + $("#crnetl").val();
		formData += "&crvol=" + $("#crvol").val();
		formData += "&crmanuf=" + $("#crmanuf").val();
		formData += "&crmandat=" + $("#crmandat").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('container/edit/'); ?>" + $("#crno").val(),
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
					window.location.href = "<?php echo site_url('container'); ?>";
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
			url: "<?php echo site_url('container/delete/'); ?>"+code,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('container'); ?>";
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

	$('#crcdp').on('change', function() {
	    var crcdp_val = this.checked ? '1' : '0';
	    return this.value=crcdp_val;
	});
	$('#crcsc').on('change', function() {
	    var crcsc_val = this.checked ? '1' : '0';
	    this.value=crcsc_val;
	});	
	$('#cracep').on('change', function() {
	    var cracep_val = this.checked ? '1' : '0';
	    this.value=cracep_val;
	});		
});
</script>	