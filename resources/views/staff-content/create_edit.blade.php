@extends('layout.default')

@section('css')
    <link rel="stylesheet" href="/assets/js/tagsinput/jquery.tagsinput.css" />
@endsection


@section('content')

    <div id="admin-container">
        <!-- This is where -->

        <div class="admin-section-title">
            <h3><i class="entypo-plus"></i> Assign series</h3>
        </div>
        <div class="clear"></div>

        <nav>
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=numbers">0-9</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=A">A</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=B">B</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=C">C</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=D">D</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=E">E</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=F">F</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=G">G</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=H">H</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=I">I</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=J">J</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=K">K</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=L">L</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=M">M</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=N">N</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=O">O</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=P">P</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=Q">Q</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=R">R</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=S">S</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=T">T</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=U">U</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=V">V</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=W">W</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=X">X</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=Y">Y</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=Z">Z</a></li>
            </ul>
        </nav>
        
        <form method="POST" action="/dashboard/staff-content/store">
            @csrf
            <div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading">
                <div class="panel-title">Series</div>
                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                        @if(isset($_GET['status']))
                            <a href="/dashboard/staff-content">Show Airing and Paused Series</a>
                        @else
                            <a href="/dashboard/staff-content?status=ended">Show Ended Series</a>
                        @endif
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    @foreach($series as $s)
                        <input type="hidden" name="serie_id[{{$s->id}}]" value="{{$s->id}}">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    {{$s->title}}
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <select name="user_ids[{{$s->id}}]" id="user_ids{{$s->id}}">
                                        <option value="">Select</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}" @if($s->assigned_to == $user->id) selected="" @endif>{{$user->username}}</option>
                                        @endforeach
                                    </select>

                                    <input type="checkbox" name="mondays[{{$s->id}}]" id="mondays{{$s->id}}" value="1" @if($s->monday == 1) checked="" @endif />
                                    <label for="mondays{{$s->id}}">M</label>

                                    <input type="checkbox" name="tuesdays[{{$s->id}}]" id="tuesdays{{$s->id}}" value="1" @if($s->tuesday == 1) checked="" @endif />
                                    <label for="tuesdays{{$s->id}}">T</label>

                                    <input type="checkbox" name="wednesdays[{{$s->id}}]" id="wednesdays{{$s->id}}" value="1" @if($s->wednesday == 1) checked="" @endif />
                                    <label for="wednesdays{{$s->id}}">W</label>

                                    <input type="checkbox" name="thursdays[{{$s->id}}]" id="thursdays{{$s->id}}" value="1" @if($s->thursday == 1) checked="" @endif />
                                    <label for="thursdays{{$s->id}}">T</label>

                                    <input type="checkbox" name="fridays[{{$s->id}}]" id="fridays{{$s->id}}" value="1" @if($s->friday == 1) checked="" @endif />
                                    <label for="fridays{{$s->id}}">F</label>

                                    <input type="checkbox" name="saturdays[{{$s->id}}]" id="saturdays{{$s->id}}" value="1" @if($s->saturday == 1) checked="" @endif />
                                    <label for="saturdays{{$s->id}}">S</label>

                                    <input type="checkbox" name="sundays[{{$s->id}}]" id="sundays{{$s->id}}" value="1" @if($s->sunday == 1) checked="" @endif />
                                    <label for="sundays{{$s->id}}">S</label>

                                    <select name="statuses[{{$s->id}}]" id="statuses{{$s->id}}" class="status">
                                        <option value="Airing" @if($s->status == 'Airing') selected="" @endif>Airing</option>
                                        <option value="Paused" @if($s->status == 'Paused' || $s->status == null) selected="" @endif>Paused</option>
                                        <option value="Ended" @if($s->status == 'Ended') selected="" @endif>Ended</option>
                                    </select>

                                    <label for="season_numbers{{$s->id}}">Season numbers</label>
                                    <input type="text" name="season_numbers[{{$s->id}}]" id="season_numbers{{$s->id}}" value="{{$s->season_number}}"/>

                                    <label for="episode_numbers{{$s->id}}">Episode numbers</label>
                                    <input type="text" name="episode_numbers[{{$s->id}}]" id="episode_numbers{{$s->id}}" value="{{$s->episode_number}}"/>

                                    <input type="checkbox" name="link_subtitle_works[{{$s->id}}]" id="link_subtitle_works{{$s->id}}" value="1" @if($s->link_subtitle_works == 1) checked="" @endif />
                                    <label for="link_subtitle_works{{$s->id}}">English Subtitle</label>

                                    <input type="checkbox" name="link_subtitle_es_works[{{$s->id}}]" id="link_subtitle_es_works{{$s->id}}" value="1" @if($s->link_subtitle_es_works == 1) checked="" @endif />
                                    <label for="link_subtitle_es_works{{$s->id}}">Spanish Subtitle</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <input type="submit" name="assign" value="Assign" class="btn btn-success" style="position: fixed; bottom: 100px; right: 50px;" />
                </div>
            </div>
        </form>
        <nav>
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=numbers">0-9</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=A">A</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=B">B</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=C">C</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=D">D</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=E">E</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=F">F</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=G">G</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=H">H</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=I">I</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=J">J</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=K">K</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=L">L</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=M">M</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=N">N</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=O">O</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=P">P</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=Q">Q</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=R">R</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=S">S</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=T">T</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=U">U</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=V">V</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=W">W</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=X">X</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=Y">Y</a></li>
                <li class="page-item"><a class="page-link" href="/dashboard/staff-content?letter=Z">Z</a></li>
            </ul>
        </nav>
        <div class="clear"></div>
    </div>
@endsection
@section('javascript')
<script>
    $(document).ready(function(){
        $('.status').change(function(){
            if($(this).find('option:selected').val() == 'Airing') {
                $('#mondays' + $(this).val()).attr('disabled', false)
                $('#tuesdays' + $(this).val()).attr('disabled', false)
                $('#wednesdays' + $(this).val()).attr('disabled', false)
                $('#thursdays' + $(this).val()).attr('disabled', false)
                $('#fridays' + $(this).val()).attr('disabled', false)
                $('#saturdays' + $(this).val()).attr('disabled', false)
                $('#sundays' + $(this).val()).attr('disabled', false)
            } else {
                $('#mondays' + $(this).val()).attr('disabled', true)
                $('#tuesdays' + $(this).val()).attr('disabled', true)
                $('#wednesdays' + $(this).val()).attr('disabled', true)
                $('#thursdays' + $(this).val()).attr('disabled', true)
                $('#fridays' + $(this).val()).attr('disabled', true)
                $('#saturdays' + $(this).val()).attr('disabled', true)
                $('#sundays' + $(this).val()).attr('disabled', true)
            }
        })
    })
</script>
@endsection