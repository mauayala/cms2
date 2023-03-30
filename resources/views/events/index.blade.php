@extends('layout.default')

@section('content')

	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-12">
				<h3><i class="entypo-star"></i>Special Events</h3>
				<a href="javascript:;" onclick="jQuery('#add-new').modal('show');" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add New Event</a>
				<a href="javascript:;" onclick="jQuery('#add-option-new').modal('show');" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add New Option</a>
				<a href="javascript:;" onclick="jQuery('#add-team-new').modal('show');" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add New Team</a>
			</div>			
		</div>
	</div>

	<!-- Add New Modal -->
	<div class="modal fade" id="add-new">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Add New Special Event</h4>
				</div>
				<div class="modal-body">						
					<form id="new-cat-form" enctype="multipart/form-data" accept-charset="UTF-8" action="{{route('dashboard.events.store')}}" method="post">
						@csrf
						<label for="name">What is the event called? (Example: UEFA Champions League)</label>
						<input name="name" id="name" placeholder="Event Name" class="form-control" value="" /><br />
						<label for="poster">Choose a poster for the event (Must be 620x920px)</label>
						<input type='file' name="poster" id="poster" required/><br />
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-info" id="submit-new-cat">Save changes</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="add-option-new">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Add a Viewing Option to a Special Event</h4>
				</div>
				<div class="modal-body">						
					<form id="new-option-form" accept-charset="UTF-8" action="{{route('dashboard.events.options.store')}}" method="post">
						@csrf
						<label for="event_id">Which event do you want to add the Option to?</label>
						<select name="event_id" class='form-control'>
							@foreach($events as $event)
								<option value='{{$event->id}}'>{{$event->name}}</option>
							@endforeach
						</select><br>
						<label for="name">Option Name: (Ex: Team vs. Team Option 1)</label>
						<input name="name" id="name" placeholder="Name" class="form-control" value="" /><br />
						<label for="mbps">What is the bitrate of the stream?</label>
						<input name="mbps" id="mbps" placeholder="MBPS" class="form-control" value="" /><br />
						<label for="link">Link Address: (m3u)</label>
						<input name="link" id="link" placeholder="Link" class="form-control" value="" /><br />
						<label for="start_date">Option Start Date</label>
						<input type='text' name="start_date" id="start_date" placeholder="Start date" class="form-control" value="" /><br />
						<label for="end_date">Option End Date</label>
						<input type='text' name="end_date" id="end_date" placeholder="End date" class="form-control" value="" /><br />
						<label for="team_id1">Team #1</label>
						<select name="team_id1" id="team_id1" class="form-control">
							@foreach ($teams as $team)
								<option value="{{$team->id}}">
									{{$team->name}}
								</option>
							@endforeach
						</select>
						<label for="team_id2">Team #2</label>
						<select name="team_id2" id="team_id2" class="form-control">
							@foreach ($teams as $team)
								<option value="{{$team->id}}">
									{{$team->name}}
								</option>
							@endforeach
						</select>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-info" id="submit-new-option">Save changes</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="add-team-new">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Add a Team</h4>
				</div>
				<div class="modal-body">
					<form id="new-team-form" enctype="multipart/form-data" accept-charset="UTF-8" action="{{route('dashboard.events.teams.store')}}" method="post">
						@csrf
						<label for="name">Name: (Ex: Team vs. Team Option 1)</label>
						<input name="name" id="name" placeholder="Name" class="form-control" value="" /><br />
						<label for="logo">Choose a logo for the team (Must be 620x920px)</label>
						<input type='file' name="logo" id="logo" required/><br />
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-info" id="submit-new-team">Save changes</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Add New Modal -->
	<div class="modal fade" id="update-category">
		<div class="modal-dialog">
			<div class="modal-content"></div>
		</div>
	</div>

	<div class="clear"></div>
		
	<div class="panel panel-primary category-panel" data-collapsed="0">
		<div class="panel-heading">
			<div class="panel-title">
				Events
			</div>
			<div class="panel-options">
				<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
			</div>
		</div>
		<div class="panel-body">
			<div id="nestable" class="nested-list dd with-margins">
				<ol class="dd-list">
					@foreach($events_raw as $event)
						<li class="dd-item" data-id="{{ $event->id }}">
							<div class="dd-handle"><img src="https://s3.amazonaws.com/ctv3/{{$event->poster}}" alt="" height="50"> {{ $event->name}}</div>
							<div class="actions">
								<a href="{{route('dashboard.events.edit', ['event' => $event->id])}}" class="edit">Edit</a> 
								<form action="{{ route('dashboard.events.destroy', ['event' => $event->id]) }}" method="post">
									@method('DELETE')
									@csrf
									<button class="delete">Delete</button>
								</form>
							</div>
							@if($event->options()->count() > 0)
							<ol class="dd-list">
								@foreach($event->options as $option)
								<li class="dd-item" data-id="{{ $option->id }}">
									<div class="dd-handle">
										<div class="row">
											<div class="col-md-3">{{ $option->name}}</div>
											<div class="col-md-3"><span class="label label-success">Expires in {{ intval((strtotime($option->end_date) - time())/3600)}} hours</span></div>
											<div class="col-md-2"><span class="label label-default">{{ $option->mbps}} MBPS</span> </div>
											<div class="col-md-4">
												@foreach ($option->teams as $team)
													<span class="label label-danger">{{$team->name}}</span> 
												@endforeach
											</div>
										</div>
									</div>
									<div class="actions"> 
										<a href="{{$option->link}}" target='_blank'><span class="label label-primary">Preview</span></a>
										<a href="{{route('dashboard.events.options.edit', ['option' => $option->id])}}" class="edit">Edit</a> 
										<form action="{{ route('dashboard.events.options.destroy', ['option' => $option->id]) }}" method="post">
											@method('DELETE')
											@csrf
											<button class="delete">Delete</button>
										</form>
									</div>
								</li>
								@endforeach
							</ol>
							@endif
						</li>
					@endforeach
				</ol>
			</div>
		</div>
	</div>

	@section('javascript')
		<script src="/admin/js/jquery.nestable.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.css"/ >
		<script src="/js/jquery.datetimepicker.full.min.js"></script>
		<script type="text/javascript">
			$('#start_date').datetimepicker({
				format:'d.m.Y H:i'
			})
			$('#end_date').datetimepicker({
				format:'d.m.Y H:i'
			})
			jQuery(document).ready(function($){
				$('#nestable').nestable({ maxDepth: 2 });

				// Add New Category
				$('#submit-new-cat').click(function(){
					if($('#poster').val() == '') { 
						toastr.error('You need to choose poster to add new event')
						return false; 
					}
					$('#new-cat-form').submit();
				});

				$('#submit-new-option').click(function(){
					$('#new-option-form').submit();
				});

				$('#submit-new-team').click(function(){
					$('#new-team-form').submit();
				});

				$('.actions .edit').click(function(e){
					$('#update-category').modal('show', {backdrop: 'static'});
					e.preventDefault();
					href = $(this).attr('href');
					$.ajax({
						url: href,
						success: function(response)
						{
							$('#update-category .modal-content').html(response);
						}
					});
				});

				$('.actions .delete').click(function(e){
					e.preventDefault();
					console.log($(this).closest('form'))
					if (confirm("Are you sure you want to delete this category?")) {
					   $(this).closest('form').submit();
					}
					return false;
				});

				$('.dd').on('change', function(e) {
					$('.category-panel').addClass('reloading');
					$.post(
						'/dashboard/events/order', 
						{ 
							order : JSON.stringify($('.dd').nestable('serialize')), 
							_token : "{{csrf_token()}}"
						}, 
						function(data){
							if(data == 0){
								toastr.error('Cannot change option to event');
								setTimeout(location.reload(), 2000);
							} else if(data == 2) {
								toastr.error('Option must be in an event');
								setTimeout(location.reload(), 2000);
							}
							$('.category-panel').removeClass('reloading');
						}
					);

				});
			});
		</script>
	@endsection
@endsection