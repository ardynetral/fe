<script type="text/javascript">
	var selectedDate;
	$(document).ready(function() {
		$("#startDate").datepicker('setDate', new Date());
		// SELECT2
		$('.select-pr').select2();
		$('.select-length').select2();
		$('.select-billtype').select2();
		$("#printPdf").on("click", function(e) {
			window.open("<?php echo site_url('losc/reportPdf'); ?>", '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
			e.preventDefault();
		});
		$("#printExl").on("click", function(e) {
			window.open("<?php echo site_url('losc/reportExcel'); ?>");
			e.preventDefault();
		});

	});
</script>