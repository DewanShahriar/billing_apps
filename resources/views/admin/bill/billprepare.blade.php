@extends('layouts.master')
@section('title',"Admin")

@section('main-content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Bill generate</h3>
            </div>
            <div class="ibox-content">
        <form action="javascript:void(0)" method="post" class="form-horizontal">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">District</label>
                            <div class="col-md-4">
                                <select class="form-control m-b" name="district_name" id="district_name">
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
                                 <span style="color: red" id="upazilla_name_error"></span>
                            </div>
                        </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Client type</label>
                        <div class="col-md-4">
                            <select class="form-control m-b" name="client_type" id="client_type">
                                <option selected value="">Select</option>
                                <option value="1">Union</option>
                                <option value="2">Pourashava</option>
                                <option value="3">School</option>
                            
                            </select>
                             <span style="color: red" id="client_type_error"></span>
                        </div>

                        <label class="col-sm-2 control-label">Client</label>
                            <div class="col-md-4">
                                <select class="form-control m-b" name="client_name_id" id="client_name" onchange="getClientFee()" >
                                    <option selected value="">Select</option>
                                    
                                </select>
                                <span style="color: red" id="client_name_error"></span>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-sm-4">
            
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Unit fee</label>
                                    <div class="col-md-6" id="fee_amount">
                                        <input type="text" class="form-control " name="fee" id="fee" value="" disabled="" placeholder="0">
                                        <span style="color: red" id="fee_error"></span>
                                    </div>
                                    
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Due</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="due" id="due" value="0.00" disabled="">
                                    </div>
                                     <span style="color: red" id="due_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Amount</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control amount" name="amount" id='amount' value="0" 
                                        placeholder="0" disabled="">
                                        <span style="color: red" id="amount_error"></span>
                                    </div>
                                     
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Discount</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control discount" name="discount" id="discount" placeholder="0.00" onkeyup="others_calculation(service_count.value)">
                                        <span id="dis" style="color: red;"></span>
                                        <span style="color: red" id="discount_error"></span>
                                    </div>
                                     
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Payable amount</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control total" name="total" value="0" id="total" placeholder="0" disabled="">
                                        <span style="color: red" id="total_error"></span>
                                    </div>
                                     
                                </div>

                                 <div class="form-group" id="last_payment">
                                   <label class="col-sm-6 control-label"> Last Payment Date </label>
                                    <div class="col-md-5 input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input name="last_payment_date" id="last_payment_date" type="text" class="form-control" value="<?php echo date("yy-m-d");?>" >
                                    </div>
                                     <span style="color: red" id="last_payment_date_error"></span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Remark</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" name="remark" id="remark"></textarea>
                                     
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <div class="col-lg-1"> 
            <div class="row"> 
                <div class="col-sm-12">
                    
                </div>
            </div>
         </div>     
        <div class="col-lg-5" style="border-left: 2px solid #efecf1; padding-right: 20px">
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-3 control-label"> Year </label>
                    <div class="col-md-4">
                        <input type="hidden" name="target" id="target" value="{{ asset('')}}">
                        <select class="form-control m-b year_selected" name="year" id="year">
                            <option selected value="">Select</option>
                            <option value="2020">2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option> 
                        </select>
                        <span class="" id="warning_msg" style="color: red;"></span>

                        <span style="color: red" id="year_error"></span>
                    </div>
                </div>

                <div class="form-group" >
                    <label class="col-sm-3 control-label">Month</label>

                    <div class="col-sm-9" id="all_month">
                       @foreach($account_month as $data)
                        <label class="container" id="month_label_{{ $data->id }}"> {{ $data->name }}
                            <input class="month_checked" name="month_id[]" type="checkbox" value="{{ $data->id }}" id="month_id{{$data->acc_no}}" onclick="others_calculation(service_count.value)">
                              <span class="checkmark "></span><span style="color:red;float:right" class="paid" id="span_id{{$data->acc_no}}"></span>
                        </label>

                        <input type="hidden" id="month_name_list" name="month_name_list[]" value="{{ $data->name }}">
                        @endforeach 
                        <input type="hidden" name="" class="month_count" id="">
                        
                    </div>
                    

                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"> Other service</label>

                    <div class="col-sm-9">
                        @foreach($others_account as $k => $item)
                       <div class="row">
                            <div class="col-md-7">
                             <label class="container"> {{ $item->name }}
                                  <input class="others_service" type="checkbox" name="others_service[]" id="others_service{{ $k+1 }}" value=" {{ $item->id }}" onclick="enableDisable('{{$k+1}}')" >
                                  <span class="checkmark "></span>
                             </label>
                            </div>

                            <div class="col-md-4" >
                                 
                                <input  style="margin-bottom: 5px; display: none" class="form-control get_others_value" type="text" name='others_service_value[]' id="others_service_value{{ $k+1}}" onkeyup="others_calculation(service_count.value)" >
                           
                            </div>
                        </div>
                        @endforeach

                        <input type="hidden" name="service_count" id="service_count" value="{{count($others_account)}}" >
                        
                    </div>
                </div>  
            </div>
        </div>
</div>
        <br>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2 pull-right">
                            <button class="btn btn-danger" type="reset" onclick="resetAll()"> Cancel </button>
                            <button onclick="bill_save()"  type="submit"  id="" class="btn btn-primary">Generate</button>
                        </div>
                     </div>
        </form>   
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal" id="save_button_modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
               <!--  <i class="fa fa-clock-o modal-icon"></i> -->
                <h4 class="modal-title">Elahabad Union Parishod</h4>
               <!--  <small>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</small> -->
            </div>
            <div class="modal-body">
                
                <h4><strong>Month Name : </strong> January </h4>
                <h4><strong>Unit Fee : </strong> 600 </h4>
                <h4><strong>Amount : </strong> 600 </h4>
                <h4><strong>Discount : </strong> 600 </h4>
                <h4><strong>Payable Amount : </strong> 600 </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
        
    <script src="{{ asset('/')}}assets/js/inspinia.js"></script>
    <script src="{{ asset('/')}}assets/js/plugins/pace/pace.min.js"></script>
    <script src="{{ asset('/')}}assets/js/plugins/iCheck/icheck.min.js"></script>
    <script src="{{ asset('/')}}assets/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="{{ asset('/')}}assets/js/billgenerate.js"></script>
    <link href="{{ asset('/')}}assets/css/bill.css" rel="stylesheet">
    <script src="{{ asset('/')}}assets/js/plugins/iCheck/icheck.min.js"></script>
        
@endsection