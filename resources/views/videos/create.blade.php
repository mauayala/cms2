@extends('layout.default')

@section('css')
	<link rel="stylesheet" href="/assets/js/tagsinput/jquery.tagsinput.css" />
@endsection


@section('content')

    <div id="admin-container">
        <!-- This is where -->

        <div class="admin-section-title">
            <h3><i class="entypo-plus"></i> Add New Movie</h3>
        </div>
        <div class="clear"></div>

        <div class="panel panel-primary" data-collapsed="0"> 
            <div class="panel-heading">
                <div class="panel-title">By Title</div>
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            <div class="panel-body" style="display: block;">
                <form class="form-inline" id="omdb-search">
                    <div class="form-group">
                        <label for="omdb_title">Title:</label>
                        <input type="text" class="form-control" autofocus name="omdb_title" id="omdb_title" placeholder="Title" />
                    </div>
                    <div class="form-group">
                        <label for="omdb_year">Year:</label>
                        <input type="text" class="form-control" name="omdb_year" id="omdb_year" placeholder="Year" />
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Search" class="btn btn-primary">
                        <button type="button" onclick="getPosters()" class="btn btn-warning">Get Posters Only</button>
                        <input type="reset" value="Reset" class="btn">
                    </div>
                </form>

                <div class="result mt-2">

                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('dashboard.videos.store') }}" accept-charset="UTF-8" file="1" enctype="multipart/form-data">
            @csrf
            <div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading">
                <div class="panel-title">Description</div>
                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    <div class="form-group">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Video Title" value="" />
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
                            <select class="form-control" id="video_category_id" name="video_category_id" required="">
                                <option value=""></option>
                                @foreach($video_categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-right: 0;">
                        <div class="form-group">
                            <select class="form-control" id="rating" name="rating">
                                <option value="NR">NR</option>
                                <option value="G">G</option>
                                <option value="PG">PG</option>
                                <option value="PG-13">PG-13</option>
                                <option value="R">R</option>
                                <option value="NC-17">NC-17</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary" data-collapsed="0"> 
                <div class="panel-heading">
                    <div class="panel-title">Movie Poster</div>
                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    <div class="col-md-6">
                        <img src="" class="video-img" width="214" height="306"/>
                        <p>Select the movie image (1280x720 px or 16:9 ratio):</p>
                        <input type="file" multiple="true" class="form-control" name="image" id="image" />
                        <input type="text" name="image_link" id="image_link" hidden="hidden">
                    </div>
                    <div class="col-md-6">
                        <img src="" class="backdrop-img" width="200"/>
                        <p>Select the movie backdrop image (1280x720 px or 16:9 ratio):</p>
                        <input type="file" multiple="true" class="form-control" name="backdrop" id="backdrop" />
                        <input type="text" name="backdrop_link" id="backdrop_link" hidden="hidden">
                    </div>
                </div>
            </div>

            <div class="panel panel-primary" data-collapsed="0"> 
                <div class="panel-heading">
                    <div class="panel-title">Video Source</div>
                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    <label for="type" style="float:left; margin-right:10px; padding-top:1px;">Video Format</label>
                    <select id="type" name="type">
                        <option value="vod">VOD</option>
                        <option value="hls">HLS</option>
                    </select>
                    <div class="vod" @if(!empty($video->hls_link) && empty($video->trailer))style="display: none;"@endif>
                        <div class="form-group">
                            <input type="text" required onfocusout="checkLink('video_file_name')" class="form-control" name="video_file_name" id="video_file_name" placeholder="Video File Name" value="@if(!empty($video->video_file_name)){{ $video->video_file_name }}@endif" />
                        </div>
                        <div class="form-group">
                            <input type="text" required class="form-control" onfocusout="checkLink('subtitle_file_name')" name="subtitle_file_name" id="subtitle_file_name" placeholder="Subtitle File Name English" value="@if(!empty($video->subtitle_file_name)){{ $video->subtitle_file_name }}@endif" />
                        </div>
                        <div class="form-group">
                            <input type="text" required class="form-control" onfocusout="checkLink('subtitle_file_name_es')" name="subtitle_file_name_es" id="subtitle_file_name_es" placeholder="Subtitle File Name Spanish" value="@if(!empty($video->subtitle_file_name_es)){{ $video->subtitle_file_name_es }}@endif" />
                        </div>
                    </div>
                    <div class="hls" @if(empty($video->hls_link))style="display: none;"@endif>
                        <div class="form-group">
                            <input type="text" class="form-control" name="hls_link" id="hls_link" placeholder="HLS link" value="@if(!empty($video->hls_link)){{ $video->hls_link }}@endif" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading"> <div class="panel-title"> Status Settings</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div>
                <div class="panel-body">
                    <div>
                        <label for="en" style="float:left; display:block; margin-right:10px;">Audio EN:</label>
                        <input type="checkbox" checked="checked" name="en" value="1" id="en" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="es" style="float:left; display:block; margin-right:10px;">Audio ES:</label>
                        <input type="checkbox" name="es" value="1" id="es" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="featured" style="float:left; display:block; margin-right:10px;">Is this movie Premiered:</label>
                        <input type="checkbox" name="featured" value="1" id="featured" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="active" style="float:left; display:block; margin-right:10px;">Is this movie Active:</label>
                        <input type="checkbox" checked="checked" name="active" value="1" id="active" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="hd" style="float:left; display:block; margin-right:10px;">Is this movie HD:</label>
                        <input type="checkbox" checked="checked" name="hd" value="1" id="hd" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="movies" style="float:left; display:block; margin-right:10px;">Kids Zone - Movies:</label>
                        <input type="radio" name="kids_zone" value="1" id="movies" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="toddlers" style="float:left; display:block; margin-right:10px;">Kids Zone - Toddlers:</label>
                        <input type="radio" name="kids_zone" value="2" id="toddlers" />
                    </div>
                </div>
            </div>

            <input type="submit" value="Add New Movie" class="btn btn-success pull-right" />

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
                        <h4 class="modal-title" id="myModalLabel">This movie is already uploaded on CloudTV, would you like to upload it duplicate anyway?</h4>
                    </div>
                    <div class="modal-body">
                        <button id='duplicate_yes' class='btn btn-success'>Yes</button>
                        <button id='duplicate_no' class='btn btn-danger'>No</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- This is where now -->
    </div>
	
	@section('javascript')
        <script type="text/javascript">
            var link_works = {
                video_file_name: 1,
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
            const categories = <?=json_encode($video_categories)?>;
            const ratings = <?=json_encode([['name' => 'NR'],['name' => 'G'], ['name' => 'PG'], ['name' => 'PG-13'], ['name' => 'R'], ['name' => 'NC-17']])?>;
            $('#enable_en').on('change', function(){
                const title = $('#title').val().toLowerCase().split(' ').join('.').split(':').join('').split('-').join('.').split("'").join('')
                if($('#enable_en').is(':checked')){
                    $('#subtitle_file_name').attr('disabled', false);
                    $('#subtitle_file_name').val(title + '.en.srt');    
                } else {
                    $('#subtitle_file_name').attr('disabled', true);
                    $('#subtitle_file_name').val('');
                }                
            })
            $('#duplicate_yes').click(function(){
                $('#duplicateModal').modal('hide');
            })
            $('#duplicate_no').click(function(){
                location.reload();
            })
            var search_result;
            $('#omdb-search').submit(function(e){
                const title = $('#omdb_title').val();
                //check if duplicated
                $.ajax({
                    url: '/dashboard/videos/duplicate/'+title,
                    method: 'GET',
                    success: function(response){
                        if(response.result){
                            $('#duplicateModal').modal('show');
                        }
                    }
                })

                e.preventDefault();
                const year = $('#omdb_year').val();
                var searchByTitle = '';
                if(year > 0) {
                    searchByTitle = 'https://api.themoviedb.org/3/search/movie?api_key=' + apikey + '&language=en-US&query=' + title + '&year=' + year;
                } else {
                    searchByTitle = 'https://api.themoviedb.org/3/search/movie?api_key=' + apikey + '&language=en-US&query=' + title;
                }
                
                $.ajax({
                    url: searchByTitle,
                    method: 'GET',
                    success: function(response){
                        console.log(response)
                        if(response.results.length > 1) {
                            response.results.forEach((element, index) => {
                                $('.result').append('<div onclick="fillForm('+index+')">'+element.title+' (' + element.release_date+ ')</div>')
                            })
                        }
                        search_result = response.results
                        response = response.results[0]
                        getSpanishTitle(response.id);
                        getMovieDetails(response.id);
                        getMovieActors(response.id);
                        getMovieRating(response.id);
                        
                        if($('#type').find(':selected').text() == 'VOD'){
                            const title = response.title.toLowerCase().split(' ').join('.').split(':').join('').split('-').join('.')
                            $('#video_file_name').val(title + '.mp4');
                            $('#subtitle_file_name_es').val(title + '.es.srt');
                            checkLink('video_file_name');
                            checkLink('subtitle_file_name_es');
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

            function fillForm(index) {
                var response = search_result[index]
                console.log(response)
                getSpanishTitle(response.id);
                getMovieDetails(response.id);
                getMovieActors(response.id);
                getMovieRating(response.id);
                
                if($('#type').find(':selected').text() == 'VOD'){
                    const title = response.title.toLowerCase().split(' ').join('.').split(':').join('').split('-').join('.')
                    $('#video_file_name').val(title + '.mp4');
                    $('#subtitle_file_name_es').val(title + '.es.srt');
                    checkLink('video_file_name');
                    checkLink('subtitle_file_name_es');
                }
            }

            function getPosters() {
                const title = $('#omdb_title').val();
                //check if duplicated
                $.ajax({
                    url: '/dashboard/videos/duplicate/'+title,
                    method: 'GET',
                    success: function(response){
                        if(response.result){
                            $('#duplicateModal').modal('show');
                        }
                    }
                })

                const year = $('#omdb_year').val();
                var searchByTitle = '';
                if(year > 0) {
                    searchByTitle = 'https://api.themoviedb.org/3/search/movie?api_key=' + apikey + '&language=en-US&query=' + title + '&year=' + year;
                } else {
                    searchByTitle = 'https://api.themoviedb.org/3/search/movie?api_key=' + apikey + '&language=en-US&query=' + title;
                }
                
                
                $.ajax({
                    url: searchByTitle,
                    method: 'GET',
                    success: function(response){
                        response = response.results[0]
                        const getMovieDetails = 'https://api.themoviedb.org/3/movie/' + response.id + '?api_key=' + apikey + '&language=en-US&append_to_response=undefined';
                        $.ajax({
                            url: getMovieDetails,
                            method: 'GET',
                            success: function(response){
                                $('.video-img').attr('src', 'https://image.tmdb.org/t/p/w780' + response.poster_path);
                                $('#image_link').val('https://image.tmdb.org/t/p/w780' + response.poster_path);
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

            function getMovieDetails(id){
                const getMovieDetails = 'https://api.themoviedb.org/3/movie/' + id + '?api_key=' + apikey + '&language=en-US&append_to_response=undefined';
                $.ajax({
                    url: getMovieDetails,
                    method: 'GET',
                    success: function(response){
                        $('#title').val(response.title);
                        $('#plot').val(response.overview);
                        // response.crew.forEach(function(item){
                        //     if(item.job == 'Director'){
                        //         director += item.name + ', ';
                        //     }
                        // })
                        // $('#director').val(director);
                        const category_es = [
                            ['Action', 'Accion'], 
                            ['Animation', 'Animacion'],
                            ['Comedy', 'Comedia'],
                            ['Documentary', 'Documentales'],
                            ['Family', 'Familia'],
                            ['Horror', 'Horror'],
                            ['Romance', 'Romance'],
                            ['War', 'Guerra'],
                            ['Adventure', 'Aventura'],
                            ['Crime', 'Crimen'],
                            ['Drama', 'Drama'],
                            ['Fantasy', 'Fantasia'],
                            ['History', 'Historia'],
                            ['Music', 'Musica'],
                            ['Mystery', 'Misterio'],
                            ['Science Fiction', 'Ciencia Ficcion'],
                            ['TV Movie', 'TV Movie'],
                            ['Thiller', 'Suspenso'],
                            ['Western', 'Del Oeste']
                        ];
                        const category = response.genres[0].name;
                        categories.forEach(function(item){
                            category_es.forEach(function(cat){
                                if(cat[0] == category && item.name == cat[1]){
                                    //$('#video_category_id').val(item.id);    
                                }
                            })
                        })
                        $('#imdb_rating').val(response.vote_average);
                        $('#runtime').val(response.runtime);
                        $('.video-img').attr('src', 'https://image.tmdb.org/t/p/w780' + response.poster_path);
                        $('#image_link').val('https://image.tmdb.org/t/p/w780' + response.poster_path);
                        $('.backdrop-img').attr('src', 'https://image.tmdb.org/t/p/original' + response.backdrop_path);
                        $('#backdrop_link').val('https://image.tmdb.org/t/p/original' + response.backdrop_path);
                        $('#released_at').val(response.release_date);
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

            function getMovieActors(id){
                const getMovieDetails = 'https://api.themoviedb.org/3/movie/' + id + '/credits?api_key=' + apikey;
                let actors = '';
                let director = '';
                $.ajax({
                    url: getMovieDetails,
                    method: 'GET',
                    success: function(response){
                        response.crew.forEach(function(item){
                            if(item.job == 'Director'){
                                director += item.name + ', ';
                            }
                        })
                        $('#director').val(director);
                        response.cast.forEach(function(item){
                            actors += item.name + ', ';
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

            function getMovieRating(id){
                const getMovieRating = 'https://api.themoviedb.org/3/movie/' + id + '/release_dates?api_key=' + apikey + '&language=en-US';
                $.ajax({
                    url: getMovieRating,
                    method: 'GET',
                    success: function(response){
                        const raw_ratings = response.results;
                        raw_ratings.forEach(function(raw_rating){
                            if(raw_rating.iso_3166_1 == 'US'){
                                ratings.forEach(function(item){
                                    if(item.name == raw_rating.release_dates[0].certification){
                                        $('#rating').val(item.name);
                                    }
                                })
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

            function getSpanishTitle(id){
                const getSpanishTitle = 'https://api.themoviedb.org/3/movie/' + id + '/alternative_titles?api_key=' + apikey + '&language=en-US';
                $.ajax({
                    url: getSpanishTitle,
                    method: 'GET',
                    success: function(response){
                        const raw_titles = response.titles;
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

            function checkLink(type) {
                if(type == 'video_file_name') {
                    link_works.video_file_name = 2;
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
                            if(type == 'video_file_name') {
                                link_works.video_file_name = 1;
                            } else if(type == 'subtitle_file_name') {
                                link_works.subtitle_file_name = 1;
                            } else if(type == 'subtitle_file_name_es') {
                                link_works.subtitle_file_name_es = 1;
                            }
                            if(link_works.video_file_name == 1 && link_works.subtitle_file_name == 1 && link_works.subtitle_file_name_es == 1) {
                                $('input.btn-success').attr('disabled', false);
                                $('input.btn-success').val('Add New Movie');
                            } else if(link_works.video_file_name == 0 || link_works.subtitle_file_name == 0 || link_works.subtitle_file_name_es == 0) {
                                $('input.btn-success').val('Link(s) does not work!');
                            }
                            $('#' + type).parent().removeClass('has-error');
                        } else {
                            $('input.btn-success').val('Link(s) does not work!');
                            $('#' + type).parent().addClass('has-error');
                            if(type == 'video_file_name') {
                                link_works.video_file_name = 0;
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
