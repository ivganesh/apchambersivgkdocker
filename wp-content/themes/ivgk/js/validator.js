$(function () {

	$.validator.addMethod("registerCashReceive", function(value, element) {
		var thisReturn = true;
	   if(  $('select[name=Register]').val() == '' )
	   {
		   if( $('input[name=Cash_Receive]').val() != '')
			   thisReturn = false;
	   }
	   else  {
		   if( $('input[name=Cash_Receive]').val() == '')
			   thisReturn = false;
	   }					   
		return thisReturn;
	}, "Invalid register | cash receive");
	
	$.validator.addMethod("registerCashPaid", function(value, element) {
		var thisReturn = true;
	   if(  $('select[name=Register]').val() == '' )
	   {
		   if( $('input[name=Cash_Paid]').val() != '')
			   thisReturn = false;
	   }
	   else  {
		   if( $('input[name=Cash_Paid]').val() == '')
			   thisReturn = false;
	   }					   
		return thisReturn;
	}, "Invalid register | Cash Paid");
	
	$.validator.addMethod("courierGST", function(value, element) {
		var thisReturn = true;
	   if(  $('input[name=Courier_Charge]').val() == '' )
	   {
		   if( $('select[name=Courier_GST]').val() != '')
			   thisReturn = false;
	   }
	   else  {
		   if( $('select[name=Courier_GST]').val() == '')
			   thisReturn = false;
	   }					   
		return thisReturn;
	}, "Invalid Courier | GST");
	
	
	
	$('body').on('click', '[id^=openUserLogs--]', function () {
		var thisId = $(this).attr("id").split("--");
		var width = screen.width * 0.90;
		width = width.toFixed(0);
		var height = screen.height;
		 var newWindow = window.open(thisId[1]+'/?multiTasking=YES&logID='+thisId[2], "userLog", "toolbar=no,scrollbars=yes,resizable=no,top=0,left=0,width="+width+",height="+height);
		return false;
	});
	
	
	
	$("textarea[name=Suggested_Report]")
	.on( "keydown", function( event ) {
		if ( event.keyCode === $.ui.keyCode.TAB &&
			$( this ).autocomplete( "instance" ).menu.active ) {
		event.preventDefault();
		}
	})
	.autocomplete({
		minLength: 0,
		source: function( request, response ) {
		response( $.ui.autocomplete.filter(
			Suggested_ReportArr, extractLast( request.term ) ) );
		},
		focus: function() {
		return false;
		},
		select: function( event, ui ) {
		var terms = splitTextarea( this.value );
		terms.pop();
		terms.push( ui.item.value );
		terms.push( "" );
		this.value = terms.join( "\n" );
		return false;
		}
	});
	
	$("textarea[name=Allergy]")
	.on( "keydown", function( event ) {
		if ( event.keyCode === $.ui.keyCode.TAB &&
			$( this ).autocomplete( "instance" ).menu.active ) {
		event.preventDefault();
		}
	})
	.autocomplete({
		minLength: 0,
		source: function( request, response ) {
		response( $.ui.autocomplete.filter(
			AllergyArr, extractLast( request.term ) ) );
		},
		focus: function() {
		return false;
		},
		select: function( event, ui ) {
		var terms = splitTextarea( this.value );
		terms.pop();
		terms.push( ui.item.value );
		terms.push( "" );
		this.value = terms.join( "\n" );
		return false;
		}
	});
	$("textarea[name=Symptoms]")
	.on( "keydown", function( event ) {
		if ( event.keyCode === $.ui.keyCode.TAB &&
			$( this ).autocomplete( "instance" ).menu.active ) {
		event.preventDefault();
		}
	})
	.autocomplete({
		minLength: 0,
		source: function( request, response ) {
		response( $.ui.autocomplete.filter(
			SymptomsArr, extractLast( request.term ) ) );
		},
		focus: function() {
		return false;
		},
		select: function( event, ui ) {
		var terms = splitTextarea( this.value );
		terms.pop();
		terms.push( ui.item.value );
		terms.push( "" );
		this.value = terms.join( "\n" );
		return false;
		}
	});
	$("textarea[name=Medical_History]")
	.on( "keydown", function( event ) {
		if ( event.keyCode === $.ui.keyCode.TAB &&
			$( this ).autocomplete( "instance" ).menu.active ) {
		event.preventDefault();
		}
	})
	.autocomplete({
		minLength: 0,
		source: function( request, response ) {
		response( $.ui.autocomplete.filter(
			Medical_HistoryArr, extractLast( request.term ) ) );
		},
		focus: function() {
		return false;
		},
		select: function( event, ui ) {
		var terms = splitTextarea( this.value );
		terms.pop();
		terms.push( ui.item.value );
		terms.push( "" );
		this.value = terms.join( "\n" );
		return false;
		}
	});
	$("textarea[name=Diagnosis]")
	.on( "keydown", function( event ) {
		if ( event.keyCode === $.ui.keyCode.TAB &&
			$( this ).autocomplete( "instance" ).menu.active ) {
		event.preventDefault();
		}
	})
	.autocomplete({
		minLength: 0,
		source: function( request, response ) {
		response( $.ui.autocomplete.filter(
			DiagnosisArr, extractLast( request.term ) ) );
		},
		focus: function() {
		return false;
		},
		select: function( event, ui ) {
		var terms = splitTextarea( this.value );
		terms.pop();
		terms.push( ui.item.value );
		terms.push( "" );
		this.value = terms.join( "\n" );
		return false;
		}
	});
	
	
	
	
	jQuery("body").on("focus", "input[name^=Dose_]", function () {
		jQuery(this).autocomplete({
			source: DoseArr, 	
			matchCase: false,
			minLength: 0,		
			autoFocus: true
		});
	});
	jQuery("body").on("focus", "input[name^=Time_]", function () {
		jQuery(this).autocomplete({
			source: TimeArr, 	
			matchCase: false,
			minLength: 0,		
			autoFocus: true
		});
	});
	jQuery("body").on("focus", "input[name^=Narration_]", function () {
		jQuery(this).autocomplete({
			source: NarrationArr, 	
			matchCase: false,
			minLength: 0,		
			autoFocus: true
		});
	});
		
jQuery("body").on("keyup", "a[id*=_addRow_]", function (e) {
	 var keyCode = e.keyCode || e.which; 
	if (keyCode === 13) 						
	{ 
		var id = 0; 
		var fieldID = $(this).attr("id").split("_addRow_");
		var thidId = $("[class*=form-control][id^="+fieldID[0]+"_]");
		$.each(thidId, function( i ) { 
		  var thisVal = parseInt( $(this).attr("id").split(fieldID[0]+"_")[1] );
		  if( thisVal > id ) id = thisVal;
		});
		var nextId = parseInt(id) + parseInt(1);
		if( $("[class*=form-control][id="+fieldID[0]+"_"+id+"]").val() != "") { 
			var funcName = fieldID[0]+"_addNewRows";
			window[funcName](nextId);
			jQuery("[class*=form-control][name="+fieldID[0]+"_" + nextId + "]").focus();
			select2Focus(fieldID[0]+"_" + nextId);
		}
		else
		{
			jQuery("[class*=form-control][name="+fieldID[0]+"_" + id + "]").focus();
			select2Focus(fieldID[0]+"_" + id);
		}
		
		
	}
});
jQuery("body").on("click", "a[id*=_addRow_]", function () {
	
		var id = 0; 
		var fieldID = $(this).attr("id").split("_addRow_");
		var thidId = $("[class*=form-control][id^="+fieldID[0]+"_]");
		$.each(thidId, function( i ) { 
		  var thisVal = parseInt( $(this).attr("id").split(fieldID[0]+"_")[1] );
		  if( thisVal > id ) id = thisVal;
		});
		var nextId = parseInt(id) + parseInt(1);
		if( $("[class*=form-control][id="+fieldID[0]+"_"+id+"]").val() != "") { 
			var funcName = fieldID[0]+"_addNewRows";
			window[funcName](nextId); 
			jQuery("[class*=form-control][name="+fieldID[0]+"_" + nextId + "]").focus();
			select2Focus(fieldID[0]+"_" + nextId);
			
		}else
		{
			jQuery("[class*=form-control][name="+fieldID[0]+"_" + id + "]").focus();
			select2Focus(fieldID[0]+"_" + id);
		}
		
	
});


jQuery("body").on("click", "a[id*=_deleteRow_]", function () {
var thisId = jQuery(this).attr("id").split("_deleteRow_");
var id = thisId[1];
var name = thisId[0];
if( $("div[id*=row_"+name+"_]").length > 1) $("div[id=row_"+name+"_"+id+"]").remove();
});
		$('select[class*=select2]').on("select2:select", function (e) 
			{ 
				 var thisName = $(this).attr("name");
				 if( $(this).prop('required') )
				 {
					 if( $(this).val() == '') 
					 {
						 $(this).parent().addClass("error"); 
						  $("label[id="+thisName+"-error]").show();
					 }
					 else

					 {
						 $(this).parent().removeClass("error"); 
						 $("label[id="+thisName+"-error]").hide();
					 }
				 }
				 else{
					  if( $(this).val() != '')
					  {						  
							$(this).parent().removeClass("error"); 
							$("label[id="+thisName+"-error]").hide();
					  }
				 }
				
			});
			
	$('body').on('click', 'a[id*=deleteImage]', function () { 
		if( confirm( 'Do you want to delete?' ))
		{
			 var thisId = $(this).attr("id").split("_");
			if(thisId.length == 2)
			{
				var thisImage = $("#uploaded_image_userProfile_"+thisId[1]).attr('src');
				var imageNo = "_"+thisId[1];
				
				
			}else
			{
				var thisImage = $("#uploaded_image_userProfile").attr('src');
				var imageNo = '';
				
			}
			thisImage = thisImage.split("/");
			if( thisImage.length > 1)
			{
				var lastElement = thisImage.length - 1;
				thisImage = thisImage[lastElement];
			}
			else thisImage = '';
			
			var imageId = $("#ID").val();
			if( imageId != '' &&  thisImage != '')
			{
				$('html').block();
				 var dataa = {};
		
				dataa['action'] = 'unlinkImage';
				dataa['imageId'] = imageId;
				dataa['imageName'] = thisImage;
				dataa['imageType'] = imageType;
				dataa['imageNo'] = imageNo;
				$.ajax({
					type: 'POST',
					url: thisAjax,
					data:dataa,
					success: function(data) {
						$('html').unblock(); 
						data = data.trim();
						if(data == 'ERROR')
						{
							alert_danger("Failed to delete image file");
						}
						else{
							alert_sucuss("Image file has been deleted");
							if(thisId.length == 2)
							{
								$("#uploaded_image_userProfile_"+thisId[1]).attr('src',data);
								
							}else
							{
								$("#uploaded_image_userProfile").attr('src',data);
							}
						}
					},
					error: function (errorThrown) {
						$('html').unblock();
						alert_danger(errorThrown.responseText);
					}
				}); 
			}
			else
			{
				alert_danger("Either Image | ID not found...");
			}
			
		}
       
    });
	
					
    var domStringTable = "<'row'<'col-rsm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12't>>" +
                    "<'row'<'col-sm-12 col-md-3'i><'col-sm-12 col-md-3'l><'col-sm-12 col-md-3'f><'col-sm-12 col-md-3'p>>";
    
    var domString = "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-3'l><'col-sm-12 col-md-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-3'i><'col-sm-12 col-md-3'l><'col-sm-12 col-md-3'f><'col-sm-12 col-md-3'p>>";
    var buttonString =     [{
        "extend": 'copy',
        "exportOptions": {
            "columns": ':visible'
        },
       
    }, {
        "extend": 'csv',
        "exportOptions": {
            "columns": ':visible'
        },
		/*
        "customize": function (win) {
            $(win.document.body).find('h1').text('');
          }*/
    }, {
        "extend": 'excel',
        "exportOptions": {
            "columns": ':visible'
        },
		/*
        "customize": function (win) {
            $(win.document.body).find('h1').text('');
          }
		  */
    }, {
        "extend": 'pdf',
        "exportOptions": {
            "columns": ':visible'
        },
		/*
        "customize": function (win) {
            $(win.document.body).find('h1').text('');
          }
		  */
    }, {
        "extend": 'print',
        "exportOptions": {
            
            "columns": ':visible'
        },
        "customize": function (win) {
          $(win.document.body).find('h1').text('');
          $(win.document.body).find( 'table' )
          .addClass( 'compact' ).css( 'font-size', 'inherit' );;
        }
    }, "colvis",];    

	

	$.validator.addMethod("greaterSlot", function(value, element) {
		var start_time = $("#Slot_Start").val();
		var end_time = $(Slot_End).val();

		var stt = new Date("November 13, 2013 " + start_time);
		stt = stt.getTime();

		var endt = new Date("November 13, 2013 " + end_time);
		endt = endt.getTime();
		return stt < endt;
		
	}, "Start Time should be less than End Time.");

	$.validator.addMethod("slotTime", function(value, element) {
	   return $('#Slot_Start').val() != $('#Slot_End').val()
	}, "Start and End time should not match");

	$.validator.addMethod("registerCashReceive", function(value, element) {
		var thisReturn = true;
	   if(  $('select[name=Register]').val() == '' )
	   {
		   if( $('[name=Cash_Receive]').val() != '')
			   thisReturn = false;
	   }
	   else  {
		   if( $('[name=Cash_Receive]').val() == '')
			   thisReturn = false;
	   }					   
		return thisReturn;
	}, "Invalid register | cash receive");
	
	$.validator.addMethod("reportFieldValidation", function(value, element) {
		var thisReturn = true;
		if(  $('select[name=Field_Type]').val() == 'text' )
		{
			if( $('[name=Field_Min]').val() != '')
			   thisReturn = false;
			if( $('[name=Field_Max]').val() != '')
			   thisReturn = false;
			if( $('[name=Field_Step]').val() != '')
			   thisReturn = false;
			if( $('[name=Field_Maxlength]').val() == '')
			   thisReturn = false;
			if( $('[name=Field_Option]').val() != '')
			   thisReturn = false;
		}
		else if(  $('select[name=Field_Type]').val() == 'number' )
		{  
		   if( $('[name=Field_Min]').val() == '')
			   thisReturn = false;
			if( $('[name=Field_Max]').val() == '')
			   thisReturn = false;
			if( $('[name=Field_Step]').val() == '')
			   thisReturn = false;
			if( $('[name=Field_Maxlength]').val() != '')
			   thisReturn = false;
			if( $('[name=Field_Option]').val() != '')
			   thisReturn = false;
		}
		else
		{
			if( $('[name=Field_Min]').val() != '')
			   thisReturn = false;
			if( $('[name=Field_Max]').val() != '')
			   thisReturn = false;
			if( $('[name=Field_Step]').val() != '')
			   thisReturn = false;
			if( $('[name=Field_Maxlength]').val() != '')
			   thisReturn = false;
			if( $('[name=Field_Option]').val() == '')
			   thisReturn = false;
		}	
		return thisReturn;
	}, "Invalid register | Cash Paid");
	$.validator.addMethod("registerCashPaid", function(value, element) {
		var thisReturn = true;
	   if(  $('select[name=Register]').val() == '' )
	   {
		   if( $('[name=Cash_Paid]').val() != '')
			   thisReturn = false;
	   }
	   else  {
		   if( $('[name=Cash_Paid]').val() == '')
			   thisReturn = false;
	   }					   
		return thisReturn;
	}, "Invalid register | Cash Paid");
				
	$('select.select2').select2({
        theme: 'bootstrap4',
        width: 'auto',
		dropdownAutoWidth: true,
		allowClear: true,
		selectOnBlur:true,
    });
    
    $('.input-group select.select2').select2({
        theme: 'bootstrap4',
        width: '50px',
		dropdownAutoWidth: true,
		allowClear: true,
		selectOnBlur:true,
    });
	

    $('.dateRangePicker').daterangepicker({
        autoUpdateInput: false,
        autoApply : true,
        locale: { 
            format: 'DD-MM-YYYY'
        }
    });
    $('.dateRangePickerCurrentDate').daterangepicker({
        locale: { 
            format: 'DD-MM-YYYY'
        }
    });
    $('.dateRangePicker,.dateRangePickerCurrentDate').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
    });
  
    $('.dateRangePicker,.dateRangePickerCurrentDate').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    

    $('body').on('keydown', 'form#submitPageForm input, form#submitPageForm select, form#submitPageForm textarea', function (e) {
        var alt = e.altKey;
        var key = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
        if (key == 13 && alt) {
            e.preventDefault();
            $('#reportPageForm').find('*').filter(':input:visible:first').focus();
        }
    });
    $('body').on('click', '[id*=editForm_],[id*=deleteForm_],[id*=copyForm_],[id*=trashForm_],[id*=restoreForm_]', function () {
        $('html').block();
        $(this).find("form").submit();
    });


	
	
	
    $('body').on('click', 'a[id^=printPage--]', function () {
        var thisId = $(this).attr("id").split("--");
        var newWindow = window.open('printinvoice/','_blank');
        newWindow.onload = function(){
            newWindow.document.getElementById('printName').value = thisId[1];
            newWindow.document.getElementById('printStep').value = thisId[2];
            newWindow.document.getElementById('printPageForm').submit();
        };
    });
	
	$('body').on('click', 'a[id^=opdPage--]', function () {
        var thisId = $(this).attr("id").split("--");
        var newWindow = window.open('doctor-opd?Patient_Name='+thisId[2],'_blank');
    });
	$('body').on('click', 'a[id^=ipdPage--]', function () {
        var thisId = $(this).attr("id").split("--");
        var newWindow = window.open('doctor-ipd?Patient_Name='+thisId[2],'_blank');
    });
	$('body').on('click', 'a[id^=openLedgerReport--]', function () {
        var thisId = $(this).attr("id").split("openLedgerReport--");
        var newWindow = window.open('ledger/?Account='+thisId[1],'_blank');
    });
	$('body').on('click', 'a[id^=openRegisterReport--]', function () {
        var thisId = $(this).attr("id").split("openRegisterReport--");
        var newWindow = window.open('register-report/?Register='+thisId[1],'_blank');
    });
    $('input[type=number]').on('wheel', function(e){
        return false;
    });
    $('#submitPageForm input[type=text],#submitPageForm input[type=email],#submitPageForm input[type=number]').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });
    $('#submitPageForm').validate({
        errorPlacement: function (error, element) {
			var attr = element.attr('showmessage'); 
			if (typeof attr !== 'undefined' && attr !== false)
				return false;
			else  error.insertAfter($(element).parent('div.input-group,div.form-group'));
        },
        rules: _rulesString,
        messages: _messagesString,
		 highlight: function(element) {
			$(element).parent().addClass("error");
			
		  },
		  unhighlight: function(element) {
			$(element).parent().removeClass("error");
			
		  },
        submitHandler: function (form) {
            var thisButton =  $(this.submitButton).attr("name");
            var thisVal =  $(this.submitButton).val();
            if( thisVal == 'TRASH')
            {
                if( !confirm("TRASH ? "))
                {
                    return false;
                }
            } 
            if(thisButton != 'REFRESH')
            {
                $('html').block();
                $("input").each(function() {
                    $(this).val($.trim($(this).val().replace('"',"'")));
                });
                var thisVal = true;
                if (typeof window['check_submit'] == "function") {
                    var thisVal = check_submit();
                }
                if( thisVal ) form.submit();
                else  $('html').unblock();
            }else
            {
                $('html').block();
                form.submit();
            }
             
                      
        }
    });

    $('#reportPageForm').validate({
          errorPlacement: function (error, element) {
			var attr = element.attr('showmessage'); 
			if (typeof attr !== 'undefined' && attr !== false)
				return false;
			else  error.insertAfter($(element).parent('div.input-group,div.form-group'));
        },
         highlight: function(element) {
			$(element).parent().addClass("error");
			
		  },
		  unhighlight: function(element) {
			$(element).parent().removeClass("error");
			
		  },
        submitHandler: function (form) {
          
            form.submit();
        }
    });

    

    $('#reportTable01').DataTable({
        "dom": domString,
		"aaSorting": [],
		"scrollX": false,
		"responsive":  false,
        "pageLength": 50,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": buttonString,
    }).buttons().container().appendTo('#reportTable01_wrapper .col-md-6:eq(2)');


    $('#reportTable02').DataTable({
        "dom": domString,
        "aaSorting": [],
		"scrollX": false,
		"responsive":  false,
        "pageLength": 50,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": buttonString,
    }).buttons().container().appendTo('#reportTable02_wrapper .col-md-6:eq(0)');


    $('#reportTable03').DataTable({
        "dom": domString,
        "aaSorting": [],
		"scrollX": false,
		"responsive":  false,
        "pageLength": 50,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": buttonString,
    }).buttons().container().appendTo('#reportTable03_wrapper .col-md-6:eq(0)');


    $('#reportTable04').DataTable({
        "dom": domString,   
		"aaSorting": [],
		"scrollX": false,
		"responsive":  false,
        "pageLength": 50,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": buttonString,
    }).buttons().container().appendTo('#reportTable04_wrapper .col-md-6:eq(0)');

    $('#reportTable10').DataTable({
		"aaSorting": [],
		"scrollX": false,
		"responsive":  false,
        "pageLength": 50,
        "lengthChange": false,
        "autoWidth": false,
    }).buttons().container().appendTo('#reportTable10_wrapper .col-md-6:eq(0)');

    jQuery('body').on('click','#Present',function(){
        $("[id=Attendance]").val('Present');
    });
    jQuery('body').on('click','#Holiday',function(){
        $("[id=Attendance]").val('Holiday');
    });

 
	 $('body').on('click', '[id*=BookSlot_]', function () {
        var thisId = $(this).attr("id").split("_");
		var dataa = {};
		dataa['action'] = 'bookSlot';
		dataa['date'] = thisId[1];
		dataa['slot'] = thisId[2];
		dataa['dr'] = thisId[3];
		var tdId = thisId[4];
		$('html').block();
		$.ajax({
				type: 'POST',
				url: thisAjax,
				data:dataa,
			
				success: function(data) 
				{
					$('html').unblock();
					if(data == 'booked')
					{
						$("#errorAlert").removeClass("alert-danger");
						$("#errorAlert").addClass("alert-success");
						$("#wp-admin-bar-Error").html("Appointment Booked"); 
						$("#errorAlert").show();
						$("#tdID_"+tdId).html('<span style="color:green;font-weight: bold;">Your Appointment</span>');
						
					}else{
						if(data == 'already') var errorData = 'Appointment already exists...';
						else if(data == 'time') var errorData = 'Past time is not allowed...';
						else "Failed to book...retry"
						$("#errorAlert").removeClass("alert-success");
						$("#errorAlert").addClass("alert-danger");
						
						$("#wp-admin-bar-Error").html(errorData);
						$("#errorAlert").show();
					}
				},
				error: function (errorThrown) {
					$('html').unblock();
					alert_danger(errorThrown.responseText);
				}
			});
        
    });

			$('body').on('blur', '#Register_Mobile', function () {
        
				var Register_Mobile =  $(this).val();
				var page = $("[name=_wp_http_referer]").val();
				if(page.match('/patient/'))
				{
					var dataa = {};
					
					dataa['action'] = 'registerMobile';
					dataa['Register_Mobile'] = Register_Mobile;
					$('html').block();
					$.ajax({
						type: 'POST',
						url: thisAjax,
						data:dataa,
					
						success: function(data) 
						{
							$('html').unblock();
							if(data == 'YES')
							{
								
								alert_danger('This mobile is already registered...');
								
							}else{
								alert_sucuss('This mobile is not registered...');
								
							}
						},
						error: function (errorThrown) {
							$('html').unblock();
							alert_danger(errorThrown.responseText);
						}
					});
				}
				
			}); 
   


    $.blockUI.defaults = {
        message: '',

        title: null,       
        draggable: true,    

        theme: false, 
        css: {
            padding: 0,
            margin: 0,
            width: '30%',
            top: '20%',
            left: '35%',
            textAlign: 'center',
            color: '#000',
            border: 'none',
            backgroundColor: '#fff',
            cursor: 'wait'
        },

        themedCSS: {
            width: '30%',
            top: '20%',
            left: '35%'
        },

        overlayCSS: {
            backgroundColor: '#000',
            opacity: 0.6,
            cursor: 'wait'
        },

        // of lingering wait cursor 
        cursorReset: 'default',

        // styles applied when using $.growlUI 
        growlCSS: {
            width: '350px',
            top: '10px',
            left: '',
            right: '10px',
            border: 'none',
            padding: '5px',
            opacity: 0.6,
            cursor: null,
            color: '#fff',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px'
        },
 
        iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank',

        forceIframe: false,

        baseZ: 9000,

        centerX: true, 
        centerY: true,

        allowBodyStretch: true,

        bindEvents: true,

        constrainTabKey: true,

        fadeIn: 200,

        fadeOut: 400,

        timeout: 0,

        showOverlay: true,

        focusInput: true,

        onBlock: null,

        onUnblock: null,

        quirksmodeOffsetHack: 4,

        blockMsgClass: 'blockMsg',

        ignoreIfBlocked: false
    };
   // $('.select2-container').css("width","100%");
   // $('.select2-container').css("position","absolute");
    $("select[class*=select2][autofocus]").select2("focus");
});

history.pushState(null, null, document.URL);
window.addEventListener('popstate', function () {
    history.pushState(null, null, document.URL);
});
Object.defineProperty(window, "console", {
    value: console,
    writable: false,
    configurable: false
});
var i = 0;

function showWarningAndThrow() {
    if (!i) {
        setTimeout(function () {
            console.log("%cWarning message", "font: 2em sans-serif; color: yellow; background-color: red;");
        }, 1);
        i = 1;
    }
    throw "Console is disabled";
}
var l, n = {
    set: function (o) {
        l = o;
    },
    get: function () {
        showWarningAndThrow();
        return l;
    }
};
Object.defineProperty(console, "_commandLineAPI", n);

function alert_danger(msg) {
    
    if (jQuery('div#errorAlert').length > 1) jQuery('div#errorAlert').not(':first').remove();
    jQuery('#errorAlert').show();
    jQuery('#errorAlert').removeClass('alert-success');
    jQuery('#errorAlert').addClass('alert-danger');
    jQuery('#wp-admin-bar-Error').html(msg);
    //setTimeout(function () { jQuery('#errorAlert').hide(); jQuery('#wp-admin-bar-Error').html(''); }, 60000);
}
function alert_sucuss(msg) {
    if (jQuery('div#errorAlert').length > 1) jQuery('div#errorAlert').not(':first').remove();
    jQuery('#errorAlert').show();
    jQuery('#errorAlert').removeClass('alert-danger');
    jQuery('#errorAlert').addClass('alert-success');
    jQuery('#wp-admin-bar-Error').html(msg);
   // setTimeout(function () { jQuery('#errorAlert').hide(); jQuery('#wp-admin-bar-Error').html(''); }, 60000);
}

function calculateDose(thisId)
{
    
    var time = jQuery("#Time_"+thisId).val();
    var days = jQuery("#Days_"+thisId).val();
    if ( !jQuery.isNumeric( days ) ) days = 0;
    var dose = jQuery("#Dose_"+thisId).val();
    if(time.length > 1 && days > 0 && dose.length > 0)
    {
        if(dose.includes('0.5') || dose.includes('1.5') || dose.includes('1 ') || dose.includes('2 '))
        {
            
            var timeCount = time.split("-");
            var timeC = 0;
            for(var i=0; i < timeCount.length; i++)
            {
                if( timeCount[i].match("1") ) timeC++;
            }

            if(dose.includes('0.5')) var time = 0.5;
            else if(dose.includes('1.5')) var time = 1.5;
            else if(dose.includes('1')) var time = 1;
            else if(dose.includes('2')) var time = 2;
            var qty = parseInt(timeC) * parseInt(days) * parseFloat(time);
            jQuery("#QTY_"+thisId).val(Math.ceil(qty));

        }else{
            jQuery("#QTY_"+thisId).val('1');
        }
        
    }
}			
function register_total()
{
		var td = jQuery('input[name^=DR_Amt_]');
		var tc = jQuery('input[name^=CR_Amt_]');
		var cr = 0; 
		var dr = 0;
		jQuery.each(tc, function(index, value){
			var val = jQuery(this).val();
            var thisName = jQuery(this).attr("name").split("_")[2];
            if( val != '' && jQuery('input[name^=DR_Amt_'+thisName+']').val() != '')
            {
                alert_danger("Either CR | DR amount must be blank at row:["+thisName+"]");
                return false;
            }
			if(!jQuery.isNumeric( val ) ) { val = 0; }
			cr = parseFloat(cr) + parseFloat(val);
		});
		jQuery.each(td, function(index, value){
			var val = jQuery(this).val();
			if(!jQuery.isNumeric( val ) ) { val = 0; }
			dr = parseFloat(dr) + parseFloat(val);
		});
		var total = parseFloat(dr) - parseFloat(cr);
		total = Math.round(total * 100) / 100;
		dr = Math.round(dr * 100) / 100;
		cr = Math.round(cr * 100) / 100;
	
		jQuery('[name=Total_DR]').val(dr);
		jQuery('[name=Total_CR]').val(cr);
        jQuery('[name=Total_Amt]').val(total);
        //register_submit();
}


function general_total(field)
{
		var td = jQuery('input[name*='+field+']');
		var dr = 0;
		jQuery.each(td, function(index, value){
			var val = jQuery(this).val();
			if(!jQuery.isNumeric( val ) ) { val = 0; }
			dr = parseFloat(dr) + parseFloat(val);
		});
		
		var val = jQuery("#Discount").val();
		if(!jQuery.isNumeric( val ) ) { val = 0; }
		dr = parseFloat(dr) - parseFloat(val);
		dr = dr.toFixed(2);	
		if(dr == 0) dr = '';
        jQuery('[name=Bill_Amount]').val(dr);
}

function discharge_total(field)
{
		var td = jQuery('input[name^='+field+']');
		var dr = 0;
		jQuery.each(td, function(index, value){
			var val = jQuery(this).val();
			if(!jQuery.isNumeric( val ) ) { val = 0; }
			val = parseFloat(val);
			dr = parseFloat(dr) + parseFloat(val);
		});
		
		
        var lastBill = $("#debtorvalue").val();
        if(!jQuery.isNumeric( lastBill ) ) { lastBill = 0; }
		lastBill = parseFloat(lastBill);
		
        var advanceBill = $("#debtorvalueadvance").val();
        if(!jQuery.isNumeric( advanceBill ) ) { advanceBill = 0; }
		advanceBill = parseFloat(advanceBill);
		
		
		var discount = $("#Discount").val();
        if(!jQuery.isNumeric( discount ) ) { discount = 0; }
		discount = parseFloat(discount);
       

        dr = parseFloat(dr) + parseFloat(lastBill) - parseFloat(discount);
        dr = Math.round(dr * 100) / 100;

        jQuery('[name=Bill_Amount]').val(dr);
		
		

        dr = parseFloat(dr)  - parseFloat(advanceBill);
        dr = Math.round(dr * 100) / 100;
        jQuery('[name=Pending_Amount]').val(dr);
}


function purSale(thisId)
{
	window.Total_QTY = 0;
	window.Total_FQ = 0;
	window.Total_GST_Amt = 0;
	window.Total_Disc = 0;
	window.Total_Taxable_Amt = 0;
	window.Total_Total = 0;
	
	var QTY = jQuery("input[name=QTY_"+thisId+"]").val();
	if ( !jQuery.isNumeric(QTY) ) QTY = 0;
	var Rate = jQuery("input[name=Rate_"+thisId+"]").val();
	if ( !jQuery.isNumeric(Rate) ) Rate = 0;
	var GST = jQuery("input[name=GST_Rate_"+thisId+"]").val();
	if ( !jQuery.isNumeric(GST) ) GST = 0;
	var Disc = jQuery("input[name=Disc_"+thisId+"]").val();
	if ( !jQuery.isNumeric(Disc) ) Disc = 0;
	
	var Disc_Amt =   QTY * Rate * Disc / 100;
	var Taxable_Amt =  ( QTY * Rate ) -  ( QTY * Rate * Disc / 100 );
	var GST_Amt =   Taxable_Amt * GST / 100 ;
	var Total =   parseFloat( Taxable_Amt ) +  parseFloat ( GST_Amt ) ;
	
	Disc_Amt = Math.round( Disc_Amt * 100 ) / 100;
	Taxable_Amt = Math.round( Taxable_Amt * 100 ) / 100;
	GST_Amt = Math.round( GST_Amt * 100 ) / 100;
	Total = Math.round( Total * 100 ) / 100;
	
	jQuery("input[name=Disc_Amt_"+thisId+"]").val(Disc_Amt);
	jQuery("input[name=Taxable_"+thisId+"]").val(Taxable_Amt);
	jQuery("input[name=GST_"+thisId+"]").val(GST_Amt);
	jQuery("input[name=Total_"+thisId+"]").val(Total);
	
	var data = jQuery('[name^=Product_Name_]');
	jQuery.each( data , function()
	{ 
		var thisId = jQuery(this).attr("name").split("Product_Name_")[1];
		var thisVal = jQuery(this).val();
		if( thisVal.length > 0)
		{
			var QTY = jQuery("input[name=QTY_"+thisId+"]").val(); 
			if ( !jQuery.isNumeric( QTY ) ) QTY = 0;
			window.Total_QTY = parseFloat( window.Total_QTY ) + parseFloat( QTY ) ;

			var FQ = jQuery("input[name=FQ_"+thisId+"]").val();
			if ( !jQuery.isNumeric( FQ ) ) FQ = 0;
			window.Total_FQ = parseFloat( window.Total_FQ ) + parseFloat( FQ ) ;
			
			var Disc_Amt = jQuery("input[name=Disc_Amt_"+thisId+"]").val();
			if ( !jQuery.isNumeric( Disc_Amt ) ) Disc_Amt = 0;
			window.Total_Disc = parseFloat( window.Total_Disc ) + parseFloat( Disc_Amt ) ;
			
			
			var Taxable_Amt = jQuery("input[name=Taxable_"+thisId+"]").val();
			if ( !jQuery.isNumeric( Taxable_Amt ) ) Taxable_Amt = 0;
			window.Total_Taxable_Amt = parseFloat( window.Total_Taxable_Amt ) + parseFloat( Taxable_Amt ) ;
			
			var GST_Amt = jQuery("input[name=GST_"+thisId+"]").val();
			if ( !jQuery.isNumeric( GST_Amt ) ) GST_Amt = 0;
			window.Total_GST_Amt = parseFloat( window.Total_GST_Amt ) + parseFloat( GST_Amt ) ;
			
			var Total = jQuery("input[name=Total_"+thisId+"]").val();
			if ( !jQuery.isNumeric( Total ) ) Total = 0;
			window.Total_Total = parseFloat( window.Total_Total ) + parseFloat( Total ) ;
		}
		
	});
	window.Total_QTY = Math.round( window.Total_QTY * 100 ) / 100;
	window.Total_Disc = Math.round( window.Total_Disc * 100 ) / 100;
	window.Total_FQ = Math.round( window.Total_FQ * 100 ) / 100;
	window.Total_GST_Amt = Math.round( window.Total_GST_Amt * 100 ) / 100;
	window.Total_Taxable_Amt = Math.round( window.Total_Taxable_Amt * 100 ) / 100;
	window.Total_Total = Math.round( window.Total_Total * 100 ) / 100;
	
	jQuery("input[name=Total_QTY]").val(window.Total_QTY);
	jQuery("input[name=Total_FQ]").val(window.Total_FQ);
	jQuery("input[name=Total_Disc]").val(window.Total_Disc);
	jQuery("input[name=Total_GST_Amt]").val(window.Total_GST_Amt);
	jQuery("input[name=Total_Taxable_Amt]").val(window.Total_Taxable_Amt);
	jQuery("input[name=Bill_Amount]").val(window.Total_Total);

}


function splitTextarea( val ) {
    return val.split( /\n\s*/ );
}
function extractLast( term ) {
    return splitTextarea( term ).pop();
}
function readURL( uploader , thisId, imageId) {
    if ( uploader.files && uploader.files[0] ){
		var thisImage = '';
		if(imageId != '')thisImage = "_"+imageId;
          $('#profilePic'+thisImage).attr('src', 
             window.URL.createObjectURL(uploader.files[0]) );
    }
}
function modalErrorShow(data,modalName)
	{
		jQuery("#"+modalName+"_Error").html('<div class="alert alert-danger">'+data+'</div>');
	}
	function modalError(data,modalName)
	{
		data = data.split(',');
		var _isDanger = "alert-success";
		var _errorStr = '';
		var _i =0;
		jQuery.each( data,function (index,value)
		{
			if(value.match("alert-danger")) _isDanger = 'alert-danger';
			_errorStr += _i == 0 ? value.split("-alert-")[0].replace('[','').replace('"','') : "<br>" + value.split("-alert-")[0].replace('[','').replace('"','');
			_i++;
		});
		jQuery("#"+modalName+"_Error").html('<div class="alert '+_isDanger+'">'+_errorStr+'</div>');
	}
 function discharge_total(field)
  {
	  var td = jQuery('input[name*='+field+']');
	  var dr = jQuery("#debtorvalue").val();
	  if(!jQuery.isNumeric( dr ) ) { dr = 0; }
	  jQuery.each(td, function(index, value){
		var val = jQuery(this).val();
		if(!jQuery.isNumeric( val ) ) { val = 0; }
		dr = parseFloat(dr) + parseFloat(val);
	  });

	  dr = Math.round(dr * 100) / 100;

		  jQuery('[name=Bill_Amount]').val(dr);
  }
function select2Focus(name)
{
	setTimeout(function() {
		$("select[name="+name+"]").select2("focus");
	}, 250);
}