<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/parsley-validation/parsley.min.js"></script>
<script type="text/javascript">
	var selectedDate;
	$(document).ready(function() {

		$("#startDate").datepicker({
			autoclose: true,
			format: 'dd/mm/yyyy',
			startDate: '-5y',
		});
		$("#endDate").datepicker({
			autoclose: true,
			format: 'dd/mm/yyyy',
			startDate: '-5y',
		});
		$("#startDate").datepicker('setDate', new Date());
		$("#endDate").datepicker('setDate', new Date());


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

		$("#printPdf").on("click", function(e) {
			e.preventDefault();
			var prcode = $("#prcode").val();
			var startDate = $("#startDate").val();
			var endDate = $("#endDate").val();
			var hour_from = ($("#timepicker1").val() == "") ? "00:00" : $("#timepicker1").val();
			var hour_to = ($("#timepicker2").val() == "") ? "23:59" : $("#timepicker2").val();			

			var date_from = startDate.split("/").reverse().join("-");
			var date_to = endDate.split("/").reverse().join("-");

			// if(prcode=="" || startDate=="" || endDate=="" || hour_from=="" || hour_to=="") {
			// 	Swal.fire({
			// 		icon: 'warning',
			// 		title: "Alert",
			// 		html: '<div class="text-danger">Lengkapi Form...</div>'
			// 	});
			// } else {

			window.open("<?php echo site_url('dailymovementout/reportPdf/'); ?>" + prcode + "/" + date_from + "/" + date_to + "/" + hour_from + "/" + hour_to, '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');

			// }
		});
		$("#printExl").on("click", function(e) {
			var prcode = $("#prcode").val();
			var startDate = $("#startDate").val();
			var endDate = $("#endDate").val();
			var hour_from = ($("#timepicker1").val() == "") ? "00:00" : $("#timepicker1").val();
			var hour_to = ($("#timepicker2").val() == "") ? "23:59" : $("#timepicker2").val();			

			var date_from = startDate.split("/").reverse().join("-");
			var date_to = endDate.split("/").reverse().join("-");
			// if(prcode=="" || startDate=="" || endDate=="" || hour_from=="" || hour_to=="") {
			// 	Swal.fire({
			// 		icon: 'warning',
			// 		title: "Alert",
			// 		html: '<div class="text-danger">Lengkapi Form...</div>'
			// 	});
			// } else {
				window.open("<?php echo site_url('dailymovementout/reportExcel/'); ?>" + prcode + "/" + date_from + "/" + date_to + "/" + hour_from + "/" + hour_to);
				e.preventDefault();
			// }
		});

  $('#formCType').parsley().on('field:validated', function() {
    var ok = $('.parsley-error').length === 0;
    $('.bs-callout-info').toggleClass('hidden', !ok);
    $('.bs-callout-warning').toggleClass('hidden', ok);
  })
  .on('form:submit', function() {
    return false; // Don't submit form for this demo
  });

	});
</script>