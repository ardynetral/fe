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
		$.ajax({
			url : "<?=site_url('cfswo/get_data_detail')?>",
			type : "POST",
			data : {"wotype": $("#wotype").val(), "woopr": $("#prcode").val()},
			dataType: "JSON",
			success: function(json){
				$("#tblDetail tbody").html(json);
			}
		});
	});
		// getDetail
	$("#prcode").on("change", function(e){
		e.preventDefault();
		$.ajax({
			url : "<?=site_url('cfswo/get_data_detail')?>",
			type : "POST",
			data : {"wotype": $("#wotype").val(), "woopr": $("#prcode").val()},
			dataType: "JSON",
			success: function(json){
				$("#tblDetail tbody").html(json);
			}
		});
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
					$("#fWO #wonoid").val(res.data.wonoid);
					// $("#fReceipt #wonoid").val(res.data.wonoid);
					// $('#prcode').prop('disabled',true);
					// $('#wotype').prop('disabled',true);
					// $('#cancel').prop('disabled',true);
					// $("#checkAll").prop('disabled',false);
					// $("#wono").val(res.data.wono);
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

	// TAB
	$("#tab_kwitansi").on("click", function(e){
		$.ajax({
			url: "<?php echo site_url('cfswo/get_data_receipt'); ?>",
			type: "POST",
			data: {"wonoid":$("#wonoid").val()},
			dataType: 'json',
			success: function(json) {
				$("#fReceipt #woreceptid").val(json.wodreceptid);
				$("#fReceipt #woreceptdate").val(json.woreceptdate);
				$("#fReceipt #wodescbiaya1").val(json.wodescbiaya1);
				$("#fReceipt #wobiaya1").val(json.wobiaya1);
				$("#fReceipt #wodescbiaya2").val(json.wodescbiaya2);
				$("#fReceipt #wobiaya2").val(json.wobiaya2);				
				$("#fReceipt #wodescbiaya3").val(json.wodescbiaya3);
				$("#fReceipt #wobiaya3").val(json.wobiaya3);
				$("#fReceipt #wodescbiaya4").val(json.wodescbiaya4);
				$("#fReceipt #wobiaya4").val(json.wobiaya4);	
				$("#fReceipt #wodescbiaya5").val(json.wodescbiaya5);
				$("#fReceipt #wobiaya5").val(json.wobiaya5);
				$("#fReceipt #wodescbiaya6").val(json.wodescbiaya6);
				$("#fReceipt #wobiaya6").val(json.wobiaya6);
				$("#fReceipt #wodescbiaya7").val(json.wodescbiaya7);
				$("#fReceipt #wobiaya7").val(json.wobiaya7);
				$("#fReceipt #wototal_pajak").val(json.wototal_pajak);
				$("#fReceipt #wobiaya_adm").val(json.wobiaya_adm);
				$("#fReceipt #womaterai").val(json.womaterai);
				$("#fReceipt #wototbiaya_lain").val(json.wototbiaya_lain);
				$("#fReceipt #wototpph23").val(json.wototpph23);
				$("#fReceipt #wototal_tagihan").val(json.wototal_tagihan);
				console.log(json);
			}			
		});
	});
	$("form#fReceipt").on("submit", function(e){
		e.preventDefault();
		// $('#saveData').prop('disabled','disabled');
		$.ajax({
			url: "<?php echo site_url('cfswo/update_receipt'); ?>",
			type: "POST",
			data: new FormData(this),
			dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,			
			beforeSend: function(){
				// $('#saveData').prop('disabled',true);
				// $('#cancel').prop('disabled',true);
			},
			success: function(res) {
				if(res.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+res.message+'</div>'
					});
					// $('#saveData').prop('disabled',true);
					// $("#fWO #wonoid").val(res.data.wonoid);
					// $("#fReceipt #wonoid").val(res.data.wonoid);
					// $('#prcode').prop('disabled',true);
					// $('#wotype').prop('disabled',true);
					// $('#cancel').prop('disabled',true);
					// $("#checkAll").prop('disabled',false);
					// $("#wono").val(res.data.wono);
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+res.message+'</div>'
					});						
					// $('#saveData').prop('disabled',false);
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
	if($("#wostock").val()=="1") { $("#wostock").prop('checked',true); }

	$("#wopraorderin").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});
	$("#wopraorderout").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});
	$("#wostock").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
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