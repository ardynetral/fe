<script type="text/javascript">
$(document).ready(function() {
	// SELECT2
	$('.select-ctype').select2();
	// DATATABLE
	$("#usrTable").DataTable({
	    select: {
	        style: 'single'
	    }
	});

	$("#input_principal").hide();

	$("#saveUser").click(function(e){
		e.preventDefault();
		var formData = "username=" + $("#username").val();
		formData += "&password=" + $("#password").val();
		formData += "&repeat_password=" + $("#repeat_password").val();
		formData += "&fullname=" + $("#fullname").val();
		formData += "&email=" + $("#email").val();
		formData += "&group_id=" + $("#group_id").val();
		formData += "&prcode=" + $("#prcode").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('user/add'); ?>",
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
					window.location.href = "<?php echo site_url('user'); ?>";
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

	$("#group_id").on("change", function(){
		if($(this).val()==2) {
			$("#input_principal").show();
			$.ajax({
				url:"<?=site_url('user/ajax_pr_dropdown');?>",
				type:"POST",
				dataType:"HTML",
				success: function(html) {
					$("#pr-dropdown").html(html);
					$('.select-pr').select2();
				}
			})
		} else {

		    $("#prcode").val('');
			$("#input_principal").hide();
		}
	});

	// EDIT DATA
	$("#updateUser").click(function(e){
		e.preventDefault();
		var formData = "username=" + $("#username").val();
		// formData += "&password=" + $("#password").val();
		// formData += "&repeat_password=" + $("#repeat_password").val();
		formData += "&fullname=" + $("#fullname").val();
		formData += "&email=" + $("#email").val();
		formData += "&group_id=" + $("#group_id").val();
		formData += "&prcode=" + $("#prcode").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('user/edit/'); ?>"+$("#uid").val(),
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
					window.location.href = "<?php echo site_url('user'); ?>";
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

	$('#usrTable tbody').on('click', '#sendEmail', function(e){
		e.preventDefault();
		var uid = $(this).data('uid');
		$.ajax({
			url: "<?php echo site_url('user/send_email/'); ?>"+uid,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message_data+'</div>'
					});							
					window.location.href = "<?php echo site_url('user'); ?>";
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