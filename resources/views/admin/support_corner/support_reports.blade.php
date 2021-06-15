@extends('layouts.master')
@section('title',"Reports")

@section('main-content')
	<div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    
                        <h3>Reports</h3>
                    
                </div>

                <div class="ibox-content">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">District</label>
                                <div class="col-md-4">
                                    <select class="form-control m-b" name="district_name" id="district_name" >
                                        <option value="" selected>Select</option>
                                        @foreach($districts as $row)
                                        <option value="{{$row->id}}">{{$row->en_name}}</option>
                                        @endforeach
                                    </select>
                                    <span style="color: red" id="district_name_error"></span>
                                </div>
                            
                                <label class="col-sm-2 control-label">Upazilla</label>
                                <div class="col-md-4">
                                    <select class="form-control m-b" name="upazilla_name" id="upazilla_name">
                                        <option selected value="">Select</option>  
                                    </select>
                                    
                                </div> 
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Client type</label>
                                <div class="col-md-4">
                                    <select class="form-control m-b" name="client_type" id="client_type">
                                        <option selected value="">Select</option>
                                        <option value="1">Union</option>
                                        <option value="2">Pourashava</option>
                                        <option value="3">School</option>
                                    
                                    </select>
                                    
                                </div>

                                <label class="col-sm-2 control-label">Client</label>
                                <div class="col-md-4">
                                     <select class="form-control m-b" name="client_name_id" id="client_name">
                                        <option selected value="">Select</option>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                 <label class="col-sm-3 control-label">Status</label>
                                <div class="col-md-9" style="padding-left: 40px; ">
                                    <select class="form-control m-b" name="status" id="status">
                                        <option selected value="">Select</option>
                                        <option value="1">Complete</option>
                                        <option value="0">Pending</option>
                                    
                                    </select>
                                
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-3">
                           <div class="form-group" id="ticket_datepicker">
                                   <label class="col-md-3 control-label" style="margin-right: 20px"> From Date </label>
                                    <div class="col-md-5 input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input name="from_date" id="from_date"  style="width: 100px" type="text" class="form-control" value="" >
                                    </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                           <div class="form-group" id="ticket_datepicker">
                                   <label class="col-md-3 control-label" style="margin-right: 20px"> To Date </label>
                                    <div class="col-md-5 input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input name="to_date" id="to_date"  style="width: 100px" type="text" class="form-control" value="" >
                                    </div>
                                </div>
                        </div>
                         <div class="col-lg-2">
                            <div class="form-group" style="margin-left: -50px">
                                
                                <div class="col-md-4">
                                     <button onclick="search_support_reports()" id="" class="btn btn-primary">Search</button>
                                
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="target" id="target" value="{{asset('')}}" />

                    <div class="table-responsive">
                        <table class="table table-striped stripe hover multiple-select-row data-table-export nowrap table-bordered table-hover dataTables-example" id="support_report_table">
                            
                                
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Assign ticket Modal -->
<div class="modal fade" id="assignleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 400px; margin-left: 100px">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Assign Ticket </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select name="user_id" id="user_id" class="form-control">
            <option value=""> Select </option>
            @foreach($users_data as $item)
            <option value="{{ $item->id}}"> {{ $item->name}}</option>
            
            @endforeach
        </select>
        <span id="user_id_error" style="color: red"></span>

        <input type="hidden" name="ticket_id" id="ticket_id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="saveAssign()" class="btn btn-primary">Assign</button>
      </div>
    </div>
  </div>
</div>

<!-- close  ticket Modal -->
<div class="modal fade" id="closeleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 400px; margin-left: 100px">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Close Ticket</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <textarea  name="success_message" id="success_message" cols="45" rows="3" required></textarea>
         <span id="success_message_error" style="color: red"></span>

        <input type="hidden" name="ticket_id" id="ticket_id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" onclick="closeTicket()" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>


@endsection

@section('js')

<script type="text/javascript">
    var user_csrf = '@php echo csrf_token() @endphp';
    $(document).ready(function(){
        get_support_list();
    }); 
</script>

<script src="{{ asset('/')}}assets/js/support.js"></script>
<script src="{{ asset('/')}}assets/js/report.js"></script>
@endsection