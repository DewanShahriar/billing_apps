@extends('layouts.master')
@section('title',"user list")

@section('main-content')
	<div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3>User list</h3>
                        <input type="hidden" name="target" id="target" value="{{asset('')}}" />
                         <a href="{{ url('user-registration')}}" class="btn btn-primary" style="float: right;position: relative; margin-top: -35px" href="">Add User</a>

                         <!-- message -->
                         @if(session()->has('success'))
                        <div class="row">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss = "alert" aria-hidden="true"><a class="close">
                                <i class="fa fa-times"></i>
                            </a></button>
                                <strong>User</strong> 
                                 {{session()->get('success')}}   
                            </div>
                        </div>
                          @endif
                           @if(session()->has('error_msg'))
                            <div class="row">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss = "alert" aria-hidden="true"><a class="close">
                                <i class="fa fa-times"></i>
                            </a></button>
                                <strong>User </strong> 
                                 {{session()->get('error_msg')}}

                            </div>    
                            </div>
                           @endif   

                    </div>
                    <div class="ibox-content">
                    <div class="table-responsive">
                    
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="user_list_table">
                        
                        </table>

                        </div>

                    </div>
                </div>
            </div>
            </div>

@endsection
@section('js')

<script type="text/javascript">
    var user_csrf = '@php echo csrf_token() @endphp';

    $(document).ready(function(){
        get_user_list();
    });
    
</script>

<script src="{{ asset('/')}}assets/js/user.js"></script>

@endsection
