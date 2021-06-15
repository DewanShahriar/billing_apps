<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav metismenu" id="side-menu">
			<li class="nav-header" style="height: 45px">
				<div class="profile-element"> 
					<span>
						<img style="height: 50px; width: 150px; position: relative; top: -20px; left: 10px" src="{{ asset('/')}}assets/images/67.png">
					</span>
				</div>

				<div class="logo-element">
					IIT
				</div>
			</li>
			
			<li class="active">
				<a href="{{ url('/home')}}">
					<i class="fa fa-th-large"></i> 
					<span class="nav-label">Dashboard</span>
				</a>
			</li>

			<li class="">
				<a href="#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">Client</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li>
					<a href="{{route('client.create')}}"> <span class="nav-label">Add</span></a>
					</li>
					<li>
					<a href="{{route('client.client_show')}}"> <span class="nav-label">List</span></a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">Bill</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li>
					<a href="{{route('billprepare.create_bill')}}"><span class="nav-label">Generate</span></a>
					
					<a href="{{route('billpayment.payment')}}"><span class="nav-label">Payment </span></a>
					</li>
					<li><a href="{{route('billprepare.bill_show')}}">List</a></li>
					<li><a href="{{route('billpayment.money_receipt')}}">Money Receipt List</a></li>
				</ul>
			</li>
			<li>
				<a href="#">
					<i class="fa fa-align-justify"></i> 
					<span class="nav-label"> Report</span><span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
					<li><a href="{{ route('report.daily_collection') }}">Daily Collection </a></li>
					<li><a href="{{ route('report.monthly_collection') }}">Monthly Collection </a></li>
					<li><a href="{{ route('report.accounts_wise_collection') }}"> Accounts Report </a></li>
				</ul>
			</li>

			
			<li>
				<a href="#"><i class="fa fa-diamond"></i> <span class="nav-label">Communication</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li><a href="{{route('communication.report_show')}}">Report</a></li>
					<li><a href="{{route('communication.quicksms_show')}}">Quick sms</a></li>
				</ul>
			</li>

			<li>
				<a href="{{route('conversation.show')}}">
					<i class="fa fa-align-justify"></i> 
					<span class="nav-label"> Conversation</span>
				</a>
				
			</li>
			
			<li>
				<a href="#">
					<i class="fa fa-user-circle-o"></i> 
					<span class="nav-label"> User</span><span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
					<li><a href="{{ url('user-registration')}}">Add</a></li>
					<li><a href="{{ url('user-list')}}">List</a></li>
					<li><a href="{{route('activity.show')}}">Activity log</a></li>
				</ul>
			</li>

			<li>
				<a href="#"><i class="fa fa-cogs"></i> <span class="nav-label">Settings</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li><a href="#">Fee Setting</a></li>
					<li><a href="#">Admin Setting</a></li>
				</ul>
			</li>

			 <li>
				<a href="{{ url('change-password')}}">
					<i class="fa fa-eye"></i> 
					<span class="nav-label"> Change Password</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class="fa fa-weixin"></i> 
					<span class="nav-label"> Support Ticket</span><span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
					<li><a href="{{ route('support.new_ticket') }}">New Ticket </a></li>
					<li><a href="{{ route('support.support_reports') }}"> Reports </a></li>
					
				</ul>
			</li>
		</ul>
	</div>
</nav>

