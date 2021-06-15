@extends('layouts.master')

@section('title', 'Change-password')
	
@section('main-content')
 <div class="row">
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Change Password  <small></small></h5>
                </div>
<div class="ibox-content">
	<div class="row">
		<div class="col-sm-10">
			<form role="form" id="change_password" method="POST" action="{{ route('password-save') }}">
	                        @csrf
				
				<div class="form-group">
					<label>{{ __('New Password') }}</label> 
					
					<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">

		            @error('password')
		                <span class="invalid-feedback" role="alert">
		                    <strong>{{ $message }}</strong>
		                </span>
		            @enderror
				</div>
				<div class="form-group">
					<label> {{ __('Confirm Password') }} </label> 
					
					 <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Password">
				</div>
				<div>
					<input type="hidden" name="id" value="{{ Auth::user()->id }}">
					<button class="btn btn-sm btn-primary pull-left m-t-n-xs" type="submit">
						<strong>{{ __('Save') }} &nbsp;</strong>
					</button>
					
					<a  href=" {{ url('user-list')}}" style="margin-left: 7px" class="btn btn-sm btn-danger pull-left m-t-n-xs"> Back</a>
				</div>
			</form>
		</div>
	</div>
</div>
</div>
</div>
</div>

@endsection

@section('js')
<!-- Jquery Validate -->
<script src="{{ asset('/')}}assets/js/plugins/validate/jquery.validate.min.js">
</script>
<!-- Custom Validate js -->
<script src="{{ asset('/')}}assets/js/user.js"></script>
 
@endsection
