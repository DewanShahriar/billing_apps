@extends('layouts.master')
@section('title',"Admin")

@section('main-content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Bill Payment</h3>
            </div>
            <div class="ibox-content">
        <form action="javascript:void(0)" method="post" class="form-horizontal">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"> District </label>
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
                                    <!-- @foreach($upazillas as $row)
                                    <option value="{{$row->id}}">{{$row->en_name}}</option>
                                    
                                    @endforeach -->
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
                                <select class="form-control m-b" name="client_name_id" id="client_name" onchange="getPaymentInfo()" >
                                    <option selected value="">Select</option>
                                    <!-- @foreach($allclients as $row)
                                    <option value="{{$row->record_id}}">{{$row->name}}</option>
                                    @endforeach -->
                                </select>
                                <span style="color: red" id="client_name_error"></span>
                            </div>
                            <input type="hidden" name="client_primary_id" id="client_primary_id">
                        </div>

                        
                        <div class="row">
                            <div class="col-sm-4">
                                <div style="text-align: center; color: blue">
                                <h3  id="record_name"> </h3>
                                <span style="color: purple" id="record_address"> </span>
                                </div>
                                
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label"> Total <span id="total_advance_text"></span></label>
                                    <div style="color: red; font-weight: bold; font-size: 16px" class="col-md-6" id="total_due">
                                        <h3 style="color: red; font-weight: bold; font-size: 16px"> &nbsp;&nbsp; 0.00</h3>
                                    </div>
                                     <input type="hidden" name="total_due_amount" id="total_due_amount">
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-6 control-label"> Payment </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="total" id="total" value="0.00">
                                    </div>
                                     <span style="color: red" id="total_error"></span>
                                    
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Rest Of Due</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control rest_of_due" name="rest_of_due" id='rest_of_due' value="0" 
                                        placeholder="0" disabled="">
                                       
                                    </div>
                                     
                                </div>


                                 <div class="form-group" id="last_payment">
                                   <label class="col-sm-6 control-label"> Payment Date </label>
                                    <div class="col-md-5 input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input name="payment_date" id="payment_date" type="text" class="form-control" value="<?php echo date("yy-m-d");?>" >
                                    </div>
                                     <span style="color: red" id="payment_date_error"></span>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-6 control-label"> Payment Method</label>
                                    <div class="col-md-6">
                                         <select class="form-control m-b" name="payment_method" id="payment_method">
                                        <option selected value="">Select</option>
                                        <option id="cash_optoin" value="{{ $cash_account->id}}"> {{$cash_account->name}} ({{$cash_account->acc_no}}) </option>

                                        <option value="{{ $bkash_account->id}}"> {{$bkash_account->name}} ({{$bkash_account->acc_no}}) </option>

                                        <option value="{{ $rocket_account->id}}"> {{$rocket_account->name}} ({{$rocket_account->acc_no}}) </option>

                                        <option value="{{ $bank_account->id}}"> {{$bank_account->name}} ({{$bank_account->acc_no}}) </option>
                                        
                                    
                                       </select>
                                       <input  class="form-control" type="text" name="payment_method_name" id="payment_method_name" placeholder="Account No">
                                     <span style="color: red" id="payment_method_error"></span>
                                </div>
                               
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
                            <option id="current_year" value="2020">2020</option>
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
                            <input class="month_checked" name="month_id[]" type="checkbox" value="{{ $data->id }}" id="month_id{{$data->id}}" onclick="others_calculation(service_count.value)">
                              <span class="checkmark "></span><span style="color:red;float:right" class="paid" id="span_id{{$data->id}}"></span>
                        </label>

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
                                  <input class="others_service" type="checkbox" name="others_service[]" id="others_service{{ $item->id }}" value=" {{ $item->id }}" >
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
    <div class="row" style="margin-bottom: 100px">
         <div class="col-sm-3"></div>
        <div class="col-sm-9">
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2 pull-left">
                    {{-- <input type="hidden" name="total_discount" id="total_discount"> --}}
                    
                    <button class="btn btn-danger" type="reset" onclick="resetAll()"> Cancel </button>
                    <button onclick="bill_payment()"  type="submit"  id="" class="btn btn-primary">Payment</button>
                </div>
             </div>
        </div>
    </div>
    
        </form>   
        </div>
        </div>
    </div>
</div>

@endsection

@section('js')
        
 <script src="{{ asset('/')}}assets/js/billpayment.js"></script>
 <script src="{{ asset('/')}}assets/js/billgenerate.js"></script>
 <link href="{{ asset('/')}}assets/css/bill.css" rel="stylesheet">
        
@endsection