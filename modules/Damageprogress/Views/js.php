<script type="text/javascript">
	var selectedDate;
	$(document).ready(function() {
		$("#startDate").datepicker({
			autoclose: true,
			format: 'dd/mm/yyyy'
		});
		$("#startDate").datepicker('setDate', new Date());
		// SELECT2
		$('.select-pr').select2();

		$("#printPdf").on("click", function(e) {
			var prcode = $("#prcode").val();
			window.open("<?php echo site_url('damageprogress/reportPdf/'); ?>" + prcode, '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
			e.preventDefault();
		});
		$("#printExl").on("click", function(e) {
			var prcode = $("#prcode").val();
			window.open("<?php echo site_url('damageprogress/reportExcel/'); ?>" + prcode);
			e.preventDefault();
		});

	});
</script>