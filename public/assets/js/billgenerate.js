let payable_amount = 0;
let amount         = 0;
let discount       = 0;
let fee_amount     = 0;
          
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#last_payment .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true,
    format: "yyyy-mm-dd"
});

jQuery(document).ready(function($) {
    $("#district_name").on('change', function() {
        resetUpazilla();

        var id = $(this).val();
        
        let target = $("#target").val();

        console.log(target);

        if(id!=''){
            $.ajax ({
                type: 'GET',
                url: target+'client/show_upazilla',
                data: { id: id },
                success : function(res) {
                    $('#upazilla_name').html(res.data);
                }
            });
        }
    });
});

jQuery(document).ready(function($) {
    $("#upazilla_name").on('change', function() {
        resetType();
        
    });
});

jQuery(document).ready(function($) {
    $("#client_type").on('change', function() {
        resetClient();
        var type = $(this).val();
        var upa = $("#upazilla_name").val();
        let target = $("#target").val();

        if(type!='' && upa!=''){
            $.ajax ({
                type: 'GET',
                url: target+'billprepare/ajax_client',
                data: { type: type,upa: upa },
                success : function(res) {
                    $('#client_name').html(res.data);
                }
            });
        }
    });
});


function getClientFee() {

    resetYearOtherFees();
    
    var record_id = document.getElementById("client_name").value;
    let target    = $("#target").val();
    
    if(record_id!=''){
        $.ajax ({
            type: 'GET',
            url: target+'billprepare/get_fee_info',
            data: { record_id: record_id },
            success : function(response) {
                $('#fee_amount').html(response.data);
            }
        });
    } else {
        $('#fee_amount').html('<input name="fee" id="fee" value="0" class="form-control fee_amount" disabled>');
    }
}

function resetAll() {

    $('#district_name').prop('selectedIndex',0);
    resetUpazilla();
    resetYearOtherFees();
}

function resetUpazilla() {

    $('#upazilla_name').prop('selectedIndex',0);
    $('#upazilla_name').html('<option selected value="">Select</option>');
    resetType();
    $('#fee_amount').val('');
}

function resetType() {

    $('#client_type').prop('selectedIndex',0);
    resetClient();
    disabledMonthOther();
    $('#fee_amount').val('');
}

function resetClient() {

    $('#client_name').prop('selectedIndex',0);
    $('#client_name').html('<option selected value="">Select</option>');
    resetFee();
    resetYearOtherFees();
    console.log($('#client_name').val());
}

function resetYearOtherFees() {

    $('#warning_msg').hide();
    $('#year').prop('selectedIndex',0);
    $('.month_checked').prop('checked',false);
    $('.paid').text('');
    $('#amount').val('');
    $('#total').val('');
    $('#discount').val('');
    $('.others_service').prop('checked',false);

    $('.get_others_value').hide();
    $('.get_others_value').val('');
}

function resetFee() {

    $('#fee_amount').html('<input name="fee" id="fee" value="0" class="form-control fee_amount" disabled>');
    console.log("i am here");
}

function disabledMonthOther() {

    $('.month_checked').prop("disabled", true);
    $('.others_service').prop('disabled',true);
}



jQuery(document).ready(function($) {

    disabledMonthOther();

    $("#year").on('change', function() {
        $('.month_checked').prop('checked',false);
        $('.others_service').prop('disabled',false);
        $('.paid').text('');
        $('.others_service').prop('checked',false);
        $('.get_others_value').hide();
        $('.get_others_value').val('');
        $('.amount').val('');
        $('.total').val('');

        let target = $("#target").val();
        var year = $(this).val();
        var record_id = $('#client_name').val();
        $('.month_checked').prop("disabled", false);
        console.log('i am a error'+ record_id);
        if(year!='' && record_id!=''){
            $('#warning_msg').hide();
            $.ajax ({
                type: 'POST',
                url: target+"billprepare/get_monthly_fee_info",
                dataType: "JSON",
                data: { year:year, record_id: record_id },
                success : function(response) {
                    $('.month_checked').prop('checked',false);
                    $('.paid').text('');
                    $.each(response.data,function(key,value){
                        $('#month_id'+value.item_id).prop('checked', true);
                        $('#month_id'+value.item_id).prop("disabled", true);

                        if(value.is_paid ==1){
                            $('#span_id'+value.item_id).text('Paid');
                            $('#span_id'+value.item_id).css('color','green');
                        }
                        else
                            $('#span_id'+value.item_id).text('Unpaid');
                    });
                }
            });
        } else if(record_id == ''){
            $('#warning_msg').text('Select a client');
            $('#warning_msg').show();
            
            disabledMonthOther();

        } else{
            
            disabledMonthOther();
        }
    });
});

// enable disable others service amount
function enableDisable(row_id){
    if ($('#others_service'+row_id).is(':checked')){
        $('#others_service_value'+row_id).show();
    } else {
        // reset and hide
        $('#others_service_value'+row_id).val('');
        $('#others_service_value'+row_id).hide();
    }

    // call to calculation
    let total_service = parseInt($("#service_count").val()) || 0;
    others_calculation(total_service);

}


// global calculation
function others_calculation(total_service){
    fee_amount = parseInt($(".fee_amount").val()) || 0;
    let total_other_amt = 0;
    let total_month_amt = 0;
    amount = 0;
    payable_amount = 0;
    discount = parseInt($('#discount').val()) || 0;
    let pay = parseInt($('.amount').val()) || 0;

    for(var i=1; i<=total_service; i++){
        total_other_amt += parseInt($("#others_service_value"+i).val()) || 0;
    }

    for(var j=1; j<=12; j++){
        if(($('#month_id'+j).prop("checked") == true) && ($('#month_id'+j).prop("disabled") == false)){
            total_month_amt += fee_amount;
        }
    }

    if(discount<0){
        $('#dis').show();
        $('#dis').text('discount can not be less than 0');
        discount = 0;
    } else if(discount > pay){
        $('#dis').show();
        $('#dis').text('discount can not be greater than amount');
        discount = 0;
    } else{
        $('#dis').hide();
    }
    
    amount += total_other_amt;
    payable_amount = amount;
    payable_amount -= discount;

    $('#amount').val(amount + total_month_amt);
    $('#total').val(payable_amount + total_month_amt);

}



function bill_save(){
   
    var district_name     = $("#district_name").val();
    var upazilla_name     = $("#upazilla_name").val();
    var client_type       = $("#client_type").val();
    var client_name       = $("#client_name").val();
    var year              = $("#year").val();
    var month_id          = month_ids;
    var others_service_id = others_service_ids;
    var amount            = $("#amount").val();
    var fee               = $("#fee").val();
    var discount          = $("#discount").val();
    var due               = $("#due").val();
    var total             = $("#total").val();
    var last_payment_date = $("#last_payment_date").val();
    var remark            = $("#remark").val();
    let target            = $("#target").val(); 


    var get_client_name   = $.trim($("#client_name").children("option:selected").text());


    var mn = $("input[type=checkbox]");

    var month_ids = Array();
    var others_service_ids = Array();

    for(var i = 0; i < mn.length; i++){
        if(mn[i].checked == true && mn[i].disabled == false && mn[i].name == 'month_id[]'){
            month_ids.push(mn[i].value);
        }
    }

    for(var i = 0; i < mn.length; i++){
        if(mn[i].checked == true && mn[i].disabled == false && mn[i].name == 'others_service[]'){
            others_service_ids.push(mn[i].value);
        }
    }
    var month_name = '';
    let y = 0;
    for(var i = 0; i < mn.length; i++){
        if(mn[i].checked == true && mn[i].disabled == false && mn[i].name == 'month_id[]'){
        month_name += $.trim($('#month_label_'+mn[i].value).text());
        if(y == mn.length-1){
           month_name += ','; 
        }else{
            month_name += '.';
        }
        y++;
        }
    }

    var others_service_value = $('input[name^=others_service_value]').map(function(idx, elem) {
        return $(elem).val();
      }).get();


   

    
    var confirm_msg = 'Total : '+amount+'\n Unit Fee : '+fee+'\n Payable Amount :'+total+'\n  Month :'+month_name;
    

    //console.log(others_service_ids);

    var error_status = false;

     if (district_name == '') {
        $("#district_name_error").html('Empty District');
        error_status = true;
    }else{
        $("#district_name_error").html('');
        
    }
    if (upazilla_name == '') {
        $("#upazilla_name_error").html('Empty Upazilla');
        error_status = true;
    }else{
        $("#upazilla_name_error").html('');
        
    }
    if (client_type == '') {
        $("#client_type_error").html('Empty Client type');
        error_status = true;
    }else{
        $("#client_type_error").html('');
        
    }
    if (client_name == '') {
        $("#client_name_error").html('Empty Client');
        error_status = true;
    }else{
        $("#client_name_error").html('');
        
    }
    if (year == '') {
        $("#year_error").html('Empty Year');
        error_status = true;
    }else{
        $("#year_error").html('');
        
    }
    if (fee == '') {
        $("#fee_error").html('Empty Fee');
        error_status = true;
    }else{
        $("#fee_error").html('');
        
    }
    if (amount <= 0) {
        $("#amount_error").html('Empty Amount');
        error_status = true;
    }else{
        $("#amount_error").html('');
        
    }
    if (total <= 0) {
        $("#total_error").html('Empty Payable Amount');
        error_status = true;
    }else{
        $("#total_error").html('');
        
    }

    if (error_status == false) {
        
        swal({
            title: "Name : "+get_client_name,
            text:  confirm_msg,
            type: "info",
            showConfirmButton: true,
            confirmButtonClass: "btn-success",
            confirmButtonText: "Yes",
            closeOnConfirm: false,
            showCancelButton: true,
            cancelButtonText: "No"
            }, 
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                type: "POST",
                url:target + "generate-bill",
                dataType: "JSON",
                data: { 
                    client_name:client_name,
                    year:year,
                    month_ids:month_ids,
                    others_service_ids:others_service_ids,
                    others_service_value:others_service_value,
                    amount:amount,
                    discount:discount,
                    fee:fee,
                    due:due,
                    total:total,
                    last_payment_date:last_payment_date,
                    remark:remark
                },
                success: function(response){
                    swal({
                        title: "Response",
                        text: response.message,
                        type: response.status,
                        showCancelButton: false,
                        showConfirmButton: true,
                        closeOnConfirm: true,
                        allowEscapeKey: false
                        })
                        //reset value 
                         resetUpazilla();
                         resetYearOtherFees();
                    }

                });
            }
        })

    } else{
        
    }


}
