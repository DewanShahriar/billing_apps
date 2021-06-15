@extends('layouts.master')
@section('title',"Admin")

@section('main-content')
    <div class="row">
        <div class="col-lg-12">
            
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="form-group">
                        <h3>Quick sms </h3>
                        
                    </div>
                </div>
                <div class="ibox-content">

                    <form onsubmit="javascript:void(0);" class="form-horizontal">

                        <!-- <div class="form-group">
                            <label class="col-sm-2 control-label">Client</label>
                            <div class="col-md-4"><input type="text" placeholder="item..." class="typeahead_3 form-control" name="name" id="name" /></div>
                        
                            
                        </div> -->

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Client</label>
                            <div class="col-md-4">
                                <select class="form-control js-example-tags" multiple="multiple" name="client[]" id="client">
                                    
                                    @foreach($allclients as $row)
                                    <option value="{{$row->mobile_no.','.$row->record_id}}">{{$row->name.' ('.$row->mobile_no.')'}}</option>
                                    
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Message</label>
                            <div class="col-md-4">
                                <textarea class="form-control" name="message" id="message" required ></textarea>
                                <div>
    <div><span id="remaining">160</span>&nbsp;Character<span class="cplural">s</span> Remaining</div>
    <div>Total&nbsp;<span id="messages">1</span>&nbsp;Message<span class="mplural">s</span>&nbsp;<span id="total">0</span>&nbsp;Character<span class="tplural">s</span></div>
</div>
                            </div>
                        
                        </div>
                        <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2 pull-right">
                                    <input type="hidden" name="target" id="target" value="{{asset('')}}" />
                                    <button class="btn btn-primary pull-right" onclick="quicksms_send()" type="button" >Send</button>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                        

                    </form>
                    <!-- <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2 pull-right">
                                    <input type="hidden" name="target" id="target" value="{{asset('')}}" />
                                    <button class="btn btn-primary pull-right" onclick="quicksms_send()" >Send</button>
                                </div>
                                
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
<script src="{{ asset('/')}}assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>
<script type="text/javascript">
    var user_csrf = '@php echo csrf_token() @endphp';
    $(document).ready(function(){
        
    });

    $(".js-example-tags").select2({
      tags: true
    });
</script>

<script src="{{ asset('/')}}assets/js/quicksms.js"></script>
@endsection