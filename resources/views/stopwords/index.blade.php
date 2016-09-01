@extends('layout')

@section('content')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
			<li class="active">Stopwords</li>
		</ol>
	</div><!--/.row-->
	
	<div class="row" style="margin-top : 20px;">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="panel-heading">Stopwords</div>
					<div class="col-md-12" style="padding-bottom: 20px;">
						<h3>Stopword Removal</h3>
						<p>Klik Process untuk melakukan penghapusan stopwords.</p>
						<form class="form-inline" role="form" action="{{ URL::to('dashboard/stopwords/process') }} " method="post" enctype="multipart/form-data">	
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<button class="btn btn-default" type="submit">Process</button>
						</form>
					</div>

					<hr style="color: black; width: 100%;">

					<div class="col-md-6">
						<h3>Tambahkan Stopword</h3>
						<p>Tambahkan stopword melalui form disamping atau dengan import menggunakan txt, excel atau csv.</p>
						<form class="form-inline" role="form" action="{{ URL::to('dashboard/stopwords/importtxt') }} " method="post" enctype="multipart/form-data">	
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<label class="btn btn-success btn-file">
							    Import <input name="import" type="file" style="display: none;">
							</label>
						</form>
					</div>
					<div class="col-md-6" style="padding-bottom: 20px;">
						<form role="form" action="{{ URL::to('dashboard/stopwords/store') }} " method="post" style="padding-top : 20px;">	
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-group"> 
								<label>Stopword</label>
								<textarea name="word" class="form-control" rows="3"></textarea>
							</div>

							<button class="btn btn-primary pull-right" type="submit">Submit</button>
						</form>
					</div>
					<hr style="color: black; width: 100%;">
					<div class="col-md-12">
						<table data-toggle="table"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
						    <thead>
						    <tr>
						        <th data-field="state" data-checkbox="true">Item ID</th>
						        <th data-field="name"  data-sortable="true" style="width: 80%;"">Stopword</th>
						    </tr>
						    </thead>
						    <tbody>
						    @foreach($stopwords as $stopword)
						    <tr>
						    	<td>{{ $stopword->id }}</td>
						    	<td>{{ $stopword->word }}</td>
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