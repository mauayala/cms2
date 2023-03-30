@extends('layout.default')

@section('css')
    <link rel="stylesheet" href="/assets/js/tagsinput/jquery.tagsinput.css" />
@endsection


@section('content')

    <div id="admin-container">
        <!-- This is where -->

        <div class="admin-section-title">
            <h3>{{ $season->title }}</h3>
            <a href="{{ url('season') . '/' . $season->id }}" target="_blank" class="btn btn-info">
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
                        <label for="season_number">Season:</label>
                        <input type="text" class="form-control" name="season_number" id="season_number" placeholder="Season number" value="{{ $season->season_number }}" />
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Search" class="btn btn-primary">
                        <input type="reset" value="Reset" class="btn">
                    </div>
                </form>
            </div>
        </div>

        <form method="POST" action="{{ route('dashboard.series.seasons.update', ['series' => $series->id, 'season' => $season->id]) }}" accept-charset="UTF-8" file="1" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading">
                <div class="panel-title">Description</div>
                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    <div class="col-md-4" style="padding-left: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="actors" id="actors" placeholder="Actors" value="{{ $season->actors }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" name="released_at" id="released_at" placeholder="Release Date" value="{{ $season->released_at }}" />
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-right: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="imdb_rating" id="imdb_rating" placeholder="IMDB Rating" value="{{ $season->imdb_rating }}" />
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-left: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="runtime" id="runtime" placeholder="Runtime (in seconds)" value="{{ $series->runtime }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control" id="rating" name="rating">
                                <option value="NR" @if($series->rating == 'NR')selected="selected"@endif>NR</option>
                                <option value="TV-Y" @if($series->rating == 'TV-Y')selected="selected"@endif>TV-Y</option>
                                <option value="TV-Y7" @if($series->rating == 'TV-Y7')selected="selected"@endif>TV-Y7</option>
                                <option value="TV-G" @if($series->rating == 'TV-G')selected="selected"@endif>TV-G</option>
                                <option value="TV-PG" @if($series->rating == 'TV-PG')selected="selected"@endif>TV-PG</option>
                                <option value="TV-14" @if($series->rating == 'TV-14')selected="selected"@endif>TV-14</option>
                                <option value="TV-MA" @if($series->rating == 'TV-MA')selected="selected"@endif>TV-MA</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading">
                <div class="panel-title">Season Poster</div>
                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    <img src="https://s3.amazonaws.com/ctv3/{{ $season->poster }}"  class="video-img" width="214" height="306"/>
                    <p>Select the season image (1280x720 px or 16:9 ratio):</p>
                    <input type="file" multiple="true" class="form-control" name="poster" id="image" />
                    <input type="text" name="image_link" id="image_link" hidden="hidden">
                </div>
            </div>

            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading"> <div class="panel-title"> Status Settings</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div>
                <div class="panel-body">
                    <div>
                        <label for="hd" style="float:left; display:block; margin-right:10px;">Is this season HD:</label>
                        <input type="checkbox" @if($season->hd == 1) checked="checked" @endif name="hd" value="1" id="hd" />
                    </div>
                </div>
            </div>
            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading"> <div class="panel-title"> Created and updated</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div>
                <div class="panel-body">
                    <div>
                        Created by: <strong>{{ $season->user->username }}</strong> on <strong>{{ $season->created_at }}</strong>
                    </div>
                    <div class="clear"></div>
                    @if(isset($season->editor->username))
                        <div>
                            Updated by: <strong>{{ $season->editor->username }}</strong> on <strong>{{ $season->updated_at }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <input type="hidden" name="season_number" id="season_number1" value="" />
            <input type="submit" value="Update Season" class="btn btn-success pull-right" />

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
            $ = jQuery;
            const ratings = <?=json_encode([['name' => 'NR'], ['name' => 'TV-Y'], ['name' => 'TV-Y7'], ['name' => 'TV-G'], ['name' => 'TV-PG'], ['name' => 'TV-14'], ['name' => 'TV-MA']])?>;
            const apikey = '5fabc059c6c919ad8fa7014c1c844cf0';
            $('#omdb-search').submit(function(e){
                e.preventDefault();
                const title = $('#omdb_title').val();
                const season_number = $('#season_number').val();
                const searchByTitle = 'https://api.themoviedb.org/3/search/tv?api_key=' + apikey + '&language=en-US&query=' + title + '&first_air_date_year={{date("Y", strtotime($series->released_at))}}'

                $('#season_number1').val(season_number);
                $.ajax({
                    url: searchByTitle,
                    method: 'GET',
                    success: function(response){
                        response = response.results[0]
                        getSeasonDetails(response.id, season_number);
                        getSeasonActors(response.id, season_number);
                        $('#imdb_rating').val(response.vote_average);
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

            function getSeasonDetails(id, season_number){
                const getSeasonDetails = 'https://api.themoviedb.org/3/tv/' + id + '/seasons/' + season_number +'?api_key=' + apikey + '&language=en-US';
                $.ajax({
                    url: getSeasonDetails,
                    method: 'GET',
                    success: function(response){
                        console.log(response)
                        $('#released_at').val(response.air_date);
                        $('.video-img').attr('src', 'https://image.tmdb.org/t/p/w500' + response.poster_path);
                        $('#image_link').val('https://image.tmdb.org/t/p/w500' + response.poster_path);
                        //$('#actors').val(response.Actors);
                        // $('#director').val(response.created_by[0].name);
                        // $('#runtime').val(response.episode_run_time);
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

            function getSeasonActors(id, season_number){
                const getSeasonActors = 'https://api.themoviedb.org/3/tv/' + id + '/seasons/' + season_number +'/credits?api_key=' + apikey + '&language=en-US';
                $.ajax({
                    url: getSeasonActors,
                    method: 'GET',
                    success: function(response){
                        let actors = ''
                        response.cast.forEach(function(actor){
                            actors += actor.name + ', '
                        })
                        $('#actors').val(actors);
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
        </script>
    @endsection
@endsection
