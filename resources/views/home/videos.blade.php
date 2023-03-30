@extends('layouts.landing')

@section('title')
    Videos
@endsection
@section('content')
<div class="container">

    <?php if(isset($page_title)): ?>
    <h3><?= $page_title ?><?php if(isset($page_description)): ?><span><?= $page_description ?></span><?php endif; ?></h3>
    <?php endif; ?>
    <div class="row">
        @each('partials.video-loop', $videos, 'video')
    </div>

    <div class="pagination">
        @if($current_page != 1)
            <a class="previous_page" href="{{ $pagination_url }}/?page={{  intval($current_page - 1) }}">Prev Page</a>
        @endif
        @if($videos->hasMorePages())
            <a class="next_page" href="{{ $pagination_url }}/?page={{ intval($current_page + 1) }}">Next Page</a>
        @endif
    </div>

</div>
@endsection