@extends('layouts.master')
@section('title',"New Ticket")

@section('main-content')
	<div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    
                        <h3> New Ticket </h3>
                         <!-- flash  start --->
                         <div class="" id="flash_msg" style="width: 400px; float: right;">
                            @if(session()->has('success'))
                                <div class="row">
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss = "alert" aria-hidden="true"><a class="close">
                                        <i class="fa fa-times"></i>
                                    </a></button>
                                        
                                         {{session()->get('success')}}   
                                    </div>
                                </div>
                            @endif
                            @if(session()->has('error_msg'))
                                <div class="row">
                                    <div class="alert alert-danger">
                                        <button type="button" class="close" data-dismiss = "alert" aria-hidden="true"><a class="close">
                                        <i class="fa fa-times"></i>
                                    </a></button>
                                        
                                         {{session()->get('success')}}   
                                    </div>
                                </div>
                            @endif
                        </div>
                </div>
                <form action="{{ route('support.support_save') }}" method="post" enctype="multipart/form-data">
                    @csrf
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-8">
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

                        <div class="col-md-4">
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-8">
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
                        <div class="col-md-4"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Title</label>
                                <div class="col-md-10">
                                   <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="Title">

                                   @error('title')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                        </div> 
                    </div><br>
                     <div class="row" >
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" >Description</label>
                                <div class="col-md-10">
                                  <textarea name="description" cols="74" rows="8"></textarea>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                        </div> 
                    </div><br>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Attch File</label>
                                <div class="col-md-6">
                                  <input type="file" name="attach_file" id="attach_file" class="@error('attach_file') is-invalid @enderror" style="width: 90px">

                                    @error('attach_file')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <img id="previewImg" src="#" alt="your image" style="width: 400px; height: 200px; " />
                        </div> 
                    </div><br><br><br>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" ></label>
                                <div class="col-md-2">
                                 {{-- <button type="submit" id="" class="btn btn-primary">Send</button> --}}
                                 <input type="submit" name="submit" class="btn btn-primary" value="Send">
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                        </div> 
                    </div>

                    <input type="hidden" name="target" id="target" value="{{asset('')}}" />
                </div>
                </form>
            </div>
        </div>
    </div>



@endsection

@section('js')

<script src="{{ asset('/')}}assets/js/support.js"></script>
<script src="{{ asset('/')}}assets/js/report.js"></script>
@endsection