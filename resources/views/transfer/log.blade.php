@extends('layout.default')

@section('content')
	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-8">
				<div class="panel panel-primary" data-collapsed="0">
					<div class="panel-heading">
						<div class="panel-title">
							Your credit transfer history
						</div>
					</div>
					<div class="panel-body">
						@if(\Auth::user()->role == 'owner')
							<div>
								<form class="form-inline" method="get" action="/dashboard/transfer/log">
									<div class="form-group">
										<label for="user">Select user</label>
										<select id="user" class="form-control" name="user">
											@foreach($users as $key => $value)
												<optgroup label="{{$key}}">
													@foreach($value as $u)
														<option value="{{$u->id}}" @if(isset($_GET['user']) && $_GET['user'] == $u->id) selected="selected" @endif>{{$u->username}}</option>
													@endforeach
												</optgroup>
											@endforeach
										</select>
									</div> 
									<div class="form-group">
										<select id="activity" name="activity" class="form-control">
											<option value="all" @if(isset($_GET['activity']) && $_GET['activity'] == 'all') selected="selected" @endif>All Activity</option>
											<option value="transfer" @if(isset($_GET['activity']) && $_GET['activity'] == 'transfer') selected="selected" @endif>Credit Transfers</option>
											<option value="owner" @if(isset($_GET['activity']) && $_GET['activity'] == 'owner') selected="selected" @endif>Owner Purchases</option>
											<option value="customer" @if(isset($_GET['activity']) && $_GET['activity'] == 'customer') selected="selected" @endif>Customer Sales</option>
										</select>
									</div>
									<div class="form-group">
										<label for="month">Month</label>
										<select id="month" name="month" class="form-control">
											<option value="1" @if((isset($_GET['month']) && $_GET['month'] == 1) || (!isset($_GET['month']) && date('m') == 1)) selected="selected" @endif>January</option>
											<option value="2" @if((isset($_GET['month']) && $_GET['month'] == 2) || (!isset($_GET['month']) && date('m') == 2)) selected="selected" @endif>February</option>
											<option value="3" @if((isset($_GET['month']) && $_GET['month'] == 3) || (!isset($_GET['month']) && date('m') == 3)) selected="selected" @endif>March</option>
											<option value="4" @if((isset($_GET['month']) && $_GET['month'] == 4) || (!isset($_GET['month']) && date('m') == 4)) selected="selected" @endif>April</option>
											<option value="5" @if((isset($_GET['month']) && $_GET['month'] == 5) || (!isset($_GET['month']) && date('m') == 5)) selected="selected" @endif>May</option>
											<option value="6" @if((isset($_GET['month']) && $_GET['month'] == 6) || (!isset($_GET['month']) && date('m') == 6)) selected="selected" @endif>June</option>
											<option value="7" @if((isset($_GET['month']) && $_GET['month'] == 7) || (!isset($_GET['month']) && date('m') == 7)) selected="selected" @endif>July</option>
											<option value="8" @if((isset($_GET['month']) && $_GET['month'] == 8) || (!isset($_GET['month']) && date('m') == 8)) selected="selected" @endif>August</option>
											<option value="9" @if((isset($_GET['month']) && $_GET['month'] == 9) || (!isset($_GET['month']) && date('m') == 9)) selected="selected" @endif>September</option>
											<option value="10" @if((isset($_GET['month']) && $_GET['month'] == 10) || (!isset($_GET['month']) && date('m') == 10)) selected="selected" @endif>October</option>
											<option value="11" @if((isset($_GET['month']) && $_GET['month'] == 11) || (!isset($_GET['month']) && date('m') == 11)) selected="selected" @endif>November</option>
											<option value="12" @if((isset($_GET['month']) && $_GET['month'] == 12) || (!isset($_GET['month']) && date('m') == 12)) selected="selected" @endif>December</option>
										</select>
									</div>
									<div class="form-group">
										<label for="year">Year</label>
										<input type="text" name="year" id="year" @if(isset($_GET['year'])) value="{{$_GET['year']}}" @else value="{{date('Y')}}" @endif class="form-control">
									</div>
									<button id="filter" type="submit" class="btn btn-success">Filter</button>
									<a href="#" onclick="exportLog()" class="btn btn-success">Export</a>
								</form>
							</div>
						@else
							<div>
								<form class="form-inline" method="get" action="/dashboard/transfer/log">
									<div class="form-group">
										<select id="activity" name="activity" class="form-control">
											<option value="all" @if(isset($_GET['activity']) && $_GET['activity'] == 'all') selected="selected" @endif>All Activity</option>
											<option value="transfer" @if(isset($_GET['activity']) && $_GET['activity'] == 'transfer') selected="selected" @endif>Credit Transfers</option>
											<option value="owner" @if(isset($_GET['activity']) && $_GET['activity'] == 'owner') selected="selected" @endif>Owner Purchases</option>
											<option value="customer" @if(isset($_GET['activity']) && $_GET['activity'] == 'customer') selected="selected" @endif>Customer Sales</option>
										</select>
									</div>
									<div class="form-group">
										<label for="month">Month</label>
										<select id="month" name="month" class="form-control">
											<option value="1" @if((isset($_GET['month']) && $_GET['month'] == 1) || (!isset($_GET['month']) && date('m') == 1)) selected="selected" @endif>January</option>
											<option value="2" @if((isset($_GET['month']) && $_GET['month'] == 2) || (!isset($_GET['month']) && date('m') == 2)) selected="selected" @endif>February</option>
											<option value="3" @if((isset($_GET['month']) && $_GET['month'] == 3) || (!isset($_GET['month']) && date('m') == 3)) selected="selected" @endif>March</option>
											<option value="4" @if((isset($_GET['month']) && $_GET['month'] == 4) || (!isset($_GET['month']) && date('m') == 4)) selected="selected" @endif>April</option>
											<option value="5" @if((isset($_GET['month']) && $_GET['month'] == 5) || (!isset($_GET['month']) && date('m') == 5)) selected="selected" @endif>May</option>
											<option value="6" @if((isset($_GET['month']) && $_GET['month'] == 6) || (!isset($_GET['month']) && date('m') == 6)) selected="selected" @endif>June</option>
											<option value="7" @if((isset($_GET['month']) && $_GET['month'] == 7) || (!isset($_GET['month']) && date('m') == 7)) selected="selected" @endif>July</option>
											<option value="8" @if((isset($_GET['month']) && $_GET['month'] == 8) || (!isset($_GET['month']) && date('m') == 8)) selected="selected" @endif>August</option>
											<option value="9" @if((isset($_GET['month']) && $_GET['month'] == 9) || (!isset($_GET['month']) && date('m') == 9)) selected="selected" @endif>September</option>
											<option value="10" @if((isset($_GET['month']) && $_GET['month'] == 10) || (!isset($_GET['month']) && date('m') == 10)) selected="selected" @endif>October</option>
											<option value="11" @if((isset($_GET['month']) && $_GET['month'] == 11) || (!isset($_GET['month']) && date('m') == 11)) selected="selected" @endif>November</option>
											<option value="12" @if((isset($_GET['month']) && $_GET['month'] == 12) || (!isset($_GET['month']) && date('m') == 12)) selected="selected" @endif>December</option>
										</select>
									</div>
									<div class="form-group">
										<label for="year">Year</label>
										<input type="text" name="year" id="year" @if(isset($_GET['year'])) value="{{$_GET['year']}}" @else value="{{date('Y')}}" @endif class="form-control">
									</div>
									<button id="filter" type="submit" class="btn btn-success">Filter</button>
								</form>
							</div>
						@endif
						<table class="table table-striped" style="color: black;">
							<?php $total = 0; ?>
							<?php $date = 0; ?>
							@foreach($logs as $log)
								@if(!is_null($log->touser) && !is_null($log->fromuser))
								<tr>
									<td>
										<?php 
											$month_array = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEPT', 'OCT', 'NOV', 'DIC'];
											$month = date('m', strtotime($log->created_at)) - 1;
											$day = date('d', strtotime($log->created_at));
										?>
										<span style="color: black;">
											{{$month_array[$month]}}<br>
											<span style="font-size: 20px;">{{$day}}</span>
										</span>
									</td>
									<td>
										<b>
											@if($log->from_user_id == $user->id)
												@if(isset($log->touser->firstname))
                                                    {{$log->touser->firstname}}
                                                @endif
                                                @if(isset($log->touser->lastname))
                                                    {{$log->touser->lastname}}
                                                @endif
                                                @if(isset($log->touser->username))
                                                    ({{$log->touser->username}})
                                                @endif
											@else
												{{$log->fromuser->firstname}} {{$log->fromuser->lastname}} ({{$log->fromuser->username}}) 
											@endif
										</b>							
										@if($log->touser->role == 'customer')
											<?php
												$touser_month = date('m', strtotime($log->touser->trial_ends_at.' -'.$date.' month')) - 1;;
												$date += $log->amount;
												$touser_date = date('d-Y', strtotime($log->touser->trial_ends_at));
											?>
											<br><span style="color: grey;">Renovacion de Subcripcion: Vence {{$month_array[$touser_month]}}-{{$touser_date}}</span>
										@elseif($log->fromuser->role == 'owner')
											<br><span style="color: grey;">Pago Recibido</span>
										@elseif($log->from_user_id == $user->id)
											<br><span style="color: grey;">Tranferencia de Creditos (Venta)</span>
										@elseif($log->to_user_id == $user->id)
											<br><span style="color: grey;">Transferencia de Creditos (Compra)</span>
										@endif	
									</td>
									<td>
										<span style="font-size: 20px;">
											@if($log->touser->role == 'customer')
												<span style="color: red;">-</span>
											@elseif($log->fromuser->role == 'owner')
												<span style="color: green;">+</span>
											@elseif($log->from_user_id == $user->id)
												<span style="color: red;"><i class="entypo-switch"></i></span>
											@elseif($log->to_user_id == $user->id)
												<span style="color: green;"><i class="entypo-switch"></i></span>
											@endif
											<b>{{$log->amount}}</b>
										</span>
										<br> CREDITOS										
									</td>
									<td>
										<b>
											<span style="font-size: 20px;">
												@if($user->role == 'owner')
													{{$log->to_credit_amount}}
												@else
													@if($log->from_user_id == $user->id)
														{{$log->from_credit_amount}}
													@else
														{{$log->to_credit_amount}}
													@endif
												@endif												
											</span>
											<br>CREDITOS
										</b>
									</td>
								</tr>
								@endif
							@endforeach					
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		function exportLog(){
			var year = $('#year').val();
			var month = $('#month').val();
			var activity = $('#activity').val();
			var user = $('#user').val();
			window.location.href = '/dashboard/transfer/export?user='+user+'&activity='+activity+'&month='+month+'&year='+year
		}
	</script>
@endsection