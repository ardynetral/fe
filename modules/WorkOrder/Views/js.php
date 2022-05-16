<script type="text/javascript">
$(document).ready(function() {
	// Error Message element
	$(".err-crno").hide(); //container number check
	// SELECT2
	$('.select-pr').select2();
	// datePicker

	// getDetail
	$("#wotype").on("change", function(e){
		e.preventDefault();
		$.ajax({
			url : "<?=site_url('wo/get_data_detail')?>",
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
			url : "<?=site_url('wo/get_data_detail')?>",
			type : "POST",
			data : {"wotype": $("#wotype").val(), "woopr": $("#prcode").val()},
			dataType: "JSON",
			success: function(json){
				$("#tblDetail tbody").html(json);			
			}
		});
	});

	// SAveHeader
	$("#saveData").on("click", function(){
		// e.preventDefault();
		formData = $('#fWO').serializeArray();
		$('#saveData').prop('disabled','disabled');
		$.ajax({
			url: "<?php echo site_url('wo/add'); ?>",
			type: "POST",
			data: formData,
			dataType: 'json',
			beforeSend: function(){
				$('#saveData').prop('disabled',true);
				$('#cancel').prop('disabled',true);
			},
			success: function(res) {
				if(res.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+res.message+'</div>'
					});
					$('#saveData').prop('disabled',true);
					$('#prcode').prop('disabled',true);
					$('#wotype').prop('disabled',true);
					$('#cancel').prop('disabled',true);
					$("#checkAll").prop('disabled',false);
					$("#wono").val(res.data.wono);
					
					$('#tblDetail tbody tr td:nth-child(1)').map(function() {
			            return $(this).find('input:checkbox').prop("disabled", false);
			        });				
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


	$("#checkAll").on("click", function(e){
		e.preventDefault();
		var CRNOS =  $('#tblDetail tbody tr td:nth-child(3)').map(function() {
            return $(this).text();
        }).get().join("','");	
		console.log(CRNOS);
		$.ajax({
			url: "<?php echo site_url('wo/save_all_detail'); ?>",
			type: "POST",
			data: { "CRNOS":CRNOS,"WONO":$("#wono").val() },
			dataType: 'json',
			success: function(res) {
				if(res.status=="success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+res.message+'</div>'
					});
					window.location.href = "<?php echo site_url('wo')?>";
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+res.message+'</div>'
					});					
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
			url: "<?php echo site_url('wo/save_one_detail'); ?>",
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
		window.open("<?php echo site_url('wo/print/'); ?>" + wono,'_blank', 'height=900,width=600,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
	});

	// DATATABLE
	runDataTables();
	var table = $('#ctTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "fixedColumns": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('wo/list_data');?>',
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
		url: "<?php echo site_url('wo/delete_container'); ?>",
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
				window.location.href = "<?php echo site_url('wo'); ?>";
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