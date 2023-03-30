@extends('layout.default')

@section('css')
	<link rel='stylesheet' href='/admin/css/sweetalert.css'>
@endsection

@section('content')
	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-3">
				<h3><i class="entypo-serie"></i> Series</h3>
				<a href="{{ route('dashboard.series.create') }}" class="btn btn-success">
					<i class="fa fa-plus-circle"></i> Add Serie
				</a>
			</div>
			<div class='col-md-3'>
				<form action='/dashboard/series' method='GET' class='form-inline'>
					<div class='form-group'>
						<select name='filter' class='form-control'>
							<option value='NR'>NR</option>
							<option value='TV-14'>TV-14</option>
							<option value='TV-PG'>TV-PG</option>
							<option value='TV-MA'>TV-MA</option>
							<option value='TV-G'>TV-G</option>
						</select>
					</div>
					<div class='form-group'>
						<button type='submit' class='btn btn-success'><i class="fa fa-filter"></i> Filter by Rating</button>
					</div>
				</form>
			</div>
			<div class="col-md-3">
				<a href="{{ route('dashboard.series.categories.index') }}" class="btn btn-success">
					Series Categories
				</a>
			</div>
			<div class="col-md-3">	
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
		@foreach($series as $serie)
			@if(isset($_GET['s']) || isset($_GET['filter']))
				@if(count($serie->series) > 0)
					<div class="row">		
						<div class="col-md-12">
							<h3>{{ $serie->name }}</h3>
						</div>
						@foreach($serie->series as $v)
							<div class="video-box pull-left">
								<article class="album">
									<header>
										<a href="{{ route('dashboard.series.seasons.index', ['series' => $v->id]) }}">
											<img src="https://s3.amazonaws.com/ctv3/{{ $v->image }}" />
										</a>
										<a href="{{ route('dashboard.series.edit', ['series' => $v->id]) }}" class="album-options">
											<i class="entypo-pencil"></i>
											Edit
										</a>
									</header>
									
									<section class="album-info text-center">
										<h3>
											<a href="{{ route('dashboard.series.edit', ['series' => $v->id]) }}">
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
											@if($v->hasErrors())
												<span class="red-circle"></span>
											@endif
											@if(isset($serie->name))
												{{ $serie->name }}
												<br/>
											@endif
											{{$v->serie_views()->count()}} VIEWS
										</div>
										<div class="album-options">
											<a href="{{ route('dashboard.series.edit', ['series' => $v->id]) }}">
												<i class="entypo-pencil"></i>
											</a>
											<form action="{{ route('dashboard.series.destroy', ['series' => $v->id]) }}" method="post">
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
						//$series->appends(Request::only('s'))->render(); 
					</div> -->
					
					</div>
				@endif
			@else
				@php $series5 = $serie->series()->take(5)->orderBy('created_at', 'DESC')->get(); @endphp
				@if($series5->count() > 0)
					<div class="row">		
						<div class="col-md-12">
							<h3>{{ $serie->name }}</h3>
						</div>
						@foreach($series5 as $v)
							<div class="video-box pull-left">
								<article class="album">
									<header>
										<a href="{{ route('dashboard.series.seasons.index', ['series' => $v->id]) }}">
											<img src="https://s3.amazonaws.com/ctv3/{{ $v->image }}" />
										</a>
										<a href="{{ route('dashboard.series.edit', ['series' => $v->id]) }}" class="album-options">
											<i class="entypo-pencil"></i>
											Edit
										</a>
									</header>
									
									<section class="album-info text-center">
										<h3>
											<a href="{{ route('dashboard.series.edit', ['series' => $v->id]) }}">
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
											@if($v->hasErrors())
												<span class="red-circle"></span>
											@endif
											@if(isset($serie->name))
												{{ $serie->name }}
												<br/>
											@endif
											{{$v->serie_views()->count()}} VIEWS
										</div>
										<div class="album-options">
											<a href="{{ route('dashboard.series.edit', ['series' => $v->id]) }}">
												<i class="entypo-pencil"></i>
											</a>
											<a href="{{ route('dashboard.series.destroy', ['serie' => $v->id]) }}" class="delete">
												<i class="entypo-trash"></i>
											</a>
										</div>
									</footer>
								</article>
							</div>
						@endforeach
					

					<div class="clear"></div>

					<!-- <div class="pagination-outter">
						//$series->appends(Request::only('s'))->render(); 
					</div> -->
					
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
					swal({   title: "Are you sure?",   text: "Do you want to permanantly delete this serie?",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   closeOnConfirm: false }, function(){    window.location = delete_link });
					return false;
				});
			});

		</script>

	@endsection

@endsection

