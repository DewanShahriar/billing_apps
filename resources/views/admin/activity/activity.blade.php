@extends('layouts.master')
@section('title',"Admin")

@section('main-content')
<div class="row">
    <div class="col-lg-12">
        
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                
                <h3 id="heading" style="font-weight: bold;color:#2980b9;">Activity log</h3> 
                
                <input type="hidden" name="target" id="target" value="{{asset('')}}" /> 
            </div>

            <div class="ibox-content">
                                
                <div class="row">
                    
                    <div class="col-md-9 ">
                        <div class="chat-discussion" id="chat-discussion">
                         
                        </div>
                        
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="search" name="search" id="search" onkeyup="user_search()">
                        <div class="chat-users">
                            

                            <div class="users-list" id="users-list" >
                                
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
                <!-- <div class="row">
                    <div class="col-lg-12">
                        <div class="chat-message-form">
                            
                            <div class="form-group">
                                
                                <textarea class="form-control message-input" name="message" placeholder="Enter message text" id="message"></textarea>
                            </div>
                            
                        </div>
                    </div>
                </div>  -->   
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    var user_csrf = '@php echo csrf_token() @endphp';
    $(document).ready(function(){
        all_user_show();
    }); 

      
</script>

<script src="{{ asset('/')}}assets/js/activity.js"></script>

@endsection