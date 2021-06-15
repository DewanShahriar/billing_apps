$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#previewImg').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$("#attach_file").change(function() {
  readURL(this);
});



// Support list
var suppor_table;

function get_support_list() {
    let target = $("#target").val();
    suppor_table = $('#support_report_table').DataTable({
        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,
        ajax:{
            dataType: "JSON",
            type: "post",
            url: target + "support/get_list",
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
                    return suppor_table.page.info().start + suppor_table.column(0).nodes().length;
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
                title: "Title",
                data: "title"
            },
            {
                title: "Client Type",
                data: null,
                render: function (data) {
                    if(data.client_type==1){
                        return 'Union';
                    }
                    else if(data.client_type==2){
                        return 'Pourashava';
                    }
                    else if(data.client_type==3){
                        return 'School';
                    }  
                }
            },
            {
                title: "Ticket Date",
                data: "created_at"
             
            },
           	{
                title: "Status",
                data: null,
                render: function (data) {
                    if(data.status==1){
                        return '<span class="label label-primary">Complete</span>';
                    }
                    else if(data.status==0){
                        return '<span class="label label-warning">Pending... </span>';
                    }
                    else if(data.status==2){
                        return '<span class="label label-info"> &nbsp; Re-open &nbsp; </span>';
                    }  
                }
            },
             {
                title: "Assign",
                data: null,
                render: function (data) {
                    var assign_name  = data.assign_name;
                    if(data.assign_id =='' || data.assign_id ==null){
                      
                      return " ";
                    }else
                    {
                        return  assign_name+" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "+" <a style='color:#222; border:1px solid #666666; text-align:center; background-color:#f9f9f9' onclick='remove_assign("+data.id+")' > <i style='width:20px; height:15px;' class='fa fa-times' aria-hidden='true'></i> </a>";
                       
                    }
                }
            },
            {
                title: "Action",
                data: null,
                render: function(data, type, row, meta){
                	if(data.status==0){
                        
                    	if(data.assign_id =='' || data.assign_id ==null){

                            return  "<a href='view_ticket/" + data.id + "' ><p class='btn btn-sm btn-info'> <i class='fa fa-eye' aria-hidden='true'></i> View</p> </a> <a onclick='assign_ticket("+data.id+")' ><p class='btn btn-sm btn-success' > <i class='fa fa-paper-plane' aria-hidden='true'></i> Assign</p> </a> <a onclick='ticket_close("+data.id+")' ><p class='btn btn-sm btn-primary'> <i class='fa fa-times' aria-hidden='true'></i> Close</p> </a>";
                         
                         }else{

                            return  "<a href='view_ticket/" + data.id + "' ><p class='btn btn-sm btn-info'> <i class='fa fa-eye' aria-hidden='true'></i> View</p> </a> <a onclick='ticket_close("+data.id+")' ><p class='btn btn-sm btn-primary'> <i class='fa fa-times' aria-hidden='true'></i> Close</p> </a>";

                        }
                    }
                	else if(data.status==1){
                		
                         return  "<a href='view_ticket/" + data.id + "' ><p class='btn btn-sm btn-info'> <i class='fa fa-eye' aria-hidden='true'></i> View</p> </a> <a onclick='re_open_ticket("+data.id+")' ><p class='btn btn-sm btn-warning'> <i class='fa fa-reply' aria-hidden='true'></i> Re-open</p> </a>";
                	}
                    if(data.status==2){
                        
                    return  "<a href='view_ticket/" + data.id + "' ><p class='btn btn-sm btn-info'> <i class='fa fa-eye' aria-hidden='true'></i> View</p> </a> <a onclick='assign_ticket("+data.id+")' ><p class='btn btn-sm btn-success' > <i class='fa fa-paper-plane' aria-hidden='true'></i> Assign</p> </a> <a onclick='ticket_close("+data.id+")' ><p class='btn btn-sm btn-primary'> <i class='fa fa-times' aria-hidden='true'></i> Close</p> </a>";
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
        dom: '<"html5buttons"B>lfrtip',
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

// search reports
function search_support_reports()
{
    district_id      = $("#district_name").val();
    upazilla_id      = $("#upazilla_name").val();
    status           = $("#status").val();
    client_type      = $("#client_type").val();
    record_id        = $("#client_name").val();
    from_date        = $("#from_date").val();
    to_date          = $("#to_date").val();

    
    $("#support_report_table").dataTable().fnSettings().ajax.data.district_id      = district_id;
    $("#support_report_table").dataTable().fnSettings().ajax.data.upazilla_id      = upazilla_id;
    $("#support_report_table").dataTable().fnSettings().ajax.data.client_type      = client_type;
    $("#support_report_table").dataTable().fnSettings().ajax.data.status           = status;
    $("#support_report_table").dataTable().fnSettings().ajax.data.record_id        = record_id;
    $("#support_report_table").dataTable().fnSettings().ajax.data.from_date        = from_date;
    $("#support_report_table").dataTable().fnSettings().ajax.data.to_date          = to_date;

    suppor_table.ajax.reload();
  }

 // assign support ticket
  function assign_ticket($id)
  {
  	var id = $id;
  	$('#assignleModal').modal('show');
  	$('#ticket_id').val(id);
    $('#user_id').val('');
    $("#user_id_error").html('');
  }

  function saveAssign()
  {
  	var ticket_id = $('#ticket_id').val();
  	var user_id = $('#user_id').val();
  	let target = $("#target").val();

    var error_status = false;

    if (user_id == '') {
        $("#user_id_error").html('Empty Assign Name');
        error_status = true;
    }else{
        $("#user_id_error").html('');
        
    }

    if (error_status == false) {

	 if(ticket_id!='' && user_id!=''){
        $.ajax ({
            type: 'POST',
            url: target+"support/ticket_assign_save",
            dataType: "JSON",
            data: { ticket_id:ticket_id, user_id: user_id },
            success : function(response) {

                $('#assignleModal').modal('hide');
                
                 swal({
                         title: "Response",
                         text: response.message,
                         type: response.status,
                         showCancelButton: false,
                         showConfirmButton: true,
                         closeOnConfirm: true,
                         allowEscapeKey: false
                     })
                     suppor_table.ajax.reload();
            }
        });
    }
}
  }

// close ticket
function ticket_close($id)
  {
    var id = $id;
    $('#closeleModal').modal('show');
    $('#ticket_id').val(id);
    $('#success_message').val('');
    $("#success_message_error").html('');
  }

function closeTicket()
  {
    var ticket_id       = $('#ticket_id').val();
    var success_message = $('#success_message').val();
    let target          = $("#target").val();

    var error_status = false;

    if (success_message == '') {
        $("#success_message_error").html('Empty Success Message');
        error_status = true;
    }else{
        $("#success_message_error").html('');
        
    }

    if (error_status == false) { 

     if(ticket_id!='' && success_message!=''){
        $.ajax ({
            type: 'POST',
            url: target+"support/ticket_close_save",
            dataType: "JSON",
            data: { ticket_id:ticket_id, success_message: success_message },
            success : function(response) {

                $('#closeleModal').modal('hide');
                
                 swal({
                         title: "Response",
                         text: response.message,
                         type: response.status,
                         showCancelButton: false,
                         showConfirmButton: true,
                         closeOnConfirm: true,
                         allowEscapeKey: false
                     })
                     suppor_table.ajax.reload();
            }
        });
    }
    }
  }

// ticket re-open 
function re_open_ticket($id)
    {
        let target    = $("#target").val();
        var ticket_id = $id;
         swal({
         title: "Confirmation",
         text: "Are you want to Re-open ?",
         type: "warning",
         showConfirmButton: true,
         confirmButtonClass: "btn-success",
         confirmButtonText: "Yes",
         closeOnConfirm: false,
         showCancelButton: true,
         cancelButtonText: "No"
     }, function(isConfirm) {
         if (isConfirm) {
             $.ajax({
                 type: "POST",
                 url: target + "support/re_open_ticket",
                 dataType: "JSON",
                 data: { ticket_id: ticket_id },
                 success: function(response) {
                     swal({
                         title: "Response",
                         text: response.message,
                         type: response.status,
                         showCancelButton: false,
                         showConfirmButton: true,
                         closeOnConfirm: true,
                         allowEscapeKey: false
                     })
                     suppor_table.ajax.reload();
                 }

             });
         }
     })
    }


function remove_assign($id)
{
        let target    = $("#target").val();
        var ticket_id = $id;
         swal({
         title: "Confirmation",
         text: "Are you want to Remove ?",
         type: "warning",
         showConfirmButton: true,
         confirmButtonClass: "btn-success",
         confirmButtonText: "Yes",
         closeOnConfirm: false,
         showCancelButton: true,
         cancelButtonText: "No"
     }, function(isConfirm) {
         if (isConfirm) {
             $.ajax({
                 type: "POST",
                 url: target + "support/remove_assign",
                 dataType: "JSON",
                 data: { ticket_id: ticket_id },
                 success: function(response) {
                     

                     swal({
                         title: "Response",
                         text: response.message,
                         type: response.status,
                         showCancelButton: false,
                         showConfirmButton: true,
                         closeOnConfirm: true,
                         allowEscapeKey: false
                     })
                     suppor_table.ajax.reload()
                     $('#assignleModal').modal('show');
                 }

             });
         }
     })
}
// date picker
  $('#ticket_datepicker .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true,
    format: "yyyy-mm-dd"
});
