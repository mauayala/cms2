@extends('layout.default')

@section('content')


<div id="admin-container">
<!-- This is where -->
	
	<div class="admin-section-title">
		<h3><i class="entypo-globe"></i> Ajustes de Usuario</h3> 
	</div>
	<div class="clear"></div>	

	<form method="POST" action="/dashboard/settings/update-user">	
		<div class="row">			
			<div class="col-md-4">
				<div class="panel panel-primary" data-collapsed="0"> 
					<div class="panel-heading"> 
						<div class="panel-title">Cambiar contraseña de Cuenta</div> 
					</div> 
					<div class="panel-body" style="display: block;"> 
						<p>Nueva Contraseña (Dejar vacio si no deseas cambiar tu contraseña):</p> 
						<input type="text" class="form-control" name="password" id="password" placeholder="Nueva Contraseña"/>
					</div> 
				</div>
			</div>

			@if(\Auth::user()->role == 'seller' || \Auth::user()->role == 'distributor')
				<div class="col-md-8">
					<div class="panel panel-primary" data-collapsed="0">
						<div class="panel-heading"> 
							<div class="panel-title">Cambiar @if(\Auth::user()->role == 'seller') distributor @else admin @endif</div>
						</div> 
						<div class="panel-body" style="display: block;"> 
							<p>Actual @if(\Auth::user()->role == 'seller') distributor @else admin @endif: {{\Auth::user()->creator->username}}</p>
							<p>Cambiar de @if(\Auth::user()->role == 'seller') distributor @else admin @endif:</p> 
							<input type="text" class="form-control" name="admin" id="admin" placeholder="Nombre de Usuario a donde gustas migrar."/>
						</div> 
					</div>
				</div>
			@endif
		</div>
		{{ csrf_field() }}
		<input type="submit" value="Update Settings" class="btn btn-success pull-right" />
	</form>

	<div class="clear"></div>

</div><!-- admin-container -->
@endsection