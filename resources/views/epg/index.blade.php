@extends('layout.default')

@section('content')

    <div id="admin-container">
        <!-- This is where -->

        <div class="admin-section-title">
            <div class='col-md-3'><h3>EPG Channels</h3></div>
            <div class='col-md-3'><a href='{{route('dashboard.epg.channels.create')}}' class='btn btn-success'>Add</a></div>
            <div class='col-md-3'><a href='{{route('dashboard.epg.categories.index')}}' class='btn btn-success'>EPG Categories</a></div>
            <div class='col-md-3'><a href='/parse?updated_by=admin' class='btn btn-success'>Parse</a></div>
        </div>
        <div class='clear'></div>

        <div class="panel panel-primary" data-collapsed="0"> 
            <div class="panel-heading">
                <div class="panel-title">Channels</div>
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            <div class="panel-body">
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <td style='width:66px;display:inline-block;'>Is link live?</td>   
                            <td>Logo</td>
                            <td>Name</td>
                            <td>Link</td>
                            <td>Category</td>
                            <td>Is Premiered?</td>
                            <td>Is Visible?</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $updated_by = '';?>
                        @foreach($epgcategories as $category)
                            @if(count($category->channels) > 0)
                                @foreach($category->channels as $c)
                                    @if($c->active)
                                        <?php $updated_by = $c->updated_by; ?>
                                        <tr>
                                            <td>@if($c->link_live)<img src='/link_live.png' width='50' class='link_live'>@endif</td>
                                            <td><img src='https://s3.amazonaws.com/ctv3/{{$c->icon}}' width='50'></td>
                                            <td>{{$c->name}}</td>
                                            <td>{{$c->link}}</td>
                                            <td>{{$category->name}}</td>
                                            <td>@if($c->is_premiered)Yes @else No @endif</td>
                                            <td>@if($c->visible)<span style='color:green'>Yes</span> @else <span style='color:red'>No</span> @endif</td>
                                            <td>
                                                <a href='{{route('dashboard.epg.channels.show', ['channel' => $c->id])}}' class='btn btn-success'>Edit</a>
                                                <form action="{{ route('dashboard.epg.channels.destroy', ['channel' => $c->id]) }}" method="post">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button class="delete">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
                Updated by: {{$updated_by}}
            </div>
        </div>
    </div>
    @section('javascript')
        <script>
            setInterval(function(){
                $('.link_live').hide()
                setTimeout(function(){
                    $('.link_live').show()
                }, 500);
            }, 1000);        
        </script>
    @endsection
@endsection
