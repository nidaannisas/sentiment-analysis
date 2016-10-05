@extends('layout')

@section('content')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
			<li class="active">Evaluation</li>
		</ol>
	</div><!--/.row-->

	<div class="row" style="margin-top : 20px;">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="panel-heading">Evaluation</div>
					<div class="col-md-4" style="padding-bottom: 20px;">
                        <h3>K-fold Cross Validation</h3>
                        <form role="form" action="{{ URL::to('dashboard/naive-bayes/classify') }} " method="post" style="padding-top : 20px;">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
								<label>Pembagian Data</label><br>
                                <div class="col-md-3 no-padding">
        							<input name="train" type="text" class="form-control"></input>
                                </div>
                                <div class="col-md-1 text-center"> : </div>
                                <div class="col-md-3 no-padding">
        							<input name="test" type="text" class="form-control"></input>
                                </div>
                            </div>
                            <br><br>
                            <div class="form-group">
								<label>K</label><br>
                                <input name="k" type="text" class="form-control" style="width:25%;"></input>
                            </div>

                            <p>
                                Last updated at 3 January 2016
                            </p>
                            <button class="btn btn-default pull-right" type="submit">Evaluate</button>
						</form>
					</div>
					<div class="col-md-8" style="padding-bottom: 20px;">
                        <div class="row">
                            <div class="col-xs-6 col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-body easypiechart-panel" style="padding: 10%;">
                                        <h3>Accuracy</h3>
                                        <div class="easypiechart" id="easypiechart-blue" data-percent="60" ><span class="percent">60%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-body easypiechart-panel">
                                        <h3>Precision</h3>
                                        <div class="easypiechart" id="easypiechart-red" data-percent="55" ><span class="percent">55%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-body easypiechart-panel">
                                        <h3>Recall</h3>
                                        <div class="easypiechart" id="easypiechart-teal" data-percent="80" ><span class="percent">80%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!--/.row-->

					</div>
					<hr style="color: black; width: 100%;">


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
<script src="{{ URL::to('js/chart.min.js') }}"></script>
<script src="{{ URL::to('js/chart-data.js') }}"></script>
<script src="{{ URL::to('js/easypiechart.js') }}"></script>
<script src="{{ URL::to('js/easypiechart-data.js') }}"></script>
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

<style>
.easypiechart-panel
{
    padding: 10%;
}
</style>

@stop
