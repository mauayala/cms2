@extends('layout.default')

@section('css')
	<link rel="stylesheet" href="/assets/js/tagsinput/jquery.tagsinput.css" />
@endsection


@section('content')
<style type="text/css">
	.panel-body > div {
		margin-bottom: 10px;
	}
</style>
<div id="admin-container">
<!-- This is where -->
	
	<div class="admin-section-title">
	<h3><i class="entypo-user"></i> Agregar Usuario Nuevo</h3> 
	
	</div>
	<div class="clear"></div>
		<form method="POST" action="{{ route('dashboard.users.store') }}" id="update_profile_form" accept-charset="UTF-8" file="1" enctype="multipart/form-data">
			@csrf
			<div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading"> 
				<div class="panel-title">
					Add User Profile
				</div> 
				<div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div> 
				<div class="panel-body" style="display: block;">
					<div>
						<p>Nombre</p>
						<input type="text" class="form-control" name="firstname" id="firstname" value="" />
					</div>

					<div>
						<p>Apellido</p>
						<input type="text" class="form-control" name="lastname" id="lastname" value="" />
					</div> 
					
					<div>
						@if($errors->first('username')) <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <strong>Oh snap!</strong> {{$errors->first('username')}}</div> @endif
						<p>Nombre de Usuario (solo minusculas)</p>
						<input type="text" class="form-control" name="username" id="username" value=""/>
					</div>

					<div>
						<p>Contraseña</p>
						<input type="password" class="form-control" name="password" id="password" value="" />
					</div>
					
					<div> 
						<p>Candado Geografico</p>
						<select id="countrylock" name="countrylock" @if(\Auth::user()->role != 'owner')disabled='disabled'@endif>
							<option value="enabled">
								Enabled
							</option>
							<option value="disabled">
								Disabled
							</option>
						</select>
					</div>

				</div> 
					<div> 
						<p>Numero de Serie de Aparato</p>
						<input type="text" class="form-control" name="devicenumber" id="devicenumber" disabled="disabled" value="" />
					</div>

					<div> 
						<p>Numero de Serie de Aparato 2</p>
						<input type="text" class="form-control" name="devicenumber2" id="devicenumber2" disabled="disabled" value="" />
					</div>

					<div> 
						<p>Clave para controles parentales dentro de el canal:</p>
						<input type="text" pattern="\d{4}" class="form-control" name="pin" id="pin" value="4121" />
					</div>

				</div>
			</div>

			<div class="row">
				<div class="col-sm-4"> 
					<div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading"> 
						<div class="panel-title">Tipo de Usuario</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div> 
						<div class="panel-body" style="display: block;"> 
						<p>Selecciona el tipo de usuario que deseas asignar:</p>
						<select id="role" name="role">
							@if(\Auth::user()->role == 'owner')
								<option value="staff">Staff</option>
								<option value="admin">Admin</option>
								<option value="distributor">Distributor</option>
								<option value="seller">Seller</option>
								<option value="customer" selected="selected">Customer</option>
							@elseif(\Auth::user()->role == 'staff')							
								<option value="distributor">Distributor</option>
								<option value="seller">Seller</option>
								<option value="customer" selected="selected">Customer</option>
							@elseif(\Auth::user()->role == 'admin')
								<option value="distributor">Distributor</option>
								<option value="seller">Seller</option>
								<option value="customer" selected="selected">Customer</option>
							@elseif(\Auth::user()->role == 'distributor')
								 <option value="seller">Seller</option>
								<option value="customer" selected="selected">Customer</option>
							@elseif(\Auth::user()->role == 'seller')
								<option value="customer" selected="selected">Customer</option>
							@endif
						</select>
						</div>
					</div>
				</div>				

			</div><!-- row -->

		 	<input type="submit" value="Create User" class="btn btn-success pull-right" />

			<div class="clear"></div>
		</form>

		<div class="clear"></div>
<!-- This is where now -->
</div>

	
	
	
	@section('javascript')


	<script type="text/javascript" src="/assets/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="/assets/js/tagsinput/jquery.tagsinput.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.mask.min.js"></script>

	<script type="text/javascript">

	$ = jQuery;

	$(document).ready(function(){

		$('#active, #disabled').change(function() {
			if($(this).is(":checked")) {
		    	$(this).val(1);
		    } else {
		    	$(this).val(0);
		    }
		    console.log('test ' + $(this).is( ':checked' ));
		});

	});



	</script>

	@endsection

@endsection