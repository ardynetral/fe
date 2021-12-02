<script type="text/javascript">
$(document).ready(function() {
	// Error Message element
	$(".err-crno").hide(); //container number check

	// SELECT2
	$('.select-pr').select2();
	$('.select-port').select2();
	$('.select-depo').select2();
	$('.select-vessel').select2();
	$('.select-voyage').select2();
	$('.select-ccode').select2();
	$('.selects').select2();
	// DATATABLE
	// $("#ctTable").DataTable({
 //        "paging": false,
 //        "info": false,		
	// });
	// datePicker
	$(".tanggal").datepicker({
		autoclose:true,
	});

	$("#saveData").click(function(e){
		e.preventDefault();																														
		// window.scrollTo(xCoord, yCoord);
		var formData = "cpiorderno=" + $("#cpiorderno").val();
		formData += "&cpiopr=" + $("#prcode").val();
		formData += "&cpicust=" + $("#cucode").val();
		formData += "&cpidish=" + $("#cpidish").val();
		formData += "&cpidisdat=" + $("#cpidisdat").val();
		formData += "&liftoffcharge=" + $("#liftoffcharge").val();
		formData += "&cpdepo=" + $("#cpdepo").val();
		formData += "&cpipratgl=" + $("#cpipratgl").val();
		formData += "&cpirefin=" + $("#cpirefin").val();
		formData += "&cpijam=" + $("#cpijam").val();
		formData += "&cpives=" + $("#cpives").val();
		formData += "&cpivoyid=" + $("#cpivoyid").val();
		formData += "&cpicargo=" + $("#cpicargo").val();
		formData += "&cpideliver=" + $("#cpideliver").val();
		// alert(formData);
		// console.log("data="+formData);

		$.ajax({
			url: "<?php echo site_url('prain/add'); ?>",
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
					// window.location.href = "<?php echo site_url('prain'); ?>";
					$("#navItem3").removeClass("disabled");
					$("#navLink3").attr("data-toggle","tab");
					$("#navLink3").trigger("click");	
					$("#praid").val(json.praid);				
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

	$("#liftoffcharge").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});

	$("#cpishold").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});

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
			url: "<?php echo site_url('city/delete/'); ?>"+code,
			type: "POST",
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
	}

	// STEP 1 :
	$("#prcode").on("change", function(){
		var prcode = $(this).val();
		$.ajax({
			url:"<?=site_url('prain/ajax_prcode_listOne/');?>"+prcode,
			type:"POST",
			data: "prcode="+prcode,
			dataType:"JSON",
			success: function(json){
				if(json.status=="Failled") {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});		
				} else {
					$("#cucode").val(json.data.cucode);
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


	// save detailContainer
	$("#saveDetail").on("click", function(e){
		e.preventDefault();
		var formData = "praid=" + $("#praid").val();
		formData += "&crno=" + $("#crno").val();
		formData += "&ccode=" + $("#ccode").val();
		formData += "&ctcode=" + $("#ctcode").val();
		formData += "&cclength=" + $("#cclength").val();
		formData += "&cpife=" + $("#cpife").val();
		formData += "&cpishold=" + $("#cpishold").val();
		formData += "&cpiremark=" + $("#cpiremark").val();
		
		$.ajax({
			url: "<?php echo site_url('prain/addcontainer'); ?>",
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
					// window.location.href = "<?php echo site_url('prain/view/'); ?>"+json.praid;
					window.location.href = "<?php echo site_url('prain'); ?>";
					// getDetailOrder(json.praid);
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

	$("#toPortBlok").children().attr('disabled', true);
	$("#toDepoBlok").children().attr('disabled', true);

	$("#repoType").on("change", function(){
		let val = $(this).val();
		console.log(val);
		// let type;
		// switch(val) {
		// 	case 'DTDOUT':
		// 		type = '1';
		// 		break;
		// 	case 'DTP':
		// 		type = '2';
		// 		break;
		// 	case 'DTI':
		// 		type = '3';
		// 		break;
		// 	case 'DTDIN':
		// 		type = '4';
		// 		break;
		// 	case 'PTD':
		// 		type = '3';
		// 		break;
		// 	case 'ITD':
		// 		type = '4';
		// 		break;
		// 	default:
		// 	type = '0';
		// } 
		if (val == 'DTDOUT' || val == 'DTDOUT') {
			$('#fromDepoBlok').removeClass('hideBlock');
			$("#fromDepoBlok").children().attr('disabled', false);
			$('#toDepoBlok').removeClass('hideBlock');
			$("#toDepoBlok").children().attr('disabled', false);

			$('#fromPortBlok').addClass('hideBlock');
			$("#fromPortBlok").children().attr('disabled', true);
			$('#toPortBlok').addClass('hideBlock');
			$("#toPortBlok").children().attr('disabled', true);
		} else if(val == 'DTP') {
			$('#fromDepoBlok').removeClass('hideBlock');
			$("#fromDepoBlok").children().attr('disabled', false);
			$('#toPortBlok').removeClass('hideBlock');
			$("#toPortBlok").children().attr('disabled', false);

			$("#toDepoBlok").addClass('hideBlock');
			$("#toDepoBlok").children().attr('disabled', true);
			$('#fromPortBlok').addClass('hideBlock');
			$("#fromPortBlok").children().attr('disabled', true);
		}  else if(val == 'PTD'){
			$('#fromDepoBlok').removeClass('hideBlock');
			$("#fromDepoBlok").children().attr('disabled', false);
			$('#fromPortBlok').removeClass('hideBlock');
			$("#fromPortBlok").children().attr('disabled', false);

			$('#toPortBlok').addClass('hideBlock');
			$("#toPortBlok").children().attr('disabled', true);
			$('#toDepoBlok').addClass('hideBlock');
			$("#toDepoBlok").children().attr('disabled', true);
		} else {
			$('#fromDepoBlok').addClass('hideBlock');
			$("#fromDepoBlok").children().attr('disabled', true);
			$('#fromPortBlok').addClass('hideBlock');
			$("#fromPortBlok").children().attr('disabled', true);

			$('#toPortBlok').addClass('hideBlock');
			$("#toPortBlok").children().attr('disabled', true);
			$('#toDepoBlok').addClass('hideBlock');
			$("#toDepoBlok").children().attr('disabled', true);
		}
	});

	$("#billType").on("change", function(){
		if ($(this).val() == 'B') {
			$(".breakdownBill").removeClass('hideBlock');
		} else {
			$(".breakdownBill").addClass('hideBlock');
		}
	});

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