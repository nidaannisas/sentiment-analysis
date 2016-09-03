<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sentiment Analysis</title>

<link href="{{ URL::to('css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::to('css/datepicker3.css') }}" rel="stylesheet">
<link href="{{ URL::to('css/styles.css') }}" rel="stylesheet">

<!--Icons-->
<script src="{{ URL::to('js/lumino.glyphs.js') }}"></script>

<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><span>Lumino</span>Admin</a>
				<ul class="user-menu">
					<li class="dropdown pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> User <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> Profile</a></li>
							<li><a href="#"><svg class="glyph stroked gear"><use xlink:href="#stroked-gear"></use></svg> Settings</a></li>
							<li><a href="#"><svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"></use></svg> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
							
		</div><!-- /.container-fluid -->
	</nav>
		
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<form role="search">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
		</form>
		<ul class="nav menu">
			<li class="<?php if (Request::is('/')) echo 'active'; ?>"><a href="{{ URL::to('/') }}"><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg> Dashboard</a></li>
			<li class="<?php if (Request::is('dashboard/tweets')) echo 'active'; ?>"><a href="{{ URL::to('dashboard/tweets') }}"><svg class="glyph stroked empty message"><use xlink:href="#stroked-empty-message"/></svg> Tweets</a></li>
			<li class="<?php if (Request::is('dashboard/tokenizing')) echo 'active'; ?>"><a href="{{ URL::to('dashboard/tokenizing') }}"><svg class="glyph stroked chain"><use xlink:href="#stroked-chain"/></svg> Tokenizing</a></li>
			<li class="<?php if (Request::is('dashboard/stopwords')) echo 'active'; ?>"><a href="{{ URL::to('dashboard/stopwords') }}"><svg class="glyph stroked trash"><use xlink:href="#stroked-trash"/></svg> Stopwords</a></li>
			<li class="<?php if (Request::is('dashboard/normalization')) echo 'active'; ?>"><a href="{{ URL::to('dashboard/normalization') }}"><svg class="glyph stroked pen tip"><use xlink:href="#stroked-pen-tip"/></svg> Word Normalization</a></li>
			<li class="<?php if (Request::is('dashboard/idf')) echo 'active'; ?>"><a href="{{ URL::to('dashboard/idf') }}"><svg class="glyph stroked notepad "><use xlink:href="#stroked-notepad"/></svg> IDF</a></li>
		</ul>

	</div><!--/.sidebar-->
		
	@yield('content')

</body>

</html>
