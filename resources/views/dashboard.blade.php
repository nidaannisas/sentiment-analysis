@extends('layout')

@section('content')

	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Icons</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Dashboard</h1>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-xs-6 col-md-4">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h3>Positive</h3>
						<div class="easypiechart" id="easypiechart-blue" data-percent="{{ $positive*100/$tweet }}" ><span class="percent">{{ round($positive*100/$tweet) }}%</span>
						</div>
						<h4>{{ $positive }}</h4>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-4">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h3>Negative</h3>
						<div class="easypiechart" id="easypiechart-red" data-percent="{{ $negative*100/$tweet }}" ><span class="percent">{{ round($negative*100/$tweet) }}%</span>
						</div>
						<h4>{{ $negative }}</h4>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-4">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h3>Neutral</h3>
						<div class="easypiechart" id="easypiechart-teal" data-percent="{{ $neutral*100/$tweet }}" ><span class="percent">{{ round($neutral*100/$tweet) }}%</span>
						</div>
						<h4>{{ $neutral }}</h4>
					</div>
				</div>
			</div>
			
		</div><!--/.row-->
	
	</div>	<!--/.main-->

	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script>
		$('#calendar').datepicker({
		});

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