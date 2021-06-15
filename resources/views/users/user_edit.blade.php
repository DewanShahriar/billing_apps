@extends('layouts.master')

@section('title', 'User-Update')
	
@section('main-content')
 <div class="row">
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Edit User <small></small></h5>
                </div>
<div class="ibox-content">
	<div class="row">
		<div class="col-sm-10">
			<form role="form" id="user_update" method="POST" action="{{ route('user-update') }}" name="edit_form">
	                        @csrf
			<div class="form-group">
				<label>{{ __('Name') }} </label> 
				<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user_info->name }}" required>

				@error('name')
	                <span class="invalid-feedback" role="alert">
	                    <strong>{{ $message }}</strong>
	                </span>
	            @enderror
			</div>
			<div class="form-group">
				<label>Mobile</label> 
				
				<input id="mobile" type="number" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ $user_info->mobile }}">

	            @error('mobile')
	                <span class="invalid-feedback" role="alert">
	                    <strong>{{ $message }}</strong>
	                </span>
	            @enderror
			</div>		
			<div class="form-group">
				<label>{{ __('Email') }}</label> 
				
				<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user_info->email }}" required >

	            @error('email')
	                <span class="invalid-feedback" role="alert">
	                    <strong>{{ $message }}</strong>
	                </span>
	            @enderror
			</div>
			<div class="form-group">
				<label> District </label> 
				<select class="form-control" name="district_name" id="district_name">
                    <option value="">Select</option>

                    <?php if($user_info->district_id !='') {?>
                    @foreach($districts as $row)
                    <option value="{{$row->id}}" <?php if ($user_info->district_id==$row->id): $dis=$row->id;?>
                        selected
                        
                    <?php endif ?>>{{$row->en_name}}</option>
                    
                    @endforeach

                    <?php }else{ ?>
                      @foreach($districts as $row)
                    <option value="{{$row->id}}">{{$row->en_name}}</option>
                    
                    @endforeach
                    <?php }?>
                </select>

			</div>
			<div class="form-group">
				<label> Upazila </label> 

				<select class="form-control" name="upazilla_name" id="upazilla_name">
                    <option value="">Select</option>
                    <?php if($user_info->upazila_id !='') {?>
                    @foreach($upazillas as $row)
                    @if($row->parent_id==$dis)
                    <option value="{{$row->id}}"
                        <?php if ($user_info->upazila_id==$row->id): $upa=$row->id; ?>
                        selected
                        
                    <?php endif ?>>{{$row->en_name}}</option>
                    @endif
                    @endforeach
                    <?php }else if($user_info->district_id !=''){?>
                    @foreach($upazillas as $row)
                    @if($row->parent_id==$dis)
                    <option value="{{$row->id}}"
                        <?php if ($user_info->upazila_id==$row->id): $upa=$row->id; ?>
                        selected
                        
                    <?php endif ?>>{{$row->en_name}}</option>
                    @endif
                    @endforeach
                   <?php }else{?>

                    @foreach($upazillas as $row)
              
                    <option value="{{$row->id}}">{{$row->en_name}}</option>
    
                    @endforeach
                   <?php }?>
                </select>
			</div>
			<div class="form-group">
				<label> Type </label> 
				<select name="type" id="type" class="form-control">
					<option value=""> Select </option>
					<option value="1" <?php if ($user_info->type==1):?>
                        selected
                        
                    <?php endif ?>> Union </option>
					<option value="2" <?php if ($user_info->type==2):?>
                        selected
                        
                    <?php endif ?>> Pouroshova </option>
					<option value="3" <?php if ($user_info->type==3):?>
                        selected
                        
                    <?php endif ?>> School </option>
				</select>

			</div>
			<div class="form-group">
				<label> Role </label> 
				<select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
					<option value=""> Select </option>
					<option value="1"> Admin </option>
					<option value="2"> Account </option>
					<option value="3"> Support </option>
				</select>

	            @error('role_id')
	                <span class="invalid-feedback" role="alert">
	                    <strong>{{ $message }}</strong>
	                </span>
	            @enderror
			</div>
			<div class="form-group">
				<label> Username </label> 
				<input id="user_name" type="text" class="form-control @error('name_name') is-invalid @enderror" name="user_name" value="{{ $user_info->user_name }}" required>

				@error('user_name')
	                <span class="invalid-feedback" role="alert">
	                    <strong>{{ $message }}</strong>
	                </span>
	            @enderror

	            <span id="error1" style="color:red;font-size:14px;"></span>
				<span id="error2" style="color:green;font-size:14px;"></span>
			</div>

			<div class="form-group">
				<label>{{ __('New Password') }}</label> 
					
				<input id="password" type="password" class="form-control" name="password"  autocomplete="new-password" placeholder="Password">
				</div>
			<div class="form-group">
					<label> {{ __('Confirm Password') }} </label> 
					
					 <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password" placeholder="Password">
			</div>
			<div>
				<input type="hidden" name="id" value="{{ $user_info->id}}">
	
				<input type="hidden" name="target" id="target" value="{{asset('')}}" />
				<button class="btn btn-sm btn-info pull-left m-t-n-xs" type="submit">
					<strong>{{ __('Update') }} &nbsp;</strong>
				</button>
				<a  href=" {{ url('user-list')}}" style="margin-left: 7px" class="btn btn-sm btn-danger pull-left m-t-n-xs"> Cancel</a>
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

<script type="text/javascript">
	   document.forms['edit_form'].elements['role_id'].value= '{{$user_info->role_id}}';
</script>

 
@endsection
