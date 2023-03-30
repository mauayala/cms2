@extends('layout.default')

@section('css')
	<link rel='stylesheet' href='/admin/css/sweetalert.css'>
@endsection

@section('content')
	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-8">
				<h3><i class="entypo-season"></i> Seasons</h3>
				<a href="{{ route('dashboard.series.seasons.create', ['series' => $series->id]) }}" class="btn btn-success">
					<i class="fa fa-plus-circle"></i> Add Season
				</a>
				<a href="#" class="btn btn-success" data-toggle="modal" data-target="#add_season">
					<i class="fa fa-plus-circle"></i> Add Season with Episodes
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
		<div class="row">		
			<div class="col-md-12">
				<h3>{{ $series->title }}</h3>
			</div>
			@foreach($seasons as $season)			
				<div class="video-box pull-left">
					<article class="album">
						<header>
							<a href="{{ route('dashboard.series.seasons.episodes.index', ['series' => $series->id, 'season' => $season->id]) }}" class="season{{$season->id}}">
								<img src="https://s3.amazonaws.com/ctv3/{{ $season->poster }}" />
							</a>
							<button class="album-options" style="margin-right: 60px;" onclick="getPosters('{{$series->title}}', '{{$season->season_number}}', {{$season->id}})">
								Update Poster
							</button>
							<a href="{{ route('dashboard.series.seasons.edit', ['series' => $series->id, 'season' => $season->id]) }}" class="album-options">
								<i class="entypo-pencil"></i>
								Edit
							</a>
						</header>
						
						<section class="album-info text-center">
							<a href="{{ route('dashboard.series.seasons.episodes.index', ['series' => $series->id, 'season' => $season->id]) }}">
								<h3>Season {{ $season->season_number }} {{ $season->released_at }}</h3>
							</a>
						</section>
						
						<footer>
							<div class="album-images-count">
								@if($season->hasErrors())
									<span class="red-circle"></span>
								@endif
								@if(isset($season->category->name))
									{{ $season->category->name }}
									<br/>
								@endif
								{{$season->season_views()->count()}} VIEWS
							</div>
							<div class="album-options">
								<a href="{{ route('dashboard.series.seasons.edit', ['series' => $series->id, 'season' => $season->id]) }}">
									<i class="entypo-pencil"></i>
								</a>
								<form action="{{ route('dashboard.series.seasons.destroy', ['series' => $series->id, 'season' => $season->id]) }}" method="post">
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
		</div>
	</div>

	<div class="modal fade" id="add_season" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Season with Episodes</h4>
			</div>
			<div class="modal-body">
				<div class='form-group'>
					<input type='text' id='season_number' placeholder='Season number' class='form-control'>
				</div>
				<div class='form-group'>
					<select id='select_format' class='form-control'>
						<option value='mp4'>mp4</option>
						<option value='mkv'>mkv</option>
						<option value='mov'>mov</option>
					</select>
				</div>
				<div class='form-group'>
					<label for='en'>Audio EN:</label>
					<input type='checkbox' id='en' value='1'>
					<label for='es'>Audio ES:</label>
					<input type='checkbox' id='es' value='1'>
				</div>
				<div class='form-group'>
					<label for='subtitle_en'>Subtitle EN:</label>
					<input type='checkbox' id='subtitle_en' value='1'>
					<label for='subtitle_es'>Subtitle ES:</label>
					<input type='checkbox' id='subtitle_es' value='1'>
				</div>
				<img src='/ajax-load.gif' class='loading'>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary add-season">Save changes</button>
			</div>
			</div>
		</div>
	</div>

	@section('javascript')
		<script src='/admin/js/sweetalert.min.js'></script>
		<script>
			$(document).ready(function(){
				$('.loading').hide();

				$('.delete').click(function(e){
					e.preventDefault();
					var t = this
					swal({   title: "Are you sure?",   text: "Do you want to permanantly delete this season?",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   closeOnConfirm: false }, function(){ $(t).closest('form').submit() });
					return false;
				});
			});

			var title = "<?=$series->title?>";

			var imdb_rating = '';
			var released_at = '';
			var image_link = '';
			var actors = '';
			var season_number = '';

			var episode_count = '';
			var id = '';

			var episode_actors = '';
			var episode_director = '';
			var episode_title = '';
			var episode_plot = '';
			var episode_imdb_rating = '';
			var episode_image_link = '';
			var episode_released_at = '';
			var serie_file_name = '';
			var subtitle_file_name_es = '';
			var subtitle_file_name = '';
			var episode_en = 0;
			var episode_es = 0;

			var subtitle_en = 0;
			var subtitle_es = 0;

            const apikey = '5fabc059c6c919ad8fa7014c1c844cf0';
            $('.add-season').click(function(e){                
				$('.loading').show();

				if($("#en").prop("checked")) {
					episode_en = 1;
				}
				if($("#es").prop("checked")) {
					episode_es = 1;
				}

				if($("#subtitle_en").prop("checked")) {
					subtitle_en = 1;
				}
				if($("#subtitle_es").prop("checked")) {
					subtitle_es = 1;
				}

				season_number = $('#season_number').val();
                const searchByTitle = 'https://api.themoviedb.org/3/search/tv?api_key=' + apikey + '&language=en-US&query=' + title

				setTimeout(function(){
					$.ajax({
						url: searchByTitle,
						method: 'GET',
						async: false,
						success: function(response){
							response = response.results[0];
							id = response.id;
							getSeasonDetails(response.id, season_number);
							getSeasonActors(response.id, season_number);
							imdb_rating = response.vote_average;						
						}
					})
					save_season()
				}, 1000);
			})

            function getSeasonDetails(id, season_number){
                const getSeasonDetails = 'https://api.themoviedb.org/3/tv/' + id + '/season/' + season_number +'?api_key=' + apikey + '&language=en-US';
                $.ajax({
                    url: getSeasonDetails,
                    method: 'GET',
					async: false,
                    success: function(response){
                        released_at = response.air_date;
                        image_link = 'https://image.tmdb.org/t/p/w500' + response.poster_path;
						episode_count = response.episodes.length
                    }
				})
            }

            function getSeasonActors(id, season_number){
                const getSeasonActors = 'https://api.themoviedb.org/3/tv/' + id + '/season/' + season_number +'/credits?api_key=' + apikey + '&language=en-US';
                $.ajax({
                    url: getSeasonActors,
                    method: 'GET',
					async: false,
                    success: function(response){
                        response.cast.forEach(function(actor){
							if(actors.length < 250){
								actors += actor.name + ', '
							}                            
                        })
                    }
                })
            }

			function save_season(){
                $.ajax({
					method: 'POST',
					url: "/dashboard/series/{{$series->id}}/seasons",   
					async: false,                 
					data: {
						imdb_rating: imdb_rating,
						released_at: released_at,
						image_link: image_link,
						actors: actors,
						user_id: <?=\Auth::user()->id?>,
						rating: '<?=$series->rating?>',
						runtime: '<?=$series->runtime?>',
						hd: 1,
						season_number: season_number,
						is_ajax: 1,
						_token: '<?=csrf_token()?>'
					},
                    success: function(response){
						for(var i = 1; i <= episode_count; i++){
							s_number = season_number;
							e_number = i;
							if(season_number < 10){
                                s_number = '0' + season_number
                            }
                            if(i < 10){
                                e_number = '0' + i
                            }
							var changed_title = title.toLowerCase().split(' ').join('.').split(':').join('.').split('-').join('.').split('?').join('.') + '.s' + s_number + 'e' + e_number

							changed_title = changed_title.replace('ñ', 'n');
							serie_file_name = changed_title + '.' + $('#select_format').val();
							if(subtitle_es == 1) {
								subtitle_file_name_es = changed_title + '.es.srt';
							}
							if(subtitle_en == 1) {
								subtitle_file_name = changed_title + '.en.srt';
							}

							add_episode(i, response.id);
						}
						$('.loading').hide()
                    }
                })
            }

			function add_episode(episode_number, season_id){
                const getEpisodeDetails = 'https://api.themoviedb.org/3/tv/' + id + '/season/' + season_number + '/episode/' + episode_number + '?api_key=' + apikey + '&language=en-US&append_to_response=undefined';
                $.ajax({
                    url: getEpisodeDetails,
                    method: 'GET',
					async:false,
                    success: function(response){
                        episode_title = response.name;
                        episode_plot = response.overview;
						var d = '';
                        response.crew.forEach(function(item){
                            if(item.job == 'Director'){
                                d += item.name + ', ';
                            }
                        })
						episode_director = d;
                        episode_imdb_rating = response.vote_average;
                        episode_image_link = 'https://image.tmdb.org/t/p/w500' + response.still_path;
                        episode_released_at = response.air_date;
						save_episode(season_id, episode_number)
                    }
                })
			}

			function save_episode(season_id, episode_number){
                $.ajax({
					method: 'POST',
					url: "/dashboard/series/{{$series->id}}/seasons/"+season_id+"/episodes",   
					async: false,                 
					data: {
						actors: actors,
						director: episode_director,
						title: episode_title,
						plot: episode_plot,
						imdb_rating: episode_imdb_rating,
						image_link: episode_image_link,
						released_at: episode_released_at,
						user_id: <?=\Auth::user()->id?>,
						hd: 1,
						runtime: '<?=$series->runtime?>',
						rating: '<?=$series->rating?>',
						serie_file_name: serie_file_name,
						subtitle_file_name_es: subtitle_file_name_es,
						subtitle_file_name: subtitle_file_name,
						episode_number: episode_number,
						en: episode_en,
						es: episode_es,
						_token: '<?=csrf_token()?>'
					},
                    success: function(response){
						location.reload()
                    }
                })
            }

			function getPosters(title, season_number, season_id) {
				const searchByTitle = 'https://api.themoviedb.org/3/search/tv?api_key=' + apikey + '&language=en-US&query=' + title
                $.ajax({
                    url: searchByTitle,
                    method: 'GET',
                    success: function(response){
						response = response.results[0];
                        const getSeasonDetails = 'https://api.themoviedb.org/3/tv/' + response.id + '/season/' + season_number +'?api_key=' + apikey + '&language=en-US';
						$.ajax({
							url: getSeasonDetails,
							method: 'GET',
							success: function(response){
								updatePoster(season_id, 'https://image.tmdb.org/t/p/w500' + response.poster_path)
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

			function updatePoster(season_id, image_link) {
				$.ajax({
                    url: '/dashboard/seasons/' + season_id + '/update-poster',
                    method: 'GET',
					data: {
						image_link: image_link
					},
                    success: function(response){
                        $('.season' + season_id + ' img').attr('src', image_link);
                    }
                })
			}
		</script>

	@endsection

@endsection

