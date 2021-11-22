<script type="text/javascript">
$(document).ready(function() {
	// SELECT2
	$('.select-ctype').select2();
	// DATATABLE
	var table = $("#ctTable").DataTable({
            scrollY: 450,
            scroller: true		
	});

	$("#saveData").click(function(e){
		e.preventDefault();
		var formData = "code=" + $("#code").val();
		formData += "&desc=" + $("#desc").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('country/add'); ?>",
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
	});

	// EDIT DATA
	$("#updateData").click(function(e){
		e.preventDefault();
		var formData = "code=" + $("#code").val();
		formData += "&desc=" + $("#desc").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('country/edit/'); ?>"+$("#code").val(),
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

	// Privilege
	// $("#fSetPrivilege").on("submit", function(e){
	// 	e.preventDefault();

	// 	var formData = table.$('input').serialize();
	// 	// var formData = $(this).serialize();
	// 	// alert(formData);
	// 	// return false;
	//     $.ajax({
	// 		url: "<?php echo site_url('groups/set_privilege/'); ?>"+$("#group_id").val(),
	// 		type: "POST",
	// 		dataType: 'json',
	// 		data: table.$('input').serialize(),
	// 		success: function(json) {
	// 			if(json.message=="success") {
	// 				// window.location.href = "<?=site_url('groups/edit_privilege/')?>"+json.group_id;
	// 			}
	// 		}
	//     });		
	// });

	$('#ctTable tbody').on('change', ' .has_view', function() {
		var val_view = this.checked ? '1' : '0';
		var privilege_id = $(this).data('privid');
		var module_id = $(this).data('modid');
		var group_id = $("#group_id").val();
		this.value=val_view;
		var formData = "privilege_id=" + privilege_id;
		formData += "&group_id=" + group_id;
		formData += "&module_id=" + module_id;
	    formData += "&has_view=" + this.value;
	    console.log(formData);
	    $.ajax({
			url: "<?php echo site_url('groups/set_privilege/'); ?>"+group_id,
			type: "POST",
			dataType: 'json',
			data: formData,
			success: function(json) {
				if(json.status=="Failled") {
					alert(json.message);
				}
			}
	    });
	});	
	
	$('#ctTable tbody').on('change', ' .has_insert', function() {
	    var val_insert = this.checked ? '1' : '0';
		var privilege_id = $(this).data('privid');
		var module_id = $(this).data('modid');
		var group_id = $("#group_id").val();
		this.value=val_insert;
		var formData = "privilege_id=" + privilege_id;
		formData += "&group_id=" + group_id;
		formData += "&module_id=" + module_id;
	    formData += "&has_insert=" + this.value;
	    console.log(formData);
	    $.ajax({
			url: "<?php echo site_url('groups/set_privilege/'); ?>"+group_id,
			type: "POST",
			dataType: 'json',
			data: formData,
			success: function(json) {
				if(json.status=="Failled") {
					alert(json.message);
				}
			}
	    });
	});	

	$('#ctTable tbody').on('change', ' .has_update', function() {
	    var val_update = this.checked ? '1' : '0';
		var privilege_id = $(this).data('privid');
		var module_id = $(this).data('modid');
		var group_id = $("#group_id").val();
		this.value=val_update;
		var formData = "privilege_id=" + privilege_id;
		formData += "&group_id=" + group_id;
		formData += "&module_id=" + module_id;
	    formData += "&has_update=" + this.value;
	    console.log(formData);
	    $.ajax({
			url: "<?php echo site_url('groups/set_privilege/'); ?>"+group_id,
			type: "POST",
			dataType: 'json',
			data: formData,
			success: function(json) {
				if(json.status=="Failled") {
					alert(json.message);
				}
			}
	    });
	});

	$('#ctTable tbody').on('change', ' .has_delete', function() {
	    var val_delete = this.checked ? '1' : '0';
		var privilege_id = $(this).data('privid');
		var module_id = $(this).data('modid');
		var group_id = $("#group_id").val();
		this.value=val_delete;
		var formData = "privilege_id=" + privilege_id;
		formData += "&group_id=" + group_id;
		formData += "&module_id=" + module_id;
	    formData += "&has_delete=" + this.value;
	    console.log(formData);
	    $.ajax({
			url: "<?php echo site_url('groups/set_privilege/'); ?>"+group_id,
			type: "POST",
			dataType: 'json',
			data: formData,
			success: function(json) {
				if(json.status=="Failled") {
					alert(json.message);
				}
			}
	    });
	});				

	$('#ctTable tbody').on('change', ' .has_approval', function() {
	    var val_approval = this.checked ? '1' : '0';
		var privilege_id = $(this).data('privid');
		var module_id = $(this).data('modid');
		var group_id = $("#group_id").val();
		this.value=val_approval;
		var formData = "privilege_id=" + privilege_id;
		formData += "&group_id=" + group_id;
		formData += "&module_id=" + module_id;
	    formData += "&has_approval=" + this.value;
	    console.log(formData);
	    $.ajax({
			url: "<?php echo site_url('groups/set_privilege/'); ?>"+group_id,
			type: "POST",
			dataType: 'json',
			data: formData,
			success: function(json) {
				if(json.status=="Failled") {
					alert(json.message);
				}
			}
	    });
	});		

	$('#ctTable tbody').on('change', ' .has_printpdf', function() {
	    var val_printpdf = this.checked ? '1' : '0';
		var privilege_id = $(this).data('privid');
		var module_id = $(this).data('modid');
		var group_id = $("#group_id").val();
		this.value=val_printpdf;
		var formData = "privilege_id=" + privilege_id;
		formData += "&group_id=" + group_id;
		formData += "&module_id=" + module_id;
	    formData += "&has_printpdf=" + this.value;
	    console.log(formData);
	    $.ajax({
			url: "<?php echo site_url('groups/set_privilege/'); ?>"+group_id,
			type: "POST",
			dataType: 'json',
			data: formData,
			success: function(json) {
				if(json.status=="Failled") {
					alert(json.message);
				}
			}
	    });
	});	

	$('#ctTable tbody').on('change', ' .has_printxls', function() {
	    var val_printxls = this.checked ? '1' : '0';
		var privilege_id = $(this).data('privid');
		var module_id = $(this).data('modid');
		var group_id = $("#group_id").val();
		this.value=val_printxls;
		var formData = "privilege_id=" + privilege_id;
		formData += "&group_id=" + group_id;
		formData += "&module_id=" + module_id;
	    formData += "&has_printxls=" + this.value;
	    console.log(formData);
	    $.ajax({
			url: "<?php echo site_url('groups/set_privilege/'); ?>"+group_id,
			type: "POST",
			dataType: 'json',
			data: formData,
			success: function(json) {
				if(json.status=="Failled") {
					alert(json.message);
				}
			}
	    });
	});		
	// $("#resetForm").on("click", function(){
	// 	document.getElementById("fSetPrivilege").reset();
	// });
});
</script>