@extends('layout.default')

@section('css')
	<link rel='stylesheet' href='/admin/css/sweetalert.css'>
@endsection

@section('content')
	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-8">
				<h3><i class="entypo-serie"></i> Episodes</h3>
				<a href="{{ route('dashboard.series.seasons.episodes.create', ['series' => $series->id, 'season' => $season->id]) }}" class="btn btn-success">
					<i class="fa fa-plus-circle"></i> Add Episode
				</a>
				<a href="{{ route('dashboard.series.seasons.episode_manual.create', ['series' => $series->id, 'season' => $season->id]) }}" class="btn btn-success">
					<i class="fa fa-plus-circle"></i> Add Episode Manually
				</a>
			</div>
		</div>
	</div>
	<div class="clear"></div>

	<div class="gallery-env">
		<div class="row">		
			<div class="col-md-12">
				<h3>{{ $series->title }}</h3>
			</div>
			@foreach($episodes as $episode)
				<div class="video-box pull-left episode">
					<article class="album">
						<header>
							<a href="{{ route('dashboard.series.seasons.episodes.edit', ['series' => $series->id, 'season' => $season->id, 'episode' => $episode->id]) }}" class="episode{{$episode->id}}">
								<img src="https://s3.amazonaws.com/ctv3/{{ $episode->poster }}" />
							</a>
							<button class="album-options" style="margin-right: 60px;" onclick="getPosters('{{$series->title}}', '{{$season->season_number}}', '{{$episode->episode_number}}', {{$episode->id}})">
								Update Poster
							</button>
							<a href="{{ route('dashboard.series.seasons.episodes.edit', ['series' => $series->id, 'season' => $season->id, 'episode' => $episode->id]) }}" class="album-options">
								<i class="entypo-pencil"></i>
								Edit
							</a>
						</header>
						
						<section class="album-info text-center">
							<h3>
								<a href="{{ route('dashboard.series.seasons.episodes.edit', ['series' => $series->id, 'season' => $season->id, 'episode' => $episode->id]) }}">
									@if(strlen($episode->title) > 25)
										{{substr($episode->title, 0, 25) . '...' }}
									@else
										{{ $episode->title }}
									@endif
								</a>
							</h3>
							<h3>{{ $episode->released_at }}</h3>
							@if($episode->link_works == 0)
								<span class="red-circle"></span>
							@endif
							<small><strong>VOD</strong> <span>@if(is_null($episode->serie_file_name)) - @else /{{$episode->serie_file_name}} @endif</span></small><br/>
							@if($episode->link_subtitle_works == 0)
								<span class="red-circle"></span>
							@endif
							<small><strong>EN SRT</strong> <span>@if(is_null($episode->subtitle_file_name)) - @else /{{$episode->subtitle_file_name}} @endif</span></small><br/>
							@if($episode->link_subtitle_es_works == 0)
								<span class="red-circle"></span>
							@endif
							<small><strong>ES SRT</strong> <span>@if(is_null($episode->subtitle_file_name_es)) - @else /{{$episode->subtitle_file_name_es}} @endif</span></small>
						</section>
						
						<footer>
							<div class="album-images-count">
								@if(!is_null($episode->season_episode))
									{{$episode->season_episode}}
									<br/>
								@else
									<?php $season_number = $episode_number = '';?>
									@if($season->season_number < 10)
										<?php $season_number = '0'.$season->season_number;?>
									@else
										<?php $season_number = $season->season_number; ?>
									@endif
									@if($episode->episode_number < 10)
										<?php $episode_number = '0'.$episode->episode_number;?>
									@else
										<?php $episode_number = $episode->episode_number; ?>
									@endif
									{{'S'.$season_number.'E'.$episode_number}}
									<br/>
								@endif
								{{$episode->episode_views->count()}} VIEWS
							</div>
							<div class="album-options">
								<a href="{{ route('dashboard.series.seasons.episodes.edit', ['series' => $series->id, 'season' => $season->id, 'episode' => $episode->id]) }}">
									<i class="entypo-pencil"></i>
								</a>
								<a href="{{ route('dashboard.series.seasons.episodes.destroy', ['series' => $series->id, 'season' => $season->id, 'episode' => $episode->id]) }}" class="delete">
									<i class="entypo-trash"></i>
								</a>
							</div>
						</footer>
					</article>
				</div>
			@endforeach
			<div class="clear"></div>
		</div>
		
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

            const apikey = '5fabc059c6c919ad8fa7014c1c844cf0';

			function getPosters(title, season_number, episode_number, episode_id) {
                const searchByTitle = 'https://api.themoviedb.org/3/search/tv?api_key=' + apikey + '&language=en-US&query=' + title;
                $('#episode_number1').val(episode_number);
                $.ajax({
                    url: searchByTitle,
                    method: 'GET',
                    success: function(response){
                        response = response.results[0];
                        const getEpisodeDetails = 'https://api.themoviedb.org/3/tv/' + response.id + '/seasons/' + season_number + '/episodes/' + episode_number + '?api_key=' + apikey + '&language=en-US&append_to_response=undefined';
                        let actors = '';
                        let director = '';
                        $.ajax({
                            url: getEpisodeDetails,
                            method: 'GET',
                            success: function(response){
								updatePoster(episode_id, 'https://image.tmdb.org/t/p/w500' + response.still_path);
                            },
                            error: function(response){
                                $('#myModal').modal('show');
                                setTimeout(
                                    function(){
                                        $('#myModal').modal('hide')
                                    }, 
                                    3000
                                );
                            }

                        })
                    },
                    error: function(response){
                        $('#myModal').modal('show');
                        setTimeout(
                            function(){
                                $('#myModal').modal('hide')
                            }, 
                            3000
                        );
                    }
                })
            }

			function updatePoster(episode_id, image_link) {
				$.ajax({
                    url: '/dashboard/episodes/' + episode_id + '/update-poster',
                    method: 'GET',
					data: {
						image_link: image_link
					},
                    success: function(response){
                        $('.episode' + episode_id + ' img').attr('src', image_link);
                    }
                })
			}
		</script>

	@endsection

@endsection

