@extends('layout.default')

@section('css')
	<link rel='stylesheet' href='/admin/css/sweetalert.css'>
@endsection

@section('content')
	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-3">
				<h3><i class="entypo-video"></i> Movies</h3>
				<a href="{{ route('dashboard.videos.create') }}" class="btn btn-success">
					<i class="fa fa-plus-circle"></i> Add Movie
				</a>
			</div>
			<div class='col-md-3'>
				<form action='/dashboard/videos' method='GET' class='form-inline'>
					<div class='form-group'>
						<select name='filter' class='form-control'>
							<option value='NR'>NR</option>
							<option value='G'>G</option>
							<option value='PG'>PG</option>
							<option value='PG-13'>PG-13</option>
							<option value='R'>R</option>
							<option value='NC-17'>NC-17</option>
						</select>
					</div>
					<div class='form-group'>
						<button type='submit' class='btn btn-success'><i class="fa fa-filter"></i> Filter by Rating</button>
					</div>
				</form>
			</div>
			<div class="col-md-2">
				<a href="{{ URL::to('dashboard/videos/categories') }}" class='btn btn-success'>
					<span class="title">Movie Categories</span>
				</a>
			</div>
			<div class="col-md-4">	
				<form method="get" role="form" class="search-form-full">
					<div class="form-group">
						<input type="text" class="form-control" name="s" value="<?=(isset($_GET['s'])) ? $_GET['s'] : ''; ?>" id="search-input" placeholder="Search...">
						<i class="entypo-search"></i>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="clear"></div>

	<div class="gallery-env">
        <div class="row">		
            <div class="col-md-12">
                <h3>Movies</h3>
            </div>
        </div>
		@foreach($videos as $v)
            <div class="video-box pull-left">
                <article class="album">
                    <header>
                        <a href="{{ route('videos.show', ['video' => $v->id])}}" target="_blank">
                            @if(substr($v->image, 0, 4) == 'http')
                                <img src="{{ $v->image }}" />
                            @else 
                                <img src="https://s3.amazonaws.com/ctv3/{{ $v->image }}" />
                            @endif
                        </a>
                        <a href="{{ route('dashboard.videos.edit', ['video' => $v->id]) }}" class="album-options">
                            <i class="entypo-pencil"></i>
                            Edit
                        </a>
                    </header>
                    
                    <section class="album-info text-center">
                        <h3>
                            <a href="{{ route('dashboard.videos.edit', ['video' => $v->id]) }}">
                                @if(strlen($v->title) > 25)
                                    {{substr($v->title, 0, 25) . '...' }}
                                @else
                                    {{ $v->title }}
                                @endif
                            </a>
                        </h3>
                        <h3>{{ $v->released_at }}</h3>
                        <small><strong>TRAILER</strong> <span>@if(is_null($v->trailer)) - @else /{{$v->trailer}} @endif</span></small><br/>
                        @if($v->errors->count() > 0)
                            <span class="red-circle"></span>
                        @endif
                        <small><strong>VOD</strong> <span>@if(is_null($v->video_file_name)) - @else /{{$v->video_file_name}} @endif</span></small><br/>
                        <small><strong>EN SRT</strong> <span>@if(is_null($v->subtitle_file_name)) - @else /{{$v->subtitle_file_name}} @endif</span></small><br/>
                        <small><strong>ES SRT</strong> <span>@if(is_null($v->subtitle_file_name_es)) - @else /{{$v->subtitle_file_name_es}} @endif</span></small>
                    </section>
                    
                    <footer>
                        <div class="album-images-count">
                            @if(isset($v->category->name))
                                {{ $v->category->name }}
                                <br/>
                            @endif
                            {{$v->video_views->count()}} VIEWS
                        </div>
                        <div class="album-options">
                            <a href="{{ route('dashboard.videos.edit', ['video' => $v->id]) }}">
                                <i class="entypo-pencil"></i>
                            </a>
                            <a href="{{ route('dashboard.videos.destroy', ['video' => $v->id]) }}" class="delete">
                                <i class="entypo-trash"></i>
                            </a>
                            <a href="{{ route('dashboard.recommendations.store', ['video_id' => $v->id]) }}">
                                <i class="entypo-star"></i>
                            </a>
                        </div>
                    </footer>
                </article>
            </div>
		@endforeach
        <div class="clear"></div>

        <div class="row">
            <div class="col-md-12">
                <h3>Series</h3>
            </div>
        </div>
        @foreach($series as $v)
            <div class="video-box pull-left">
                <article class="album">
                    <header>
                        <a href="{{ route('dashboard.series.seasons.index', ['series' => $v->id]) }}">
                            <img src="https://s3.amazonaws.com/ctv3/{{ $v->image }}" />
                        </a>
                        <a href="{{ route('dashboard.series.edit', ['series' => $v->id]) }}" class="album-options">
                            <i class="entypo-pencil"></i>
                            Edit
                        </a>
                    </header>
                    
                    <section class="album-info text-center">
                        <h3>
                            <a href="{{ route('dashboard.series.edit', ['series' => $v->id]) }}">
                                @if(strlen($v->title) > 25)
                                    {{substr($v->title, 0, 25) . '...' }}
                                @else
                                    {{ $v->title }}
                                @endif
                            </a>
                        </h3>
                        <h3>{{ $v->released_at }}</h3>
                    </section>
                    
                    <footer>
                        <div class="album-images-count">
                            @if($v->hasErrors())
                                <span class="red-circle"></span>
                            @endif
                            @if(isset($v->category->name))
                                {{ $v->category->name }}<br/>
                            @endif
                            {{$v->serie_views()->count()}} VIEWS
                        </div>
                        <div class="album-options">
                            <a href="{{ route('dashboard.series.edit', ['series' => $v->id]) }}">
                                <i class="entypo-pencil"></i>
                            </a>
                            <a href="{{ route('dashboard.series.destroy', ['serie' => $v->id]) }}" class="delete">
                                <i class="entypo-trash"></i>
                            </a>
                            <a href="{{ route('dashboard.recommendations.store', ['serie_id' => $v->id]) }}">
                                <i class="entypo-star"></i>
                            </a>
                        </div>
                    </footer>
                </article>
            </div>
		@endforeach
        <div class="clear"></div>

        <div class="row">
            <div class="col-md-12">
                <h3>Users</h3>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr class="table-header">
                    <th>Nombre de Usuario</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Tipo de Usuario</th>
                    <th>Direccion IP</th>
                    <th>Esta viendo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><a href="{{route('dashboard.users.show', ['user' => $user['username']])}}" target="_blank">
                            <?php if(strlen($user['username']) > 40){
                                    echo substr($user['username'], 0, 40) . '...';
                                } else {
                                    echo $user['username'];
                                }
                            ?>
                            </a>
                        </td>
                        <td>
                            @if($user['role'] == 'customer')
                                <?php 
                                    $today = strtotime(date('Y-m-d H:i:s'));
                                    $ends = strtotime($user['trial_ends_at']);
                                    if(($ends - $today) < 0){
                                        $interval = -1;
                                    } else {
                                        $interval = round(($ends - $today)/(60*60*24));	
                                    }
                                ?>
                                @if( $interval < 0 )
                                    <div class="label label-danger"><i class="fa fa-frown-o"></i> Expired {{abs(round(($ends - $today)/(60*60*24)))}} days ago </div>
                                @elseif( $interval < 6 )
                                    <div class="label label-warning"><i class="fa fa-meh-o"></i> Expires in {{$interval}} days</div> 
                                @elseif( $interval > 5 && $interval < 32 )
                                    <div class="label label-success"><i class="fa fa-frown-o"></i> Expires in {{$interval}} days</div>
                                @elseif( $interval > 31 && $interval < 366 )
                                    <div class="label label-info"><i class="fa fa-ticket"></i> Expires in {{ $interval }} days</div>
                                @elseif( $interval > 365 )
                                    <div class="label label-primary"><i class="fa fa-ticket"></i> Expires in {{$interval}} days</div>
                                @endif
                            @endif
                        </td>
                        <td class="roles">
                            @if($user['role'] == 'distributor')
                                <div class="label label-info" style="background-image: url(/distributor.png);padding-left: 35px;">
                                Distributor</div>
                            @elseif($user['role'] == 'seller')
                                <div class="label" style="background-color: #f7931e;"><i class="fa fa-envelope"></i>
                                Seller</div>
                            @elseif($user['role'] == 'customer')
                                <div class="label label-warning"><i class="fa fa-life-saver"></i>
                                Customer</div>
                            @elseif($user['role'] == 'admin')
                                <div class="label label-success" style="background-image: url(/admin.png);padding-left: 35px;">
                                Administrator</div>
                            @elseif($user['role'] == 'staff')
                                <div class="label label-danger" style="background-image: url(/staff.png);padding-left: 35px;">
                                Staff</div>
                            @elseif($user['role'] == 'owner')
                                <div class="label label-primary" style="background-image: url(/owner.png);padding-left: 35px;">
                                <?= ucfirst($user['role']) ?></div>
                            @endif						 
                        </td>
                        <td>{{ $user['ip'] }}</td>
                        <td>{{$user->isOnline()}}</td>
                        <td>						
                            <a href="{{route('dashboard.users.edit', ['user' => $user['id']]) }}" class="btn btn-xs btn-info"><span class="fa fa-edit"></span> Editar</a>
                            @if($user['role'] == 'owner')
                                <a href="{{route('dashboard.users.destroy', ['user' => $user['id']])}}" class="btn btn-xs btn-danger delete"><span class="fa fa-trash"></span> Eliminar</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clear"></div>
	</div>

@endsection

@section('javascript')
    <script src='/admin/js/sweetalert.min.js'></script>
    <script>

        $(document).ready(function(){
            var delete_link = '';

            $('.delete').click(function(e){
                e.preventDefault();
                delete_link = $(this).attr('href');
                swal({   title: "Are you sure?",   text: "Do you want to permanantly delete this video?",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   closeOnConfirm: false }, function(){    window.location = delete_link });
                return false;
            });
        });

    </script>

@endsection