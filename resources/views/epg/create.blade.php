@extends('layout.default')

@section('content')

    <div id="admin-container">
        <!-- This is where -->

        <div class="admin-section-title">
            <h3>Add Channel</h3>
        </div>

        <div class="panel panel-primary" data-collapsed="0"> 
            <div class="panel-heading">
                <div class="panel-title">Channels</div>
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{route('dashboard.epg.channels.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input name='channel1' class='form-control' type='text' placeholder='Channel name'>
                    </div>
                    <div class="form-group">
                        <select name='channel' class='form-control'>
                            <option value='0'>Custom</option>
                            @foreach($channels as $channel)
                                <option value='{{$channel->id}}'>{{$channel->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type='text' placeholder='Link' name='link' class='form-control'>
                    </div>
                    <div class="form-group">
                        <select name='category_id' class='form-control'>
                            @foreach($categories as $category)
                                <option value='{{$category->id}}'>{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type='file' placeholder='Link' name='logo' class='form-control'>
                    </div>
                    <div class="form-group">
                        <input type='checkbox' name='is_premiered' id='is_premiered'>
                        <label for='is_premiered'>Is Premiered?</label>
                    </div>
                    <div class="form-group">
                        <input type='checkbox' name='visible' checked='checked' id='visible'>
                        <label for='visible'>Is Visible?</label>
                    </div>
                    <div class="form-group">
                        <input type='submit' class='btn btn-success' value='Save'>
                    </div>
                    {{csrf_field()}}
                </form>
            </div>
        </div>        
    </div>
@endsection
