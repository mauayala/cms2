@extends('layout.default')

@section('css')
    <link rel="stylesheet" href="/assets/js/tagsinput/jquery.tagsinput.css" />
@endsection


@section('content')

    <div id="admin-container">
        <!-- This is where -->

        <div class="admin-section-title">
            <h3><i class="entypo-plus"></i> Add New Episode</h3>
        </div>
        <div class="clear"></div>

        <form method="POST" action="/dashboard/series/{{$series->id}}/seasons/{{$season->id}}/episode_manual/store" accept-charset="UTF-8" file="1" enctype="multipart/form-data">
            <div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading">
                <div class="panel-title">Description</div>
                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    <div class='col-md-6' style="padding-left: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="episode_start" placeholder="Episode start at"/>
                        </div>
                    </div>
                    <div class='col-md-6' style="padding-right: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="episode_end" placeholder="Episode end at"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="actors" id="actors" placeholder="Actors"/>
                    </div>
                    <div class="col-md-4" style="padding-left: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="director" id="director" placeholder="Director"/>
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-right: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="imdb_rating" id="imdb_rating" placeholder="IMDB Rating"/>
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-left: 0;">
                        <div class="form-group">
                            <input type="text" class="form-control" name="runtime" id="runtime" placeholder="Runtime (in minutes)"/>
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-right: 0;">
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
                </div>
            </div>

            <div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading">
                <div class="panel-title">Episode Poster</div>
                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    <img src="" class="video-img" width="200"/>
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
                        <option value="vod">VOD</option>
                        <option value="hls">HLS</option>
                    </select>
                    <div class="vod">
                        <div class="form-group">
                            <input type="text" class="form-control" name="serie_file_name" id="serie_file_name" placeholder="Serie File Name"/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="subtitle_file_name" id="subtitle_file_name" placeholder="Subtitle File Name English"/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="subtitle_file_name_es" id="subtitle_file_name_es" placeholder="Subtitle File Name Spain"/>
                        </div>
                    </div>
                    <div class="hls">
                        <div class="form-group">
                            <input type="text" class="form-control" name="hls_link" id="hls_link" placeholder="HLS link"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading"> <div class="panel-title"> Status Settings</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div>
                <div class="panel-body">
                    <div>
                        <label for="en" style="float:left; display:block; margin-right:10px;">Audio EN:</label>
                        <input type="checkbox" name="en" value="1" id="en" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="es" style="float:left; display:block; margin-right:10px;">Audio ES:</label>
                        <input type="checkbox" name="es" value="1" id="es" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="featured" style="float:left; display:block; margin-right:10px;">Is this episode Premiered:</label>
                        <input type="checkbox" name="featured" value="1" id="featured" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="active" style="float:left; display:block; margin-right:10px;">Is this episode Active:</label>
                        <input type="checkbox" checked="checked" name="active" value="1" id="active" />
                    </div>
                    <div class="clear"></div>
                    <div>
                        <label for="hd" style="float:left; display:block; margin-right:10px;">Is this episode HD:</label>
                        <input type="checkbox" checked="checked" name="hd" value="1" id="hd" />
                    </div>
                </div>
            </div>

            {{csrf_field()}}
            <input type="hidden" name="user_id" value="{{\Auth::user()->id}}" />
            <input type="hidden" name="episode_number" id="episode_number1" value="" />
            <input type="submit" value="Add New Episode" class="btn btn-success pull-right" />

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
        </script>
    @endsection
@endsection
