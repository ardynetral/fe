<script type="text/javascript">
$(document).ready(function() {
	// Error Message element
	$(".err-crno").hide(); //container number check
	// $("#update").hide();
	// $("#updateDetail").hide();
	$("#editOrderFrame").hide();
	// SELECT2
	// $('.select-pr').select2();
	// $('.select-port').select2();
	// $('.select-depo').select2();
	// $('.select-vessel').select2();
	// $('.select-voyage').select2();
	// $('.select-ccode').select2();

	// DATATABLE
	runDataTables();
	var table = $('#ctTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "fixedColumns": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('rip/list_data');?>',
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

	// datePicker
	$(".tanggal").datepicker({
		autoclose: true,
	});

	$("form#fEstimasi").on("submit", function(e) {
		e.preventDefault();

		$.ajax({
			url: "<?php echo site_url('rip/add'); ?>",
			type: "POST",
			data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,					
			dataType: 'json',
			success: function(json) {
				if (json.status == "success") {
					Swal.fire({
						icon: 'success',
						title: "Success",
						html: '<div class="text-success">' + json.message + '</div>'
					});
					$("#saveData").prop('disabled', true);
					$("#addDetail").prop('disabled', false);
				} else {
					Swal.fire({
						icon: 'warning',
						title: "Alert",
						html: '<div class="text-danger">' + json.message + '</div>'
					});
				}
			}
		});
	});

	$('#tblList_add tbody').on('click', '.delete', function(e) {
		e.preventDefault();
		var svid = $(this).data('svid');
		var rpid = $(this).data('rpid');
		var rdno = $(this).data('rdno');
		var crno = $(this).data('crno');
		Swal.fire({
			title: 'Are you sure?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
				delete_data(svid,rpid,rdno,crno);
			}
		});

	});
	$('#tblList_edit tbody').on('click', '.delete', function(e) {
		e.preventDefault();
		var svid = $(this).data('svid');
		var rpid = $(this).data('rpid');
		var rdno = $(this).data('rdno');
		var crno = $(this).data('crno');
		Swal.fire({
			title: 'Are you sure?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
				delete_data_edit(svid,rpid,rdno,crno);
			}
		});

	});
	function delete_data(svid,rpid,rdno,crno) {
		$.ajax({
			url: "<?php echo site_url('rip/delete_detail'); ?>",
			type: "POST",
			data: { "svid":svid, "rpid":rpid, "rdno":rdno, "crno":crno },
			dataType: 'json',
			success: function(json) {
				if (json.status == "success") {
					Swal.fire({
						icon: 'success',
						title: "Success",
						html: '<div class="text-success">' + json.message + '</div>'
					});
					$("#tblList_add tbody").html(json.data);
				} else {
					Swal.fire({
						icon: 'error',
						title: "Error",
						html: '<div class="text-danger">' + json.message + '</div>'
					});
				}
			}
		});
	}

	function delete_data_edit(svid,rpid,rdno,crno) {
		$.ajax({
			url: "<?php echo site_url('rip/delete_detail_edit'); ?>",
			type: "POST",
			data: { "svid":svid, "rpid":rpid, "rdno":rdno, "crno":crno },
			dataType: 'json',
			success: function(json) {
				if (json.status == "success") {
					Swal.fire({
						icon: 'success',
						title: "Success",
						html: '<div class="text-success">' + json.message + '</div>'
					});
					$("#tblList_edit tbody").html(json.data);
				} else {
					Swal.fire({
						icon: 'error',
						title: "Error",
						html: '<div class="text-danger">' + json.message + '</div>'
					});
				}
			}
		});
	}
	// save detailContainer
	$("#formDetail").on("submit", function(e) {
		e.preventDefault();
		$("#tblList_add tbody").html("");
		var url="";
		if($("#act").val()=="add") { url = "<?php echo site_url('rip/save_detail'); ?>"; }
		else if($("#act").val()=="edit") { url = "<?php echo site_url('rip/update_detail'); ?>"; }
		$.ajax({
			url: url,
			type: "POST",
			data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,		
			dataType: 'json',
			success: function(json) {
				if (json.status == "success") {
					Swal.fire({
						icon: 'success',
						title: "Success",
						html: '<div class="text-success">' + json.message + '</div>'
					});
					$("#tblList_add tbody").html(json.data);
				} else {
					Swal.fire({
						icon: 'error',
						title: "Error",
						html: '<div class="text-danger">' + json.message + '</div>'
					});
				}
			}
		});

	});

	$("#muname").on("change", function(){
		calculate_cost();		
	});

	$("#lccode").on("change", function(){
		calculate_cost();		
	});

	$("#cmcode").on("change", function(){
		calculate_cost();		
	});
	
	$("#dycode").on("change", function(){
		calculate_cost();		
	});

	$("#rmcode").on("change", function(){
		calculate_cost();	
	});

	$("input:radio[name=rdcalmtd]").on("change", function(){
		calculate_cost();	
	});
	$("#rdqtyact").on("keyup", function(){
		$("#rdmhr").val("0");
		$("#rdmat").val("0");
		$("#rdtotal").val("0");	
		$.ajax({
			url: "<?php echo site_url('rip/calculateTotalCost'); ?>",
			type: "POST",
			data: {
				"rdloc": $("#lccode").val(),
				"rdcom": $("#cmcode").val(),
				"rddmtype": $("#dycode").val(),
				"rdrepmtd": $("#rmcode").val(),
				"rdsize": $("#rdsize").val(),
				"rdcalmtd": $("#rdcalmtd").val(),
				"rdqty": $("#rdqtyact").val(),
				"muname": $("#muname").val(),
				// "prcode": $("#prcode").val(),
				"crno": $("#rpcrno").val()
			},		
			dataType: 'json',
			success: function(json) {
				if(json.status=="success") {
					if($("#rdqtyact").val()< json.data._start) {
						Swal.fire({
							icon: 'error',
							title: "Error",
							html: '<div class="text-danger">Quantity min:' + json.data._start + '</div>'
						});	
					}if($("#rdqtyact").val()> json.data._limit) {
						Swal.fire({
							icon: 'error',
							title: "Error",
							html: '<div class="text-danger">Quantity max:' + json.data._limit + '</div>'
						});					
					} else{					
						$("#rdmhr").val(json.data._hoursidr);
						$("#rdmat").val(json.data._mtrlcostidr);
						$("#rdtotal").val(json.data._laborcostidr);
					}
				}
				// console.log(json.xmtrl_cost);
			}
		});
					
	});

	$("#addDetail").on("click", function(e){
		$("#act").val("add");
		var det_crno = $("#rpcrno").val();
		var det_svid = $("#svid").val();
		var tblRow = parseInt($("#tblList_add tr").length);
		$("#saveDetail").show();
		$("#saveDetail").prop("disabled",false);
		$("#updateDetail").hide();
		$("#updateDetail").prop("disabled",true);
		$("#formDetail").show();
		$("#formDetail").trigger("reset");
		$("#det_crno").val(det_crno);
		$("#rpid").val(tblRow);
		$("#lccode").val('');
		$("#cmcode").val('');
		$("#dycode").val('');
		$("#rmcode").val('');			
	});

	$("#addDetailEdit").on("click", function(e){
		var det_crno = $("#rpcrno").val();
		var det_svid = $("#svid").val();
		var tblRow = parseInt($("#tblList_edit tr").length);
		$("#act").val("add");
		// $("#formUpdateDetail").attr("id","formInsertEditDetail");
		$("#saveDetail").show();
		$("#saveDetail").prop("disabled",false);
		$("#updateDetail").hide();
		$("#updateDetail").prop("disabled",true);
		$("#formDetail").trigger("reset");
		$("#det_crno").val(det_crno);
		$("#det_svid").val(det_svid);
		$("#rpid").val(tblRow);
		$("#lccode").val('');
		$("#cmcode").val('');
		$("#dycode").val('');
		$("#rmcode").val('');	
	});
	// save detailContainer
	$("#formUpdateDetail").on("submit", function(e) {
		e.preventDefault();
		var url="";
		if($("#act").val()=="add") { url = "<?php echo site_url('rip/save_detail'); ?>"; }
		else if($("#act").val()=="edit") { url = "<?php echo site_url('rip/update_detail'); ?>"; }
		$.ajax({
			url: url,
			type: "POST",
			data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,		
			dataType: 'json',
			success: function(json) {
				if (json.status == "success") {
					$("#tblList_edit tbody").html("");
					Swal.fire({
						icon: 'success',
						title: "Success",
						html: '<div class="text-success">' + json.message + '</div>'
					});
					$("#tblList_edit tbody").html(json.data);
				} else {
					Swal.fire({
						icon: 'error',
						title: "Error",
						html: '<div class="text-danger">' + json.message + '</div>'
					});
				}
			}
		});
	});

	$("#rpcrno").on("keyup", function() {
		var crno = $("#rpcrno").val();
		var status = "";
		$("#fEstimasi").trigger("reset");
		$("#formDetail").trigger("reset");
		$("#tblList_add tbody").html("");
		$("#rpcrno").val(crno);				
		$(this).val($(this).val().toUpperCase());
		$.ajax({
			url: "<?= site_url('rip/getDataEstimasi'); ?>",
			type: "POST",
			data: { "crno": crno },
			dataType: "JSON",
			success: function(json) {
				if (json.status == "Failled") {
					$(".err-crno").show();
					$(".err-crno").html(json.message);
					$("#crno").css("background", "#ffbfbf!important");
					$("#crno").css("border-color", "#ea2525");
				} else {
					$(".err-crno").html("");
					$(".err-crno").hide();
					$("#rpcrno").css("background", "#fff!important");
					$("#rpcrno").css("border-color", "#ccc");

					// CHECK DATA HEADER
					if(json.data_estimasi==""){
						// bisa insert header
						$("#saveData").prop("disabled", false);					
					} else {
						// tidak bisa insert header
						// bisa insert detail
						$("#saveData").prop("disabled", true);
						$("#addDetail").prop("disabled", false);							
					}

					// fill data Header
					var svsurdat = $.format.date(json.header.svsurdat,"dd-MM-yyyy");
					$("#det_crno").val(crno);
					$("#svid").val(json.header.svid);
					$("#det_svid").val(json.header.svid);
					$("#syid").val(json.header.syid);
					$("#rpcrton").val(json.header.svcrton);
					$("#rpcrtby").val(json.header.svcrtby);
					$("#rpnoest").val(json.header.rpnoest);
					$("#cccode").val(json.header.cccode);
					$("#ctcode").val(json.header.ctcode);
					$("#cclength").val(json.header.cclength);
					$("#ccheight").val(json.header.ccheight);
					$("#coexpdate").val(json.header.coexpdate);
					$("#cono").val(json.header.cono);
					$("#svsurdat").val(svsurdat);
					$("#svcond").val(json.header.svcond);
					$("#svcond").val(json.header.svcond);
					$("#rpver").val(json.header.rpver);
					$("#wono").val(json.header.wono);
					$("#inspektor").val(json.header.syid);
					$("#tblList_add tbody").html(json.detail);

					if(json.header.crlastact=="WR") {
						$("#finishRepair").prop("disabled",false);
						$("#finishCleaning").prop("disabled",true);
					} else if(json.header.crlastact=="WC") {
						$("#finishRepair").prop("disabled",true);
						$("#finishCleaning").prop("disabled",false);
					} else {
						$("#finishRepair").prop("disabled",true);
						$("#finishCleaning").prop("disabled",true);
					}


					console.log(json.header);
				}
			}
		});
	});

	$("#finishRepair").on("click", function(e){
		$.ajax({
			url:"<?=site_url('rip/finish_repair');?>",
			type:"POST",
			data:{"crno":$("#rpcrno").val(),"svid":$("#svid").val()},
			dataType:"JSON",
			success: function(res){
				if (res.status == "success") {
					Swal.fire({
						icon: 'success',
						title: "Success",
						html: '<div class="text-success">' + res.message + '</div>'
					});
					$("#finishRepair").prop("disabled", true);
					$("#finishCleaning").prop("disabled", false);
				} else {
					Swal.fire({
						icon: 'error',
						title: "Error",
						html: '<div class="text-danger">' + res.message + '</div>'
					});
					$("#finishRepair").prop("disabled", false);
					$("#finishCleaning").prop("disabled", true);					
				}

			}
		});
	});

	$("#finishCleaning").on("click", function(e){
		$.ajax({
			url:"<?=site_url('rip/finish_cleaning');?>",
			type:"POST",
			data:{"crno":$("#rpcrno").val(),"svid":$("#svid").val()},
			dataType:"JSON",
			success: function(res){
				if (res.status == "success") {
					Swal.fire({
						icon: 'success',
						title: "Success",
						html: '<div class="text-success">' + res.message + '</div>'
					});
					$("#finishRepair").prop("disabled", true);
					$("#finishCleaning").prop("disabled", true);
				} else {
					Swal.fire({
						icon: 'error',
						title: "Error",
						html: '<div class="text-danger">' + res.message + '</div>'
					});
					$("#finishRepair").prop("disabled", true);
					$("#finishCleaning").prop("disabled", false);					
				}

			}
		});
	});
	$("#tblList_add tbody").on("click",".edit", function(e){
		e.preventDefault();
		$("#saveDetail").prop("disabled",false);
		$("#act").val("edit");
		var row = $(this).closest("tr");
		var svid = $("#fEstimasi").find("#svid").val();
		var rpid = row.find(".no").text();
		var lccode = row.find(".lccode").text();
		var cmcode = row.find(".cmcode").text();
		var dycode = row.find(".dycode").text();
		var rmcode = row.find(".rmcode").text();
		var rdcalmtd = row.find(".rdcalmtd").text();
		var rdsize = row.find(".rdsize").text();
		var muname = row.find(".muname").text();
		var rdqtyact = row.find(".rdqty").text();
		var rdmhr = row.find(".rdmhr").text();
		var tucode = row.find(".curr_symbol").text();
		var rdmat = row.find(".rdmat span").text();
		var rdaccount = row.find(".rdaccount").text();
		var curr_code = "";
		if(tucode=="IDR") {	curr_code="001"; } 
		else if(tucode=="USD") { curr_code="002"; } 
		else if(tucode=="JPY") { curr_code="003"; }
		else if(tucode=="SGD") { curr_code="004"; }
		else if(tucode=="EUD") { curr_code="005"; }
		$("#svid").val(svid);
		$("#rpid").val(rpid);
		$("#lccode").val(lccode);
		$("#cmcode").val(cmcode);
		$("#dycode").val(dycode);
		$("#rmcode").val(rmcode);
		$("#rdsize").val(rdsize);
		$("#muname").val(muname);
		$("#rdqtyact").val(rdqtyact);
		$("#rdmhr").val(rdmhr);
		$("#tucode").val(curr_code);
		$("#rdmat").val(rdmat);
		$("input[name=rdcalmtd][value=" + rdcalmtd + "]").prop('checked', true);
		$("input[name=rdaccount][value=" + rdaccount + "]").prop('checked', true);			console.log(rpid);
		$("#fileList").html("");	
		loadFileList(svid,rpid);		
	});

	$("#tblList_edit tbody").on("click",".edit", function(e){
		e.preventDefault();
		$("#act").val("edit");
		$("#saveDetail").hide();
		$("#saveDetail").prop("disabled",true);
		$("#updateDetail").show();
		$("#updateDetail").prop("disabled",false);
		var row = $(this).closest("tr");
		var crno = row.find(".crno").text();
		var svid = row.find(".svid").text();
		var rpid = row.find(".no").text();
		var lccode = row.find(".lccode").text();
		var cmcode = row.find(".cmcode").text();
		var dycode = row.find(".dycode").text();
		var rmcode = row.find(".rmcode").text();
		var rdcalmtd = row.find(".rdcalmtd").text();
		var rdsize = row.find(".rdsize").text();
		var muname = row.find(".muname").text();
		var rdqtyact = row.find(".rdqty").text();
		var rdmhr = row.find(".rdmhr").text();
		var tucode = row.find(".curr_symbol").text();
		var rdmat = row.find(".rdmat span").text();
		var rdaccount = row.find(".rdaccount").text();
		var rdtotal = row.find(".rdtotal").text();
		var curr_code = "";
		if(tucode=="IDR") {	curr_code="001"; } 
		else if(tucode=="USD") { curr_code="002"; } 
		else if(tucode=="JPY") { curr_code="003"; }
		else if(tucode=="SGD") { curr_code="004"; }
		else if(tucode=="EUD") { curr_code="005"; }
		$("#det_crno").val(crno);
		$("#svid").val(svid);
		$("#det_svid").val(svid);			
		$("#rpid").val(rpid);
		$("#lccode").val(lccode);
		$("#cmcode").val(cmcode);
		$("#dycode").val(dycode);
		$("#rmcode").val(rmcode);
		$("#rdsize").val(rdsize);
		$("#muname").val(muname);
		$("#rdqtyact").val(rdqtyact);
		$("#rdmhr").val(rdmhr);
		$("#tucode").val(curr_code);
		$("#rdmat").val(rdmat);
		$("#rdtotal").val(rdtotal);
		$("input[name=rdcalmtd][value=" + rdcalmtd + "]").prop('checked', true);
		$("input[name=rdaccount][value=" + rdaccount + "]").prop('checked', true);
		  $("#fileList").html("");	
		  loadFileList(svid,rpid);	
	});

	function loadFileList(svid,rpid) {
		$.ajax({
			url: "<?= site_url('rip/getFileList/'); ?>" + svid +"/"+ rpid,
			type: "POST",
			dataType: "JSON",
			success: function(json) {
				$("#fileList").html(json);
			}
		});			
	}
	// check file size while upload
	$('#files').bind('change', function() {

	  //this.files[0].size gets the size of your file.
	  if(this.files[0].size > 524288) {
		Swal.fire({
		  icon: 'error',
		  title: "MAX 512 KB",
		  html: '<div class="text-danger">File terlalu besar!</div>'
		});
		this.value='';
	  }

	});
	// End Step 2
	$("#ctTable tbody").on("click", ".print", function(e) {
		e.preventDefault();
		var crno = $(this).data("crno");
		var type = $(this).data("type");
		window.open("<?=site_url('rip/print/'); ?>" + crno + "/" + type, '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
	});
	$("#print").on("click", function(e) {
		e.preventDefault();
		var crno = $(this).data("crno");
		var type = $(this).data("type");
		window.open("<?=site_url('rip/print/'); ?>" + crno + "/" + type, '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
	});
	

});

function calculate_cost() {
	// jika < start  maka "error message"
	// jika > limit  maka "error message"
	$("#rdmhr").val("0");
	$("#rdmat").val("0");
	$("#rdtotal").val("0");	
	$.ajax({
		url: "<?php echo site_url('rip/calculateTotalCost'); ?>",
		type: "POST",
		data: {
			"rdloc": $("#lccode").val(),
			"rdcom": $("#cmcode").val(),
			"rddmtype": $("#dycode").val(),
			"rdrepmtd": $("#rmcode").val(),
			"rdsize": $("#rdsize").val(),
			"rdcalmtd": $("#rdcalmtd").val(),
			"rdqty": $("#rdqtyact").val(),
			"muname": $("#muname").val(),
			// "prcode": $("#prcode").val(),
			"crno": $("#rpcrno").val()
		},		
		dataType: 'json',
		success: function(json) {
			if(json.status=="success") {
				$("#rdmhr").val(json.data._hoursidr);
				$("#rdmat").val(json.data._mtrlcostidr);
				$("#rdtotal").val(json.data._laborcostidr);
			}
			// console.log(json.xmtrl_cost);
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