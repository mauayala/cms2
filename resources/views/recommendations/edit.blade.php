<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Update Event</h4>
</div>

<div class="modal-body">
	<form id="update-cat-form" enctype="multipart/form-data" accept-charset="UTF-8" action="{{ route('dashboard.events.update', ['event' => $event->id]) }}" method="post">
		@csrf
		@method('PATCH')
        <label for="name">Event Name</label>
        <input name="name" id="name" placeholder="Event Name" class="form-control" value="{{ $event->name }}" /><br />
		<label for="poster">Choose a poster for the event (Must be 620x920px)</label>
		<input type='file' name="poster" id="poster" required/><br />
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