@extends('layout.default')

@section('css')
	<link rel='stylesheet' href='/admin/css/sweetalert.css'>
@endsection

@section('content')
	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-3">
				<h3><i class="entypo-video"></i> Movies</h3>
				<a href="{{ route('dashboard.videos.create') }}" class="btn btn-success">
					<i class="fa fa-plus-circle"></i> Add Movie
				</a>
			</div>
			<div class='col-md-3'>
				<form action='/dashboard/videos' method='GET' class='form-inline'>
					<div class='form-group'>
						<select name='filter' class='form-control'>
							<option value='NR'>NR</option>
							<option value='G'>G</option>
							<option value='PG'>PG</option>
							<option value='PG-13'>PG-13</option>
							<option value='R'>R</option>
							<option value='NC-17'>NC-17</option>
						</select>
					</div>
					<div class='form-group'>
						<button type='submit' class='btn btn-success'><i class="fa fa-filter"></i> Filter by Rating</button>
					</div>
				</form>
			</div>
			<div class="col-md-2">
				<a href="{{ route('dashboard.videos.categories.index') }}" class='btn btn-success'>
					<span class="title">Movie Categories</span>
				</a>
			</div>
			<div class="col-md-4">	
				<form method="get" role="form" class="search-form-full">
					<div class="form-group">
						<input type="text" class="form-control" name="s" value="<?=(isset($_GET['s'])) ? $_GET['s'] : ''; ?>" id="search-input" placeholder="Search...">
						<i class="entypo-search"></i>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="clear"></div>

	<div class="gallery-env">
		@foreach($videos as $video)
			@if(isset($_GET['s']) || isset($_GET['filter']))
				@if(count($video->videos) > 0)
					<div class="row">		
						<div class="col-md-12">
							<h3>{{ $video->name }}</h3>
						</div>
						@foreach($video->videos as $v)
							<div class="video-box pull-left">
								<article class="album">
									<header>
										<a href="{{ URL::to('video/') . '/' . $v->id }}" target="_blank">
											@if(substr($v->image, 0, 4) == 'http')
												<img src="{{ $v->image }}" />
											@else 
												<img src="https://s3.amazonaws.com/ctv3/{{ $v->image }}" />
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
										<small><strong>TRAILER</strong> <span>@if(is_null($v->trailer)) - @else /{{$v->trailer}} @endif</span></small><br/>
										@if($v->link_works == 0)
											<span class="red-circle"></span>
										@endif
										<small><strong>VOD</strong> <span>@if(is_null($v->video_file_name)) - @else /{{$v->video_file_name}} @endif</span></small><br/>
										@if($v->link_subtitle_works == 0)
											<span class="red-circle"></span>
										@endif
										<small><strong>EN SRT</strong> <span>@if(is_null($v->subtitle_file_name)) - @else /{{$v->subtitle_file_name}} @endif</span></small><br/>
										@if($v->link_subtitle_es_works == 0)
											<span class="red-circle"></span>
										@endif
										<small><strong>ES SRT</strong> <span>@if(is_null($v->subtitle_file_name_es)) - @else /{{$v->subtitle_file_name_es}} @endif</span></small>
									</section>
									
									<footer>
										<div class="album-images-count">
											@if(isset($video->name))
												{{ $video->name }}
												<br>
											@endif
											{{$v->video_views->count()}} VIEWS
										</div>
										<div class="album-options">
											<a href="{{ route('dashboard.videos.edit', ['video' => $v->id]) }}">
												<i class="entypo-pencil"></i>
											</a>
											<a href="{{ route('dashboard.videos.destroy', ['video' => $v->id]) }}" class="delete">
												<i class="entypo-trash"></i>
											</a>
										</div>
									</footer>
								</article>
							</div>
						@endforeach
						<div class="clear"></div>				
					</div>
				@endif
			@else
				@php $videos5 = $video->videos()->orderBy('created_at', 'desc')->limit(5)->get(); @endphp
				@if($videos5->count() > 0)
					<div class="row">		
						<div class="col-md-12">
							<h3>{{ $video->name }}</h3>
						</div>
						@foreach($videos5 as $v)
							<div class="video-box pull-left">
								<article class="album">
									<header>
										<a href="{{ URL::to('video/') . '/' . $v->id }}" target="_blank">
											@if(substr($v->image, 0, 4) == 'http')
												<img src="{{ $v->image }}" />
											@else 
												<img src="https://s3.amazonaws.com/ctv3/{{ $v->image }}" />
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
										<small><strong>TRAILER</strong> <span>@if(is_null($v->trailer)) - @else /{{$v->trailer}} @endif</span></small><br/>
										@if($v->link_works == 0)
											<span class="red-circle"></span>
										@endif
										<small><strong>VOD</strong> <span>@if(is_null($v->video_file_name)) - @else /{{$v->video_file_name}} @endif</span></small><br/>
										@if($v->link_subtitle_works == 0)
											<span class="red-circle"></span>
										@endif
										<small><strong>EN SRT</strong> <span>@if(is_null($v->subtitle_file_name)) - @else /{{$v->subtitle_file_name}} @endif</span></small><br/>
										@if($v->link_subtitle_es_works == 0)
											<span class="red-circle"></span>
										@endif
										<small><strong>ES SRT</strong> <span>@if(is_null($v->subtitle_file_name_es)) - @else /{{$v->subtitle_file_name_es}} @endif</span></small>
									</section>
									
									<footer>
										<div class="album-images-count">
											@if(isset($video->name))
												{{ $video->name }}
												<br>
											@endif
											{{$v->video_views->count()}} VIEWS
										</div>
										<div class="album-options">
											<a href="{{ route('dashboard.videos.edit', ['video' => $v->id]) }}">
												<i class="entypo-pencil"></i>
											</a>
											<a href="{{ route('dashboard.videos.destroy', ['video' => $v->id]) }}" class="delete">
												<i class="entypo-trash"></i>
											</a>
										</div>
									</footer>
								</article>
							</div>
						@endforeach
						<div class="clear"></div>				
					</div>
				@endif
			@endif
			
		@endforeach
		
	</div>


	@section('javascript')
		<script src='/admin/js/sweetalert.min.js'></script>
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

