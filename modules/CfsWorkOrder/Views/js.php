<script type="text/javascript">
$(document).ready(function() {
	// Error Message element
	$(".err-crno").hide(); //container number check
	// SELECT2
	$('.select-pr').select2();
	$('.select-cccode').select2();
	// datePicker

	// getDetail
	$("#wotype").on("change", function(e){
		e.preventDefault();
		if($("#wotype").val()=="3") {
			$("#tContainer").addClass("hide-block");
		} else {
			$("#tContainer").removeClass("hide-block");
		}
		console.log("change");
	});

	// SAveHeader
	$("form#fWO").on("submit", function(e){
		e.preventDefault();
		$('#saveData').prop('disabled','disabled');
		$.ajax({
			url: "<?php echo site_url('cfswo/add'); ?>",
			type: "POST",
			data: new FormData(this),
			dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,			
			beforeSend: function(){
				$('#saveData').prop('disabled',true);
				// $('#cancel').prop('disabled',true);
			},
			success: function(res) {
				if(res.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+res.message+'</div>'
					});
					$('#saveData').prop('disabled',true);
					$('#contentDetail').removeClass('hide-block');
					$("#wodescbiaya1").focus();
					$("#fWO #wonoid").val(res.data.wonoid);
					$("#formContainer #wono_id").val(res.data.wonoid);
					$("#formContainer #wo_no").val(res.data.wono);
					$("#formContainer #wo_type").val(res.data.wotype);
					$("#formContainer #wo_stok").val(res.data.wostok);

					$("#tblList_add tbody").html(json.dataContainer);

				} else {

					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+res.message+'</div>'
					});						
					$('#saveData').prop('disabled',false);
				}
			}
		});		
	});
	// UpdateHeader
	$("form#fEditWO").on("submit", function(e){
		e.preventDefault();
		$('#saveData').prop('disabled','disabled');
		$.ajax({
			url: "<?php echo site_url('cfswo/edit/'); ?>"+$("form#fEditWO #wonoid").val(),
			type: "POST",
			data: new FormData(this),
			dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,			
			beforeSend: function(){
				$('#saveData').prop('disabled',true);
			},
			success: function(res) {
				if(res.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+res.message+'</div>'
					});
					$('#saveData').prop('disabled',true);
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+res.message+'</div>'
					});						
					$('#saveData').prop('disabled',false);
				}
			}
		});		
	});

	// TAB PROFORMA
	$("form#fReceipt").on("submit", function(e){
		e.preventDefault();
		// $('#saveData').prop('disabled','disabled');
		$.ajax({
			url: "<?php echo site_url('cfswo/insertReceipt/'); ?>"+$("#fWO #wonoid").val(),
			type: "POST",
			data: new FormData(this),
			dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,			
			beforeSend: function(){
				$('#saveDataReceipt').prop('disabled',true);
				// $('#cancel').prop('disabled',true);
			},
			success: function(res) {
				if(res.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+res.message+'</div>'
					});
					$('#saveDataReceipt').prop('disabled',true);
					$('#tab_rab').trigger('click');
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+res.message+'</div>'
					});						
					$('#saveDataReceipt').prop('disabled',false);
				}
			}
		});		
	});
	$("form#fEditReceipt").on("submit", function(e){
		e.preventDefault();
		// $('#saveData').prop('disabled','disabled');
		$.ajax({
			url: "<?php echo site_url('cfswo/update_receipt/'); ?>"+$("#fEditWO #wonoid").val(),
			type: "POST",
			data: new FormData(this),
			dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,			
			beforeSend: function(){
				$('#updateDataReceipt').prop('disabled',true);
				// $('#cancel').prop('disabled',true);
			},
			success: function(res) {
				if(res.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+res.message+'</div>'
					});
					$('#updateDataReceipt').prop('disabled',true);
					$('#tab_rab').trigger('click');
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+res.message+'</div>'
					});						
					$('#updateDataReceipt').prop('disabled',false);
				}
			}
		});		
	});

	// TAB RAB
	$("form#fRab").on("submit", function(e){
		e.preventDefault();
		// $('#saveData').prop('disabled','disabled');
		$.ajax({
			url: "<?php echo site_url('cfswo/insertRab/'); ?>"+$("#fWO #wonoid").val(),
			type: "POST",
			data: new FormData(this),
			dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,			
			beforeSend: function(){
				$('#saveDataRab').prop('disabled',true);
				// $('#cancel').prop('disabled',true);
			},
			success: function(res) {
				if(res.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+res.message+'</div>'
					});
					$('#saveDataRab').prop('disabled',true);
					if($("#fWO #wotype").val()!="3"){
						$('#tab_container').trigger('click');
					}
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+res.message+'</div>'
					});						
					$('#saveDataRab').prop('disabled',false);
				}
			}
		});		
	});
	$("form#fEditRab").on("submit", function(e){
		e.preventDefault();
		// $('#saveData').prop('disabled','disabled');
		$.ajax({
			url: "<?php echo site_url('cfswo/update_rab/'); ?>"+$("#fEditWO #wonoid").val(),
			type: "POST",
			data: new FormData(this),
			dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,			
			beforeSend: function(){
				$('#updateDataRab').prop('disabled',true);
				// $('#cancel').prop('disabled',true);
			},
			success: function(res) {
				if(res.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+res.message+'</div>'
					});
					$('#updateDataRab').prop('disabled',true);
					$('#tab_rab').trigger('click');
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+res.message+'</div>'
					});						
					$('#updateDataRab').prop('disabled',false);
				}
			}
		});		
	});

	// TAB CONTAINER 
	$("form#formContainer").on("submit", function(e){
		e.preventDefault();
		// $('#saveData').prop('disabled','disabled');
		$.ajax({
			url: "<?php echo site_url('cfswo/insertContainer'); ?>",
			type: "POST",
			data: new FormData(this),
			dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,			
			beforeSend: function(){
				$('#saveDataContainer').prop('disabled',true);
				// $('#cancel').prop('disabled',true);
			},
			success: function(res) {
				if(res.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+res.message+'</div>'
					});
					// $('#saveDataContainer').prop('disabled',true);
					$("#tblList_add tbody").html(res.dataContainer);
					$("#formContainer").trigger("reset");
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+res.message+'</div>'
					});						
					$('#saveDataContainer').prop('disabled',false);
					// $("#tblList_add tbody").html(res.dataContainer);
				}
			}
		});		
	});

	$("form#formContainerEdit").on("submit", function(e){
	});

	$("#crno").on("keyup", function(){
		var onstock = $('input:radio[name=wostok]:checked').val();
		var crno = $("#crno").val();
		$(this).val($(this).val().toUpperCase());	
		var url ="";

		if(onstock=="0") {
			url = "<?=site_url('cfswo/checkContainerIn');?>";
		} else if(onstock=="1") {
			url = "<?=site_url('cfswo/checkContainerOut');?>";
		}

		$.ajax({
			url:url,
			type:"POST",
			data: {"crno":crno},
			dataType:"JSON",
			beforeSend: function(){
				$(".err-crno").show();
				$(".err-crno").html("checking...");
			},
			success: function(json) {

				$("#ccode").select2().select2('val','');
				$("#ctcode").val("");
				$("#cclength").val("");
				$("#ccheight").val("");
				if(json.status=="invalid") {

					$(".err-crno").show();
					$(".err-crno").html(json.message);
					$("#crno").css("background", "#ffbfbf!important");
					$("#crno").css("border-color", "#ea2525");
					$("#saveContainer").prop("disabled",true);					

				} else {
					if(json.status=="new") {
						$("#statusContainer").val("new");
					} else {
						$("#statusContainer").val("");
					}

					$(".err-crno").html("");
					$(".err-crno").hide();
					$("#crno").css("background", "#fff!important");
					$("#crno").css("border-color", "#ccc");
					$("#saveContainer").prop("disabled",false);				

					if(json.data!=null) {
						$("#cccode").select2().select2('val',json.data.cccode);
						$("#ctcode").val(json.data.container_code.ctcode);
						$("#cclength").val(json.data.container_code.cclength);
						$("#ccheight").val(json.data.container_code.ccheight);
					}
				}
			}
		});

	});

	$("#cccode").on("change", function(){
		$("#ctcode").val("");
		$("#cclength").val("");
		$("#ccheight").val("");		
		var cccode  = $(this).val();
		$.ajax({
			url:"<?=site_url('prain/ajax_ccode_listOne/');?>"+cccode,
			type:"POST",
			data: "cccode="+cccode,
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


	$("#tblDetail tbody").on("change",".checked_cr", function(e){
		e.preventDefault();
		var item = $(this);
		var item_checked = this.checked ? '1' : '0';
		this.value=item_checked;
		// item.prop("disabled", true);
		console.log(item_checked);
		var WONO = $("#wono").val();
		var CRNO = $(this).closest('tr').find('td:nth-child(3)').text();
		var SVID = $(this).closest('tr').find('td:nth-child(7)').text();
		
		$.ajax({
			url: "<?php echo site_url('cfswo/save_one_detail'); ?>",
			type: "POST",
			data: { "WONO":WONO,"CRNO":CRNO,"SVID":SVID },
			dataType: 'json',
			beforeSend: function(){
				item.prop("disabled", true);
			},
			success: function(res) {
				if(res.status=="success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+res.message+'</div>'
					});
					item.prop("disabled", true);
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+res.message+'</div>'
					});
					item.prop("disabled", false);	
				}
			}
		});
	});
	
	// btn insertContainer
	$("#insertContainer").on("click", function(e){
		$('#containerEditModal').modal('toggle');
		$("#formContainerEdit").trigger('reset');
		$("#formContainerEdit #act").val('add');	
		$("#formContainerEdit #saveDetail").show();
		$("#formContainerEdit #updateDetail").hide();
	});
	
	// edit container
	$('#tblList_edit tbody').on('click', '.edit', function(e){
		e.preventDefault();	
		$('#containerEditModal').modal('toggle');
		$("#formContainerEdit #act").val('edit');
		$("#formContainerEdit #saveDetail").hide();
		$("#formContainerEdit #updateDetail").show();
		var row = $(this).closest("tr");
		var crno = row.find(".crno").text();
		var cccode = row.find(".cccode").text();
		var ctcode = row.find(".ctcode").text();
		var cclength = row.find(".cclength").text();
		var ccheight = row.find(".ccheight").text();
		var fe = row.find(".fe").text();
		var sealno = row.find(".sealno").text();
		var remark = row.find(".remark").text();
		$("#formContainerEdit #crno").val(crno);
		$("#cccode").select2().select2('val',cccode);
		$("#formContainerEdit #ctcode").val(ctcode);
		$("#formContainerEdit #cclength").val(cclength);
		$("#formContainerEdit #ccheight").val(ccheight);
		$("#formContainerEdit #sealno").val(sealno);
		$("#formContainerEdit #remark").val(remark);

	});

	$('#tblDetail tbody').on('click', '.delete', function(e){
		e.preventDefault();	
		var CRNO = $(this).data('crno');
		var SVID = $(this).data('svid');
		Swal.fire({
		  title: 'Are you sure?',
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		  if (result.isConfirmed) {
		  	delete_data(CRNO,SVID);
		  }
		});		
		
	});

	// PRINT
	$("#ctTable tbody").on("click",".print", function(e){
		e.preventDefault();
		var wono = $(this).data('wono');
		window.open("<?php echo site_url('cfswo/print/'); ?>" + wono,'_blank', 'height=900,width=600,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
	});

	// CHECKBOX
	if($("#wopraorderin").val()=="1") {	$("#wopraorderin").prop('checked',true); }
	if($("#wopraorderout").val()=="1") { $("#wopraorderout").prop('checked',true); }
	if($("#wostok").val()=="1") { $("#wostok").prop('checked',true); }

	$("#wopraorderin").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});
	$("#wopraorderout").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});
	$("#wostok").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});	


	// HITUNG TOTAL CHARGE
	// TAB PROFORMA
	$("#fReceipt #wototal_pajak").on("keyup", function(){
		hitungTotalProforma();
	});	
	$("#fReceipt #wobiaya_adm").on("keyup", function(){
		hitungTotal();
	});	
	$("#fReceipt #womaterai").on("keyup", function(){
		hitungTotalProforma();
	});
	$("#fReceipt #wototal_pajak").on("keyup", function(){
		hitungTotalProforma();
	});	
	$("#fReceipt #wototbiaya_lain").on("keyup", function(){
		hitungTotalProforma();
	});
	$("#fReceipt #wototpph23").on("keyup", function(){
		hitungTotalProforma();
	});	

	// Tab RAB
	$("#fRab #wototal_pajak").on("keyup", function(){
		hitungTotalRab();
	});	
	$("#fRab #wobiaya_adm").on("keyup", function(){
		hitungTotalRab();
	});	
	$("#fRab #womaterai").on("keyup", function(){
		hitungTotalRab();
	});
	$("#fRab #wototal_pajak").on("keyup", function(){
		hitungTotalRab();
	});	
	$("#fRab #wototbiaya_lain").on("keyup", function(){
		hitungTotalRab();
	});
	$("#fRab #wototpph23").on("keyup", function(){
		hitungTotalRab();
	});		
	// DATATABLE
	runDataTables();
	var table = $('#ctTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "fixedColumns": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('cfswo/list_data');?>',
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
    // END DATATABLE
});

function hitungTotalProforma() {
	var b1 = parseInt($("#fReceipt #wobiaya1").val());
	var b2 = parseInt($("#fReceipt #wobiaya2").val());
	var b3 = parseInt($("#fReceipt #wobiaya3").val());
	var b4 = parseInt($("#fReceipt #wobiaya4").val());
	var b5 = parseInt($("#fReceipt #wobiaya5").val());
	var b6 = parseInt($("#fReceipt #wobiaya6").val());
	var b7 = parseInt($("#fReceipt #wobiaya7").val());
	var pph = parseInt($("#fReceipt #wototal_pajak").val());
	var adm = parseInt($("#fReceipt #wobiaya_adm").val());
	var materai = parseInt($("#fReceipt #womaterai").val());
	var biaya_lain = parseInt($("#fReceipt #wototbiaya_lain").val());
	var pph23 = parseInt($("#fReceipt #wototpph23").val());
	var total = b1+b2+b3+b4+b5+b6+b7+pph+adm+materai+biaya_lain+pph23;
	$("#fReceipt #wototal_tagihan").val(total);
}
function hitungTotalRab() {
	var b1 = parseInt($("#fRab #wobiaya1").val());
	var b2 = parseInt($("#fRab #wobiaya2").val());
	var b3 = parseInt($("#fRab #wobiaya3").val());
	var b4 = parseInt($("#fRab #wobiaya4").val());
	var b5 = parseInt($("#fRab #wobiaya5").val());
	var b6 = parseInt($("#fRab #wobiaya6").val());
	var b7 = parseInt($("#fRab #wobiaya7").val());
	var b8 = parseInt($("#fRab #wobiaya8").val());
	var b9 = parseInt($("#fRab #wobiaya9").val());
	var b10 = parseInt($("#fRab #wobiaya10").val());
	var b11 = parseInt($("#fRab #wobiaya11").val());
	var b12 = parseInt($("#fRab #wobiaya12").val());
	var b13 = parseInt($("#fRab #wobiaya13").val());
	var b14 = parseInt($("#fRab #wobiaya14").val());
	var b15 = parseInt($("#fRab #wobiaya15").val());
	var b16 = parseInt($("#fRab #wobiaya16").val());
	var b17 = parseInt($("#fRab #wobiaya17").val());
	var b18 = parseInt($("#fRab #wobiaya18").val());
	var b19 = parseInt($("#fRab #wobiaya19").val());
	var b20 = parseInt($("#fRab #wobiaya20").val());
	var pph = parseInt($("#fRab #wototal_pajak").val());
	var adm = parseInt($("#fRab #wobiaya_adm").val());
	var materai = parseInt($("#fRab #womaterai").val());
	var biaya_lain = parseInt($("#fRab #wototbiaya_lain").val());
	var pph23 = parseInt($("#fRab #wototpph23").val());
	var total = b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12+b13+b14+b15+b16+b17+b18+b19+b20+pph+adm+materai+biaya_lain+pph23;
	$("#fRab #wototal_tagihan").val(total);
}
function delete_data(CRNO,SVID) {
	$.ajax({
		url: "<?php echo site_url('cfswo/delete_container'); ?>",
		type: "POST",
		data: {"CRNO":CRNO,"SVID":SVID},
		dataType: 'json',
		success: function(json) {
			if(json.status == "success") {
				Swal.fire({
				  icon: 'success',
				  title: "Success",
				  html: '<div class="text-success">'+json.message+'</div>'
				});							
				window.location.href = "<?php echo site_url('cfswo'); ?>";
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
						$('#ctTable > tbody').html(
				            '<tr class="odd">' +
				              '<td valign="top" colspan="6" class="dataTables_empty">Loading&hellip; <i class="fa fa-gear fa-1x fa-spin"></i></td>' +
				            '</tr>'
				          );
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