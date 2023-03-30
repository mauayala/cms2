@extends('layout.default')

@section('content')
	<div class="gallery-env">
		<h2>Movies</h2>
		@foreach($movies as $video)
			@if(count($video->videos()->orderBy('created_at', 'DESC')->get()) > 0)
				<div class="row">		
					<div class="col-md-12">
						<h3>{{ $video->name }}</h3>
					</div>
					@foreach($video->videos()->orderBy('created_at', 'DESC')->get() as $v)
						<div class="video-box pull-left">
							<article class="album">
								<header>
									<a href="{{ URL::to('video/') . '/' . $v->id }}" target="_blank">
										@if(substr($v->image, 0, 4) == 'http')
											<img src="{{ $v->image }}" />
										@else 
											<img src="/images/{{ $v->image }}" />
										@endif
									</a>
									<a href="{{ route('dashboard.videos.edit', ['video' => $v->id]) }}" class="album-options">
										<i class="entypo-pencil"></i>
										Edit
									</a>
								</header>
								
								<section class="album-info text-center">
									<h3>
										<a href="{{ route('dashboard.videos.edit', ['video' => $v->id]) }}">
											@if(strlen($v->title) > 25)
												{{substr($v->title, 0, 25) . '...' }}
											@else
												{{ $v->title }}
											@endif
										</a>
									</h3>
									<h3>{{ $v->released_at }}</h3>
								</section>
								
								<footer>
									<div class="album-images-count">
										@if(isset($v->category->name))
											{{ $v->category->name }}
										@endif
									</div>
									<div class="album-options">
										<a href="{{ route('dashboard.videos.edit', ['video' => $v->id]) }}">
											<i class="entypo-pencil"></i>
										</a>
										<form action="{{ route('dashboard.videos.destroy', ['video' => $v->id]) }}" method="post">
											@method('DELETE')
											@csrf
											<button class="delete"><i class="entypo-trash"></i></button>
										</form>
									</div>
								</footer>
							</article>
						</div>
					@endforeach
				

				<div class="clear"></div>

				<!-- <div class="pagination-outter">
					//$videos->appends(Request::only('s'))->render(); 
				</div> -->
				
				</div>
			@endif
		@endforeach		
	</div>

	<div class="gallery-env">
		<h2>Series</h2>
		@foreach($series as $video)
			@if(count($video->videos()->orderBy('created_at', 'DESC')->get()) > 0)
				<div class="row">		
					<div class="col-md-12">
						<h3>{{ $video->name }}</h3>
					</div>
					@foreach($video->videos()->orderBy('created_at', 'DESC')->get() as $v)
						<div class="video-box pull-left">
							<article class="album">
								<header>
									<a href="{{ URL::to('video/') . '/' . $v->id }}" target="_blank">
										@if(substr($v->image, 0, 4) == 'http')
											<img src="{{ $v->image }}" />
										@else 
											<img src="/images/{{ $v->image }}" />
										@endif
									</a>
									<a href="{{ route('dashboard.videos.edit', ['video' => $v->id]) }}" class="album-options">
										<i class="entypo-pencil"></i>
										Edit
									</a>
								</header>
								
								<section class="album-info text-center">
									<h3>
										<a href="{{ route('dashboard.videos.edit', ['video' => $v->id]) }}">
											@if(strlen($v->title) > 25)
												{{substr($v->title, 0, 25) . '...' }}
											@else
												{{ $v->title }}
											@endif
										</a>
									</h3>
									<h3>{{ $v->released_at }}</h3>
								</section>
								
								<footer>
									<div class="album-images-count">
										@if(isset($v->category->name))
											{{ $v->category->name }}
										@endif
									</div>
									<div class="album-options">
										<a href="{{ route('dashboard.videos.edit', ['video' => $v->id]) }}">
											<i class="entypo-pencil"></i>
										</a>
										<form action="{{ route('dashboard.videos.destroy', ['video' => $v->id]) }}" method="post">
											@method('DELETE')
											@csrf
											<button class="delete"><i class="entypo-trash"></i></button>
										</form>
									</div>
								</footer>
							</article>
						</div>
					@endforeach
				

				<div class="clear"></div>

				<!-- <div class="pagination-outter">
					//$videos->appends(Request::only('s'))->render(); 
				</div> -->
				
				</div>
			@endif
		@endforeach		
	</div>


	@section('javascript')
		<script>

			$(document).ready(function(){
				var delete_link = '';

				$('.delete').click(function(e){
					e.preventDefault();
					delete_link = $(this).attr('href');
					swal({   title: "Are you sure?",   text: "Do you want to permanantly delete this video?",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   closeOnConfirm: false }, function(){    window.location = delete_link });
					return false;
				});
			});

		</script>

	@endsection

@endsection

