@extends('layouts.master')
@section('title',"Accounts Collection")

@section('main-content')
	<div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    
                        <h3>Accounts Collection Reoprt</h3>
                    
                </div>
                <form action="{{ route('report.accounts_report') }}" method="post" target="_blank">
                    @csrf
                <div class="ibox-content">

                    <div class="form-group row">
                        <label for="accounts" class="col-sm-1 col-form-label">Accounts</label>
                        <div class="col-sm-2">
                            <select class="form-control m-b" name="account_id" id="account_id">
                                <option selected value="">Select</option>
                                <option  value="{{ $cash_account->id}}"> {{$cash_account->name}} ({{$cash_account->acc_no}}) </option>

                                <option value="{{ $bkash_account->id}}"> {{$bkash_account->name}} ({{$bkash_account->acc_no}}) </option>

                                <option value="{{ $rocket_account->id}}"> {{$rocket_account->name}} ({{$rocket_account->acc_no}}) </option>

                                <option value="{{ $bank_account->id}}"> {{$bank_account->name}} ({{$bank_account->acc_no}}) </option>
                                
                            
                               </select>
                        </div>
                         <label for="fromDate" class="col-sm-1 col-form-label">From Date</label>
                        <div class="col-sm-2">
                           <input  type="date" name="from_date" value="<?php echo date("Y-m-d")?>" class="form-control">
                        </div>
                         <label for="toDate" class="col-sm-1 col-form-label">To Date</label>
                        <div class="col-sm-2">
                           <input  type="date" name="to_date" value="<?php echo date("Y-m-d")?>" class="form-control">
                        </div>
                         <div class="col-sm-2">
                          <button type="submit" id="" class="btn btn-primary">Search</button>
                        </div>
                  </div>
                </div>
                    
                </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('js')

@endsection