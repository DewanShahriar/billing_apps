@extends('layouts.master')
@section('title',"Admin")

@section('main-content')
	<div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>Client Profile</h3>
                    <a href="{{route('client.client_show')}}" class="btn btn-info" style="float: right;position: relative; margin-top: -35px" ><i class="fa fa-hand-o-left"> Back</i></a>
                </div>
                
                <div class="ibox-content">
                    <form action="#" method="get" class="form-horizontal">
                    	<div class="form-group">
                    		<label class="col-sm-2 control-label">District</label>
                            <div class="col-md-4">
                                @foreach($districts as $row)
                                    @if ($client->district_id==$row->id)
                                        {{$row->en_name}}
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Upazilla</label>
                            <div class="col-md-4">
                                @foreach($upazillas as $row)
                                    @if($client->upazilla_id==$row->id)
                                        {{$row->en_name}}
                                    @endif
                                @endforeach   
                            </div>
                        </div>
                        
                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Name</label>
                            <div class="col-md-4">{{$client->name}}
                            </div>
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Code</label>
                            <div class="col-md-4">{{$client->code}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Record id</label>
                            <div class="col-md-4">{{$client->record_id}}
                            </div>    
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Mobile no</label>
                            <div class="col-md-4">{{$client->mobile_no}}
                            </div>
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Email</label>
                            <div class="col-md-4">{{$client->email}}
                            </div>
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Weblink</label>
                            <div class="col-md-4">{{$client->weblink}}
                            </div>
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Address</label>
                            <div class="col-md-4">{{$client->address}}
                            </div>
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Client type</label>
                            <div class="col-md-4">
                                @if($client->client_type==1)
                                Union
                                @elseif($client->client_type==2)
                                Pourashava
                                @elseif($client->client_type==3)
                                School
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Payment type</label>
                            <div class="col-md-4">
                                @if($fee->payment_type==1)
                                Monthly
                                @elseif($fee->payment_type==2)
                                Quarterly
                                @elseif($fee->payment_type==3)
                                Semi-annually
                                @elseif($fee->payment_type==4)
                                Yearly
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Fee</label>
                            <div class="col-md-4">{{$fee->fee_amount}}
                            </div>
                        </div>

                        <div class="form-group">
                        	<label class="col-sm-2 control-label">Quantity</label>
                            <div class="col-md-4">{{$fee->quantity}}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Service date</label>
                            <div class="col-md-4">{{$client->service_date}}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Domain expire</label>
                            <div class="col-md-4">{{$client->domain_expire}}
                            </div>
                    	</div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-md-4">
                                @if($client->status==1)
                                Activated
                                @elseif($client->status==2)
                                Deactivated
                                @elseif($client->status==3)
                                Other
                                @endif
                            </div>
                        </div>

                    	<div class="form-group">
                    		
                    	</div>
                    
                        <!-- <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2 pull-right">
                                <button class="btn btn-danger" type="submit">Cancel</button>
                                <button class="btn btn-info " type="submit">Update</button>
                            </div>
                        </div> -->
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
            
            


        </script>
@endsection