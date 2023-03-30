@extends('layout.default')

@section('css')
    <link rel="stylesheet" href="/assets/js/tagsinput/jquery.tagsinput.css" />
@endsection


@section('content')

    <div id="admin-container">
        <!-- This is where -->

        <div class="admin-section-title">
            <h3><i class="entypo-plus"></i> Add New Serie</h3>
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
                        <input type="text" class="form-control" name="omdb_title" id="omdb_title" placeholder="Title" />
                    </div>
                    <div class="form-group">
                        <label for="year">Year:</label>
                        <input type="text" class="form-control" name="year" id="year" placeholder="Year"/>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Search" class="btn btn-primary">
                        <input type="reset" value="Reset" class="btn">
                    </div>
                </form>

                <div class="result"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('dashboard.series.store') }}" accept-charset="UTF-8" file="1" enctype="multipart/form-data">
            @csrf
            <div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading">
                <div class="panel-title">Description</div>
                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    <div class="form-group">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Serie Title" value="" />
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="title_es" id="title_es" placeholder="Serie Title Spanish" value="" />
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="plot" id="plot" placeholder="Plot"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="actors" id="actors" placeholder="Actors" value="" />
                    </div>
                    <div class="col-md-4" style="padding-left: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="director" id="director" placeholder="Director" value="" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" name="released_at" id="released_at" placeholder="Release Date" value="" />
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-right: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="imdb_rating" id="imdb_rating" placeholder="IMDB Rating" value="" />
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-left: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="runtime" id="runtime" placeholder="Runtime (in seconds)" value="" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control" id="serie_category_id" name="serie_category_id" required="">
                                <option value=""></option>
                                @foreach($serie_categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-right: 0;">
                        <div class="form-group">
                            <select class="form-control" id="rating" name="rating">
                                <option value="NR">NR</option>
                                <option value="TV-Y">TV-Y</option>
                                <option value="TV-Y7">TV-Y7</option>
                                <option value="TV-G">TV-G</option>
                                <option value="TV-PG">TV-PG</option>
                                <option value="TV-14">TV-14</option>
                                <option value="TV-MA">TV-MA</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <input type="text" class="form-control" name="trailer" id="trailer" placeholder="Trailer File Name" value="@if(!empty($video->trailer)){{ $video->trailer }}@endif" />
                    </div>                    
                </div>
            </div>

            <div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading">
                <div class="panel-title">Serie Poster</div>
                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    <div class="col-md-6">
                        <img src="" class="video-img" width="214" height="306"/>
                        <p>Select the serie image (1280x720 px or 16:9 ratio):</p>
                        <input type="file" multiple="true" class="form-control" name="image" id="image" />
                        <input type="text" name="image_link" id="image_link" hidden="hidden">
                    </div>
                    <div class="col-md-6">
                        <img src="" class="backdrop-img" width="200"/>
                        <p>Select the serie backdrop image (1280x720 px or 16:9 ratio):</p>
                        <input type="file" multiple="true" class="form-control" name="backdrop" id="backdrop" />
                        <input type="text" name="backdrop_link" id="backdrop_link" hidden="hidden">
                    </div>
                    <div class="col-md-6">
                        <img src="" class="backdrop-img" width="200"/>
                        <p>Select the serie featured backdrop image (1280x720 px or 16:9 ratio):</p>
                        <input type="file" multiple="true" class="form-control" name="featured_backdrop" id="featured_backdrop" />
                    </div> 
                </div>
            </div>

            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading"> <div class="panel-title"> Status Settings</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div>
                <div class="panel-body">
                    <div>
                        <label for="active" style="float:left; display:block; margin-right:10px;">Is this serie Active:</label>
                        <input type="checkbox" checked="checked" name="active" value="1" id="active" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="hd" style="float:left; display:block; margin-right:10px;">Is this serie HD:</label>
                        <input type="checkbox" checked="checked" name="hd" value="1" id="hd" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="multiseasoned" style="float:left; display:block; margin-right:10px;">Is this serie multiseasoned:</label>
                        <input type="checkbox" name="multiseasoned" value="1" id="multiseasoned" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="script_check" style="float:left; display:block; margin-right:10px;">Is this serie enabled to be checked:</label>
                        <input type="checkbox" name="script_check" value="1" id="script_check" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="series" style="float:left; display:block; margin-right:10px;">Kids Zone - Series:</label>
                        <input type="radio" name="kids_zone" value="1" id="series" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="toddlers" style="float:left; display:block; margin-right:10px;">Kids Zone - Toddlers:</label>
                        <input type="radio" name="kids_zone" value="2" id="toddlers" />
                    </div>
                </div>
            </div>

            <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}" />
            <input type="submit" value="Add New Serie" class="btn btn-success pull-right" />

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
        <!-- Add duplicated video/serie -->
        <div class="modal fade" id="duplicateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">This serie is already uploaded on CloudTV, would you like to upload it duplicate anyway?</h4>
                    </div>
                    <div class="modal-body">
                        <button id='duplicate_yes' class='btn btn-success'>Yes</button>
                        <button id='duplicate_no' class='btn btn-danger'>No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @section('javascript')
        <script type="text/javascript">
            $ = jQuery;

            const categories = <?=json_encode($serie_categories)?>;
            const ratings = <?=json_encode([['name' => 'NR'], ['name' => 'TV-Y'], ['name' => 'TV-Y7'], ['name' => 'TV-G'], ['name' => 'TV-PG'], ['name' => 'TV-14'], ['name' => 'TV-MA']])?>;
            const apikey = '5fabc059c6c919ad8fa7014c1c844cf0';
            
            $('#duplicate_yes').click(function(){
                $('#duplicateModal').modal('hide');
            })
            $('#duplicate_no').click(function(){
                location.reload();
            })

            var search_result;

            $('#omdb-search').submit(function(e){
                e.preventDefault();
                const title = $('#omdb_title').val();

                $.ajax({
                    url: '/dashboard/series/duplicate/'+title,
                    method: 'GET',
                    success: function(response){
                        if(response.result){
                            $('#duplicateModal').modal('show');
                        }
                    }
                })

                let year = $('#year').val();
                var searchByTitle = '';
                if(year > 0){
                    searchByTitle = 'https://api.themoviedb.org/3/search/tv?api_key=' + apikey + '&language=en-US&query=' + title + '&first_air_date_year=' + year;
                } else {
                    searchByTitle = 'https://api.themoviedb.org/3/search/tv?api_key=' + apikey + '&language=en-US&query=' + title;
                }

                $.ajax({
                    url: searchByTitle,
                    method: 'GET',
                    success: function(response){
                        console.log(response)

                        if(response.results.length > 1) {
                            response.results.forEach((element, index) => {
                                $('.result').append('<div onclick="fillForm('+index+')">'+element.name+' (' + element.first_air_date+ ')</div>')
                            })
                        }
                        search_result = response.results

                        response = response.results[0]
                        getSpanishTitle(response.id);
                        getSerieDetails(response.id);
                        getSerieRating(response.id);
                        getSerieActors(response.id);
                        
                        $('#title').val(response.name);
                        $('#plot').val(response.overview);
                        $('#released_at').val(response.first_air_date);
                        $('#imdb_rating').val(response.vote_average);
                        $('.video-img').attr('src', 'https://image.tmdb.org/t/p/w500' + response.poster_path);
                        $('#image_link').val('https://image.tmdb.org/t/p/w500' + response.poster_path);
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

            function fillForm(index) {
                var response = search_result[index]
                console.log(response)
                getSpanishTitle(response.id);
                getSerieDetails(response.id);
                getSerieRating(response.id);
                getSerieActors(response.id);
                
                $('#title').val(response.name);
                $('#plot').val(response.overview);
                $('#released_at').val(response.first_air_date);
                $('#imdb_rating').val(response.vote_average);
                $('.video-img').attr('src', 'https://image.tmdb.org/t/p/w500' + response.poster_path);
                $('#image_link').val('https://image.tmdb.org/t/p/w500' + response.poster_path);
            }

			function getSerieDetails(id){
				const getSerieDetails = 'https://api.themoviedb.org/3/tv/' + id + '?api_key=' + apikey + '&language=en-US&append_to_response=undefined';
				$.ajax({
                    url: getSerieDetails,
                    method: 'GET',
                    success: function(response){             
                        if(response.number_of_seasons > 0){
                            $('#multiseasoned').attr('checked', true)
                        }
                        const category_es = [
                            ['Action & Adventure', 'Accion & Aventura'], 
                            ['Animation', 'Animacion'],
                            ['Comedy', 'Comedia'],
                            ['Documentary', 'Documentales'],
                            ['Family', 'Familia'],
                            ['Kids', 'Kids Zone'],
                            ['War & Politics', 'Guerra & Politica'],
                            // ['Adventure', 'Aventura'],
                            ['Crime', 'Crimen'],
                            ['Drama', 'Drama'],
                            ['Mystery', 'Misterio'],
                            ['News', 'Noticias'],
                            ['Reality', 'Reality TV'],
                            ['Sci-Fi & Fantasy', 'Fantasia & Ciencia Ficcion'],
                            ['Soap', 'Telenovela'],
                            ['Talk', 'Talk Shows'],
                            ['Western', 'Del Oeste']
                        ];
                        const category = response.genres[0].name;
                        categories.forEach(function(item){
                            category_es.forEach(function(cat){
                                if(cat[0] == category && item.name == cat[1]){
                                    $('#serie_category_id').val(item.id);    
                                }
                            })
                        })
                        //$('#actors').val(response.Actors);
                        $('#director').val(response.created_by[0].name);
                        $('#runtime').val(response.episode_run_time[0]);
                        $('.backdrop-img').attr('src', 'https://image.tmdb.org/t/p/original' + response.backdrop_path);
                        $('#backdrop_link').val('https://image.tmdb.org/t/p/original' + response.backdrop_path);
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
                        const raw_ratings = response.results;
                        ratings.forEach(function(item){
                        	raw_ratings.forEach(function(raw_rating){
                        		if(raw_rating.iso_3166_1 == 'US' && item.name == raw_rating.rating){
                        			$('#rating').val(item.name);
	                            }	
                        	})                            
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

            function getSerieActors(id){
                const getSerieActors = 'https://api.themoviedb.org/3/tv/' + id + '/credits?api_key=' + apikey + '&language=en-US';
                $.ajax({
                    url: getSerieActors,
                    method: 'GET',
                    success: function(response){
                        let actors = '';
                        response.cast.forEach(function(actor){
                            actors += actor.name + ', ';
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

            function getSpanishTitle(id){
                const getSpanishTitle = 'https://api.themoviedb.org/3/tv/' + id + '/alternative_titles?api_key=' + apikey + '&language=en-US';
                $.ajax({
                    url: getSpanishTitle,
                    method: 'GET',
                    success: function(response){
                        const raw_titles = response.results;
                        raw_titles.forEach(function(raw_title){
                            if(raw_title.iso_3166_1 == 'ES'){
                                $('#title_es').val(raw_title.title);
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
