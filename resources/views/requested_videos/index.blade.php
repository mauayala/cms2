@extends('layout.default')

@section('content')
	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-3">
				<h3><i class="entypo-video"></i> Requested movies</h3>
			</div>
		</div>
	</div>
	<div class="clear"></div>

	<div class="gallery-env">
        <table class="table">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Title</td>
                    <td>Requested date</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                @foreach($videos as $video)
                    <tr>
                        <td>{{$video->id}}</td>
                        <td>{{$video->title}}</td>
                        <td>{{date('Y-m-d H:i', strtotime($video->created_at))}}</td>
                        <td>
                            <a href="/dashboard/requested-videos/{{$video->id}}/update?status=resolved" class="btn btn-success">Resolved</a>
                            <a href="/dashboard/requested-videos/{{$video->id}}/update?status=removed" class="btn btn-danger">Remove</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
	</div>

@endsection

