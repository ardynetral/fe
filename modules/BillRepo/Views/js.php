<script type="text/javascript">
var selectedDate;
$(document).ready(function() {
	$("#startDate").datepicker('setDate', new Date());
	$("#endDate").datepicker('setDate', new Date());
	// SELECT2
	$('.select-pr').select2();
	$('.select-length').select2();
	$('.select-billtype').select2();
	$("#printPdf").on("click", function(e) {
		let depot = $('#depot option:selected');
		let principal = $('#prcode option:selected');
		let startDate = $('#startDate').val();
		let endDate = $('#endDate').val();
		let vessel = $('#vessel option:selected');
		let voyage = $('#voyage').val();
		let billNo = $('#billNo').val();
		let outDepot = $('#outDepot').val();
		window.open(`<?php echo site_url('billrepo/reportPdf/'); ?>${depot}/${principal}/${startDate}/${endDate}/${vessel}/${voyage}/${billNo}/${outDepot}`, '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
		e.preventDefault();
	});
	$("#printExl").on("click", function(e) {
		window.open("<?php echo site_url('billrepo/reportExcel'); ?>");
		e.preventDefault();
	});

});
</script>