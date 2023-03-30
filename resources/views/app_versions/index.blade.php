@extends('layout.default')

@section('css')
	<link rel='stylesheet' href='/admin/css/sweetalert.css'>
@endsection

@section('content')
	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-4">
				<h3><i class="entypo-video"></i> App Versionn</h3>
			</div>
		</div>
	</div>
	<div class="clear"></div>

	<div class="gallery-env">
		<div class="col-md-4 col-md-offset-4">
			<form method="POST" action="{{ route('dashboard.app_versions.store') }}" accept-charset="UTF-8" file="1" enctype="multipart/form-data">
				@csrf
				<div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading">
					<div class="panel-title">To send an update to all users, select a .apk file from your local machine and define the app version. Once saved, this update will roll out to all users. Once you Upload and Publish, the action is irreversible.</div>
						<div class="panel-options">
							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						</div>
					</div>
					<div class="panel-body" style="display: block;">
						<div class="form-group">
							<input type="file" class="form-control" name="apk" id="apk" />
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="version" id="version" placeholder="4.1" value="" />
						</div>
						<input type="submit" value="Update and Publish" class="btn btn-success btn-block" />
					</div>
				</div>
			</form>
			<div>
				@foreach($app_versions as $version)
					<div @if($loop->iteration == 1) style="border: 2px solid green; border-radius: 10px; padding: 1rem;" @else style="padding: 1rem;" @endif>
						@if($loop->iteration == 1)
							Current version: {{$version->version}}
						@else
							Version: {{$version->version}}
						@endif
						<br/>
						File name: {{$version->link}}<br/>
						File link: <a href="{{$version->link}}">{{$version->link}}</a><br/>
						Uploaded on: {{$version->created_at}}<br/>
						Users on this version: {{$version->users->count()}}
					</div>
				@endforeach
			</div>
		</div>
	</div>
@endsection

