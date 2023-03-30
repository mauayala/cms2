@extends('layout.default')

@section('css')
	<link rel='stylesheet' href='/admin/css/sweetalert.css'>
@endsection

@section('content')
	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-3">
				<h3><i class="entypo-video"></i> Series</h3>
			</div>
			<div class='col-md-3'>
				<form action='/dashboard/series/view-count' method='GET' class='form-inline'>
					<div class='form-group'>
						<label for="from_date">From Date</label>
						<input type="date" name="from_date" id="from_date" class="form-control" value="{{$_GET['from_date'] ?? ''}}">
					</div>
					<div class='form-group'>
						<label for="to_date">To Date</label>
						<input type="date" name="to_date" id="to_date" class="form-control" value="{{$_GET['to_date'] ?? ''}}">
					</div>
					<div class='form-group'>
						<button type='submit' class='btn btn-success'><i class="fa fa-filter"></i> Filter</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="clear"></div>

	<div class="gallery-env">
        <div class="row">
            <div class="col-md-12">
                <h3>Series</h3>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr class="table-header">
                    <th>Title</th>
                    <th>Views</th>
                </tr>
            </thead>
            <tbody>
				@foreach($series as $serie)
                    <tr>
                        <td><a href="{{route('dashboard.series.seasons.index', ['series' => $serie->id])}}" target="_blank">{{$serie->title}}</a></td>
                        <td>{{$serie->video_views_count}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clear"></div>
	</div>

@endsection

