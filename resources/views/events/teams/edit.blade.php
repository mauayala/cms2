@extends('layout.default')

@section('content')

	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-12">
				<h3><i class="entypo-star"></i>Teams</h3>
			</div>			
		</div>
	</div>
		
	<div class="panel panel-primary category-panel" data-collapsed="0">
		<div class="panel-heading">
			<div class="panel-title">
				Teams
			</div>
			<div class="panel-options">
				<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
			</div>
		</div>
		<div class="panel-body">
			<form id="update-cat-form" enctype="multipart/form-data" accept-charset="UTF-8" action="{{ route('dashboard.events.teams.update', ['team' => $team->id]) }}" method="post">
				@csrf
				@method('PATCH')
				<label for="name">Name</label>
				<input name="name" id="name" placeholder="Name" class="form-control" value="{{ $team->name }}" /><br />
				<label for="logo">Choose a logo for the team (Must be 620x920px)</label>
				<input type='file' name="logo" id="logo"/><br />
				<button class="btn btn-info">Update</button>
			</form>
		</div>
	</div>
@endsection