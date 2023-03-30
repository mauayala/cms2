@extends('layout.default')

@section('content')
	<script type="text/javascript">
		var $ = jQuery;
	</script>
	<link rel="stylesheet" href="/admin/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<script src="/admin/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-primary" data-collapsed="0">
					<div class="panel-heading">
						<div class="panel-title">
							Crear Creditos
						</div>
					</div>
					<div class="panel-body">
						<form method="POST" action="{{url('dashboard/transfer/store')}}" id="credit_create">
							<div class="form-group ui-widget">
								<label for="user">User</label>
								<input name="user" id="user" class="form-control">
							</div>
							<div class="form-group">
								<label for="amount">Cantidad de Creditos a Crear como OWNER</label>
								<input type="number" class="form-control" name="amount" id="amount" min='1'>
							</div>
							
							<div class="form-group">
								<button class="btn btn-success" data-toggle="modal" data-target="#myModal" id="create">Crear y Enviar Creditos</button>
							</div>
							{{ csrf_field() }}
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  	<div class="modal-dialog" role="document">
		    <div class="modal-content">
		      	<div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">Estas seguro que deseas transferir <span id="credit_amount"> cr.</span> a <span id="selected_user"></span>?</h4>
		      	</div>
		      	<div class="modal-body">
			        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
			        <button type="button" class="btn btn-primary" onclick="$('#credit_create').submit()">Yes</button>
		      	</div>
		    </div>
	  	</div>
	</div>
	<script type="text/javascript">
		$('#create').on('click', function(){
			$('#credit_amount').html($('#amount').val());
			$('#selected_user').html($('#user').val());
		})

		$( "#user" ).autocomplete({
			source: "/dashboard/transfer/get-users",
			minLength: 3,
		})
	</script>
	<style>
		.ui-autocomplete {
			position: absolute;
			top: 100%;
			left: 0;
			z-index: 1000;
			display: none;
			float: left;
			min-width: 160px;
			padding: 5px 0;
			margin: 2px 0 0;
			list-style: none;
			font-size: 14px;
			text-align: left;
			background-color: #ffffff;
			border: 1px solid #cccccc;
			border: 1px solid rgba(0, 0, 0, 0.15);
			border-radius: 4px;
			-webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
			box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
			background-clip: padding-box;
		}

		.ui-autocomplete > li > div {
			display: block;
			padding: 3px 20px;
			clear: both;
			font-weight: normal;
			line-height: 1.42857143;
			color: #333333;
			white-space: nowrap;
		}

		.ui-state-hover,
		.ui-state-active,
		.ui-state-focus {
			text-decoration: none;
			color: #262626;
			background-color: #f5f5f5;
			cursor: pointer;
		}

		.ui-helper-hidden-accessible {
			border: 0;
			clip: rect(0 0 0 0);
			height: 1px;
			margin: -1px;
			overflow: hidden;
			padding: 0;
			position: absolute;
			width: 1px;
		}
	</style>
@endsection