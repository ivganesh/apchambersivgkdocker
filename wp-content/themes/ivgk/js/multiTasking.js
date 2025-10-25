
    $('#AddRefer').modal({backdrop: 'static', keyboard: false, show:false});
    $('#AddRecetion').modal({backdrop: 'static', keyboard: false, show:false});
    $('#AddCash').modal({backdrop: 'static', keyboard: false, show:false});
    $('#AddAdmit').modal({backdrop: 'static', keyboard: false, show:false});
    $('#AddAppoint').modal({backdrop: 'static', keyboard: false, show:false});
    jQuery('body').on('click','[id*=ReferMultiTasking__]',function(){
        $("[id^=AddRefer_]").val(""); 
        var now = new Date();
        var dddd = ("0" + now.getDate()).slice(-2);
        var mmmm = ("0" + (now.getMonth() + 1)).slice(-2);
        var currentDate = now.getFullYear()+"-"+(mmmm)+"-"+(dddd) ;
        var thisId = jQuery(this).attr("id").split("__")[1];
        jQuery("#AddRefer_Error").html('');       
        jQuery("#AddRefer_Refer_Date").val(currentDate);
        jQuery("#AddRefer_Patient_Name").val(thisId);
    });

    jQuery('body').on('click','[id*=ReceptionMultiTasking__]',function(){
        $("[id^=AddReception_]").val(""); 
        var now = new Date();
        var dddd = ("0" + now.getDate()).slice(-2);
        var mmmm = ("0" + (now.getMonth() + 1)).slice(-2);
        var currentDate = now.getFullYear()+"-"+(mmmm)+"-"+(dddd) ;
        var thisId = jQuery(this).attr("id").split("__")[1];
        jQuery("#AddReception_Error").html(''); 
        jQuery("#AddReception_Bill_Date").val(currentDate);
        jQuery("#AddReception_Patient_Name").val(thisId);
    });

    


	
	
	
     jQuery('body').on('click','[id*=CashMultiTasking__]',function(){
        $("[id^=AddCash_]").val(""); 
        var now = new Date();
        var dddd = ("0" + now.getDate()).slice(-2);
        var mmmm = ("0" + (now.getMonth() + 1)).slice(-2);
        var currentDate = now.getFullYear()+"-"+(mmmm)+"-"+(dddd) ;
        var thisId = jQuery(this).attr("id").split("__")[1];
        jQuery("#AddCash_Error").html(''); 
        jQuery("#AddCash_Bill_Date").val(currentDate);
        jQuery("#AddCash_Account_Name_1").val(thisId);
        
    });
    jQuery('body').on('click','[id*=AdmitMultiTasking__]',function(){
        $("[id^=AddAdmit_]").val(""); 
        var now = new Date();
        var dddd = ("0" + now.getDate()).slice(-2);
        var mmmm = ("0" + (now.getMonth() + 1)).slice(-2);
        var currentDate = now.getFullYear()+"-"+(mmmm)+"-"+(dddd) ;
        var currentHours = ("0" + now.getHours()).slice(-2);
        var currentMinutes = ("0" + now.getMinutes()).slice(-2);
        var currentTime = currentHours + ":" + currentMinutes + ":00";
        var thisId = jQuery(this).attr("id").split("__")[1];
        jQuery("#AddAdmit_Error").html(''); 
        jQuery("#AddAdmit_Admit_Date").val(currentDate);
        jQuery("#AddAdmit_Admit_Time").val(currentTime);		
        jQuery("#AddAdmit_Patient_Name").val(thisId);
    });
	
	jQuery('body').on('change','[id=AddAppoint_Appoint_Date]',function(){
        var thisId = $("#AddAppoint_Patient_Name").val();
		 var dataa = {};
		
		dataa['action'] = 'getLastAppoint';
		dataa['patientId'] = thisId;
		dataa['appointDate'] = $("#AddAppoint_Appoint_Date").val();
		if( dataa['patientId'] == '' || dataa['appointDate'] == '' )
			return false;
		$('html').block();
		$.ajax({
			type: 'POST',
			url: thisAjax,
			data:dataa,
			success: function(data) {
				$('html').unblock(); 
				data = data.trim();
				if(data == 'ERROR')
				{
					if(data.match("alert-danger"))
					{
						modalError(data,"AddAppoint");
					}else{ 
						modalError(data,"AddAppoint");  
					}
				}
				else{
					data = data.split('----');
					modalError(data[0],"AddAppoint"); 
					jQuery("#AddAppoint_Bill_Amount").val(data[1]);
				}
			},
			error: function (errorThrown) {
				$('html').unblock();
				alert_danger(errorThrown.responseText);
			}
		}); 
        
    });
	
    jQuery('body').on('click','[id*=AppointMultiTasking__]',function(){
		
		 var thisId = jQuery(this).attr("id").split("__")[1];
		 var dataa = {};
		 var now = new Date();
		var dddd = ("0" + now.getDate()).slice(-2);
		var mmmm = ("0" + (now.getMonth() + 1)).slice(-2);
		var currentDate = now.getFullYear()+"-"+(mmmm)+"-"+(dddd) ;
		
		dataa['action'] = 'getLastAppoint';
		dataa['patientId'] = thisId;
		dataa['appointDate'] = currentDate; 
		$('html').block();
		$.ajax({
			type: 'POST',
			url: thisAjax,
			data:dataa,
			success: function(data) {
				$('html').unblock(); 
				data = data.trim();
				if(data == 'ERROR')
				{
					alert_danger("Cant get last apppointment.");
				}
				else{
					data = data.split('----');
					$('#AddAppoint').modal('toggle');
					$("[id^=AddAppoint_]").val(""); 
					
					var currentHours = ("0" + now.getHours()).slice(-2);
					var currentMinutes = ("0" + now.getMinutes()).slice(-2);
					var currentTime = currentHours + ":" + currentMinutes + ":00";
				   
					jQuery("#AddAppoint_Error").html(''); 
					jQuery("#AddAppoint_Appoint_Date").val(currentDate);
					jQuery("#AddAppoint_Appoint_Time").val(currentTime);
					jQuery("#AddAppoint_Patient_Name").val(thisId);
					jQuery("#AddAppoint_Bill_Amount").val(data[1]);
					modalError(data[0],"AddAppoint");
					
				}
			},
			error: function (errorThrown) {
				$('html').unblock();
				alert_danger(errorThrown.responseText);
			}
		}); 
		
    });

    $("#modalReferForm").validate({
		rules: {
			Register: {
				required: function(element) {
					return $("#AddRefer_Cash_Receive").val() != "";
				},
			},
        },
        messages: {
			Register:"Register cant be blank if CASH is not blank.",
        },
		 highlight: function(element) {
			$(element).parent().addClass("error");
			
		  },
		  unhighlight: function(element) {
			$(element).parent().removeClass("error");
			
		  },
        submitHandler: function(form) { 
			$('html').block();
            $.ajax({
                type: 'POST',
                url: thisAjax,
                data:$(form).serialize(),
                success: function(data) {
					$('html').unblock(); 
                    if(data.trim() != 'ERROR')
                    {
                        if(data.match("alert-danger"))
                        {
                            modalError(data,"AddRefer");
                        }else{ modalError(data,"AddRefer");  $("[id^=AddRefer_]").val("");  $('#AddRefer').modal('toggle'); }
                    }else{
                        modalErrorShow(data,"AddRefer");
                    }
                },
				error: function (errorThrown) {
					$('html').unblock();
					alert_danger(errorThrown.responseText);
				}
            });
            return false; 
        }
    });
    $("#modalCashForm").validate({
		rules: {
			Register: {
				required: function(element) {
					return $("#AddCash_Cash_Receive").val() != "";
				},
			},
        },
        messages: {
			Register:"Register cant be blank if CASH is not blank.",
        },
		 highlight: function(element) {
			$(element).parent().addClass("error");
			
		  },
		  unhighlight: function(element) {
			$(element).parent().removeClass("error");
			
		  },
        submitHandler: function(form) { 
			$('html').block();
            $.ajax({
                type: 'POST',
                url: thisAjax,
                data:$(form).serialize(),
                success: function(data) {
					$('html').unblock(); 
                    if(data.trim() != 'ERROR')
                    {
                        if(data.match("alert-danger","AddCash"))
                        {
                            modalError(data,"AddCash");
                        }else{ modalError(data,"AddCash");  $("[id^=AddCash_]").val("");  $('#AddCash').modal('toggle'); }
                    }else{
                        modalErrorShow(data,"AddCash");
                    }
                },
				error: function (errorThrown) {
					$('html').unblock();
					alert_danger(errorThrown.responseText);
				}
            });
            return false; 
        }
    });
    $("#modalAppointForm").validate({
		rules: {
			Register: {
				required: function(element) {
					return $("#AddAppoint_Cash_Receive").val() != "";
				},
			},
        },
        messages: {
			Register:"Register cant be blank if CASH is not blank.",
        },
		 highlight: function(element) {
			$(element).parent().addClass("error");
			
		  },
		  unhighlight: function(element) {
			$(element).parent().removeClass("error");
			
		  },
        submitHandler: function(form) { 
			$('html').block();
            $.ajax({
                type: 'POST',
                url: thisAjax,
                data:$(form).serialize(),
                success: function(data) {
					$('html').unblock(); 
                    if(data.trim() != 'ERROR')
                    {
                        if(data.match("alert-danger","AddAppoint"))
                        {
                            modalError(data,"AddAppoint");
                        }else{  modalError(data,"AddAppoint");  $("[id^=AddAppoint_]").val("");  $('#AddAppoint').modal('toggle');  }
                    }else{
                        modalErrorShow(data,"AddAppoint");
                    }
                },
				error: function (errorThrown) {
					$('html').unblock();
					alert_danger(errorThrown.responseText);
				}
            });
            return false; 
        }
    });
	
    $("#modalAdmitForm").validate({
		rules: {
			Register: {
				required: function(element) {
					return $("#AddAdmit_Cash_Receive").val() != "";
				},
			},
        },
        messages: {
			Register:"Register cant be blank if CASH is not blank.",
        },
		 highlight: function(element) {
			$(element).parent().addClass("error");
			
		  },
		  unhighlight: function(element) {
			$(element).parent().removeClass("error");
			
		  },
        submitHandler: function(form) { 
			$('html').block();
            $.ajax({
                type: 'POST',
                url: thisAjax,
                data:$(form).serialize(),
                success: function(data) {
					$('html').unblock(); 
                    if(data.trim() != 'ERROR')
                    {
                        if(data.match("alert-danger","AddAdmit"))
                        {
                            modalError(data,"AddAdmit");
                        }else{ modalError(data,"AddAdmit");  $("[id^=AddAdmit_]").val("");  $('#AddAdmit').modal('toggle');  }
                    }else{
                        modalErrorShow(data,"AddAdmit");
                    }
                },
				error: function (errorThrown) {
					$('html').unblock();
					alert_danger(errorThrown.responseText);
				}
            });
            return false; 
        }
    });
    $("#modalReceptionForm").validate({
		rules: {
			Register: {
				required: function(element) {
					return $("#AddReception_Cash_Receive").val() != "";
				},
			},
        },
        messages: {
			Register:"Register cant be blank if CASH is not blank.",
        },
		 highlight: function(element) {
			$(element).parent().addClass("error");
			
		  },
		  unhighlight: function(element) {
			$(element).parent().removeClass("error");
			
		  },
        submitHandler: function(form) { 
			$('html').block();
            $.ajax({
                type: 'POST',
                url: thisAjax,
                data:$(form).serialize(),
                success: function(data) {
					$('html').unblock(); 
                    if(data.trim() != 'ERROR')
                    {
                        if(data.match("alert-danger","AddReception"))
                        {
                            modalError(data,"AddReception");
                        }else{ modalError(data,"AddReception");  $("[id^=AddReception_]").val("");  $('#AddReception').modal('toggle');  }
                    }else{
                        modalErrorShow(data,"AddReception");
                    }
                },
				error: function (errorThrown) {
					$('html').unblock();
					alert_danger(errorThrown.responseText);
				}
            });
            return false; 
        }
    });

    $('#AddCash').on('shown.bs.modal', function () {
        $('[name=DR_Amount]').focus();
    });
    $('#AddAppoint').on('shown.bs.modal', function () {
        $('[name=Appoint_DR]').focus();
    }); 
    $('#AddAdmit').on('shown.bs.modal', function () {
        $('[name=Room_No]').focus();
    });
    $('#AddReception').on('shown.bs.modal', function () {
        $('[name=Charge_Type_1]').focus();
    });
    $('#AddRefer').on('shown.bs.modal', function () {
        $('[name=Refer_By]').focus();
    });
