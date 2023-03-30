@extends('layout.default')

@section('css')
	<link rel='stylesheet' href='/admin/css/sweetalert.css'>
@endsection

@section('content')
	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-9">
				<h3><i class="entypo-serie"></i> Staff duties</h3>
			</div>
		</div>
	</div>
	<div class="clear"></div>

	<div class="gallery-env">
        <div class="row">
            <div class="col-md-12">
                <form action="/dashboard/staff_duties" method="POST">
                    @csrf
                    @foreach($staff_duties as $sd)
                        <input type="hidden" name="staff_duties[]" value="{{$sd->id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>{{$sd->name}}</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="users{{$sd->id}}">Staff</label>
                                    <select name="users[]" id="users{{$sd->id}}" class="form-control">
                                        @foreach($users as $u)
                                            <option value="{{$u->id}}" @if($sd->user_id === $u->id) selected @endif>{{$u->username}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="max_broken_links{{$sd->id}}">Max broken link</label>
                                    <input type="text" name="max_broken_links[]" id="max_broken_links{{$sd->id}}" class="form-control" value="{{$sd->max_broken_link}}">
                                </div>
                            </div>
                        </div>
                    @endforeach    
                    <button class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
	</div>
@endsection

