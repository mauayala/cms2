@extends('layout.default')

@section('content')
<style type="text/css">
    .panel-body > div {
        margin-bottom: 10px;
    }
</style>
<div id="admin-container">    
    <div class="admin-section-title">
        <h3><i class="entypo-down-circled"></i> Report</h3> 
    </div>
    <div class="clear"></div>
        <form method="POST" action="/dashboard/users/report">
            {{csrf_field()}}
            <div class="form-group">
                <label for="month">Month</label>
                <input type="number" name="month" id="month" class="form-control">
            </div>
            <input type="submit" value="Request" class="btn btn-success pull-right" />
        </form>

        <div class="clear"></div>
<!-- This is where now -->
</div>
@endsection