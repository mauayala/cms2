@extends('layout.default')

@section('content')
	<style type="text/css">
		.roles > .label {
			background-repeat: no-repeat; 
			background-size: contain; 
			padding-left: 32px; 
			background-position: 15%;
		}
	</style>

    <div class="admin-section-title">
		<div class="row">
			<div class="col-md-8">
				<h3><i class="entypo-user"></i> Listing users for {{$user->username}}</h3><br>
			</div>
		</div>
	</div>
	<div class="clear"></div>

	<table class="table table-striped">
		<thead>
			<tr class="table-header">
				<th>Nombre de Usuario</th>
				<th>Fecha de Vencimiento</th>
				<th>Tipo de Usuario</th>
				<th>Direccion IP</th>
				<th>Esta viendo</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $user)
				<tr>
					<td><a href="/user/{{ $user['username'] }}" target="_blank">
						<?php if(strlen($user['username']) > 40){
								echo substr($user['username'], 0, 40) . '...';
							  } else {
							  	echo $user['username'];
							  }
						?>
						</a>
					</td>
					<td>
						@if($user['role'] == 'customer')
							<?php 
								$today = strtotime(date('Y-m-d H:i:s'));
								$ends = strtotime($user['trial_ends_at']);
								if(($ends - $today) < 0){
									$interval = -1;
								} else {
									$interval = round(($ends - $today)/(60*60*24));	
								}
							?>
							@if( $interval < 0 )
								<div class="label label-danger"><i class="fa fa-frown-o"></i> Expired </div>
							@elseif( $interval < 6 )
								<div class="label label-warning"><i class="fa fa-meh-o"></i> Expires in {{$interval}} days</div> 
							@elseif( $interval > 5 && $interval < 32 )
								<div class="label label-success"><i class="fa fa-frown-o"></i> Expires in {{$interval}} days</div>
							@elseif( $interval > 31 && $interval < 366 )
								<div class="label label-info"><i class="fa fa-ticket"></i> Expires in {{ $interval }} days</div>
							@elseif( $interval > 365 )
								<div class="label label-primary"><i class="fa fa-ticket"></i> Expires in {{$interval}} days</div>
							@endif
						@endif
					</td>
					<td class="roles">
						@if($user['role'] == 'distributor')
							<div class="label label-info" style="background-image: url(/distributor.png);padding-left: 35px;">
							Distributor</div>
						@elseif($user['role'] == 'seller')
							<div class="label" style="background-color: #f7931e;"><i class="fa fa-envelope"></i>
							Seller</div>
						@elseif($user['role'] == 'customer')
							<div class="label label-warning"><i class="fa fa-life-saver"></i>
							Customer</div>
						@elseif($user['role'] == 'admin')
							<div class="label label-success" style="background-image: url(/admin.png);padding-left: 35px;">
							Administrator</div>
						@elseif($user['role'] == 'staff')
							<div class="label label-danger" style="background-image: url(/staff.png);padding-left: 35px;">
							Staff</div>
						@endif						 
					</td>
					<td>{{ $user['ip'] }}</td>
					<td style="color: @if($rawusers->find($user['id']) != null && $rawusers->find($user['id'])->isOnline() == 'Offline') #ED1C24 @else green @endif;">@if($rawusers->find($user['id']) != null) {{$rawusers->find($user['id'])->isOnline()}} @endif</td>
					<td>						
						<a href="{{route('dashboard.users.edit', ['user' => $user['id']])}}" class="btn btn-xs btn-info"><span class="fa fa-edit"></span> Editar</a>						
						@if($user['role'] == 'owner')
							<a href="{{route('dashboard.users.destroy', ['user' => $user['id']])}}" class="btn btn-xs btn-danger delete"><span class="fa fa-trash"></span> Eliminar</a>
						@endif					
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	{{$users->links()}}


	@section('javascript')
		<script>
			$ = jQuery;
			$(document).ready(function(){
				$('.delete').click(function(e){
					e.preventDefault();
					if (confirm("Are you sure you want to delete this user?")) {
				       window.location = $(this).attr('href');
				    }
				    return false;
				});

				setInterval(updateUser, 10000);
			});

			function updateUser(){
				$.ajax({
					url: '/dashboard/users/updateUser',
					data: {_token: "{{csrf_token()}}"},
					method: 'POST',
					success: function(data){
						$('tbody').html(data);
					}
				})
			}
		</script>
	@endsection

@endsection

