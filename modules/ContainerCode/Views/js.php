<script type="text/javascript">
$(document).ready(function() {
	// SELECT2
	$('.select-ctype').select2();
	// DATATABLE
	// $("#ctTable").DataTable({
	//     select: {
	//         style: 'single'
	//     }
	// });

	$("#saveCCode").click(function(e){
		e.preventDefault();
		var formData = "cccode=" + $("#cccode").val();
		formData += "&ctype=" + $("#ctype").val();
		formData += "&height=" + $("#height").val();
		formData += "&length=" + $("#length").val();
		formData += "&alias1=" + $("#alias1").val();
		formData += "&alias2=" + $("#alias2").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('containercode/add'); ?>",
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
					window.location.href = "<?php echo site_url('containercode'); ?>";
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

	// $("#cccode").on("keyup", function(){

	// 	$.ajax({
	// 		url:"<?php echo site_url('containercode/cek_cccode');?>",
	// 		type:'POST',
	// 		dataType:'json',
	// 		data:$(this).val(),
	// 		success: function(json) {
	// 			if(json.status==true) {
	// 				alert("Container code exists.");
	// 				return false;
	// 			} else if (json.status==false) {
	// 				$("#ctype").prop("disabled",false);
	// 				$("#length").prop("disabled",false);
	// 				$("#height").prop("disabled",false);
	// 				$("#saveCCode").prop("disabled",false);
	// 			} else {
	// 				alert("Error: "+ json.message);
	// 				return false;
	// 			}
	// 			// console.log(json);
	// 		}
	// 	});
	// });

	// EDIT DATA
	$("#updateCCode").click(function(e){
		e.preventDefault();
		var formData = "cccode=" + $("#cccode").val();
		formData += "&ctype=" + $("#ctype").val();
		formData += "&height=" + $("#height").val();
		formData += "&length=" + $("#length").val();
		formData += "&alias1=" + $("#alias1").val();
		formData += "&alias2=" + $("#alias2").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('containercode/edit/'); ?>"+$("#cccode").val(),
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
					window.location.href = "<?php echo site_url('containercode'); ?>";
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

	runDataTables();
	var table = $('#ctTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "fixedColumns": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('containercode/list_data');?>',
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
			url: "<?php echo site_url('containercode/delete/'); ?>"+code,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('containercode'); ?>";
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