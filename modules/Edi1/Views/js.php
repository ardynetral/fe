<script type="text/javascript">
var selectedDate;
$(document).ready(function() {

		$(".tanggal").datepicker({
			autoclose: true,
		});

		$('#timepicker1').timepicker({
			defaultTime: false,
			showMeridian: false,
			explicitMode: false
		});
		$('#timepicker2').timepicker({
			defaultTime: false,
			showMeridian: false,
			explicitMode: false

		});

		$("#startDate").on('changeDate', function(selected) {
			var startDate = new Date(selected.date.valueOf());
			$("#endDate").datepicker('setStartDate', startDate);
			if ($("#startDate").val() > $("#endDate").val()) {
				$("#endDate").val($("#startDate").val());
			}
		});
		// SELECT2
		$('.select-pr').select2();

		// $("#printEDI").on("click", function(e) {
		// 	window.open("<?php echo site_url('edi1/printEDI'); ?>", '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
		// 	e.preventDefault();
		// });

	$("#fEDI1").on("submit", function(e){
		e.preventDefault();

		$.ajax({
			url: "<?php echo site_url('edi1/printEDI'); ?>",
			type: "POST",
			data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,					
			dataType: 'json',
            beforeSend: function () {
				$("#printEDI").prop('disabled', true);
            },					
			success: function(json) {
				if (json.status == "success") {
					window.open("<?=base_url('public/media/codeco')?>"+"/"+json.data, "EDI", "height=700,width=400,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes");
					// myWindow.document.write(json.data);							
				} else {
					Swal.fire({
						icon: 'warning',
						title: "Alert",
						html: '<div class="text-danger">' + json.message + '</div>'
					});
				}
	
			},
            complete: function () {
                $("#printEDI").prop('disabled', false);
            },	
		});
	});

});
</script>