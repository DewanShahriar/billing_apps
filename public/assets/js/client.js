$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var dis_id = 0, upa_id = 0, cli_type = 0, status = null;

function client_delete(id)
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
            url: target + "client/client_delete",
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
                client_table.ajax.reload();
            	}

        	});
    	}
 	})
}

jQuery(document).ready(function($) {
    $("#district_name").on('change', function() {
        
        var id = $(this).val();
        let target = $("#target").val();
        console.log(target);
        if(id!=''){
            $.ajax ({
                type: 'GET',
                url: target+'client/show_upazilla',
                data: { id: id },
                success : function(res) {
                    $('#upazilla_id').html(res.data);
                }
            });
        }
    });
});


var client_table;

function get_client_list() {
    let target = $("#target").val();
    client_table =$('#client_list_table').DataTable({
        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,
        ajax:{
            dataType: "JSON",
            type: "post",
            url: target + "client/client_data_show",
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
                    return client_table.page.info().start + client_table.column(0).nodes().length;
                }
            },
            {
                title: "District name",
                data: "district_name",
            },
            {
                title: "Upazila name",
                data: "upazilla_name"
            },
            {
                title: "Name",
                data: null,
                render: function(data){
                    return "<a  href='client_profile/" + data.id + "' class='client-link'>"+ data.name +"</a>";

                }
            },
            {
                title: "Mobile",
                data: "mobile_no"
            },
            {
                title: "Type",
                data: null,
                render: function (data) {
                    if(data.client_type==1){
                        return '<span class="label label-info">Union</span>';
                    }
                    else if(data.client_type==2){
                        return '<span class="label label-info">Pourashava</span>';
                    }
                    else if(data.client_type==3){
                        return '<span class="label label-info">School</span>';
                    }
                }
            },
            {
                title: "Email",
                data: "email"
            },
            {
                title: "Weblink",
                data: null,
                render: function(data){
                    return "<a href='"+data.weblink+"'' class='client-link' target='_blank'><span class='label label-success'>Go to website</span></a>";

                }
                
            },
            {
                title: "Domain expire",
                data: "domain_expire"
            },
            {
                title: "Status",
                data: null,
                render: function (data) {
                    if(data.status==1){
                        return '<span class="label label-primary">Active</span>';
                    }
                    else if(data.status==2){
                        return '<span class="label label-warning">Sespend</span>';
                    }
                    else if(data.status==3){
                        return '<span class="label label-warning">Other</span>';
                    }
                }
            },
            {
                title: "Action",
                data: null,
                render: function(data){
                    return "<a  href='client_update/" + data.id + "'><p class='btn btn-sm btn-warning' ><i class='fa fa-edit'> Edit</i></p></a> <a href='client_profile/" + data.id + "'><p class='btn btn-sm btn-primary' ><i class='fa fa-paste'> View </i></p> </a> <a  href='javascript:void(0)'><p class='btn btn-sm btn-danger' onclick='client_delete("+data.id+")' > <i class='fa fa-trash'> Delete</i></p></a>";

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
    status     = $('#status').val();
    
    $("#client_list_table").dataTable().fnSettings().ajax.data.dis_id   = dis_id;
    $("#client_list_table").dataTable().fnSettings().ajax.data.upa_id   = upa_id;
    $("#client_list_table").dataTable().fnSettings().ajax.data.cli_type = cli_type;
    $("#client_list_table").dataTable().fnSettings().ajax.data.status   = status;
    
    client_table.ajax.reload();
    
}


jQuery(document).ready(function($) {
    $("#client_type").on('change', function() {
        
        var type   = $(this).val();
        var upa    = $("#upazilla_name").val();
        let target = $("#target").val();
        if(type ==1){
           
           $('#unionList').hide();
           $('#vatArea').hide();  

        }else if(type ==2){

            $('#unionList').hide(); 
            $('#vatArea').show(); 

        }else if(type ==3){
            
             $('#unionList').show();
             $('#vatArea').hide(); 

            if(type!='' && upa!=''){
                $.ajax ({
                    type: 'GET',
                    url: target+'client/get_union_list',
                    data: { type: type,upa: upa },
                    success : function(res) {
                        $('#union_name').html(res.data);
                    }
                });
            }
        }else{
             $('#unionList').hide(); 
        }

        
    });
});
// vat calculation 
jQuery(document).ready(function($) {
    $("#vat").on('change', function() {
    
        var fee = parseInt($("#fee_amount").val());

        var vat = parseInt($("#vat").val());
 
        var totalVat = parseInt((fee*vat)/100);
        var total    = parseInt(fee+totalVat);
    
        $('#fee_amount').val(total);

    });
});
