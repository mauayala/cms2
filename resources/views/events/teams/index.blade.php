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
			<div class="nested-list dd with-margins">
				<ol class="dd-list">
					@foreach($teams as $team)
						<li class="dd-item">
							<div class="dd-handle"><img src="{{$team->logo}}" alt="" height="50"> {{ $team->name}}</div>
							<div class="actions">
								<a href="{{route('dashboard.events.teams.edit', ['team' => $team->id])}}" class="edit">Edit</a> 
								<form action="{{ route('dashboard.events.teams.destroy', ['team' => $team->id]) }}" method="post">
									@method('DELETE')
									@csrf
									<button class="delete">Delete</button>
								</form>
							</div>
						</li>
					@endforeach
				</ol>
			</div>
		</div>
	</div>
@endsection