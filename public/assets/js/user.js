$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

// user delete call function
function delete_user(user_id)
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
     }, function(isConfirm) {
         if (isConfirm) {
             $.ajax({
                 type: "POST",
                 url: target + "user-delete",
                 dataType: "JSON",
                 data: { user_id: user_id },
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
                     user_table.ajax.reload();
                 }

             });
         }
     })
    }

/*======= User registration =====*/

$(document).ready(function(){

             $("#user_registration").validate({
                 rules: {
                     password: {
                         required: true,
                         minlength: 8
                     },
                     password_confirmation: {
                         equalTo:'#password',
                     },
                     name: {
                         required: true,
                         
                     },
                     role_id: {
                         required: true,
                         number: true
                     },
                     mobile: {
                         required: true,
                         minlength: 11
                     },
                     user_name: {
                         required: true,
                         minlength: 4
                     }
                 }
             });
        });
/*======= User Update =====*/
$(document).ready(function(){

             $("#user_update").validate({
                 rules: {
                     name: {
                         required: true,
                         
                     },
                     role_id: {
                         required: true,
                         number: true
                     },
                     mobile: {
                         required: true,
                         minlength: 11
                     },
                     user_name: {
                         required: true,
                         minlength: 4
                     },
                     password: {
                         minlength: 8
                     },
                     password_confirmation: {
                         equalTo:'#password',
                     },
                 }
             });
        });

/*=========== get username ========*/
    $('#user_name').change(function () {
        let loc = $('meta[name=path]').attr("content");
        var user_name = $(this).val();
       $.ajax({
                type:'POST',
                url:loc+'/get_username',
                data: {user_name: user_name},
                success: function (data) {
                    if(data==1){
                                document.getElementById('error1').innerHTML="Not Available";
                                document.getElementById('user_name').value="";
                                document.getElementById('error2').innerHTML="";
                            }
                            else {
                                //return true;
                                document.getElementById('error2').innerHTML=" Available";
                                document.getElementById('error1').innerHTML="";
                            }
                }
            });
    });

 //===datepicker===//
// $('#from_date, #to_date').datepicker({
//         language: 'en',
//         autoClose: true,
//         dateFormat: 'yyyy-mm-dd',
// });   

var user_table;
/*===User  registration list===*/
 function get_user_list() {

        let target = $("#target").val();

         user_table =    $('#user_list_table').DataTable({
                scrollCollapse: true,
                autoWidth: false,
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    dataType: "JSON",
                    type: "post",
                    url: target + "user-info",
                    data: {
                       // _token : user_csrf
                },

            },
            columns:[
                {
                    title: "SL",
                    data: null,
                    render: function(){
                        return user_table.page.info().start + user_table.column(0).nodes().length;
                    }
                },
                {
                    title: "Name",
                    data: "name"
                },
                {
                    title: "Mobile",
                    data: "mobile"
                },
                {
                    title: "Email",
                    data: "email"
                },
                {
                    title: "Username",
                    data: "user_name"
                },
               {
                title: "Type",
                data: null,
                render: function (data) {
                    if(data.type==1){
                        return '<span class="label label-primary"> &nbsp;Union </span>';
                    }
                    else if(data.type==2){
                        return '<span class="label label-warning">Pouroshova</span>';
                    }
                    else if(data.type==3){
                        return '<span class="label label-info"> School </span>';
                    }else{
                        return " ";
                    }  
                }
            },
                {
                    title: "Action",
                    data: null,
                    render: function(data){
                        return "<a  href='user-edit/" + data.user_id + "'><p class='btn btn-sm btn-warning'><i class='fa fa-edit'> Edit</i></p></a> <a  href='javascript:void(0)'><p class='btn btn-sm btn-danger' onclick='delete_user("+data.user_id+")' > <i class='fa fa-trash'> Delete</i></p></a>";
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
                    { extend: 'copy'},
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

  /*=============== Change Password ==============*/
  $(document).ready(function(){

             $("#change_password").validate({
                 rules: {
                     password: {
                         required: true,
                         minlength: 8
                     },
                     password_confirmation: {
                         equalTo:'#password',
                     }
                 }
             });
  });

  // get upazila name
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
        }else {
            $('#upazilla_name').html('<option value="">Select</option>');
        }
    });
});
