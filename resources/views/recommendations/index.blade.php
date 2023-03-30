@extends('layout.default')

@section('content')

	<div class="admin-section-title">
		<div class="row">
			<div class="col-md-12">
				<h3><i class="entypo-star"></i>Special Recommendations</h3>
			</div>			
		</div>
	</div>

	<div class="clear"></div>
		
	<div class="panel panel-primary category-panel" data-collapsed="0">
		<div class="panel-heading">
			<div class="panel-title">
				Recommendations
			</div>
			<div class="panel-options">
				<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
			</div>
		</div>
		<div class="panel-body">
			<div id="nestable" class="nested-list dd with-margins">
				<ol class="dd-list">
					@foreach($recommendations_raw as $recommendation)
						<li class="dd-item" data-id="{{ $recommendation->id }}">
							<div class="dd-handle">{{ $recommendation->video ? $recommendation->video->title : $recommendation->serie->title}}</div>
							<div class="actions">
								<form action="{{ route('dashboard.recommendations.destroy', ['recommendation' => $recommendation->id]) }}" method="post">
									@method('DELETE')
									@csrf
									<button class="delete">Delete</button>
								</form>
							</div>
						</li>
					@endforeach
				</ol>
			</div>
		</div>
	</div>

	@section('javascript')
		<script src="/admin/js/jquery.nestable.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.css"/ >
		<script src="/js/jquery.datetimepicker.full.min.js"></script>
		<script type="text/javascript">
			$('#start_date').datetimepicker({
				format:'d.m.Y H:i'
			})
			$('#end_date').datetimepicker({
				format:'d.m.Y H:i'
			})
			jQuery(document).ready(function($){
				$('#nestable').nestable({ maxDepth: 2 });

				// Add New Category
				$('#submit-new-cat').click(function(){
					if($('#poster').val() == '') { 
						toastr.error('You need to choose poster to add new event')
						return false; 
					}
					$('#new-cat-form').submit();
				});

				$('#submit-new-option').click(function(){
					$('#new-option-form').submit();
				});

				$('.actions .edit').click(function(e){
					$('#update-category').modal('show', {backdrop: 'static'});
					e.preventDefault();
					href = $(this).attr('href');
					$.ajax({
						url: href,
						success: function(response)
						{
							$('#update-category .modal-content').html(response);
						}
					});
				});

				$('.actions .delete').click(function(e){
					e.preventDefault();
					console.log($(this).closest('form'))
					if (confirm("Are you sure you want to delete this category?")) {
					   $(this).closest('form').submit();
					}
					return false;
				});

				$('.dd').on('change', function(e) {
					$('.category-panel').addClass('reloading');
					$.post(
						'/dashboard/recommendations/order', 
						{ 
							order : JSON.stringify($('.dd').nestable('serialize')), 
							_token : "{{csrf_token()}}"
						}, 
						function(data){
							if(data == 0){
								toastr.error('Cannot change option to event');
								setTimeout(location.reload(), 2000);
							} else if(data == 2) {
								toastr.error('Option must be in an event');
								setTimeout(location.reload(), 2000);
							}
							$('.category-panel').removeClass('reloading');
						}
					);

				});
			});
		</script>
	@endsection
@endsection