@if(!empty(config('dz.public.global.js')))
	@foreach(config('dz.public.global.js') as $script)
			<script src="{{ asset($script) }}" type="text/javascript"></script>
	@endforeach
@endif
@if(!empty(config('dz.public.pagelevel.js.dashboard')))
	@foreach(config('dz.public.pagelevel.js.dashboard') as $script)
			<script src="{{ asset($script) }}" type="text/javascript"></script>
	@endforeach
@endif