@extends('layout')

@section('content')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
			<li class="active">IDF</li>
		</ol>
	</div><!--/.row-->
	
	<div class="row" style="margin-top : 20px;">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="panel-heading">IDF</div>
					<div class="col-md-12" style="padding-bottom: 20px;">
						<h3>IDF</h3>
						<p>Klik Process untuk menghitung nilai IDF.</p>
						<form class="form-inline" role="form" action="{{ URL::to('dashboard/idf/process') }} " method="post" enctype="multipart/form-data">	
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<button class="btn btn-default" type="submit">Process</button>
						</form>
					</div>

					<hr style="color: black; width: 100%;">

					<div class="col-md-12">
						<table data-toggle="table"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
						    <thead>
						    <tr>
						        <th data-field="state" data-checkbox="true">Item ID</th>
						        <th data-field="token"  data-sortable="true" style="width: 80%;"">Token</th>
						        <th data-field="positive"  data-sortable="true" style="width: 80%;"">Positive</th>
						        <th data-field="negative"  data-sortable="true" style="width: 80%;"">Negative</th>
						        <th data-field="neutral"  data-sortable="true" style="width: 80%;"">Neutral</th>
						        <th data-field="IDF"  data-sortable="true" style="width: 80%;"">IDF</th>
						    </tr>
						    </thead>
						    <tbody>
						    @foreach($words as $word)
						    <tr>
						    	<td>{{ $word->id }}</td>
						    	<td>{{ $word->word }}</td>
						    	<td></td>
						    	<td></td>
						    	<td></td>
						    	<td>{{ $word->idf }}</td>
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

<style type="text/css">
	.btn-file {
	    position: relative;
	    overflow: hidden;
	}
	.btn-file input[type=file] {
	    position: absolute;
	    top: 0;
	    right: 0;
	    min-width: 100%;
	    min-height: 100%;
	    font-size: 100px;
	    text-align: right;
	    filter: alpha(opacity=0);
	    opacity: 0;
	    outline: none;
	    background: white;
	    cursor: inherit;
	    display: block;
	}
</style>

@stop