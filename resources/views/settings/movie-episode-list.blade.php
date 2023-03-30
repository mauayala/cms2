@extends('layout.default')

@section('content')
	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-8">
				<div class="panel panel-primary" data-collapsed="0">
					<div class="panel-heading">
						<div class="panel-title">
							Movie, episode list
						</div>
					</div>
					<div class="panel-body">
                        <div>
                            <form class="form-inline" method="get" action="/dashboard/settings/export">
                                <div class="form-group">
                                    <label for="month">Month</label>
                                    <select id="month" name="month" class="form-control">
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="year">Year</label>
                                    <input type="text" name="year" id="year" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-success">Export</button>
                            </form>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection