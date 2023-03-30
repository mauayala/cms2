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
			<div class="col-sm-4 col-xs-6">
				<div class="tile-stats tile-red">
					<div class="icon"><i class="entypo-users"></i></div>
					<div class="num" data-start="0" data-end="{{ $total_customers }}" data-postfix="" data-duration="1500" data-delay="0">0</div>
					<h3>Subscriptores Activos</h3>
					<p>La cantidad de subscriptores activos en CloudTV.</p>
				</div>
			</div><!-- column -->
	
			<div class="col-sm-4 col-xs-6">
				<div class="tile-stats tile-green">
					<div class="icon"><i class="entypo-user-add"></i></div>
					<div class="num" data-start="0" data-end="{{ $new_customers }}" data-postfix="" data-duration="1500" data-delay="600">0</div>
					<h3>Subscriptores Nuevos</h3>
					<p>La cantidad de subscriptores nuevos en CloudTV este mes.</p>
				</div>
			</div><!-- column -->
	
			<div class="col-sm-4 col-xs-6">
				<div class="tile-stats tile-aqua">
					<div class="icon"><i class="entypo-video"></i></div>
					<div class="num" data-start="0" data-end="{{ $online_customers }}" data-postfix="" data-duration="1500" data-delay="1200">0</div>
					<h3>Online Customers</h3>
					<p>Online Customers</p>
				</div>
			</div><!-- column -->
	
		</div>
		
		<div class="row">
			<div class="col-md-8">

				<a target="_blank" href="https://youtu.be/7-Hfc2J_-ac" class="btn btn-danger" role="button">VER VIDEO-TUTORIAL PARA ENCONTRAR ICONO HD</a>

				<a target="_blank" href="https://docs.google.com/document/d/1uanI7Dxxvi238UMtS3GSm84KFqyUzIZKe0S7gnhSTDs/edit?usp=sharing" class="btn btn-primary" role="button">NECESITAS AYUDA? PRESIONA AQUI!</a>

				@if(\Auth::user()['role'] == 'owner')
					<a href="/dashboard/users/report" class="btn btn-danger" role="button">REPORT</a>
				@endif
				<br>	
				<br>

				<!-- <div class="alert alert-warning" role="alert">
					  <strong>LIGA APK</strong> La liga para descargar CloudTV APK para cajas Android, MiBox, Amazon FireTV, etc es: <strong>http://akuv.cc</strong> (o http://akuv.cc/app.apk)
				</div> -->

				<!-- 
				<div class="alert alert-info" role="alert">
 					 <strong>ATENCIÓN ADMINISTRADORES Y DISTRIBUIDORES (Supers)</strong> Ya se puede agregar paneles de nuevo. Simplemente al crear un usuario nuevo, en la parte de abajo, seleccionar el <strong>Tipo de Usuario</strong>.
				</div> -->


				<div class="alert alert-warning" role="alert">
  					<h4 class="alert-heading">LIGA APK</h4>
  						<p>La liga para descargar CloudTV APK para cajas Android, MiBox, Amazon FireTV, etc es: <strong>http://akuv.cc</strong>.
  						<p class="mb-0">Recuerda que para el FireDL o FileExplorerES se tiene que usar la liga con el apk: http://akuv.cc/app.apk</p>
				</div>

				<div class="alert alert-info" role="alert">
  					<h4 class="alert-heading">Atención Admins y Distribuidores (Supers)</h4>
  						<p>Ya se puede agregar paneles de nuevo. Simplemente al crear un usuario nuevo, en la parte de abajo, seleccionar el <strong>Tipo de Usuario</strong>.</p>
  					
				</div>

				<div class="alert alert-success" role="alert">
  					<h4 class="alert-heading">Finalmente una solucion!</h4>
  						<p>Ahora, cuando un cliente te diga que algun video no funciona, solo pidele que intente reproducirlo por lo menos 5 veces. Esto nos enviara un mensaje automaticamente y notificaremos a el cliente cuando haya sido corregido. </p>
  						<p class="mb-0">CloudTV</p>
				</div>
					
					<!-- <button type="button" href="www.google.com" class="btn btn-primary btn-lg btn-block">NECESITO AYUDA PORQUE NO ENTIENDO NADA</button> -->
					<br>
				<h3><i class="entypo-user"></i> Mis Usuarios</h3> 
				<a href="/dashboard/users/create" class="btn btn-success"><i class="fa fa-plus-circle"></i> Agregar Usuario</a>
			</div>
			<div class="col-md-4">
				<form method="get" role="form" class="search-form-full"> 
					<div class="form-group"> 
						<input type="text" class="form-control" name="search" id="search-input" value="{{ $_GET['search'] ?? '' }}" placeholder="Search..."> 
						<i class="entypo-search"></i> 
					</div> 
					<div class="form-group pt-5">
						<select class="form-control" name="role">
							@if(\Auth::user()['role'] == 'owner')
								<option value="owner" @if(isset($_GET['role']) && $_GET['role'] == 'owner')selected="selected"@endif>Owner</option>
								<option value="staff" @if(!isset($_GET['role']) || (isset($_GET['role']) && $_GET['role'] == 'staff'))selected="selected"@endif>Staff</option>
								<option value="admin" @if(isset($_GET['role']) && $_GET['role'] == 'admin')selected="selected"@endif>Admin</option>
								<option value="distributor" @if(isset($_GET['role']) && $_GET['role'] == 'distributor')selected="selected"@endif>Distributor</option>
								<option value="seller" @if(isset($_GET['role']) && $_GET['role'] == 'seller')selected="selected"@endif>Seller</option>
								<option value="customer" @if(isset($_GET['role']) && $_GET['role'] == 'customer')selected="selected"@endif>Customer</option>
							@elseif(\Auth::user()['role'] == 'staff')
								<option value="admin" @if(!isset($_GET['role']) || (isset($_GET['role']) && $_GET['role'] == 'admin'))selected="selected"@endif>Admin</option>
								<option value="distributor" @if(isset($_GET['role']) && $_GET['role'] == 'distributor')selected="selected"@endif>Distributor</option>
								<option value="seller" @if(isset($_GET['role']) && $_GET['role'] == 'seller')selected="selected"@endif>Seller</option>
								<option value="customer" @if(isset($_GET['role']) && $_GET['role'] == 'customer')selected="selected"@endif>Customer</option>
							@elseif(\Auth::user()['role'] == 'admin')
								<option value="distributor" @if(!isset($_GET['role']) || (isset($_GET['role']) && $_GET['role'] == 'distributor'))selected="selected"@endif>Distributor</option>
								<option value="seller" @if(isset($_GET['role']) && $_GET['role'] == 'seller')selected="selected"@endif>Seller</option>
								<option value="customer" @if(isset($_GET['role']) && $_GET['role'] == 'customer')selected="selected"@endif>Customer</option>
							@elseif(\Auth::user()['role'] == 'distributor')
								 <option value="seller" @if(!isset($_GET['role']) || (isset($_GET['role']) && $_GET['role'] == 'seller'))selected="selected"@endif>Seller</option>
								<option value="customer" @if(isset($_GET['role']) && $_GET['role'] == 'customer')selected="selected"@endif>Customer</option>
							@elseif(\Auth::user()['role'] == 'seller')
								<option value="customer" selected="selected">Customer</option>
							@endif
						</select>
					</div> 
					<button class="btn btn-primary">Filter</button>
				</form>
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
			@foreach($customers as $user)
				<tr>
					<td><a href="/dashboard/users/{{ $user['username'] }}" target="_blank">
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
								<div class="label label-danger"><i class="fa fa-frown-o"></i> Expired {{abs(round(($ends - $today)/(60*60*24)))}} days ago </div>
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
						@elseif($user['role'] == 'owner')
							<div class="label label-primary" style="background-image: url(/owner.png);padding-left: 35px;">
							<?= ucfirst($user['role']) ?></div>
						@endif						 
					</td>
					<td>{{ $user['ip'] }}</td>
					<td style="color: @if($user['last_action_at'] < date('Y-m-d H:i:s', time() - 7200)) #ED1C24 @else green @endif;">
						@if($user['last_action_at'] < date('Y-m-d H:i:s', time() - 7200))
							Offline
						@else
							{{\App\Models\User::find($user['id'])->isOnline()}}
						@endif
					</td>
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

	{{$customers->links()}}

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

