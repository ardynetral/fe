<script type="text/javascript">
$(document).ready(function(){
	//containerCode dropdown
	$("#crno").focus();
	$('.select-ccode').select2();
	$('.select-material').select2();

	// $("#ctTable").DataTable({
 //        "paging":   false,
 //        "info": false,		
	// });

	$("#cccode").on("change", function(){
		var cccode = $(this).val();
		$.ajax({
			url:"<?=site_url('container/ajax_ccode/');?>"+cccode,
			type:"POST",
			dataType:"JSON",
			success: function(json) {
				$("#ctcode_view").val(json.ctcode);
				$("#ctcode").val(json.ctcode);
				$("#cclength").val(json.cclength);
				$("#ccheight").val(json.ccheight);
				console.log(json.cclength);
			}
		});
	});

	$("#saveContainer").click(function(e){
		e.preventDefault();
		// var crcdp$("#crcdp").val();
		var formData = "crno=" + $("#crno").val();
		formData += "&mtcode=" + $("#mtcode").val();
		formData += "&cccode=" + $("#cccode").val();
		formData += "&crowner=" + $("#crowner").val();
		formData += "&crcdp=" + $("#crcdp").val();
		formData += "&crcsc=" + $("#crcsc").val();
		formData += "&cracep=" + $("#cracep").val();
		formData += "&crmmyy=" + $("#crmmyy").val();
		formData += "&crweightk=" + $("#crweightk").val();
		formData += "&crweightl=" + $("#crweightl").val();
		formData += "&crtarak=" + $("#crtarak").val();
		formData += "&crtaral=" + $("#crtaral").val();
		formData += "&crnetk=" + $("#crnetk").val();
		formData += "&crnetl=" + $("#crnetl").val();
		formData += "&crvol=" + $("#crvol").val();
		formData += "&crmanuf=" + $("#crmanuf").val();
		formData += "&crmandat=" + $("#crmandat").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('container/add'); ?>",
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
					window.location.href = "<?php echo site_url('container'); ?>";
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

	$("#updateContainer").click(function(e){
		e.preventDefault();
		// var crcdp$("#crcdp").val();
		var formData = "crno=" + $("#crno").val();
		formData += "&mtcode=" + $("#mtcode").val();
		formData += "&cccode=" + $("#cccode").val();
		formData += "&crowner=" + $("#crowner").val();
		formData += "&crcdp=" + $("#crcdp").val();
		formData += "&crcsc=" + $("#crcsc").val();
		formData += "&cracep=" + $("#cracep").val();
		formData += "&crmmyy=" + $("#crmmyy").val();
		formData += "&crweightk=" + $("#crweightk").val();
		formData += "&crweightl=" + $("#crweightl").val();
		formData += "&crtarak=" + $("#crtarak").val();
		formData += "&crtaral=" + $("#crtaral").val();
		formData += "&crnetk=" + $("#crnetk").val();
		formData += "&crnetl=" + $("#crnetl").val();
		formData += "&crvol=" + $("#crvol").val();
		formData += "&crmanuf=" + $("#crmanuf").val();
		formData += "&crmandat=" + $("#crmandat").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('container/edit/'); ?>" + $("#crno").val(),
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
					window.location.href = "<?php echo site_url('container'); ?>";
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
			url: "<?php echo site_url('container/delete/'); ?>"+code,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('container'); ?>";
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

	$("#crno").on("keyup", function(){
		var crno = $("#crno").val();
		var status = "";
		$.ajax({
			url:"<?=site_url('container/checkContainerNumber');?>",
			type:"POST",
			data: "ccode="+crno,
			dataType:"JSON",
			success: function(json){
				if(json.status=="Failled") {
					$(".err-crno").show();
					$("#validNum").hide();
					$(".err-crno").html(json.message);
					$("#crno").css("background", "#ffbfbf!important");
					$("#crno").css("border-color", "#ea2525");					
				} else {
					$(".err-crno").html("");
					$(".err-crno").hide();
					$("#validNum").show();
					$("#crno").css("background", "#fff!important");
					$("#crno").css("border-color", "#1D92AF");
				}
			}
		});
	});

	$('#crcdp').on('change', function() {
	    var crcdp_val = this.checked ? '1' : '0';
	    return this.value=crcdp_val;
	});
	$('#crcsc').on('change', function() {
	    var crcsc_val = this.checked ? '1' : '0';
	    this.value=crcsc_val;
	});	
	$('#cracep').on('change', function() {
	    var cracep_val = this.checked ? '1' : '0';
	    this.value=cracep_val;
	});		

	runDataTables();
	var table = $('#ctTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "fixedColumns": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('container/list_data');?>',
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