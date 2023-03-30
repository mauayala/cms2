<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Update Event</h4>
</div>

<div class="modal-body">
	<form id="update-cat-form" accept-charset="UTF-8" action="{{ route('dashboard.events.options.update', ['option' => $option->id]) }}" method="post">
		@csrf
		@method('PATCH')
		<label for="event_id">Select event</label>
		<select name="event_id" class='form-control'>
			@foreach($events as $event)
				<option value='{{$event->id}}' @if($option->event_id == $event->id) selected='selected'@endif>{{$event->name}}</option>
			@endforeach
		</select><br>
		<label for="name">Option Name: (Ex: Team vs. Team Option 1)</label>
		<input name="name" id="name" placeholder="Name" class="form-control" value="{{ $option->name }}" /><br />
		<label for="mbps">What is the bitrate of the stream?</label>
		<input name="mbps" id="mbps" placeholder="MBPS" class="form-control" value="{{ $option->mbps }}" /><br />
		<label for="link">Link Address: (m3u)</label>
		<input name="link" id="link" placeholder="Link" class="form-control" value="{{ $option->getOriginal('link') }}" /><br />
		<label for="start_date">Option Start Date</label>
		<input name="start_date" id="start_date" placeholder="Start date" class="form-control" value="{{ $option->start_date }}" /><br />
		<label for="end_date">Option End Date</label>
		<input name="end_date" id="end_date" placeholder="End date" class="form-control" value="{{ $option->end_date }}" /><br />
		<label for="team_id1">Team #1</label>
		<select name="team_id1" id="team_id1" class="form-control">
			<option value=""></option>
			@foreach ($teams as $team)
				<option value="{{$team->id}}" @if($option->teams()->where('teams.id', $team->id)->first()) selected="" @endif>
					{{$team->name}}
				</option>
			@endforeach
		</select>
		<label for="team_id2">Team #2</label>
		<select name="team_id2" id="team_id2" class="form-control">
			<option value=""></option>
			@foreach ($teams as $team)
				<option value="{{$team->id}}" @if($option->teams()->where('teams.id', $team->id)->first()) selected="" @endif>
					{{$team->name}}
				</option>
			@endforeach
		</select>
		<input type="hidden" name="id" id="id" value="{{ $option->id }}" />
    </form>
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" class="btn btn-info" id="submit-update-cat">Update</button>
</div>

<script>
	$(document).ready(function(){
		$('#submit-update-cat').click(function(){
			$('#update-cat-form').submit();
		});
	});
</script>