@extends('layouts.master')
@section('title',"View Ticket")

@section('main-content')
	<div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">                   
                        <h3> {{ $get_ticket_data->title}} </h3>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">District</label>
                                <div class="col-md-4">
                                  {{ $get_ticket_data->district_name}}
                                </div>
                            
                                <label class="col-sm-2 control-label">Upazilla</label>
                                <div class="col-md-4">
                                {{ $get_ticket_data->upazilla_name}}
                                </div> 
                            </div>
                        </div>

                        <div class="col-md-4">
                             
                              <a style="float: right;" onclick='ticket_close({{ $get_ticket_data->id}})' ><p class='btn btn-sm btn-primary'> <i class='fa fa-times' aria-hidden='true'></i> Close</p> </a>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Client type</label>
                                <div class="col-md-4">
                                   {{ $get_ticket_data->name}}
                                </div>

                                <label class="col-sm-2 control-label">Client</label>
                                <div class="col-md-4">
                                   <?php

                                   if($get_ticket_data->client_type == 1){
                                    echo "Union";
                                   }elseif ($get_ticket_data->client_type == 2) {
                                    echo "Pourashava";
                                   }elseif ($get_ticket_data->client_type == 3){
                                    echo "School";
                                   }
                                   ?>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div><br>
                
                     <div class="row" >
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" >Description</label>
                                <div class="col-md-10">
                                 {{ $get_ticket_data->description}}
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                        </div> 
                    </div><br>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Attch File</label>
                                <div class="col-md-6">
                                  <img  src="{{ asset('')}}/assets/images/{{ $get_ticket_data->attach_file}}" alt="your image" style="width: 600px; height: 300px; " />
                                </div>
                            </div>
                        </div> 
                       
                    </div><br><br><br>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" ></label>
                                <div class="col-md-2">
                                
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                        </div> 
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- close  ticket Modal -->
<div class="modal fade" id="closeleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 400px; margin-left: 100px">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Close Ticket</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <textarea name="success_message" id="success_message" cols="45" rows="3"></textarea>
          <span id="success_message_error" style="color: red"></span>
        <input type="hidden" name="ticket_id" id="ticket_id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" onclick="closeTicket()" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')
<script src="{{ asset('/')}}assets/js/support.js"></script>
@endsection