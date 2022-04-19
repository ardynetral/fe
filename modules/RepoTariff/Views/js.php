<script type="text/javascript">
$(document).ready(function() {
	// SELECT2
	// $('.select-cncode').select2();
	$('.select-pr').select2();
	// datePicker
	$(".tanggal").datepicker({
		autoclose:true,
	});

	// DATATABLE
	runDataTables();
	var table = $('#ctTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "fixedColumns": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('repotariff/list_data');?>',
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

	$("form#Rtheader").on("submit", function(e){
		e.preventDefault();
		$.ajax({
			url: "<?php echo site_url('repotariff/add'); ?>",
			type: "POST",
			data: new FormData(this),
			dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,				
			success: function(json) {
				if(json.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});
					$("#tariffDetail #prcode").val($("#prcode").val());
					$("#tariffDetail #rtno").val($("#rtno").val());
					$("#saveData").prop("disabled", true);
					$("#addDetail").prop("disabled", false);
					$("#repoTariffDetailModal").modal("toggle")
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


// DETAIL
	// INSERT
	$("form#tariffDetail").on("submit", function(e){
		e.preventDefault();
		var prcode = $("#prcode").val();
		var rtno = $("#rtno").val();
		$.ajax({
			url: "<?php echo site_url('repotariff/add_detail'); ?>",
			type: "POST",
			data: new FormData(this),
			dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,				
			success: function(json) {
				if(json.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});
					$("#tariffDetail").trigger("reset");
					// do reload table detail
					load_detail_table(prcode, rtno);
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

	// EDIT
	$("form#editTariffDetail").on("submit", function(e){
		e.preventDefault();
		var prcode = $("#prcode").val();
		var rtno = $("#rtno").val();
		$.ajax({
			url: "<?php echo site_url('repotariff/edit_detail'); ?>",
			type: "POST",
			data: new FormData(this),
			dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,				
			success: function(json) {
				if(json.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});
					$("#tariffDetail").trigger("reset");
					// do reload table detail
					$("#editDetailModal").modal("toggle");
					load_detail_table(prcode,rtno);
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
	$("#rt-detail tbody").on("click",".edit", function(e){
		e.preventDefault();
		var prcode = $(this).data("prcode");
		var rtno = $(this).data("rtno");
		var rttype = $(this).data("rttype");
		var rtef = $(this).data("rtef");
		$.ajax({
			url:"<?php echo site_url('repotariff/get_one_detail/')?>"+prcode+"/"+rtno+"/"+rttype+"/"+rtef,
			type:"POST",
			dataType:"JSON",
			success: function(json) {
				if(json.status=="Failled") {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});		
				} else {
					$("#editDetailModal").modal("toggle");
					// $("#rtef").val()
					if(json.data.rtef=="1") {
						$('input:radio[name=rtef]').filter('[value=1]').prop('checked', true);
					} else if(json.data.rtef=="0") {
						$('input:radio[name=rtef]').filter('[value=0]').prop('checked', true);
					}
					$("#editTariffDetail #prcode").val(json.data.prcode);
					$("#editTariffDetail #rtno").val(json.data.rtno);
					$("#editTariffDetail #rtid").val(json.data.rtid);
					$("#editTariffDetail #rtpackv20").val(json.data.rtpackv20);
					$("#editTariffDetail #rtpackv40").val(json.data.rtpackv40);
					$("#editTariffDetail #rtpackv45").val(json.data.rtpackv45);
					$("#editTariffDetail #rtlon20").val(json.data.rtlon20);
					$("#editTariffDetail #rtlon40").val(json.data.rtlon40);
					$("#editTariffDetail #rtlon45").val(json.data.rtlon45);					
					$("#editTariffDetail #rtlof20").val(json.data.rtlof20);
					$("#editTariffDetail #rtlof40").val(json.data.rtlof40);
					$("#editTariffDetail #rtlof45").val(json.data.rtlof45);
					$("#editTariffDetail #rtdocm").val(json.data.rtdocm);
					$("#editTariffDetail #rtdocv").val(json.data.rtdocv);
					$("#editTariffDetail #rthaulv20").val(json.data.rthaulv20);
					$("#editTariffDetail #rthaulv40").val(json.data.rthaulv40);
					$("#editTariffDetail #rthaulv45").val(json.data.rthaulv45);
					getRepoType(json.data.rttype);

					$("#rtid").attr("readonly","readonly");
					$("#retype").attr("readonly","readonly");
					$("#rtef").attr("readonly","readonly");
				}
			}
		});
	});

	$('#rt-detail tbody').on('click', '.delete', function(e){
		e.preventDefault();	
		var prcode = $(this).data("prcode");
		var rtno = $(this).data("rtno");
		var rttype = $(this).data("rttype");
		var rtef = $(this).data("rtef");
		Swal.fire({
		  title: 'Are you sure?',
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		  if (result.isConfirmed) {
		  	delete_data(prcode,rtno,rttype,rtef);
		  }
		});		
		
	});

	function delete_data(prcode,rtno,rttype,rtef) {
		$.ajax({
			url: "<?php echo site_url('repotariff/delete_detail/'); ?>"+prcode+"/"+rtno+"/"+rttype+"/"+rtef,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});
					load_detail_table(prcode,rtno);
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

	$("#tariffDetail #rtid").on("change", function(e){
		e.preventDefault();
		$("#tariffDetail #fromDepoBlok").children().attr('disabled', true);
		$("#tariffDetail #fromPortBlok").children().attr('disabled', true);
		$("#tariffDetail #fromCityBlok").children().attr('disabled', true);

		$("#tariffDetail #toPortBlok").children().attr('disabled', true);
		$("#tariffDetail #toDepoBlok").children().attr('disabled', true);
		$("#tariffDetail #toCityBlok").children().attr('disabled', true);			
		var rtid = $(this).val();
		if(rtid=="1") {
			$("#tariffDetail #retype").html('<option value="">- select -</option>'
				+ '<option value="11">DEPOT to DEPOT (OUT)</option>'
				+ '<option value="12">DEPOT to PORT</option>'
				+ '<option value="13">DEPOT to INTERCITY</option> ');

		} else if(rtid=="2") {		
			$("#tariffDetail #retype").html('<option value="">- select -</option>'
				+ '<option value="21">DEPOT to DEPOT (IN)</option>'
				+ '<option value="22">PORT to DEPO</option>'
				+ '<option value="23">INTERCITY to DEPO</option> ');

		} else {
			$("#tariffDetail #retype").html('<option value="">- select -</option>');
		}
	});

	// $("#prcode").on("change", function(e){
	// 	var prcode = $(this).val();
	// 	load_detail_table(prcode);
	// });

	$("#addDetail").on("click", function(e){
		e.preventDefault();
		var prcode = $("#prcode").val();
		var rtno = $("#rtno").val();
		$("#tariffDetail").trigger("reset");
		$("#tariffDetail #prcode").val(prcode);
		$("#tariffDetail #rtno").val(rtno);
	});
});


function getRepoType(rttype){
	if(rttype=="11") {
		$("#retype").html(
		'<option value="">- select -</option>' +
		'<option value="11" selected>DEPOT to DEPOT(OUT)</option>'+
		'<option value="12">DEPOT to PORT</option>'+
		'<option value="13">DEPOT to INTERCITY</option>');
	}else if(rttype=="12") {
		$("#retype").html(
		'<option value="">- select -</option>' +
		'<option value="11">DEPOT to DEPOT(OUT)</option>'+
		'<option value="12" selected>DEPOT to PORT</option>'+
		'<option value="13">DEPOT to INTERCITY</option>');
	}else if(rttype=="13") {
		$("#retype").html(
		'<option value="">- select -</option>' +
		'<option value="11">DEPOT to DEPOT(OUT)</option>'+
		'<option value="12">DEPOT to PORT</option>'+
		'<option value="13" selected>DEPOT to INTERCITY</option>');
	}else if(rttype=="21") {
		$("#retype").html(
		'<option value="">- select -</option>' +
		'<option value="21" selected>DEPOT to DEPOT(IN)</option>'+
		'<option value="22">PORT to DEPOT</option>'+
		'<option value="23">INTERCITY to DEPOT</option>');
	}else if(rttype=="22") {
		$("#retype").html(
		'<option value="">- select -</option>' +
		'<option value="21">DEPOT to DEPOT(IN)</option>'+
		'<option value="22" selected>PORT to DEPOT</option>'+
		'<option value="23">INTERCITY to DEPOT</option>');
	}else if(rttype=="23") {
		$("#retype").html(
		'<option value="">- select -</option>' +
		'<option value="21">DEPOT to DEPOT(IN)</option>'+
		'<option value="22">PORT to DEPOT</option>'+
		'<option value="23" selected>INTERCITY to DEPOT</option>');
	}		
}

function load_detail_table(prcode,rtno) {
	$('#rcTable tbody').html("");
	$.ajax({
		url: "<?=site_url('repotariff/load_detail_table')?>",
		type:"POST",
		data: {"prcode":prcode,"rtno":rtno},
		dataType: "json",
		success: function(json) {
			$('#rt-detail tbody').html(json);
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

    $("#comp_code").on("change", function(e){
    	e.preventDefault();
    	$.ajax({
    		url:"<?=site_url('mnrtariff/get_component_desc')?>",
    		method:"POST",
    		data:{"comp_code":$(this).val()},
    		dataType:"JSON",
    		success: function(json){
    			$("#comp_description").val(json);
    		}
    	});
    });

    $("#repair_code").on("change", function(e){
    	e.preventDefault();
    	$.ajax({
    		url:"<?=site_url('mnrtariff/get_repair_desc')?>",
    		method:"POST",
    		data:{"repair_code":$(this).val()},
    		dataType:"JSON",
    		success: function(json){
    			$("#repair_description").val(json);
    		}
    	});
    });      
}
</script>