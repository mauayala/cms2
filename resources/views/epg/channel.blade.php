@extends('layout.default')

@section('content')

    <div id="admin-container">
        <!-- This is where -->

        <div class="admin-section-title">
            <h3>{{$channel->name}}</h3>
        </div>

        <div class="panel panel-primary" data-collapsed="0"> 
            <div class="panel-heading">
                <div class="panel-title">Channels</div>
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{route('dashboard.epg.channels.update', ['channel' => $channel->id])}}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <input type='text' placeholder='Link' name='link' class='form-control' value='@if(isset($channel->link)){{$channel->getOriginal('link')}}@endif'>
                    </div>
                    <div class="form-group">
                        <select name='channel' class='form-control'>
                            <option value='0'></option>
                            @foreach($channels as $c)
                                <option value='{{$c->id}}' @if(isset($channel->name) && $channel->name == $c->name) selected='selected' @endif>{{$c->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type='file' placeholder='Link' name='logo' class='form-control'>
                    </div>
                    <div class="form-group">
                        <input name='channel1' class='form-control' type='text' placeholder='Channel name'value='@if(isset($channel->name)){{$channel->name}}@endif'>
                    </div>
                    <div class="form-group">
                        <select name='category_id' class='form-control'>
                            @foreach($categories as $category)
                                <option value='{{$category->id}}' @if($category->id==$channel->category_id)selected='selected'@endif>{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type='checkbox' name='is_premiered' @if($channel->is_premiered)checked='checked'@endif id='is_premiered'>
                        <label for='is_premiered'>Is Premiered?</label>
                    </div>
                    <div class="form-group">
                        <input type='checkbox' name='visible' @if($channel->visible)checked='checked'@endif id='visible'>
                        <label for='visible'>Is Visible?</label>
                    </div>
                    <div class="form-group">
                        <input type='submit' class='btn btn-success' value='Save'>
                    </div>
                    <div class="form-group">
                        <a href='/dashboard/epg/programs/{{$channel->id}}' class='btn btn-success'>Edit EPG Data</a>
                    </div>
                    {{csrf_field()}}
                </form>
            </div>
        </div>        
    </div>
@endsection
