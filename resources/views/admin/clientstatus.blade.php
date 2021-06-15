@extends('layouts.master')
@section('title',"Admin")

@section('main-content')
	<div class="row">
        <div class="col-lg-12">
            <div class="" id="flash_msg">
                    @if(session()->has('notif'))
                        <div class="row">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss = "alert" aria-hidden="true"><a class="close">
                                <i class="fa fa-times"></i>
                            </a></button>
                                <strong>Notification</strong> 
                                 {{session()->get('notif')}}   
                            </div>
                        </div>
                    @endif
                    @if(session()->has('updates'))
                        <div class="row">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss = "alert" aria-hidden="true"><a class="close">
                                <i class="fa fa-times"></i>
                            </a></button>
                                <strong>Notification</strong> 
                                 {{session()->get('updates')}}

                            </div>    
                        </div>
                    @endif   
                </div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    
                        <h3>Client list</h3>
                        <a href="{{route('client.create')}}" class="btn btn-primary" style="float: right;position: relative; margin-top: -35px" ><i class="fa fa-plus"> Create new</i></a>
                    
                </div>

                <div class="ibox-content">
                <form method="get" action="{{ url('client/client_data_pdf')}}" target="_blank"> 
                    <!-- {{csrf_field()}} -->
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

                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-md-4">
                                    <select class="form-control m-b" name="status" id="status">
                                        <option selected value="">Select</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    
                                    </select>
                                    
                                </div>

                                

                            </div>
                        </div>
                    </div>
                    <input type="submit" value="print"  class="btn btn-primary pull-right" >
                    <!-- <a href="{{ url('client/client_data_pdf')}}" onclick="this.form.submit()" target="_blank" class='btn btn-sm btn-primary pull-right'>Print</a> -->

                </form>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                    

                                    <button onclick="all_search()" id="" class="btn btn-primary pull-right">Search</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ibox-content">
                    <input type="hidden" name="target" id="target" value="{{asset('')}}" />

                    <div class="table-responsive">
                        <table class="table table-striped stripe hover multiple-select-row data-table-export nowrap table-bordered table-hover dataTables-example" id="client_list_table" >
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
<script type="text/javascript">
    var user_csrf = '@php echo csrf_token() @endphp';
    $(document).ready(function(){
        get_client_list();
    }); 

    var flash_msg = $('#flash_msg');
    setTimeout(function(){
        flash_msg.addClass('hidden');
    },5000)  
</script>

<script src="{{ asset('/')}}assets/js/client.js"></script>

@endsection