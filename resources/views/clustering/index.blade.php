@extends('layout')

@section('content')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
			<li class="active">Tweets</li>
		</ol>
	</div><!--/.row-->

	<div class="row" style="margin-top : 20px;">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="panel-heading">Clustering</div>
                    <div class="col-md-12" style="margin:0; padding:0;">
                        <div class="col-md-6">
                            <h3>K-Means</h3>
                            <p>Clustering dengan menggunakan k-means</p>

                            <form class="form-inline" role="form" action="{{ URL::to('dashboard/clustering/process') }} " method="post" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-primary pull-right" type="submit">Process</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <h3>Cut Data</h3>
                            <p>Potong data yang override.</p>

                            <form class="form-inline" role="form" action="{{ URL::to('dashboard/clustering/cut') }} " method="post" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-primary pull-right" type="submit">Cut</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin:0; padding:0;">
					    <hr style="color: black; width: 100%;">
                    </div>
					<div class="col-md-12">
						<table class="table table-bordered">
						    <thead>
						    <tr>
						        <th></th>
                                <th class="table-head">Positive</th>
						        <th class="table-head">Negative</th>
						        <th class="table-head">Neutral</th>
						    </tr>
						    </thead>
						    <tbody>
						    <tr>
						    	<td class="table-left">Positive</td>
						    	<td class="table-data">123</td>
						    	<td class="table-data">135</td>
                                <td class="table-data">135</td>
							</tr>
                            <tr>
						    	<td class="table-left">Negative</td>
						    	<td class="table-data">123</td>
						    	<td class="table-data">135</td>
                                <td class="table-data">135</td>
							</tr>
                            <tr>
						    	<td class="table-left">Neutral</td>
						    	<td class="table-data">123</td>
						    	<td class="table-data">135</td>
                                <td class="table-data">135</td>
							</tr>
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

    .table-head
    {
        background-color: gainsboro;
        text-align: center;
        vertical-align: middle !important;
    }
    .table-left
    {
        background-color: gainsboro;
        vertical-align: middle;
        font-weight: 600;
    }
    .table-data
    {
        text-align: center;
        vertical-align: middle;
    }
</style>

@stop
