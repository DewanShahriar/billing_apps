//CSRF token setup for ajax call
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

//message body show in modal
function modal_show(index) {

    var data = report_table.row(index).data();
    console.log(data.message);
    $('#msg_body').modal('show');
    $('#msg').text(data.message);
}


var report_table, dis_id = 0, upa_id = 0, cli_type = 0, cli_id = 0, com_type = 0, status = null, custom = 0;

//all search
function get_report_list() {

    let target = $("#target").val();

    report_table = $('#report_list_table').DataTable({
        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,
        ajax:{
            dataType: "JSON",
            type: "post",
            url: target + "communication/search_report_list_show",
            data: {
               
        	},
        },
        columns:[
            {
                title: "SL",
                data: null,
                render: function(){
                    return report_table.page.info().start + report_table.column(0).nodes().length;
                }           
            },
            {
                title: "Client Name",
                data: "name"
            },
            {
                title: "Mobile",
                data: "to_addr"
            },
            {
                title: "Title",
                data: "title"
            },
            {
                title: "Message",
                data: null,
                render: function (data, type, row, meta) {
                    return data.message.substring(0,30)+" <a href='javascript:void(0)'><p class='btn btn-sm btn-success' onclick='modal_show("+meta.row+")' >more</p></a>"; 
                }
            },
            {
                title: "Type",
                data: null,
                render: function (data) {
                    if(data.type==1){
                        return '<span class="label label-info">SMS</span>'; 
                    } else{
                        return '<span class="label label-default">Email</span>';
                    }
                    
                }
            },
            
            {
                title: "Sending time",
                data: "sending_time"
            },
            {
                title: "Status",
                data: null,
                render: function (data) {
                    if(data.is_send==0){
                        return '<span class="label label-warning">unsend</span>';
                    }
                    else if(data.is_send==1){
                        return '<span class="label label-success">Successful</span>';
                    }
                    else if(data.is_send==2){
                        return '<span class="label label-danger">failed</span>';
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

//report custom search call from search button
function all_search()
{
    dis_id     = $("#district_name").val();
    upa_id     = $("#upazilla_name").val();
    cli_type   = $("#client_type").val();
    cli_id     = $("#client_name").val();
    com_type   = $("#com_type").val();
    status     = $("#status").val();
    custom     = $("#custom").val();
    
    $("#report_list_table").dataTable().fnSettings().ajax.data.dis_id   = dis_id;
    $("#report_list_table").dataTable().fnSettings().ajax.data.upa_id   = upa_id;
    $("#report_list_table").dataTable().fnSettings().ajax.data.cli_type = cli_type;
    $("#report_list_table").dataTable().fnSettings().ajax.data.cli_id   = cli_id;
    $("#report_list_table").dataTable().fnSettings().ajax.data.com_type = com_type;
    $("#report_list_table").dataTable().fnSettings().ajax.data.status   = status;
    $("#report_list_table").dataTable().fnSettings().ajax.data.custom   = custom;

    report_table.ajax.reload();
    
}