@extends('layout')

@section('content')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
			<li class="active">Naive Bayes</li>
		</ol>
	</div><!--/.row-->
	
	<div class="row" style="margin-top : 20px;">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="panel-heading">Naive Bayes</div>
					<div class="col-md-6">
						<h3>Klasifikasi Naive Bayes</h3>
						<p>Tambahkan tweets pada form disamping untuk klasifikasi menggunakan naive bayes.</p>
					</div>
					<div class="col-md-6" style="padding-bottom: 20px;">
						<form role="form" action="{{ URL::to('dashboard/naive-bayes/classify') }} " method="post" style="padding-top : 20px;">	
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-group"> 
								<label>Tweets</label>
								<textarea name="tweet" class="form-control" rows="4"></textarea>
							</div>
							<button class="btn btn-primary pull-right" type="submit">Classify</button>
						</form>
					</div>
					<hr style="color: black; width: 100%;">
					<div class="col-md-12">
						<table data-toggle="table"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true">
						    <thead>
						    <tr>
						        <th data-field="state" data-checkbox="true">Item ID</th>
						        <th data-field="name"  data-sortable="true" style="width: 80%;"">Tweet</th>
						        <th data-field="price" data-sortable="true">Sentiment</th>
						    </tr>
						    </thead>
						    <tbody>
						    @foreach($tweets as $tweet)
						    <tr>
						    	<td>{{ $tweet->id }}</td>
						    	<td>{{ $tweet->tweet }}</td>
						    	<td>{{ $tweet->sentiment->name }}</td>
							</tr>
							@endforeach
						    </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div><!--/.row-->	
	
	
</div><!--/.main-->

<link href="{{ URL::to('css/bootstrap-table.css') }}" rel="stylesheet">

<script src="{{ URL::to('js/jquery-1.11.1.min.js') }}"></script>
<script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
<script src="{{ URL::to('js/bootstrap-datepicker.js') }}"></script>
<script src="{{ URL::to('js/bootstrap-table.js') }}"></script>
<script>
	!function ($) {
		$(document).on("click","ul.nav li.parent > a > span.icon", function(){		  
			$(this).find('em:first').toggleClass("glyphicon-minus");	  
		}); 
		$(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
	}(window.jQuery);

	$(window).on('resize', function () {
	  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
	})
	$(window).on('resize', function () {
	  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
	})
</script>	

@stop