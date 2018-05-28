<body class="cmsinmotion">
	<header class="navbar navbar-fixed-top header-pages" role="navigation">
		<div class="container-fluid">
		<!--Toggle menu-->
		
			<!--heaader-->
			<div class="navbar-header">
				<div class="navbar-nav">
					<ul class="navbar-nav">
						<li class="navbar-title">
							<a href="<?=base_path_rewrite;?>/pages/">
								<i class="fa fa-chevron-left"></i>
							</a>
							<span><?=$page->title;?></span>
						
						</li>
					</ul>
				</div>
			</div>
			<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
				<ul class="navbar-nav navbar-right">
					<li class="navbar-devices selectdevice">
						<div id="dd" class="wrapper-dropdown-1" tabindex="1">
							<span>Device: 100%</span>
							<ul class="dropdown" tabindex="1">
								<li id="device-iphone"><a href="#">iPhone</a></li>
								<li id="device-ipad"><a href="#">iPad</a></li>
								<li id="device-100"><a href="#">100%</a></li>
							</ul>
						</div>
					</li>	
					<li>
						<a href="<?=base_path_rewrite;?>/pages/render" target="_new" class="btn-publish"><i class="fa fa-sign-in"></i> Publish site</a>
					</li>
				</ul>
			</nav>
	  		</div>
	</header>

<div class="container">
	<?=flash();?>
</div>