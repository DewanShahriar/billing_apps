@extends('layouts.master')
@section('title',"Admin")

@section('main-content')
	<div class="row">
        <div class="col-lg-12">
            <div class="" id="flash_msg">
                    @if(session()->has('error_msg'))
                        <div class="row">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss = "alert" aria-hidden="true"><a class="close">
                                <i class="fa fa-times"></i>
                            </a></button>
                                <strong>Notification</strong> 
                                 {{session()->get('error_msg')}}   
                            </div>
                        </div>
                    @endif 
                </div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="form-group">
                        <h3>Client Add </h3>
                        
                    </div>
                </div>
                <div class="ibox-content">

                    <form action="{{ url('client/client_insert')}}" method="post" class="form-horizontal">
                        {{csrf_field()}}
                    	<div class="form-group">
                    		<label class="col-sm-2 control-label">District</label>
                            <div class="col-md-4">
                            	<select class="form-control m-b" name="district_id" id="district_name" required>
	                            	<option value="none" selected>Select</option>
	                            	@foreach($districts as $row)
	                                <option value="{{$row->id}}">{{$row->en_name}}</option>
	                                
	                                @endforeach
                            	</select>
                            </div>
                        
                        	<label class="col-sm-2 control-label">Upazilla</label>
                            <div class="col-md-4">
                            	<select class="form-control m-b" name="upazilla_id" id="upazilla_name" required>
	                            	<option selected="none">Select</option>
	                               
                            	</select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Client type</label>
                            <div class="col-md-4">
                                <select class="form-control m-b"  name="client_type" id="client_type" required>
                                    <option selected="none">Select</option>
                                    <option value="1">Union</option>
                                    <option value="2">Pourashava</option>
                                    <option value="3">School </option>
                                
                                </select>
                            </div>

                            <label class="col-sm-2 control-label">Code</label>
                            <div class="col-md-4"><input type="text" class="form-control" name="code" placeholder="Code" required></div>
                        </div>
                        <div class="form-group">
                            <div id="unionList" style="display: none;">
                                <label class="col-sm-2 control-label">Union</label>
                                <div class="col-md-4">
                                     <select class="form-control m-b" name="union_id" id="union_name">
                                        <option selected value="">Select</option>
                                        
                                    </select>
                                </div>
                            </div>

                            <div id="client_name_wrapper">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-md-4"><input type="text" class="form-control" id="name" name="name" placeholder="Name" required></div>
                            </div>
                            
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Mobile no</label>
                            <div class="col-md-4"><input type="text" class="form-control" name="mobile_no" placeholder="Mobile no" required></div>
                        
                        	<label class="col-sm-2 control-label">Email</label>
                            <div class="col-md-4"><input type="text" class="form-control" name="email" placeholder="Email" required></div>
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Weblink</label>
                            <div class="col-md-4"><input type="text" class="form-control" name="weblink" placeholder="https://www.example.com" required></div>
                        
                        	<label class="col-sm-2 control-label">Address</label>
                            <div class="col-md-4"><input type="text" class="form-control" name="address" placeholder="Address" required></div>
                        </div>

                        <div class="form-group">

                            <label class="col-sm-2 control-label">Payment type</label>
                            <div class="col-md-4">
                            	<select class="form-control m-b" name="payment_type">
	                            	<option selected="none">Select</option>
	                                <option value="1">Monthly</option>
	                                <option value="2">Quarterly</option>
	                                <option value="3">Semi-annually</option>
	                                <option value="4">Yearly</option>
                            	</select>
                            </div>
                            <label class="col-sm-2 control-label">Fee</label>
                            <div class="col-md-4"><input type="text" class="form-control" name="fee_amount" id="fee_amount" placeholder="0.00" required></div>

                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Quantity</label>
                            <div class="col-md-4"><input type="text" class="form-control" name="quantity" placeholder="0" required></div>
                            <div id="vatArea" style="display: none;">
                            <label class="col-sm-2 control-label"> Vat </label>
                            <div class="col-md-4"><input type="text" class="form-control" name="vat" id="vat" placeholder="0%" ></div>
                            </div>
                        </div>

                        <div class="form-group" id="data_1">
                        	<div class="col-sm-6">
                            	<label class="col-sm-4 control-label">Service date</label>
                            	<div class="input-group date col-md-8">
                                	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="service_date" value="<?php echo date("yy-m-d");?>">
                            	</div>
                    		</div>
                        	<div class="col-sm-6">
                            	<label class="col-sm-4 control-label">Domain Expire</label>
                            	<div class="input-group date col-md-8">
                                	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="domain_expire" value="<?php echo date("yy-m-d");?>">
                            	</div>
                            </div>

                    	</div>
                    	<div class="form-group">
                    		
                    	</div>
                    
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2 pull-right">
                            <input type="hidden" name="target" id="target" value="{{asset('')}}" />

                                <a href="{{route('client.client_show')}}" class="btn btn-danger" onclick="">Cancel</a>
                                <button class="btn btn-primary " type="submit">Add new</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

<script type="text/javascript">
	// datepicker
	$('#data_1 .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true,
    format: "yyyy-mm-dd"
	});
    
    //change upazilla on district change
    jQuery(document).ready(function($) {
        $("#district_name").on('change', function() {
            var id = $(this).val();
            console.log(id);
            if(id!=''){
                $.ajax ({
                    type: 'GET',
                    url: '{{asset('')}}client/show_upazilla',
                    data: { id: id },
                    success : function(res) {
                        $('#upazilla_name').html(res.data);
                        console.log(data);
                    }
                });
            }
        });
    });

    var flash_msg = $('#flash_msg');
    setTimeout(function(){
        flash_msg.addClass('hidden');
    },5000)

</script>
<script src="{{ asset('/')}}assets/js/client.js"></script>

@endsection