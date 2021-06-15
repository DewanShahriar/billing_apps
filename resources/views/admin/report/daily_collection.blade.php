@extends('layouts.master')
@section('title',"Daily Collection")

@section('main-content')
	<div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    
                        <h3>Daily Collection Reoprt</h3>
                    
                </div>
                <form action="{{ route('report.daily_collection_report') }}" method="post" target="_blank">
                    @csrf
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

                                <label class="col-sm-2 control-label"> </label>
                                <div class="col-md-4">
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Client</label>
                                <div class="col-md-4">
                                     <select class="form-control m-b" name="client_name_id" id="client_name">
                                        <option selected value="">Select</option>
                                        
                                    </select>
                                </div>

                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-md-4">
                                     <button type="submit" id="" class="btn btn-primary">Search</button>
                                
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="target" id="target" value="{{asset('')}}" />

                    <div class="daily_collection_area" style="text-align: center;">

                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>



@endsection

@section('js')

<script src="{{ asset('/')}}assets/js/report.js"></script>
@endsection