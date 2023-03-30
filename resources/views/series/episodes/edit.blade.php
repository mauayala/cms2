@extends('layout.default')

@section('css')
    <link rel="stylesheet" href="/assets/js/tagsinput/jquery.tagsinput.css" />
@endsection


@section('content')

    <div id="admin-container">
        <!-- This is where -->

        <div class="admin-section-title">
            <h3>{{ $episode->title }}</h3>
            <a href="{{ url('episode') . '/' . $episode->id }}" target="_blank" class="btn btn-info">
                <i class="fa fa-eye"></i> Preview <i class="fa fa-external-link"></i>
            </a>
        </div>
        <div class="clear"></div>

        <div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading">
            <div class="panel-title">By Title</div>
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            <div class="panel-body" style="display: block;">
                <form class="form-inline" id="omdb-search">
                    <div class="form-group">
                        <label for="omdb_title">Title:</label>
                        <input type="text" class="form-control" name="omdb_title" id="omdb_title" placeholder="Title" value="{{$series->title}}" />
                    </div>
                    <div class="form-group">
                        <label for="season_number">Season number:</label>
                        <input type="text" class="form-control" name="season_number" id="season_number" placeholder="Season number" value="{{$season->season_number}}" />
                    </div>
                    <div class="form-group">
                        <label for="episode_number">Episode number:</label>
                        <input type="text" class="form-control" name="episode_number" id="episode_number" placeholder="Episode number" value="{{ $episode->episode_number }}"/>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Search" class="btn btn-primary">
                        <button type="button" onclick="getPosters()" class="btn btn-warning">Get Posters Only</button>
                        <input type="reset" value="Reset" class="btn">
                    </div>
                </form>
            </div>
        </div>

        <form method="POST" action="{{ route('dashboard.series.seasons.episodes.update', ['series' => $series->id, 'season' => $season->id, 'episode' => $episode->id]) }}" accept-charset="UTF-8" file="1" enctype="multipart/form-data">
            @csrf
            <div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading">
                <div class="panel-title">Description</div>
                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    <div class="form-group">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Episode Title" value="{{ $episode->title }}" />
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="plot" id="plot" placeholder="Plot">{{ htmlspecialchars($episode->plot) }}</textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="actors" id="actors" placeholder="Actors" value="{{$actors}}" />
                    </div>
                    <div class="col-md-4" style="padding-left: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="director" id="director" placeholder="Director" value="{{ $episode->director }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" name="released_at" id="released_at" placeholder="Release Date" value="{{ $episode->released_at }}" />
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-right: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="imdb_rating" id="imdb_rating" placeholder="IMDB Rating" value="{{ $episode->imdb_rating }}" />
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-left: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="runtime" id="runtime" placeholder="Runtime (in seconds)" value="{{ $series->runtime/60 }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control" id="rating" name="rating">
                                <option value="NR" @if(!empty($series->rating) && $series->rating == 'NR')selected="selected"@endif>NR</option>
                                <option value="TV-Y" @if(!empty($series->rating) && $series->rating == 'TV-Y')selected="selected"@endif>TV-Y</option>
                                <option value="TV-Y7" @if(!empty($series->rating) && $series->rating == 'TV-Y7')selected="selected"@endif>TV-Y7</option>
                                <option value="TV-G" @if(!empty($series->rating) && $series->rating == 'TV-G')selected="selected"@endif>TV-G</option>
                                <option value="TV-PG" @if(!empty($series->rating) && $series->rating == 'TV-PG')selected="selected"@endif>TV-PG</option>
                                <option value="TV-14" @if(!empty($series->rating) && $series->rating == 'TV-14')selected="selected"@endif>TV-14</option>
                                <option value="TV-MA" @if(!empty($series->rating) && $series->rating == 'TV-MA')selected="selected"@endif>TV-MA</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-right: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="season_episode" id="season_episode" placeholder="Season and episode numbers" value="{{ $episode->season_episode }}" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading">
                <div class="panel-title">Episode Poster</div>
                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    <img src="https://s3.amazonaws.com/ctv3/{{ $episode->poster }}"  class="video-img" width="200"/>
                    <p>Select the episode image (1280x720 px or 16:9 ratio):</p>
                    <input type="file" multiple="true" class="form-control" name="image" id="image" />
                    <input type="text" name="image_link" id="image_link" hidden="hidden">
                </div>
            </div>

            <div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading">
                <div class="panel-title">Episode Source</div>
                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    <label for="type" style="float:left; margin-right:10px; padding-top:1px;">Episode Format</label>
                    <select id="type" name="type">
                        <option value="vod" @if($episode->hls_link == '') selected='selected' @endif>VOD</option>
                        <option value="hls" @if($episode->hls_link != '') selected='selected' @endif>HLS</option>
                    </select>
                    <div class="vod" @if(!empty($episode->hls_link) && empty($episode->serie_file_name))style="display: none;"@endif>
                        <div class="form-group">
                            <input type="text" required onfocusout="checkLink('serie_file_name')" class="form-control" name="serie_file_name" id="serie_file_name" placeholder="Serie File Name" value="@if(!empty($episode->serie_file_name)){{ $episode->serie_file_name }}@endif" />
                        </div>
                        <div class="form-group">
                            <input type="text" required onfocusout="checkLink('subtitle_file_name')" class="form-control" name="subtitle_file_name" id="subtitle_file_name" placeholder="Subtitle File Name English" value="@if(!empty($episode->subtitle_file_name)){{ $episode->subtitle_file_name }}@endif" />
                        </div>
                        <div class="form-group">
                            <input type="text" required onfocusout="checkLink('subtitle_file_name_es')" class="form-control" name="subtitle_file_name_es" id="subtitle_file_name_es" placeholder="Subtitle File Name Spain" value="@if(!empty($episode->subtitle_file_name_es)){{ $episode->subtitle_file_name_es }}@endif" />
                        </div>
                    </div>
                    <div class="hls" @if(empty($episode->hls_link))style="display: none;"@endif>
                        <div class="form-group">
                            <input type="text" class="form-control" name="hls_link" id="hls_link" placeholder="HLS link" value="@if(!empty($episode->hls_link)){{ $episode->hls_link }}@endif" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading"> <div class="panel-title"> Status Settings</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div>
                <div class="panel-body">
                    <div>
                        <label for="en" style="float:left; display:block; margin-right:10px;">Audio EN:</label>
                        <input type="checkbox" @if($episode->en == 1) checked="checked" @endif name="en" value="1" id="en" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="es" style="float:left; display:block; margin-right:10px;">Audio ES:</label>
                        <input type="checkbox" @if($episode->es == 1) checked="checked" @endif name="es" value="1" id="es" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="featured" style="float:left; display:block; margin-right:10px;">Is this episode Premiered:</label>
                        <input type="checkbox" @if($episode->featured == 1) checked="checked" @endif name="featured" value="1" id="featured" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="active" style="float:left; display:block; margin-right:10px;">Is this episode Active:</label>
                        <input type="checkbox" @if($episode->active == 1) checked="checked" @endif name="active" value="1" id="active" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="hd" style="float:left; display:block; margin-right:10px;">Is this episode HD:</label>
                        <input type="checkbox" @if($episode->hd == 1) checked="checked" @endif name="hd" value="1" id="hd" />
                    </div>
                </div>
            </div>
            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading"> <div class="panel-title"> Created and updated</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div>
                <div class="panel-body">
                    <div>
                        Created by: <strong>{{ $episode->user?->username }}</strong> on <strong>{{ $episode->created_at }}</strong>
                    </div>
                    <div class="clear"></div>
                    @if(isset($episode->editor->username))
                        <div>
                            Updated by: <strong>{{ $episode->editor->username }}</strong> on <strong>{{ $episode->updated_at }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <input type="hidden" name="episode_number" id="episode_number1" value="{{ $episode->episode_number }}" />
            <input type="submit" value="Update Episode" class="btn btn-success pull-right" />

        </form>

        <div class="clear"></div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Error</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" role="alert">Error, nothing found</div>
                    </div>
                </div>
            </div>
        </div>
    <!-- This is where now -->
    </div>
    
    @section('javascript')
        <script type="text/javascript">
            var link_works = {
                serie_file_name: 1,
                subtitle_file_name: 1,
                subtitle_file_name_es: 1,
            };
            $ = jQuery;
            const apikey = '5fabc059c6c919ad8fa7014c1c844cf0';
            $(document).ready(function(){
                $('#type').on('change', function(){
                    if($('#type').find(':selected').text() == 'VOD'){
                        $('.vod').show();
                        $('.hls').hide();
                    } else {
                        $('.vod').hide();
                        $('.hls').show();
                    }
                });
            });
            const ratings = <?=json_encode([['name' => 'NR'], ['name' => 'TV-Y'], ['name' => 'TV-Y7'], ['name' => 'TV-G'], ['name' => 'TV-PG'], ['name' => 'TV-14'], ['name' => 'TV-MA']])?>;
            $('#omdb-search').submit(function(e){
                e.preventDefault();
                const title = $('#omdb_title').val();
                let season_number = $('#season_number').val();
                let episode_number = $('#episode_number').val();
                const searchByTitle = 'https://api.themoviedb.org/3/search/tv?api_key=' + apikey + '&language=en-US&query=' + title;
                $('#episode_number1').val(episode_number);
                $.ajax({
                    url: searchByTitle,
                    method: 'GET',
                    success: function(response){
                        response = response.results[0]
                        getEpisodeDetails(response.id, season_number, episode_number);
                        
                        if($('#type').find(':selected').text() == 'VOD'){
                            if(season_number < 10){
                                season_number = '0' + season_number
                            }
                            if(episode_number < 10){
                                episode_number = '0' + episode_number
                            }
                            let title = response.name.toLowerCase().split(' ').join('.').split(':').join('.').split('-').join('.').split("'").join('') + '.s' + season_number + 'e' + episode_number;

                            $('#serie_file_name').val(title + '.mp4');
                            $('#subtitle_file_name').val(title + '.en.srt');
                            $('#subtitle_file_name_es').val(title + '.es.srt');

                            checkLink('serie_file_name');
                            checkLink('subtitle_file_name');
                            checkLink('subtitle_file_name_es');

                            $('#season_episode').val('S'+season_number+'E'+episode_number)
                        }
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
                
            })
            
            function getPosters() {
                const title = $('#omdb_title').val();
                let season_number = $('#season_number').val();
                let episode_number = $('#episode_number').val();
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
                                $('.video-img').attr('src', 'https://image.tmdb.org/t/p/w500' + response.still_path);
                                $('#image_link').val('https://image.tmdb.org/t/p/w500' + response.still_path);
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

            function getEpisodeDetails(id, season_number,episode_number){
                const getEpisodeDetails = 'https://api.themoviedb.org/3/tv/' + id + '/seasons/' + season_number + '/episodes/' + episode_number + '?api_key=' + apikey + '&language=en-US&append_to_response=undefined';
                let actors = '';
                let director = '';
                $.ajax({
                    url: getEpisodeDetails,
                    method: 'GET',
                    success: function(response){
                        $('#title').val(response.name);
                        $('#plot').val(response.overview);
                        response.crew.forEach(function(item){
                            if(item.job == 'Director'){
                                director += item.name + ', ';
                            }
                        })
                        $('#director').val(director);
                        $('#imdb_rating').val(response.vote_average);
                        $('.video-img').attr('src', 'https://image.tmdb.org/t/p/w500' + response.still_path);
                        $('#image_link').val('https://image.tmdb.org/t/p/w500' + response.still_path);
                        $('#released_at').val(response.air_date);
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

            function getSerieRating(id){
                const getSerieRating = 'https://api.themoviedb.org/3/tv/' + id + '/content_ratings?api_key=' + apikey + '&language=en-US';
                $.ajax({
                    url: getSerieRating,
                    method: 'GET',
                    success: function(response){
                        const rating = response.results[3].rating;
                        ratings.forEach(function(item){
                            if(item.name == rating){
                                $('#rating').val(item.name);
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

            function checkLink(type) {
                if(type == 'serie_file_name') {
                    link_works.serie_file_name = 2;
                } else if(type == 'subtitle_file_name') {
                    link_works.subtitle_file_name = 2;
                } else if(type == 'subtitle_file_name_es') {
                    link_works.subtitle_file_name_es = 2;
                }
                
                $('input.btn-success').attr('disabled', true);
                $('input.btn-success').val('Checking link...');
                $.ajax({
                    url: '/dashboard/videos/check-link?type=' + type + '&value=' + $('#' + type).val(),
                    method: 'GET',
                    success: function(response){
                        if(response == 1) {
                            if(type == 'serie_file_name') {
                                link_works.serie_file_name = 1;
                            } else if(type == 'subtitle_file_name') {
                                link_works.subtitle_file_name = 1;
                            } else if(type == 'subtitle_file_name_es') {
                                link_works.subtitle_file_name_es = 1;
                            }
                            if(link_works.serie_file_name == 1 && link_works.subtitle_file_name == 1 && link_works.subtitle_file_name_es == 1) {
                                $('input.btn-success').attr('disabled', false);
                                $('input.btn-success').val('Add New Movie');
                            } else if(link_works.serie_file_name == 0 || link_works.subtitle_file_name == 0 || link_works.subtitle_file_name_es == 0) {
                                $('input.btn-success').val('Link(s) does not work!');
                            }
                            $('#' + type).parent().removeClass('has-error');
                        } else {
                            $('input.btn-success').val('Link(s) does not work!');
                            $('#' + type).parent().addClass('has-error');
                            if(type == 'serie_file_name') {
                                link_works.serie_file_name = 0;
                            } else if(type == 'subtitle_file_name') {
                                link_works.subtitle_file_name = 0;
                            } else if(type == 'subtitle_file_name_es') {
                                link_works.subtitle_file_name_es = 0;
                            }
                        }
                    }
                })
            }
        </script>
    @endsection
@endsection
