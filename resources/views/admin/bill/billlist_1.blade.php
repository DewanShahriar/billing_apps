@extends('layouts.master')
@section('title',"Admin")

@section('main-content')
	<div class="row">
        <div class="col-lg-12">
            <!-- <div class="" id="flash_msg">
                    @if(session()->has('notif'))
                        <div class="row">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss = "alert" aria-hidden="true"><a class="close">
                                <i class="fa fa-times"></i>
                            </a></button>
                                <strong>Notification</strong> 
                                 {{session()->get('notif')}}   
                            </div>
                        </div>
                    @endif
                    @if(session()->has('updates'))
                        <div class="row">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss = "alert" aria-hidden="true"><a class="close">
                                <i class="fa fa-times"></i>
                            </a></button>
                                <strong>Notification</strong> 
                                 {{session()->get('updates')}}

                            </div>    
                        </div>
                    @endif   
                </div> -->
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    
                        <h3>Bill list</h3>
                        <a href="{{route('billprepare.create_bill')}}" class="btn btn-primary" style="float: right;position: relative; margin-top: -35px" ><i class="fa fa-plus"> Create bill</i></a>
                    
                </div>

                <div class="ibox-content">
                    <input type="hidden" name="target" id="target" value="{{asset('')}}" />

                    <div class="table-responsive">
                        <table class="footable table table-stripped toggle-arrow-tiny" id="">
                            <thead>
                                <tr>
                                    <th data-toggle="true">SL</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Invoice id</th>
                                    <th>Amount</th>
                                    <th>Generated date</th>
                                    <th>Status</th>
                                    <th data-hide="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Patrick Smith</td>
                                    <td>0800 051213</td>
                                    <td>shah@gmail.com</td>
                                    <td>2020030001</td>
                                    <td>4000</span></td>
                                    <td>Jul 14, 2013</td>
                                    <td>unpaid</td>
                                    <td><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                                </tr>
                            </tbody>
                            
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
        get_bill_list();
    }); 


    $(document).ready(function() {

        $('.footable').footable();
        $('.footable2').footable();

    });

    
    // var flash_msg = $('#flash_msg');
    // setTimeout(function(){
    //     flash_msg.addClass('hidden');
    // },5000)  
</script>

<script src="{{ asset('/')}}assets/js/billing.js"></script>
@endsection