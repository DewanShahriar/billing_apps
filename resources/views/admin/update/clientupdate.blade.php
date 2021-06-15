@extends('layouts.master')
@section('title',"Admin")

@section('main-content')
	<div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>Client Informations Update</h3>
                    <input type="hidden" name="target" id="target" value="{{asset('')}}" />
                </div>
                <div class="ibox-content">
                    <form action="{{ url('client/client_edit')}}" method="POST" class="form-horizontal">
                        {{csrf_field()}}
                    	<div class="form-group">
                    		<label class="col-sm-2 control-label">District</label>
                            <div class="col-md-4">
                                <select class="form-control m-b" name="district_id" id="district_name">
                                    <option value="none">Select</option>
                                    @foreach($districts as $row)
                                    <option value="{{$row->id}}" <?php if ($client->district_id==$row->id): $dis=$row->id;?>
                                        selected
                                        
                                    <?php endif ?>>{{$row->en_name}}</option>
                                    
                                    @endforeach
                                </select>
                            </div>
                            
                        	<label class="col-sm-2 control-label">Upazilla</label>
                            <div class="col-md-4">
                                <select class="form-control m-b" name="upazilla_id" id="upazilla_id">
                                    <option value="none">Select</option>
                                    @foreach($upazillas as $row)
                                    @if($row->parent_id==$dis)
                                    <option value="{{$row->id}}"
                                        <?php if ($client->upazilla_id==$row->id): $upa=$row->id; ?>
                                        selected
                                        
                                    <?php endif ?>>{{$row->en_name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
                        <?php  if($client->union_id !=''  || $client->union_id !=NULL){ ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Union</label>
                            <div class="col-md-4">
                                <select class="form-control m-b" name="union_id" id="union_id">
                                    <option value="none">Select</option>
                                    @foreach($unions as $row)
                                    @if($row->parent_id==$upa)
                                    <option value="{{$row->id}}"
                                        <?php if ($client->union_id==$row->id): ?>
                                        selected
                                        
                                    <?php endif ?>>{{$row->en_name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <?php }?>
                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Name</label>
                            <div class="col-md-4"><input type="text" class="form-control" value="{{$client->name}}" name="name" placeholder="Name" required></div>
                        
                        	<label class="col-sm-2 control-label">Code</label>
                            <div class="col-md-4"><input type="text" class="form-control" value="{{$client->code}}" name="code" placeholder="Code" required></div>
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Mobile no</label>
                            <div class="col-md-4"><input type="text" class="form-control" value="{{$client->mobile_no}}" name="mobile_no" placeholder="Mobile no" required></div>
                        
                        	<label class="col-sm-2 control-label">Email</label>
                            <div class="col-md-4"><input type="text" class="form-control" value="{{$client->email}}" name="email" placeholder="Email" required></div>
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Weblink</label>
                            <div class="col-md-4"><input type="text" class="form-control" value="{{$client->weblink}}" name="weblink" placeholder="Weblink" required></div>
                        
                        	<label class="col-sm-2 control-label">Address</label>
                            <div class="col-md-4"><input type="text" class="form-control" value="{{$client->address}}" name="address" placeholder="Address"></div>
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Client type</label>
                            <div class="col-md-4">
                            	<select class="form-control m-b" name="client_type" required>
	                            	<option >Select</option>
	                                <option value="1" <?php if($client->client_type==1):?>selected <?php endif?>>Union</option>
	                                <option value="2" <?php if($client->client_type==2):?>selected <?php endif?>>Pourashava</option>
                                    <option value="3" <?php if($client->client_type==3):?>selected <?php endif?>>School </option>
                                
                            	</select>
                            </div>

                            <label class="col-sm-2 control-label">Payment type</label>
                            <div class="col-md-4">
                            	<select class="form-control m-b" name="payment_type">
	                            	
	                                <option value="1" <?php if($fee->payment_type==1):?>selected <?php endif?>>Monthly</option>
	                                <option value="2" <?php if($fee->payment_type==2):?>selected <?php endif?>>Quarterly</option>
	                                <option value="3" <?php if($fee->payment_type==3):?>selected <?php endif?>>Semi-annually</option>
	                                <option value="4" <?php if($fee->payment_type==4):?>selected <?php endif?>>Yearly</option>
                            	</select>
                            </div>
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Fee</label>
                            <div class="col-md-4"><input type="text" class="form-control" value="{{$fee->fee_amount}}" name="fee_amount" id="fee_amount" placeholder="0" required></div>

                        	<label class="col-sm-2 control-label">Quantity</label>
                            <div class="col-md-4"><input type="text" class="form-control"  value="{{$fee->quantity}}" name="quantity" placeholder="0"></div>
                        </div>
                        @if($client->client_type ==2)
                        <div class="form-group">
                            <div id="vatArea">
                            <label class="col-sm-2 control-label"> Vat </label>
                            <div class="col-md-4"><input type="text" class="form-control" name="vat" id="vat" placeholder="0%" ></div>
                            </div>
                        </div>
                        @endif
                        <div class="form-group" id="data_1">
                        	<div class="col-sm-6">
                            	<label class="col-sm-4 control-label">Service date</label>
                            	<div class="input-group date col-md-8">
                                	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="service_date" class="form-control" value="{{$client->service_date}}">
                            	</div>
                    		</div>
                        	<div class="col-sm-6">
                            	<label class="col-sm-4 control-label">Domain Expire</label>
                            	<div class="input-group date col-md-8">
                                	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="domain_expire" value="{{$client->domain_expire}}">
                            	</div>
                            </div>
                    	</div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-md-4">
                                <select class="form-control m-b" name="status" required>
                                    
                                    <option value="1" <?php if($client->status==1):?>selected <?php endif?>>Activated</option>
                                    <option value="2" <?php if($client->status==2):?>selected <?php endif?>>Deactivated</option>
                                    <option value="3" <?php if($client->status==3):?>selected <?php endif?>>Other</option>
                                </select>
                            </div>

                            
                        </div>
                    	<div class="form-group">
                    		
                    	</div>
                    
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2 pull-right">
                                <input type="hidden" name="client_id" value="{{$client->id}}">
                                <input type="hidden" name="record_id" value="{{$client->record_id}}">
                                <a href="{{route('client.client_show')}}" class="btn btn-danger" type="submit">Cancel</a>
                                <button class="btn btn-info " type="submit">Update</button>
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
			
        	$('#data_1 .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            format: "yyyy-mm-dd"
        	});

            // jQuery(document).ready(function($) {
            //     $("#district_name").on('change', function() {
            //         var id = $(this).val();
            //         console.log(id);
            //         if(id!=''){
            //             $.ajax ({
            //                 type: 'GET',
            //                 url: '{{asset('')}}client/show_upazilla',
            //                 data: { id: id },
            //                 success : function(res) {
            //                     $('#upazilla_name').html(res.data);
            //                     console.log(data);
            //                 }
            //             });
            //         }
            //     });
            // });
            
            


        </script>
        <script src="{{ asset('/')}}assets/js/client.js"></script>
@endsection