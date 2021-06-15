@extends('layouts.master')
@section('title',"Admin")

@section('main-content')
	<div class="row">
        <div class="col-lg-12">
            
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    
                        <h3>Bill list</h3>
                        <a href="{{route('billprepare.create_bill')}}" class="btn btn-primary" style="float: right;position: relative; margin-top: -35px" ><i class="fa fa-plus"> Create bill</i></a>
                    
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
                        <div class="col-lg-6">
                            <div class="form-group">
                                <!-- <label class="col-sm-2 control-label">Com type</label>
                                <div class="col-md-4">
                                    <select class="form-control m-b" name="com_type" id="com_type">
                                        <option selected value="">Select</option>
                                        <option value="1">SMS</option>
                                        <option value="2">Email</option>
                                    </select>
                                </div> -->

                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-md-4">
                                    <select class="form-control m-b" name="status" id="status">
                                        <option selected value="">Select</option>
                                        <option value="0">Unpaid</option>
                                        <option value="1">Paid</option>
                                        
                                    </select>
                                     <span style="color: red" id="client_type_error"></span>
                                </div>

                                <div class="form-group">
                                <button onclick="all_search()" id="" class="btn btn-primary">Search</button>
                                
                                    
                               

                                
                            </div>

                                
                            </div>
                        </div>

                        <!-- <div class="col-lg-6">
                            <div class="form-group">
                                <button onclick="all_search()" id="" class="btn btn-primary">Search</button>
                                
                                    
                               

                                
                            </div>
                        </div> -->
                        
                    </div>
                
                

                
            </div>

                <div class="ibox-content">
                    <input type="hidden" name="target" id="target" value="{{asset('')}}" />

                    <div class="table-responsive">
                        <table class="table table-striped stripe hover multiple-select-row data-table-export nowrap table-bordered table-hover dataTables-example" id="bill_list_table">
                            
                                
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
        get_bill_list();
    }); 
 
</script>

<script src="{{ asset('/')}}assets/js/billing.js"></script>
@endsection