@extends('layouts.master')

@section('title', 'User-Registration')
	
@section('main-content')
 <div class="row">
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Add User <small></small></h5>
                </div>
<div class="ibox-content">
	<div class="row">
		<div class="col-sm-10">
			<form role="form" id="user_registration" method="POST" action="{{ route('user-save') }}">
	                        @csrf
			<div class="form-group">
				<label>{{ __('Name') }} </label> 
				<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Name" autofocus>

				@error('name')
	                <span class="invalid-feedback" role="alert">
	                    <strong>{{ $message }}</strong>
	                </span>
	            @enderror
			</div>
			<div class="form-group">
				<label>Mobile</label> 
				
				<input id="mobile" type="number" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="mobile" placeholder="Mobile">

	            @error('mobile')
	                <span class="invalid-feedback" role="alert">
	                    <strong>{{ $message }}</strong>
	                </span>
	            @enderror
			</div>		
			<div class="form-group">
				<label> {{ __('Email') }} </label> 
				
				<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email">

	            @error('email')
	                <span class="invalid-feedback" role="alert">
	                    <strong>{{ $message }}</strong>
	                </span>
	            @enderror
			</div>
			<div class="form-group">
				<label> District </label> 
				<select class="form-control" name="district_name" id="district_name" >
                     <option value="">Select</option>
                    @foreach($districts as $row)
                    <option value="{{$row->id}}" {{ $row->id == old('district_name') ? 'selected' : '' }}>{{$row->en_name}}</option>
                    @endforeach
                </select>
			</div>
			<div class="form-group">
				<label> Upazila </label> 

				<select class="form-control" name="upazilla_name" id="upazilla_name">
                        <option selected value="">Select</option>  
                </select>

			</div>
			<div class="form-group">
				<label> Type </label> 
				<select name="type" id="type" class="form-control">
					<option value=""> Select </option>
					<option value="1" {{ 1 == old('type') ? 'selected' : '' }}> Union </option>
					<option value="2" {{ 2 == old('type') ? 'selected' : '' }}> Pouroshova </option>
					<option value="3" {{ 3 == old('type') ? 'selected' : '' }}> School </option>
				</select>

			</div>
			<div class="form-group">
				<label> Role </label> 
				<select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
					<option value=""> Select </option>
					<option value="1" {{ 1 == old('role_id') ? 'selected' : '' }}> Admin </option>
					<option value="2" {{ 2 == old('role_id') ? 'selected' : '' }}> Account </option>
					<option value="3" {{ 3 == old('role_id') ? 'selected' : '' }}> Support </option>
				</select>

	            @error('role_id')
	                <span class="invalid-feedback" role="alert">
	                    <strong>{{ $message }}</strong>
	                </span>
	            @enderror
			</div>
			<div class="form-group">
				<label> Username </label> 
				<input id="user_name" type="text" class="form-control @error('name_name') is-invalid @enderror" name="user_name" value="{{ old('user_name') }}" required  placeholder="Username">

				@error('user_name')
	                <span class="invalid-feedback" role="alert">
	                    <strong>{{ $message }}</strong>
	                </span>
	            @enderror

	            <span id="error1" style="color:red;font-size:14px;"></span>
				<span id="error2" style="color:green;font-size:14px;"></span>
			</div>
			<div class="form-group">
				<label>{{ __('Password') }}</label> 
				
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
			<input type="hidden" name="target" id="target" value="{{asset('')}}" />
			<button class="btn btn-sm btn-primary pull-left m-t-n-xs" type="submit">
				<strong>{{ __('Save') }} &nbsp;</strong>
			</button>
			<button style="margin-left: 7px" class="btn btn-sm btn-danger pull-left m-t-n-xs" type="reset">
				<strong> Cancel </strong>
			</button>
			</div>
		</form>
		</div>
	</div>
</div>
</div>
</div>
</div>
<!-- close  ticket Modal -->
<div class="modal fade" id="changePasswordleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 400px; margin-left: 100px">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Close Ticket</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <textarea  name="success_message" id="success_message" cols="45" rows="3" required></textarea>
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
<!-- Jquery Validate -->
<script src="{{ asset('/')}}assets/js/plugins/validate/jquery.validate.min.js">
</script>
<!-- Custom Validate js -->
<script src="{{ asset('/')}}assets/js/user.js"></script>
 
@endsection
