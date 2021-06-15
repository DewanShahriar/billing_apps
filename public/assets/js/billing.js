$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var dis_id = 0, upa_id = 0, cli_type = 0, cli_id = 0, status = null;

function send_sms(index) {
    let target = $("#target").val();
    
    var data = bill_table.row(index).data();
    console.log(data);
    var id = data.invoice_id;
    var months = data.names;
    var monthArr = months.split(',');
    var len = monthArr.length;
    //console.log(months);
    var cli_mo = data.mobile_no;
    var cli_id = data.record_id;
    var amt = data.net_amount;
    var last_date = data.last_payment_date;
    var msg_body = 'Your bill for '+months+'. Total due BDT '+amt+' Please pay your bill by '+last_date+'.\nInnovation IT';

    swal({
    title: "SMS send to "+cli_mo,
    text: msg_body,
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
            url: target + "billprepare/sms_save",
            dataType: "JSON",
            data: { 
                id: id, 
                cli_mo: cli_mo, 
                cli_id: cli_id, 
                msg_body: msg_body
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
                // bill_table.ajax.reload();
                }

            });
        }
    })

}

function send_email(index) {
    let target = $("#target").val();
    
    var data = bill_table.row(index).data();
    // console.log(data);
    var id = data.invoice_id;
    var months = data.names;
    var cli_mo = data.mobile_no;
    var cli_id = data.record_id;
    var cli_email = data.email;
    var amt = data.net_amount;
    var last_date = data.last_payment_date;
    var msg_body = 'Your bill for '+months+'. Total due BDT '+amt+' Please pay your bill by '+last_date+'.\nInnovation IT';

    swal({
    title: "SMS email to "+cli_email,
    text: msg_body,
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
            url: target + "billprepare/email_save",
            dataType: "JSON",
            data: { 
                id: id, 
                cli_email: cli_email, 
                cli_id: cli_id, 
                msg_body: msg_body
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
                bill_table.ajax.reload();
                }

            });
        }
    })

}

jQuery(document).ready(function($) {
    $("#district_name").on('change', function() {
        
        var id = $(this).val();
        let target = $("#target").val();

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
    $("#client_type").on('change', function() {
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

function bill_delete(id)
{
    let target = $("#target").val();

    swal({
    title: "Confirmation",
    text: "Are you want to delete ?",
    type: "warning",
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
            url: target + "billprepare/bill_delete",
            dataType: "JSON",
            data: { id: id },
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
                bill_table.ajax.reload();
                }

            });
        }
    })
}


var bill_table;

function get_bill_list() {
    let target = $("#target").val();
    bill_table = $('#bill_list_table').DataTable({
        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,
        ajax:{
            dataType: "JSON",
            type: "post",
            url: target + "billprepare/bill_list_show",
            data: {
               
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
                title: "Invoice id",
                data: "invoice_id"
            },
            {
                title: "Amount",
                data: "net_amount"
            },
            {
                title: "Generated date",
                data: "created_at"
            },
            {
                title: "Status",
                data: null,
                render: function (data) {
                    if(data.is_paid==1){
                        return '<span class="label label-primary">Paid</span>';
                    }
                    else if(data.is_paid==0){
                        return '<span class="label label-danger">Unpaid</span>';
                    } 
                }
            },
            {
                title: "Action",
                data: null,
                render: function(data, type, row, meta){
                    if(data.is_paid==0){
                        
                        return  "<a href='javascript:void(0)'><p class='btn btn-sm btn-default' onclick='send_sms("+meta.row+")' ><i class='fa fa-wechat'> SMS</i></p></a> <a href='javascript:void(0)'><p class='btn btn-sm btn-info' onclick='send_email("+meta.row+")'><i class='fa fa-telegram'> Email</i></p></a> <a  href='bill_update/" + data.invoice_id + "'><p class='btn btn-sm btn-warning' ><i class='fa fa-edit'> Edit</i></p></a> <a href='bill_view/" + data.invoice_id + "' target='_blank'><p class='btn btn-sm btn-primary' ><i class='fa fa-paste'> View </i></p> </a> <a  href='javascript:void(0)'><p class='btn btn-sm btn-danger' onclick='bill_delete("+data.invoice_id+")' > <i class='fa fa-trash'> Delete</i></p></a> <a href='bill_view/" + data.invoice_id + "' target='_blank'><p class='btn btn-sm btn-success' ><i class='fa fa-money'> Payment </i></p> </a>";
                    } else{
                        return "<a href='bill_view/" + data.invoice_id + "' target='_blank'><p class='btn btn-sm btn-primary' ><i class='fa fa-paste'> View </i></p> </a>";
                    }
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

function all_search()
{
    
    dis_id     = $("#district_name").val();
    upa_id     = $("#upazilla_name").val();
    cli_type   = $("#client_type").val();
    cli_id     = $("#client_name").val();
    status     = $("#status").val();
    
    $("#bill_list_table").dataTable().fnSettings().ajax.data.dis_id   = dis_id;
    $("#bill_list_table").dataTable().fnSettings().ajax.data.upa_id   = upa_id;
    $("#bill_list_table").dataTable().fnSettings().ajax.data.cli_type = cli_type;
    $("#bill_list_table").dataTable().fnSettings().ajax.data.cli_id   = cli_id;
    $("#bill_list_table").dataTable().fnSettings().ajax.data.status   = status;

    bill_table.ajax.reload();
    
}