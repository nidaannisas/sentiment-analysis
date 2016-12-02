@extends('layout')

@section('content')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
			<li class="active">Naive Bayes Evaluation</li>
		</ol>
	</div><!--/.row-->

	<div class="row" style="margin-top : 20px;">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<!-- <div class="panel-body">
					<div class="panel-heading">Evaluation</div>
					<div class="col-md-4" style="padding-bottom: 20px;">
                        <h3>K-fold Cross Validation</h3>
                        <form role="form" action="{{ URL::to('dashboard/evaluation/evaluate') }} " method="post" style="padding-top : 20px;">
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

                        </div>

					</div>
					<hr style="color: black; width: 100%;">
				</div> -->

                <div class="panel-body">
					<div class="panel-heading">Evaluation</div>
                    <div class="col-md-6">
						<h3>Evaluation</h3>
						<p>Tambahkan note untuk evaluasi yang akan dilakukan.</p>
					</div>
					<div class="col-md-6" style="padding-bottom: 20px;">
						<form role="form" action="{{ URL::to('dashboard/evaluation/evaluate') }} " method="post" style="padding-top : 20px;">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-group">
								<label>Note</label>
								<textarea name="note" class="form-control" rows="3"></textarea>
							</div>
                            <div class="form-group">
								<label>Data</label>
								<select class="form-control" name="data">
									<option value="TRAIN">Train</option>
                                    <option value="TEST">Test</option>
                                    <option value="ALL">All</option>
								</select>
							</div>
                            <div class="form-group checkbox">
							    <label><input name="feature_selection" type="checkbox">Feature Selection</label>&nbsp;&nbsp;&nbsp;
                                <label><input name="negation_handling" type="checkbox">Negation Handling</label>
							</div>
							<button class="btn btn-primary pull-right" type="submit">Process</button>
						</form>
					</div>
					<hr style="color: black; width: 100%;">
					<div class="col-md-12">
						<table data-toggle="table"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
						    <thead>
						    <tr>
						        <th data-field="id" data-checkbox="true">ID</th>
						        <th data-field="accuracy"  data-sortable="true" style="width: 80%;">Accuracy</th>
						        <th data-field="precision_positive" data-sortable="true">Precision<br />Positive</th>
                                <th data-field="precision_negative" data-sortable="true">Precision<br /> Negative</th>
                                <th data-field="precision_neutral" data-sortable="true">Precision<br /> Neutral</th>
                                <th data-field="recall_positive" data-sortable="true">Recall<br /> Positive</th>
                                <th data-field="recall_negative" data-sortable="true">Recall<br /> Negative</th>
                                <th data-field="recall_neutral" data-sortable="true">Recall<br /> Neutral</th>
                                <th data-field="process_time" data-sortable="true">Process<br /> Time</th>
                                <th data-field="note" data-sortable="true">Note</th>
						    </tr>
						    </thead>
						    <tbody>
						    @foreach($evaluations as $evaluation)
						    <tr>
						    	<td>{{ $evaluation->id }}</td>
						    	<td>{{ $evaluation->accuracy }}</td>
                                <td>{{ $evaluation->precision_positive }}</td>
                                <td>{{ $evaluation->precision_negative }}</td>
                                <td>{{ $evaluation->precision_neutral }}</td>
                                <td>{{ $evaluation->recall_positive }}</td>
                                <td>{{ $evaluation->recall_negative }}</td>
                                <td>{{ $evaluation->recall_neutral }}</td>
                                <td>{{ $evaluation->process_time }}</td>
                                <td>{{ $evaluation->note }}</td>
							</tr>
							@endforeach
						    </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>


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
