@extends('layout.default')

@section('content')
	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-primary" data-collapsed="0">
					<div class="panel-heading">
						<div class="panel-title">
							Anti Fraud System
						</div>
					</div>
					<div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td>Credits acquired in last 30 days</td>
                                    <td>Amount of active users</td>
                                    <td>Ratio</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                @if($user->childrenCount() > 0)
                                    <tr>
                                        <td>{{$user->username}}</td>
                                        <td>{{$user->boughtLastMonth()}}</td>
                                        <td>{{$user->childrenCount()}}</td>
                                        <td @if(($user->boughtLastMonth() / $user->childrenCount()) > 1) style="color: #34B73A" @else style="color:#FF2121" @endif>{{$user->boughtLastMonth() / $user->childrenCount()}}</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

