@extends('layout')

@section('content')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
			<li class="active">Pembagian Data</li>
		</ol>
	</div><!--/.row-->

	<div class="row" style="margin-top : 20px;">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="panel-heading">Pembagian Data</div>
                    <?php if(session()->has('error')) echo '<div class="alert bg-danger" role="alert"><svg class="glyph stroked cancel"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#stroked-cancel"></use></svg> '.session('error').'<a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a></div>'; ?>
                    <?php if(session()->has('error')) echo '<div class="alert bg-success" role="alert">
					<svg class="glyph stroked checkmark"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#stroked-checkmark"></use></svg> '.session('success').' <a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a></div>'; ?>
                    <div class="col-md-4" style="padding-bottom: 20px;">
                        <h3>Random</h3>
                        <form role="form" action="{{ URL::to('dashboard/pembagian-data/process') }} " method="post" style="padding-top : 20px;">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-md-12" style="padding-left:0;">
                                <div class="col-md-6" style="padding-left:0;">
                                    <div class="form-group">
        								<label>Data Latih (%)</label>
        								<input id="data_train" name="data_train" type="text" class="form-control"></input>
        							</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
        								<label>Data Uji (%)</label>
        								<input id="data_test" name="data_test" type="text" class="form-control"></input>
        							</div>
                                </div>
                            </div>
                            <p>
                                Atau
                            </p>
                            <div class="col-md-12" style="padding-left:0;">
                                <div class="col-md-4" style="padding-left:0;">
                                    <div class="form-group">
        								<label>Positive</label>
        								<input id="positive" name="positive" value="{{ $pembagian->positive }}" type="text" class="form-control"></input>
        							</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
        								<label>Negative</label>
        								<input id="negative" name="negative" value="{{ $pembagian->negative }}" type="text" class="form-control"></input>
        							</div>
                                </div>
                                <div class="col-md-4" style="padding-right:0;">
                                    <div class="form-group">
        								<label>Neutral</label>
        								<input id="neutral" name="neutral" value="{{ $pembagian->neutral }}" type="text" class="form-control"></input>
        							</div>
                                </div>
                            </div>

                            <br />
                            <div class="form-group">
                                <p>Last updated at {{ $pembagian->created_at }}</p>
                            </div>
                            <button class="btn btn-default pull-right" type="submit">Process</button>
						</form>
					</div>
					<div class="col-md-8" style="padding-bottom: 20px;">
                        <div class="row">
                            <div class="col-xs-6 col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-body easypiechart-panel" style="padding: 10%;">
                                        <h3>Positive</h3>
                                        <div class="easypiechart" id="easypiechart-blue" data-percent="{{ $positive*100/($positive+$negative+$neutral) }}" ><span class="percent">{{ $positive }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-body easypiechart-panel">
                                        <h3>Negative</h3>
                                        <div class="easypiechart" id="easypiechart-red" data-percent="{{ $negative*100/($positive+$negative+$neutral) }}" ><span class="percent">{{ $negative }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-body easypiechart-panel">
                                        <h3>Neutral</h3>
                                        <div class="easypiechart" id="easypiechart-teal" data-percent="{{ $neutral*100/($positive+$negative+$neutral) }}" ><span class="percent">{{ $neutral }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!--/.row-->

					</div>
					<hr style="color: black; width: 100%;">
					<div class="col-md-12">
						<table data-toggle="table"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
						    <thead>
						    <tr>
						        <th data-field="id" data-checkbox="true">Item ID</th>
						        <th data-field="tweet"  data-sortable="true" style="width: 80%;">Tweet</th>
						        <th data-field="sentiment" data-sortable="true">Sentiment</th>
                                <th data-field="type" data-sortable="true">Type</th>
						    </tr>
						    </thead>
						    <tbody>
						    @foreach($tweets as $tweet)
						    <tr>
						    	<td>{{ $tweet->id }}</td>
						    	<td>{{ $tweet->tweet }}</td>
						    	<td>{{ $tweet->sentiment->name }}</td>
                                <td>{{ $tweet->type }}</td>
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
<script src="{{ URL::to('js/chart.min.js') }}"></script>
<script src="{{ URL::to('js/chart-data.js') }}"></script>
<script src="{{ URL::to('js/easypiechart.js') }}"></script>
<script src="{{ URL::to('js/easypiechart-data.js') }}"></script>
<script>
    $('#data_train').change(function() {
        var positive = {{ $positive }};
        var negative = {{ $negative }};
        var neutral = {{ $neutral }};

        var train = $('#data_train').val();

        positive = Math.round(train*positive/100);
        negative = Math.round(train*negative/100);
        neutral = Math.round(train*neutral/100);

        $('#positive').val(positive);
        $('#negative').val(negative);
        $('#neutral').val(neutral);
    });
</script>
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
