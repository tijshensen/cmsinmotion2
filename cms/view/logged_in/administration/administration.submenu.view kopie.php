	
  <div class="container">
    <div class="row">
      <div class="col col-sm-6">
        <h1><a href="#" title="Dashboard">Administration</a>
          <p class="lead">Use this panel to configure the CMS.</p></h1>
      </div>
    </div>
  </div>
  
  <div class="container">
    <div class="row">
      <div class="col col-sm-2">
      
      	<ul class="nav nav-pills nav-stacked" style="margin-top: 17px;">
		  <li <?=($requestAction == '' || $requestAction == 'sessions' ? 'class="active"' : '');?>><a href="<?=base_path_rewrite;?>/administration/">Active Sessions</a></li>
		  <li <?=($requestAction == 'users' ? 'class="active"' : '');?>><a href="<?=base_path_rewrite;?>/administration/users">Users</a></li>
		  <?php if ($_SESSION['rights']['accessAdvanced'] == 1) { ?>
		  <li <?=($requestAction == 'groups' ? 'class="active"' : '');?>><a href="<?=base_path_rewrite;?>/administration/groups">Groups/ACL</a></li>
		  <?php } ?>
		  <li <?=($requestAction == 'logging' ? 'class="active"' : '');?>><a href="<?=base_path_rewrite;?>/administration/logging">Logging</a></li>
		  <li <?=($requestAction == 'load' || $requestAction == 'templates' ? 'class="active"' : '');?>><a href="<?=base_path_rewrite;?>/administration/load">Templates</a></li>
		  <li <?=($requestAction == 'sitesettings' ? 'class="active"' : '');?>><a href="<?=base_path_rewrite;?>/administration/sitesettings">Site Settings</a></li>
		  <li <?=($requestAction == 'sitemap' ? 'class="active"' : '');?>><a href="<?=base_path_rewrite;?>/administration/sitemap">Sitemap</a></li>
		  <?php if ($globalSettings->isMultiLanguage == 1) { ?>
		  <li <?=($requestAction == 'languages' ? 'class="active"' : '');?>><a href="<?=base_path_rewrite;?>/administration/languages">Languages</a></li>
		  <?php } ?>
		</ul>	
      
      </div>