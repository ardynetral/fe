<script type="text/javascript">
$(document).ready(function() {
	$("#btnResetPassword").on("click", function(e){
		e.preventDefault();
		$.ajax({
			url: "<?php echo site_url('forgot_password'); ?>",
			type: "POST",
			data: { "email":$("#email").val()},
			dataType: 'json',
			success: function(json) {
				if (json.message == "success") {
					Swal.fire({
						icon: 'success',
						title: "Success",
						html: '<div class="text-success">Please check email to reset your PASSWORD.</div>'
					});
				} else {
					Swal.fire({
						icon: 'warning',
						title: "Oops",
						html: '<div class="text-danger">' + json.message + '</div>'
					});
				}
			}
		});		
	});
});	
</script>