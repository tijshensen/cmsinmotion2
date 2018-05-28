<body class="cmsinmotion">	
	
	<header class="navbar navbar-fixed-top header-pages" role="navigation">
		<div class="container-fluid">
		<!--Toggle menu-->
		<div class="leftmenu">
			<div id="menu" class="menu-toggle" >
			    <ul class="controlpan">
			        <li>
						<a class="showMenu" href="#">
							<i class="fa fa-bars"></i>
							<span>CMSinMotion</span>
						</a>
			        </li>
			        <li>
						<a href="<?=base_path_rewrite;?>/pages/" class="link">
							<i class="fa fa-file-o pages"></i>
							<span>Pages</span>
						</a>
			        </li>
			        <li>
						<a href="<?=base_path_rewrite;?>/inserts/" class="link">
							<i class="fa fa-file-code-o inserts"></i>
							<span>Inserts</span>
						</a>
			        </li>
					<li class="divider"></li>
			        <li class="config">
						<a href="#" class="link alldepartments">
							<i class="fa fa fa-cog"></i>
							<span>Settings</span>
						</a>
			        </li>
			        <ul class="alldepartmentslist">
			            <li>
							<a href="<?=base_path_rewrite;?>/administration/">Control Panel</a>
			            </li>
			            <li>
							<a href="<?=base_path_rewrite;?>/license/">License</a>
			            </li>
			            <li>
			            	<a href="<?=base_path_rewrite;?>/logout/">Logout</a>
			            </li>
			        </ul>
			    </ul>
			</div>
		</div>

			<!--heaader-->
		
			<div class="navbar-header">
				<div class="navbar-nav">
					<ul class="navbar-nav">
						<li class="navbar-title">
							<a class="showMenu" href="#"><i class="fa fa-bars"></i></a>
							<span>CMSinMotion - Rendering...</span>
						</li>
					</ul>
				</div>
			</div>
			
	  	</div><!--container fluid-->
	</header>	
	
<div class="container">
	<?=flash();?>
</div>
