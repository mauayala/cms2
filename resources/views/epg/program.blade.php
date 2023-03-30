@extends('layout.default')

@section('content')

    <div id="admin-container">
        <!-- This is where -->

        <div class="admin-section-title">
            <h3>{{$channel->name}}</h3>
        </div>

        <div class="panel panel-primary" data-collapsed="0"> 
            <div class="panel-heading">
                <div class="panel-title">Add EPG Data</div>
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            <div class="panel-body">
                <form method="POST" action="/dashboard/epg/programs/{{$channel->id}}">
                    <div class="form-group">
                        <input type='text' placeholder='Title' name='title' class='form-control'>
                    </div>
                    <div class="form-group">
                        <input type='text' placeholder='Subtitle' name='subtitle' class='form-control'>
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
