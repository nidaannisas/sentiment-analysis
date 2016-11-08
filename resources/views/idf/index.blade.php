@extends('layout')
@section('content')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main" ng-controller="tfidfController">
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
			<li class="active">TF-IDF</li>
		</ol>
	</div><!--/.row-->

	<div class="row" style="margin-top : 20px;">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="panel-heading">IDF</div>
					<div class="col-md-4" style="padding-bottom: 20px;">
						<h3>TF-IDF</h3>
						<p>Klik Process untuk menghitung nilai TF-IDF.</p>
						<form class="form-inline" role="form" action="{{ URL::to('dashboard/idf/process') }} " method="post" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<button class="btn btn-default" type="submit">Process</button>
						</form>
					</div>
					<div class="col-md-4" style="padding-bottom: 20px;">
						<h3>DF Selection</h3>
						<p>Klik Process untuk menseleksi fitur dengan nilai df diatas tertentu.</p>
						<form role="form" action="{{ URL::to('dashboard/idf/dfselection') }} " method="post" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-group">
								<label>Value</label>
								<input name="selection" type="text" class="form-control"></input>
							</div>
							<button class="btn btn-danger pull-right" type="submit">Remove</button>
						</form>
					</div>
					<div class="col-md-4" style="padding-bottom: 20px;">
						<h3>IDF Selection</h3>
						<p>Klik Process untuk menseleksi fitur dengan nilai dibawah tertentu.</p>
						<form role="form" action="{{ URL::to('dashboard/idf/selection') }} " method="post" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-group">
								<label>Value</label>
								<input name="selection" type="text" class="form-control"></input>
							</div>
							<button class="btn btn-danger pull-right" type="submit">Remove</button>
						</form>
					</div>

					<hr style="color: black; width: 100%;">

					<div class="col-md-12">
                        <form>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                    <input type="text" class="form-control" placeholder="Search" ng-model="search">
                                </div>
                            </div>
                        </form>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td>
                                        <a ng-click="sortType = 'id'; sortReverse = !sortReverse">
                                            id
                                            <span ng-show="sortType == 'id' && !sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'id' && sortReverse" class="fa fa-caret-up"></span>
                                        </a>
                                    </td>
                                    <td>
                                        <a ng-click="sortType = 'word'; sortReverse = !sortReverse">
                                            Term
                                            <span ng-show="sortType == 'word' && !sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'word' && sortReverse" class="fa fa-caret-up"></span>
                                        </a>
                                    </td>
                                    <td>
                                        <a ng-click="sortType = 'count_positive'; sortReverse = !sortReverse">
                                            Count Positive
                                            <span ng-show="sortType == 'count_positive' && !sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'count_positive' && sortReverse" class="fa fa-caret-up"></span>
                                        </a>
                                    </td>
                                    <td>
                                        <a ng-click="sortType = 'count_negative'; sortReverse = !sortReverse">
                                            Count Negative
                                            <span ng-show="sortType == 'count_negative' && !sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'count_negative' && sortReverse" class="fa fa-caret-up"></span>
                                        </a>
                                    </td>
                                    <td>
                                        <a ng-click="sortType = 'count_neutral'; sortReverse = !sortReverse">
                                            Count Neutral
                                            <span ng-show="sortType == 'count_neutral' && !sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'count_neutral' && sortReverse" class="fa fa-caret-up"></span>
                                        </a>
                                    </td>
                                    <td>
                                        <a ng-click="sortType = 'count'; sortReverse = !sortReverse">
                                            Count
                                            <span ng-show="sortType == 'count' && !sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'count' && sortReverse" class="fa fa-caret-up"></span>
                                        </a>
                                    </td>
                                    <td>
                                        <a ng-click="sortType = 'count_tweet'; sortReverse = !sortReverse">
                                            DF
                                            <span ng-show="sortType == 'count_tweet' && !sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'count_tweet' && sortReverse" class="fa fa-caret-up"></span>
                                        </a>
                                    </td>
                                    <td>
                                        <a ng-click="sortType = 'idf'; sortReverse = !sortReverse">
                                            IDF
                                            <span ng-show="sortType == 'idf' && !sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'idf' && sortReverse" class="fa fa-caret-up"></span>
                                        </a>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr dir-paginate="data in datas | orderBy:sortType:sortReverse | filter:search|itemsPerPage:10">
                                    <td ng-bind="data.id"></td>
                                    <td ng-bind="data.word"></td>
                                    <td ng-bind="data.count_positive"></td>
                                    <td ng-bind="data.count_negative"></td>
                                    <td ng-bind="data.count_neutral"></td>
                                    <td ng-bind="data.count"></td>
                                    <td ng-bind="data.count_tweet"></td>
                                    <td ng-bind="data.idf"></td>
                                </tr>
                            </tbody>
                        </table>

                        <dir-pagination-controls
                            max-size="7"
                            direction-links="true"
                            boundary-links="true" class="pull-right">
                        </dir-pagination-controls>
					</div>
				</div>
			</div>
		</div>
	</div><!--/.row-->


</div><!--/.main-->

<!-- CSS -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

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
