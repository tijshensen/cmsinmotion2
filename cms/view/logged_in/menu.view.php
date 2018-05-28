<header class="navbar header-pages">	
	<div class="container-fluid">
		<div class="leftmenu">
			<div id="menu" class="menu-toggle" >
			    <ul class="controlpan">
			        <li>
						<a class="showMenu" href="#">
							<i class="fa fa-angle-left"></i>
							<span>Settings</span>
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
							<i class="fa fa-file-code-o"></i>
							<span>Inserts</span>
						</a>
			        </li>
		            <li>
						<a href="<?=base_path_rewrite;?>/administration/load/" class="link">
							<i class="fa fa-sliders "></i>
							<span>Settings</span>
						</a>
		            </li>
		            <li>
		            	<a href="<?=base_path_rewrite;?>/logout/" class="link">
							<i class="fa fa-sign-out "></i>
							<span>Logout</span>
						</a>
		            </li>
			      
			    </ul>
			</div>
		</div>

			<!--heaader-->
			
		
		<div class="navbar-header">
			<div class="navbar-nav">
				<ul class="navbar-nav">
					<li class="navbar-title">
						<a class="showMenu" href="#"><i class="fa fa-bars"></i></a>
						<span>Settings - <?=$globalSettings->siteTitle;?></span>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>