
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function disabledMonthOther() {
    $('.month_checked').prop("disabled", true);
    $('.others_service').prop('disabled',true);
}

function reset_payment_value(){
        $('.total').val('');
        $('#total_due').html('');
        $('#rest_of_due').val('');
        $('#payment_method').val('');
        $('#payment_method_name').val('');
        $('#record_name').html('');
        $('#record_address').html('');
}


// Get Client payment information

    function getPaymentInfo() {
        
        disabledMonthOther();
        
        var c_date = new Date();
        var year   = c_date.getFullYear();
    
        var record_id = document.getElementById("client_name").value;
        let target = $("#target").val();

        $('.month_checked').prop('checked',false);
        $('.paid').text('');
        $('.others_service').prop('checked',false);
        $('.get_others_value').hide();
        $('.get_others_value').val('');
        $('.amount').val('');
        $('.total').val('');
        $('#total_due').html('');
        $('#record_name').html('');
        $('#record_address').html('');


        //console.log(record_id);
        if(record_id!=''){
            $.ajax ({
                type: 'POST',
                url: target+'billpayment/get_payment_fee_info',
                dataType: "JSON",
                data: { year:year, record_id: record_id },
                success : function(response) {

                    $('.month_checked').prop('checked',false);
                    $('.others_service').prop('checked',false);
                    $('.paid').text('');
                    
                    document.getElementById("current_year").selected = "true";

                     var total_amunt_check = Math.sign(response.data.total_due);

                     if(total_amunt_check ==1){

                        $('#total_advance_text').text('Due');

                     }else if(total_amunt_check ==-1){

                        $('#total_advance_text').text('Advance');
                     }

                    $('#total_due_amount').val(Math.abs(response.data.total_due));
                   // $('#total_discount').val(Math.abs(response.data.total_discount));
                    $('#total_due').html(Math.abs(response.data.total_due));
                    $('#record_name').html(response.data.client_info.record_name);
                    $('#record_address').html(response.data.client_info.record_address);
                    $('#client_primary_id').val(response.data.client_info.client_p_id);

                    $.each(response.data.voucher_info, function(key,value){
                        
                        $('#month_id'+value.item_id).prop('checked', true);
                        $('#month_id'+value.item_id).prop("disabled", true);

                        $('#others_service'+value.item_id).prop('checked', true);
                        $('#others_service'+value.item_id).prop("disabled", true);

                        if(value.is_paid ==1){
                            $('#span_id'+value.item_id).text('Paid');
                            $('#span_id'+value.item_id).css('color','green');
                            

                        }else{
                            $('#span_id'+value.item_id).text('Unpaid');
                        }                       
                    });                  
                }

            });
        } else {
           // ...record_id empty
        }
    }

jQuery(document).ready(function($) {
    $('#payment_method_name').hide();
    $("#payment_method").on('change', function() {
          var method = $( "#payment_method" ).val();
          if(method !=16 && method !=''){
            $('#payment_method_name').show();
          }else{
            $('#payment_method_name').hide();
          }
        
    });
});


jQuery(document).ready(function($) {
    $("#total").on('keyup', function() {
        var total            = $( "#total" ).val();
        var total_due_amount = $( "#total_due_amount" ).val();
        var rest_of_due =  Math.abs(total_due_amount-total);

        parseInt($("#rest_of_due" ).val(rest_of_due));

        
    });
});

// payment bill
function bill_payment()
{
   
    var district_name      = $("#district_name").val();
    var upazilla_name      = $("#upazilla_name").val();
    var client_type        = $("#client_type").val();
    var client_name        = $("#client_name").val();
    var client_primary_id  = $("#client_primary_id").val();
    var year               = $("#year").val();
    var total              = $("#total").val();
    //var total_discount     = $("#total_discount").val();
    //var total              = total_fee+total_discount;
    var total_due_amount   = $("#total_due_amount").val();
    var rest_of_due        = $("#rest_of_due").val();
    var payment_method     = $("#payment_method").val();
    var payment_method_name= $("#payment_method_name").val();
    var payment_date       = $("#payment_date").val();
    var remark             = $("#remark").val();
    let target             = $("#target").val();


    var get_client_name   = $.trim($("#client_name").children("option:selected").text());
    
    var confirm_msg = 'Total : '+total_due_amount+'\n Payment :'+total;

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
     if (total == '') {
        $("#total_error").html('Empty Payment');
        error_status = true;
    }else{
        $("#total_error").html('');
        
    }
    if (payment_method == '') {
        $("#payment_method_error").html('Empty Payment');
        error_status = true;
    }else{
        $("#payment_method_error").html('');
        
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
                url:target + "payment-bill",
                dataType: "JSON",
                data: { 
                    client_name:client_name,
                    client_primary_id:client_primary_id,
                    year:year,
                    total_due_amount:total_due_amount,
                    rest_of_due:rest_of_due,
                    payment_method:payment_method,
                    payment_method_name:payment_method_name,
                    total:total,
                    payment_date:payment_date,
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
                        reset_payment_value();
                    }

                });
            }
        })             
        }else{
            alert(" Empty payment");
        }

}

// Money receipt list
var bil_table;

function get_money_receipt_list() {
    let target = $("#target").val();
    bill_table = $('#money_receipt_list_table').DataTable({
        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,
        ajax:{
            dataType: "JSON",
            type: "post",
            url: target + "payment/money_receipt_show",
            data: {
               // _token : user_csrf
            },
        },
        columns:[
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            {
                title: "SL",
                data: null,
                render: function(){
                    return bill_table.page.info().start + bill_table.column(0).nodes().length;
                }           
            },
            {
                title: "Name",
                data: "name"
            },
            {
                title: "Mobile",
                data: "mobile_no"
            },
            {
                title: "Email",
                data: "email"
            },
            {
                title: "Transaction id",
                data: "trans_id"
            },
            {
                title: "Amount",
                data: "total_amount"
            },
            {
                title: "Payment date",
                data: "payment_date"
            },
            {
                title: "Action",
                data: null,
                render: function(data, type, row, meta){
                        
                    return  "<a href='money_receipt_view/" + data.trans_id + "' target='_blank'><p class='btn btn-sm btn-primary' ><i class='fa fa-money'> Money Receipt </i></p> </a> ";
                
                }
            },
        ],
        columnDefs: [{
            targets: "datatable-nosort",
            orderable: false,
        }],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "language": {
            "info": "_START_-_END_ of _TOTAL_ entries",
            searchPlaceholder: "Search"
        },
        dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},
                {extend: 'print',
                     customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                    }
                }
            ]
    });
}

// search payment
function search_money_receipt()
{
    district_id      = $("#district_name").val();
    upazilla_id      = $("#upazilla_name").val();
    client_type      = $("#client_type").val();
    record_id        = $("#client_name").val();

    
    $("#money_receipt_list_table").dataTable().fnSettings().ajax.data.district_id      = district_id;
    $("#money_receipt_list_table").dataTable().fnSettings().ajax.data.upazilla_id      = upazilla_id;
    $("#money_receipt_list_table").dataTable().fnSettings().ajax.data.client_type      = client_type;
    $("#money_receipt_list_table").dataTable().fnSettings().ajax.data.record_id        = record_id;

    bill_table.ajax.reload();
    
}
