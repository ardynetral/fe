<script type="text/javascript">
$(document).ready(function() {
	// SELECT2
	$('.select-ctype').select2();
	
	// DATATABLE
	runDataTables();
	var table = $('#usrTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "fixedColumns": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('users/list_data');?>',
            pages: 5  
        } )
        ,
        sDom: 'T<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"pull-right"ip>>>',
        PaginationType : "bootstrap", 
        oLanguage: { "sSearch": "",
            "sLengthMenu" : "_MENU_ &nbsp;"}
    });

	check_group();

	$("#saveUser").click(function(e){
		e.preventDefault();
		var formData = "username=" + $("#username").val();
		formData += "&password=" + $("#password").val();
		formData += "&repeat_password=" + $("#repeat_password").val();
		formData += "&fullname=" + $("#fullname").val();
		formData += "&email=" + $("#email").val();
		formData += "&group_id=" + $("#group_id").val();
		formData += "&prcode=" + $("#prcode").val();
		formData += "&cucode=" + $("#cucode").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('users/add'); ?>",
			type: "POST",
			data: formData,
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.data+'</div>'
					});							
					window.location.href = "<?php echo site_url('users'); ?>";
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

	$("#group_id").on("change", function(){
		if($(this).val()==2) {
			$("#input_debitur").hide();
			$("#input_principal").show();
			$.ajax({
				url:"<?=site_url('users/ajax_pr_dropdown');?>",
				type:"POST",
				dataType:"HTML",
				success: function(html) {
					$("#pr-dropdown").html(html);
					$('.select-pr').select2();
				}
			});
		} else if ($(this).val()==1) {
			$("#input_principal").hide();
			$("#input_debitur").show();
			$.ajax({
				url:"<?=site_url('users/ajax_debitur_dropdown');?>",
				type:"POST",
				dataType:"HTML",
				success: function(html) {
					$("#debitur-dropdown").html(html);
					$('.select-debitur').select2();
				}
			});
		} else {

		    $("#prcode").val('');
			$("#input_principal").hide();
			$("#input_debitur").hide();
		}
	});

	// EDIT DATA
	$("#updateUser").click(function(e){
		e.preventDefault();
		var formData = "username=" + $("#username").val();
		formData += "&userId=" + $("#uid").val();
		// formData += "&repeat_password=" + $("#repeat_password").val();
		formData += "&fullname=" + $("#fullname").val();
		formData += "&email=" + $("#email").val();
		formData += "&group_id=" + $("#group_id").val();
		formData += "&prcode=" + $("#prcode").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('users/edit/'); ?>"+$("#uid").val(),
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
					window.location.href = "<?php echo site_url('users'); ?>";
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

	$('#usrTable tbody').on('click', '#sendEmail', function(e){
		e.preventDefault();
		var uid = $(this).data('uid');
		$.ajax({
			url: "<?php echo site_url('users/send_email/'); ?>"+uid,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message_data+'</div>'
					});							
					window.location.href = "<?php echo site_url('users'); ?>";
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

	function check_group() {
		if($("#group_id").val()==1) {
			$("#input_debitur").show();
			$("#input_principal").hide();
			$('.select-debitur').select2();
		} else if($("#group_id").val()==2) {
			$("#input_principal").show();
			$("#input_debitur").hide();	
			$('.select-pr').select2();			
		} else {
			$("#input_debitur").hide();			
			$("#input_principal").hide();
		}
		console.log($("#group_id").val());
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