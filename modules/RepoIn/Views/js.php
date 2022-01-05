<script type="text/javascript">
$(document).ready(function() {
	// Error Message element
	$(".err-crno").hide(); //container number check

	// SELECT2
	$('.select-pr').select2();
	$('.select-port').select2();
	$('.select-depo').select2();
	$('.select-city').select2();
	$('.select-vessel').select2();
	$('.select-voyage').select2();
	$('.select-ccode').select2();
	$('.selects').select2();
	$('#rebilltype').select2();
	$('#retype').select2().prop('disabled', true);
	// DATATABLE
	// $("#ctTable").DataTable({
 //        "paging": false,
 //        "info": false,		
	// });
	// datePicker
	$(".tanggal").datepicker({
		autoclose:true,
		format:'dd-mm-yyyy',
		startDate: '-5y'
	});

	$("form#formOrderRepo").on("submit", function(e){
		e.preventDefault();																														
		// window.scrollTo(xCoord, yCoord);
		$.ajax({
			url: "<?php echo site_url('repoin/add'); ?>",
			type: "POST",
			data:  new FormData(this),
            processData: false,
            contentType: false,
            cache: false,			
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});		
					$("#repoid").val(json.repoid);
					$("#saveData").prop('disabled', true);
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

	$("#updateNewData").on("click", function(e){
		e.preventDefault();
		var fomData = "reorderno=" + $("#reorderno").val();
		fomData += "&revlift=" + parseInt($("#revlift").val());
		fomData += "&revdoc=" + parseInt($("#revdoc").val());
		fomData += "&re20=" + parseInt($("#re20").val());
		fomData += "&retot20=" + parseInt($("#retot20").val());
		fomData += "&re40=" + parseInt($("#re40").val());
		fomData += "&retot40=" + parseInt($("#retot40").val());
		fomData += "&re45=" + parseInt($("#re45").val());
		fomData += "&retot45=" + parseInt($("#retot45").val());
		fomData += "&subtotbreak=" + parseInt($("#subtotbreak").val());
		fomData += "&subtotpack=" + parseInt($("#subtotpack").val());
		fomData += "&totpack=" + parseInt($("#totpack").val());
		fomData += "&totbreak=" + parseInt($("#totbreak").val());
		fomData += "&revpack20=" + parseInt($("#revpack20").val());
		fomData += "&revpacktot20=" + parseInt($("#revpacktot20").val());
		fomData += "&revpack40=" + parseInt($("#revpack40").val());
		fomData += "&revpacktot40=" + parseInt($("#revpacktot40").val());
		fomData += "&revpack45=" + parseInt($("#revpack45").val());
		fomData += "&revpacktot45=" + parseInt($("#revpacktot45").val());
		fomData += "&reother1=" + parseInt($("#reother1").val());
		fomData += "&reother2="	+ parseInt($("#reother2").val());
		$.ajax({
			url: "<?php echo site_url('repoin/update_new_data'); ?>",
			type: "POST",
			data:  fomData,			
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});		
					window.location.href = "<?php echo site_url('repoin'); ?>";
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

	$("form#fUpdateOrderRepo").on("submit", function(e){
		e.preventDefault();	
		$.ajax({
			url: "<?php echo site_url('repoin/edit/'); ?>"+$("#reorderno").val(),
			type: "POST",
			data:  new FormData(this),
            processData: false,
            contentType: false,
            cache: false,			
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});		
					window.location.href = "<?php echo site_url('repoin'); ?>";
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

	$("#liftoffcharge").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});

	$("#cpishold").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});

	// $("#revlift").on("keyup",function(){
	// 	// console.log("hitung...");
	// 	// $("#hitung_subtotal1").trigger('click');
	// 	alert("hahaha");
	// });
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

	function delete_data(code) {
		$.ajax({
			url: "<?php echo site_url('repoin/delete/'); ?>"+code,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('repoin'); ?>";
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

	// STEP 1 :
	$("#prcode").on("change", function(){
		$('#retype').select2().prop('disabled', false);
		var cpopr = $(this).val();
		$.ajax({
			url:"<?=site_url('repoin/ajax_prcode_listOne/');?>"+cpopr,
			type:"POST",
			data: "cpopr="+cpopr,
			dataType:"JSON",
			success: function(json){
				if(json.status=="Failled") {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});		
				} else {
					$("#cpcust").val(json.data.cucode);
				}
			}
		});
	}) ;

	// Vessel dropdown change
	$("#cpives").on("change", function(){
		var vesid = $(this).val();
		$.ajax({
			url:"<?=site_url('prain/ajax_vessel_listOne/');?>"+vesid,
			type:"POST",
			data: "vesid="+vesid,
			dataType:"JSON",
			success: function(json){
				if(json.status=="Failled") {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});		
				} else {
					$("#cpopr").val(json.data.vesopr);
				}
			}
		});		
	});


	// STEP 2 : 
	//Get Container Code detail
	$("#ccode").on("change", function(){
		var cccode  = $(this).val();
		$.ajax({
			url:"<?=site_url('prain/ajax_ccode_listOne/');?>"+cccode,
			type:"POST",
			data: "ccode="+ccode,
			dataType:"JSON",
			success: function(json){
				if(json.status=="Failled") {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});		
				} else {
					$("#ctcode").val(json.data.ctcode);
					$("#cclength").val(json.data.cclength);
					$("#ccheight").val(json.data.ccheight);
				}

			}
		});
	});

	$("#reother1").on("keyup", function(){
		var subtotbreak = parseInt($("#subtotbreak").val());
		var totbreak = subtotbreak + parseInt($(this).val()) + parseInt($("#reother2").val());
		$("#totbreak").val(totbreak);

		var subtotpack = parseInt($("#subtotpack").val());
		var totpack = subtotpack + parseInt($(this).val()) + parseInt($("#reother2").val());
		$("#totpack").val(totpack);
	});

	$("#reother2").on("keyup", function(){
		var subtotbreak = parseInt($("#subtotbreak").val());
		var totbreak = subtotbreak + parseInt($(this).val()) + parseInt($("#reother1").val());
		$("#totbreak").val(totbreak);

		var subtotpack = parseInt($("#subtotpack").val());
		var totpack = subtotpack + parseInt($(this).val()) + parseInt($("#reother1").val());
		$("#totpack").val(totpack);
	});	
	// save detailContainer
	$("#saveDetail").on("click", function(e){
		e.preventDefault();
		var formData = "repoid=" + $("#repoid").val();
		formData += "&crno=" + $("#crno").val();
		formData += "&ccode=" + $("#ccode").val();
		formData += "&ctcode=" + $("#ctcode").val();
		formData += "&cclength=" + $("#cclength").val();
		formData += "&ccheight=" + $("#ccheight").val();
		formData += "&cpife=" + $("#cpife").val();
		formData += "&cpishold=" + $("#cpishold").val();
		formData += "&reporemark=" + $("#reporemark").val();
		
		$.ajax({
			url: "<?php echo site_url('repoin/addcontainer'); ?>",
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
					$("#formDetail").trigger("reset");
					$("#ccode").select2().select2('val','');
					// insert value quantity
					$("#std20").val(json.QTY.std20);
					$("#std40").val(json.QTY.std40);
					$("#hc20").val(json.QTY.hc20);
					$("#hc40").val(json.QTY.hc40);
					$("#hc45").val(json.QTY.hc45);

					var reother1 = parseInt($("#reother1").val());
					var reother2 = parseInt($("#reother2").val());

					// breakdown
					var revlift = parseInt($("#revlift").val());
					var revdoc = parseInt($("#revdoc").val());
					var re20 = parseInt($("#re20").val());
					var re40 = parseInt($("#re40").val());
					var re45 = parseInt($("#re45").val());
					var retot20 = parseInt(json.QTY.std20) * re20; 
					var retot40 = parseInt(json.QTY.std40) * re40; 
					var retot45 = parseInt(json.QTY.std45) * re45; 
					$("#retot20").val(retot20);
					$("#retot40").val(retot40);
					$("#retot45").val(retot45);
					var subtotbreak = revlift+revdoc+retot20+retot40+retot45;
					$("#subtotbreak").val(subtotbreak);
					var totbreak = subtotbreak+reother1+reother2;
					$("#totbreak").val(totbreak);

					// pack
					var revpack20 = parseInt($("#revpack20").val());
					var revpack40 = parseInt($("#revpack40").val());
					var revpack45 = parseInt($("#revpack45").val());
					var revpacktot20 = parseInt(json.QTY.hc20) * revpack20; 
					var revpacktot40 = parseInt(json.QTY.hc40) * revpack40; 
					var revpacktot45 = parseInt(json.QTY.hc45) * revpack45; 					
					$("#revpacktot20").val(revpacktot20);
					$("#revpacktot40").val(revpacktot40);
					$("#revpacktot45").val(revpacktot45);
					var subtotpack = revpacktot20+revpacktot40+revpacktot45;
					$("#subtotpack").val(subtotpack);
					var totpack = subtotpack+reother1+reother2;
					$("#totpack").val(totpack);

					loadTableContainer($("#repoid").val());

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

	$("#crno").on("keyup", function(){
		var crno = $("#crno").val();
		var status = "";
		$.ajax({
			url:"<?=site_url('prain/checkContainerNumber');?>",
			type:"POST",
			data: "ccode="+crno,
			dataType:"JSON",
			success: function(json){
				if(json.status=="Failled") {
					$(".err-crno").show();
					$(".err-crno").html(json.message);
					$("#crno").css("background", "#ffbfbf!important");
					$("#crno").css("border-color", "#ea2525");					
				} else {
					$(".err-crno").html("");
					$(".err-crno").hide();
					$("#crno").css("background", "#fff!important");
					$("#crno").css("border-color", "#ccc");
				}
			}
		});
	});

	// End Step 2
	$('#ctTable tbody').on("click",".print_order", function(e){
		e.preventDefault();
		var praid = $(this).data("praid");
        window.open("<?php echo site_url('prain/print_order/'); ?>" + praid, '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
	});

	//repo type
	$("#fromDepoBlok").children().attr('disabled', true);
	$("#fromPortBlok").children().attr('disabled', true);
	$("#fromCityBlok").children().attr('disabled', true);

	$("#toPortBlok").children().attr('disabled', true);
	$("#toDepoBlok").children().attr('disabled', true);
	$("#toCityBlok").children().attr('disabled', true);

	$("#retype").on("change", function(){
		let val = $(this).val();
		let prcode = $("#prcode").val();
		// DEPO TO DEPO OUT
		if (val == '11' || val == '11') {
			$('#fromDepoBlok').removeClass('hideBlock');
			$("#fromDepoBlok").children().attr('disabled', false);
			$('#fromPortBlok').addClass('hideBlock');
			$("#fromPortBlok").children().attr('disabled', true);
			$('#fromCityBlok').addClass('hideBlock');
			$("#fromCityBlok").children().attr('disabled', true);

			$('#toDepoBlok').removeClass('hideBlock');
			$("#toDepoBlok").children().attr('disabled', false);
			$('#toPortBlok').addClass('hideBlock');
			$("#toPortBlok").children().attr('disabled', true);
			$('#toCityBlok').addClass('hideBlock');
			$("#toCityBlok").children().attr('disabled', true);
		
		// DEPO TO PORT
		} else if(val == '12') {
			$('#fromDepoBlok').removeClass('hideBlock');
			$("#fromDepoBlok").children().attr('disabled', false);
			$('#toPortBlok').removeClass('hideBlock');
			$("#toPortBlok").children().attr('disabled', false);
			$('#fromCityBlok').addClass('hideBlock');
			$("#fromCityBlok").children().attr('disabled', true);

			$("#toDepoBlok").addClass('hideBlock');
			$("#toDepoBlok").children().attr('disabled', true);
			$('#fromPortBlok').addClass('hideBlock');
			$("#fromPortBlok").children().attr('disabled', true);
			$('#toCityBlok').addClass('hideBlock');
			$("#toCityBlok").children().attr('disabled', true);

		// DEPO TO INTERCITY
		}  else if(val == '13'){
			$('#fromDepoBlok').removeClass('hideBlock');
			$("#fromDepoBlok").children().attr('disabled', false);
			$('#fromPortBlok').addClass('hideBlock');
			$("#fromPortBlok").children().attr('disabled', true);
			$('#fromCityBlok').addClass('hideBlock');
			$("#fromCityBlok").children().attr('disabled', true);

			$('#toPortBlok').addClass('hideBlock');
			$("#toPortBlok").children().attr('disabled', true);
			$('#toDepoBlok').addClass('hideBlock');
			$("#toDepoBlok").children().attr('disabled', true);
			$('#toCityBlok').removeClass('hideBlock');
			$("#toCityBlok").children().attr('disabled', false);

		// DEPO TO DEPO IN
		} else if(val == '21'){
			$('#fromDepoBlok').removeClass('hideBlock');
			$("#fromDepoBlok").children().attr('disabled', false);
			$('#fromPortBlok').addClass('hideBlock');
			$("#fromPortBlok").children().attr('disabled', true);
			$('#fromCityBlok').addClass('hideBlock');
			$("#fromCityBlok").children().attr('disabled', true);

			$('#toDepoBlok').removeClass('hideBlock');
			$("#toDepoBlok").children().attr('disabled', false);
			$('#toPortBlok').addClass('hideBlock');
			$("#toPortBlok").children().attr('disabled', true);
			$('#toCityBlok').addClass('hideBlock');
			$("#toCityBlok").children().attr('disabled', true);

		// PORT TO DEPO
		}  else if(val == '22'){
			$('#fromDepoBlok').addClass('hideBlock');
			$("#fromDepoBlok").children().attr('disabled', true);
			$('#fromPortBlok').removeClass('hideBlock');
			$("#fromPortBlok").children().attr('disabled', false);
			$('#fromCityBlok').addClass('hideBlock');
			$("#fromCityBlok").children().attr('disabled', true);	

			$('#toPortBlok').addClass('hideBlock');
			$("#toPortBlok").children().attr('disabled', true);
			$('#toDepoBlok').removeClass('hideBlock');
			$("#toDepoBlok").children().attr('disabled', false);
			$('#toCityBlok').addClass('hideBlock');
			$("#toCityBlok").children().attr('disabled', true);	

		// INTRCITY TO DEPO
		}   else if(val == '23'){
			$('#fromDepoBlok').addClass('hideBlock');
			$("#fromDepoBlok").children().attr('disabled', true);
			$('#fromPortBlok').addClass('hideBlock');
			$("#fromPortBlok").children().attr('disabled', true);
			$('#fromCityBlok').removeClass('hideBlock');
			$("#fromCityBlok").children().attr('disabled', false);			

			$('#toPortBlok').addClass('hideBlock');
			$("#toPortBlok").children().attr('disabled', true);
			$('#toDepoBlok').removeClass('hideBlock');
			$("#toDepoBlok").children().attr('disabled', false);
			$('#toCityBlok').addClass('hideBlock');
			$("#toCityBlok").children().attr('disabled', true);			

		} else {
			$('#fromDepoBlok').addClass('hideBlock');
			$("#fromDepoBlok").children().attr('disabled', true);
			$('#fromPortBlok').addClass('hideBlock');
			$("#fromPortBlok").children().attr('disabled', true);			
			$('#fromCityBlok').addClass('hideBlock');
			$("#fromCityBlok").children().attr('disabled', true);

			$('#toPortBlok').addClass('hideBlock');
			$("#toPortBlok").children().attr('disabled', true);
			$('#toDepoBlok').addClass('hideBlock');
			$("#toDepoBlok").children().attr('disabled', true);
			$('#toCityBlok').addClass('hideBlock');
			$("#toCityBlok").children().attr('disabled', true);
		}

		$.ajax({
			url:"<?=site_url('repoin/get_repo_tariff_detail');?>",
			type:"POST",
			data: {'prcode':prcode,'retype':val},
			dataType:"JSON",
			success: function(json){
				if(json.status=="Failled") {
					// breakdown
					$("#revlift").val("0");
					$("#revdoc").val("0");
					$("#re20").val("0");
					$("#re40").val("0");
					$("#re45").val("0");
					// package
					$("#revpack20").val("0");
					$("#revpack40").val("0");
					$("#revpack45").val("0");
				} else {
					console.log(json.data);
					// breakdown
					$("#revlift").val(json.contract.coadmv);
					$("#revdoc").val(json.data.rtdocv);
					$("#re20").val(json.data.rthaulv20);
					$("#re40").val(json.data.rthaulv40);
					$("#re45").val(json.data.rthaulv45);
					// package
					$("#revpack20").val(json.data.rtpackv20);
					$("#revpack40").val(json.data.rtpackv40);
					$("#revpack45").val(json.data.rtpackv45);
				}
			}
		});

	});

	$("#rebilltype").on("change", function(){
		var billType = $(this).val();
		if (billType==='1') {
			// showBreakDown();
			$("#breakDown").show();			
			$("#Package").hide();			
			$("#totpack").hide();			
			$("#totbreak").show();			
		} else if (billType==='2'){
			$("#breakDown").hide();			
			$("#Package").show();
			$("#totpack").show();			
			$("#totbreak").hide();				
		} else {
			$("#breakDown").hide();			
			$("#Package").hide();			
		}
	});

	// Print Kitir
	$("#rcTable tbody").on('click','.cetak_kitir', function(e){
		e.preventDefault();
		var repoid = $(this).attr('data-repoid');
		var crno = $(this).attr('data-crno');
		var reorderno = $(this).attr('data-reorderno');
		window.open("<?php echo site_url('repoin/cetak_kitir/'); ?>" + crno + "/" + reorderno + "/" + repoid, '_blank', 'height=900,width=600,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
	});

	// Datatable
	runDataTables();
	var table = $('#ctTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "fixedColumns": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('repoin/list_data');?>',
            pages: 5  
        } )
        ,
        sDom: 'T<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"pull-right"ip>>>',
        PaginationType : "bootstrap", 
        oLanguage: { "sSearch": "",
            "sLengthMenu" : "_MENU_ &nbsp;"}
    });
	
	$('.dataTables_filter input').attr("placeholder", "Search");
    $('.DTTT_container').css('display','none');
    $('.DTTT').css('display','none');

});

function loadTableContainer(repoid) {
	$('#rcTable tbody').html("");
	$.ajax({
		url: "<?=site_url('repoin/ajax_repo_containers')?>",
		type:"POST",
		data: "repoid="+repoid,
		dataType: "json",
		success: function(json) {
			$('#rcTable tbody').html(json);
		}
	});
}

function showBreakDown() {
	$("#breakDownBill").html(
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">Lift On Adm</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+				
			'<div class="col-sm-4">'+
				'<input type="text" name="revlift" class="form-control" id="revlift" value="<?=@$_SESSION['revlift'];?>" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">Doc Fee</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="revdoc" class="form-control" id="revdoc" value="<?=@$_SESSION['revdoc'];?>" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">Haulage 20"</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="re20" class="form-control" id="re20" value="<?=@$_SESSION['rthaulv20'];?>" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">Total Haulage 20"</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="retot20" class="form-control" id="retot20" value="0" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">Haulage 40"</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="re40" class="form-control" id="re40" value="<?=@$_SESSION['rthaulv40'];?>" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">Total Haulage 40"</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="retot40" class="form-control" id="retot40" value="0" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">Haulage 45"</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="re45" class="form-control" id="re45" value="<?=@$_SESSION['rthaulv45'];?>" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">Total Haulage 45"</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="retot45" class="form-control" id="retot45" value="0" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 1</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="SUBTOT" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="subtotbreak" class="form-control" id="subtotbreak" value="0" required readonly>'+
			'</div>'+
		'</div>'
	);

}

function showPackage() {
	$("#breakDownBill").html(
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">20"</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="revpack20" class="form-control" id="revpack20" value="<?=@$_SESSION['rtpackv20'];?>" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">Total 20"</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="revpacktot20" class="form-control" id="revpacktot20" value="0" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">40"</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="revpack40" class="form-control" id="revpack40" value="<?=@$_SESSION['rtpackv40'];?>" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">Total 40"</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="revpacktot40" class="form-control" id="revpacktot40" value="0" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">45"</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="revpack45" class="form-control" id="revpack45" value="<?=@$_SESSION['rtpackv45'];?>" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right">Total45"</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="IDR" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="revpacktot45" class="form-control" id="revpacktot45" value="0" required>'+
			'</div>'+
		'</div>'+
		'<div class="form-group">'+
			'<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 1</label>'+
			'<div class="col-sm-2">'+
				'<input type="text" name="" class="form-control" id="" value="SUBTOT" readonly>'+
			'</div>'+			
			'<div class="col-sm-4">'+
				'<input type="text" name="subtotpack" class="form-control" id="subtotpack" value="0" readonly required>'+
			'</div>'+
		'</div>'
	);
}

function totalBreak() {
	
}
function totalPack() {
	var totpack = subtotpack+reother1+reother2;
	$("#totpack").val(totpack);
}

function runDataTables() {		
    $.fn.dataTable.pipeline = function ( opts ) { 
        var conf = $.extend({
            pages: 5,      
            url: '',      
            data: null,    
            method: 'POST'  
        }, opts);

        var cacheLower = -1;
        var cacheUpper = null;
        var cacheLastRequest = null;
        var cacheLastJson = null;

        return function (request, drawCallback, settings) {
            
			var ajax = true;
            var requestStart = request.start;
            var drawStart = request.start;
            var requestLength = request.length;
            var requestEnd = requestStart + requestLength;

            if (settings.clearCache) { 
                ajax = true;
                settings.clearCache = false;
            }
            else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) { 
                ajax = true;
            }
            else if (JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
                JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
        ) { 
                ajax = true;
            }

            cacheLastRequest = $.extend(true, {}, request);

            if (ajax) { 

                cacheLower = requestStart;
                cacheUpper = requestStart + (requestLength * conf.pages);

                request.start = requestStart;
                request.length = requestLength * conf.pages;
                request.startdate = $("#startdate").val();
                request.enddate = $("#enddate").val();
                request.rows = requestLength;

                if ($.isFunction(conf.data)) {
                   
                    var d = conf.data(request);
                    if (d) {
                        $.extend(request, d);
                    }
                }
                else if ($.isPlainObject(conf.data)) { 
                    $.extend(request, conf.data);
                }

                settings.jqXHR = $.ajax({
                    "type": conf.method,
                    "url": conf.url,
                    "data": request,
                    "dataType": "json",
                    "cache": false,
					"beforeSend": function(){
						$("#spinner").show();
						$("#SearchSC").attr("disabled","disabled");
						$("#SearchSC").append('<i class="fa fa-gear fa-1x fa-spin"></i>');
					},
                    "success": function (json) {
						$("#spinner").hide();
						$(".fa-spin").remove();
						$("#SearchSC").removeAttr("disabled");
                        cacheLastJson = $.extend(true, {}, json);

                        if (cacheLower != drawStart) {
                            json.data.splice(0, drawStart - cacheLower);
                        }
                        json.data.splice(requestLength, json.data.length);

                        drawCallback(json);
                    }
                });
            }
            else {
                json = $.extend(true, {}, cacheLastJson);
                json.draw = request.draw;  
                json.data.splice(0, requestStart - cacheLower);
                json.data.splice(requestLength, json.data.length);

                drawCallback(json);
            }
        }
    } 	   
}

</script>