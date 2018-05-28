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
		            <li>
						<a href="<?=base_path_rewrite;?>/administration/" class="link">
							<span>Control Panel</span>
						</a>
		            </li>
		            <li>
						<a href="<?=base_path_rewrite;?>/license/" class="link">
							<span>License<span>
						</a>
		            </li>
		            <li>
		            	<a href="<?=base_path_rewrite;?>/logout/" class="link">
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
							<span><?=$globalSettings->siteTitle;?></span>
						</li>
					</ul>
				</div>
			</div>
			
			
		<?php //if ($globalSettings->isMultiLanguage == 1 && $MLhasLicenseNoSupport == 0) { ?>
		<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
			<ul class="navbar-nav navbar-right">
				<li class="navbar-devices selectdevice">
					<div id="dd" class="wrapper-dropdown-1" tabindex="1" style="width: 290px;">
						<span>Language: <?=$selectedLanguage;?></span>
						<ul class="dropdown" tabindex="1">
							<?php foreach($languages as $language) { ?>
							<li id="language-<?=$language['id'];?>"><a href="<?=base_path_rewrite;?>/administration/changelanguage/<?=$language['id'];?>"><?=$language['name'];?></a></li>
							<?php } ?>
							<li id="language-new"><a href="#extraLanguage" data-toggle="modal">+ Add new</a></li>
							
						</ul>
					</div>
				</li>
			</ul>
		</nav>
		
		<?php //} ?>
			
	  	</div><!--container fluid-->
	</header>	