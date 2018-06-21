<body class="page wp-admin wp-core-ui js cmsinmotion nav-menus-php sticky-menu menu-max-depth-1 <?=$requestType;?>" screen_capture_injected="true">
	
	
	<?=flash();?>


<?php if ($globalSettings->isMultiLanguage == 1 && $MLhasLicenseNoSupport == 0) { ?>

<div id="extraLanguage" class="modal fade">
  <div class="modal-dialog">
  	<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Multilingual site - new language</h4>
      </div>
      <div class="modal-body">
        <p>Please select your <strong>new</strong> site language. It will be added to the list of selectable languages, and an additional render will be done specifically for this language when you publish the site.</p><br />
       
       <form action="<?=base_path_rewrite;?>/administration/add_multilingual" id="formLanguage_new" method="POST">
       
        <p>Please enter the site title for this language.</p><br />
        <input type="text" name="sitetitle" value="<?=$globalSettings->siteTitle;?>" class="form-control" />
        <br />
       
        <select name="firstLanguage" id="firstLanguage" class="form-control">
        	<option>English</option>
        	<option>German (Deutsch)</option>
        	<option>Spanish (Español)</option>
        	<option>Dutch (Nederlands)</option>
        	<option>French (Français)</option>
        </select>
        <br />
        <p>Please enter the URL of the site for this language.</p><br />
        <input type="text" name="url" value="<?=base_path_site_url;?>" class="form-control" />
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="executeLanguageNew();">Add language</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">

	function executeLanguageNew() {
		$('#formLanguage_new').submit();
	}

</script>

<?php } ?>

<?php if($globalSettings->isMultiLanguage == 1 && $MLhasLicenseNoSupport == 1) { ?>

<div id="siteMultilanguageSetup" class="modal fade">
  <div class="modal-dialog">
  	<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Multilingual site setup</h4>
      </div>
      <div class="modal-body">
        <p>Thank you for purchasing the multilingual site license. To start creating sites in different languages, please select your <strong>current</strong> site language.</p><br />
       
       <form action="<?=base_path_rewrite;?>/administration/setup_multilingual" id="formLanguage" method="POST">
        <select name="firstLanguage" id="firstLanguage" class="form-control">
        	<option>English</option>
        	<option>German (Deutsch)</option>
        	<option>Spanish (Español)</option>
        	<option>Dutch (Nederlands)</option>
        	<option>French (Français)</option>
        </select>
        <br />
        <p>Please enter the URL of the site for this language.</p><br />
        <input type="text" name="url" value="<?=base_path_site_url;?>" class="form-control" />
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">I don't want to do this now</button>
        <button type="button" class="btn btn-primary" onclick="executeLanguageSetup();">Enable feature</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">

	function executeLanguageSetup() {
		$('#formLanguage').submit();
	}

</script>

<?php } ?>

<?php if($_SESSION['firstUse'] == 1) { $_SESSION['firstUse'] = 0; ?>

<div id="siteHelloUser" class="modal fade">
  <div class="modal-dialog">
  	<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Welcome your new CMSinMotion website</h4>
      </div>
      <div class="modal-body">
        <p>Thank you for purchasing a SaaS license for CMSinMotion. You can create your first site by uploading a template in <strong>Site Settings</strong>. A webdesigner can help you create a template for you.<br /><br />Should you need any assistance regarding the functioning of the CMS, please contact us at support@cmsinmotion.com.</p><br />
        <br />	
        <h4>Enjoy creating your website!</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">I want to look around first</button>
        <button type="button" class="btn btn-primary" onclick="window.location.href='<?=base_path_rewrite;?>/administration/load';">Take me to Site Settings</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">

		$('#siteHelloUser').modal();
	

</script>

<?php } ?>


	<header class="navbar navbar-fixed-top header-pages" role="navigation">
		<div class="container-fluid">
		<!--Toggle menu-->
			
			<div class="leftmenu">
				<div id="menu" class="menu-toggle" >
			    <ul class="controlpan">
			        <li>
						<a class="showMenu" href="#">
							<i class="fa fa-bars"></i>
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

			<!--header-->
			
		
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
					
			<?php if ($globalSettings->isMultiLanguage == 1 && $MLhasLicenseNoSupport == 0) { ?>
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
			<?php } ?>
			
	  	</div><!--container fluid-->
	</header>	




<div class="row" style="margin-top: 50px;">
	<div class="col-sm-7 col-md-8">
	  <!--start new page -->
	  <div class="page-left" style="margin:40px;">
		  
		  <div class="dropdown">
		    <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false" class="btn btn-primary">
		      New
		     
		    </button>
		    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
				<?php foreach($pageTypes as $pageType) { ?>
				<li>
					<a id="new-page-<?=$pageType['id'];?>" style="padding: 10px 20px;"><?=$pageType['name'];?></a>
				</li>
				<?php } ?>
		    </ul>
		  </div>
		  

	
		 <!--end new page -->
		 
         <!--start sortable menu-items --> 
		 
          <?php if (is_array($allPages)) { ?>
		<form id="update-nav-menu" action="" method="post" enctype="multipart/form-data">
         	<ul class="menu ui-sortable" id="menu-to-edit"> 
			
          <?php           
          // Check parent until zero
	function checkParent($parentID, $depth = 0, $allPagesWithID) {
	          $depth = $depth + 1;
	          if ($allPagesWithID[$parentID]['parent_id'] != 0) {
		          
		          return checkParent($allPagesWithID[$parentID]['parent_id'], $depth, $allPagesWithID);
		          			          
	          } else {
		          
		          return $depth;
		          
	          }		          
	          
          }
          
          foreach($allPages as $page) {
          if ($page['parent_id'] != 0) {
	         
	          $page['depth'] = checkParent($page['parent_id'], 0, $allPagesWithID);
	          
          } else {
	          
	          $page['depth'] = 0;
	          
          }
          
          if ($page['menu_title'] != "") { $page['title'] = $page['menu_title']; }
          
           ?>
          
          
          	<li id="menu-item-<?=$page['id'];?>" class="menu-item menu-item-category menu-item-depth-<?=$page['depth'];?> menu-item-edit-inactive" data-depth="<?=$page['depth'];?>">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title"  id='edit-settings-<?=$page['id'];?>'><span><?=$page['title'];?></span> <span class="is-submenu" style="<?php if($page['parent_id'] == 0) { ?>display: none;<?php } ?>">sub item</span></span>
					<span class="item-controls">
						<span class="item-type"><?=$pageTypes[$page['template_id']]['name'];?></span>
					</span>
				</dt>
			</dl>
			<ul class="menu-item-transport"></ul>
			<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?=$page['id'];?>]" value="<?=$page['id'];?>">
				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?=$page['id'];?>]" value="<?=$page['parent_id'];?>">
			</li>
		
	          
          <?php } ?>
                    
          </ul>
	  </form>    
	  </div><!--end page-left --> 
	     
	     <?php } ?>
      </div> <!--col end-->
	  
	  <div class="col-sm-5 col-md-4 hidden-xs"></div>
	  
    </div> <!-- row end-->

	
	  <?php 
          
                    
          foreach($allPages as $page) {
          
          if ($page['parent_id'] != 0) {
	         
	          $page['depth'] = checkParent($page['parent_id'], 0, $allPagesWithID);
	          
          } else {
	          
	          $page['depth'] = 0;
	          
          }
                    
           ?>

			<div class="menu-item-settings" id="menu-item-settings-<?=$page['id'];?>" style="display:none;">
				<div style="padding: 20px;">
									<a class="btn btn-success" href="<?=base_path_rewrite;?>/pages/edit/<?=$page['id'];?>" style="margin: 59px 0 45px 0;">Edit Page</a>
				<div class="form-group">
				    <label for="title">Menu title</label><!--override, kan een URL zijn of een sectie, maar oude link moet bewaard blijven -->
				    <input type="text" name="title[]" onkeyup="editMenuTitle(<?=$page['id'];?>, this.value);<?php if ($page['page_seo_name'] == "") { ?>createDefaultMenu(<?=$page['id'];?>, this.value);<?php } ?> savePageTimer(<?=$page['id'];?>);" id="menu_<?=$page['id'];?>_title" class="form-control" value="<?=$page['menu_title'];?>">
				</div>	
				<div class="form-group">
				    <label for="title">Location</label><!--override, kan een URL zijn of een sectie, maar oude link moet bewaard blijven -->
					<div class="input-group">
					    <div class="input-group-addon"><?=base_path_site_url;?>/</div>
					    <input type="text" name="location[]" onkeyup="savePageTimer(<?=$page['id'];?>);" value="<?=$page['page_seo_name'];?>" id="menu_<?=$page['id'];?>_location" class="form-control">
					</div>
				</div>		
				<!--
				<div class="form-group">
				    <label for="link">Link (use http:// to redirect outside website)</label>
				    <input type="text" name="link[]" id="menu_<?=$page['id'];?>_link" class="form-control">
				</div>
				<div class="form-group">
				    <label for="title-attribute">Menu title alternative (alt)</label>
				    <input type="text" name="title-attribute[]" id="menu_<?=$page['id'];?>_alt" class="form-control">
				</div>
				/-->
				<div class="form-group">
				    <label for="title-attribute">Title</label><!--override, kan een URL zijn of een sectie, maar oude link moet bewaard blijven -->
				    <input type="text" name="pagetitle[]" value="<?=$page['title'];?>" id="menu_<?=$page['id'];?>_pagetitle" class="form-control" onkeyup="countCharactersTitle(<?=$page['id'];?>); savePageTimer(<?=$page['id'];?>);">
				    <div class="charcount" id="charCountert_<?=$page['id'];?>">0/74</div>
				</div>
				<div class="form-group">
				    <label for="title-attribute">Description</label><!--override, kan een URL zijn of een sectie, maar oude link moet bewaard blijven -->
				    <textarea rows="3" name="page-description[]" id="menu_<?=$page['id'];?>_metadescription" class="form-control" onkeyup="countCharactersMeta(<?=$page['id'];?>); savePageTimer(<?=$page['id'];?>);"><?=$page['meta_description'];?></textarea>
				    <div class="charcount" id="charCounterp_<?=$page['id'];?>">0/170</div>
				</div>
				<div class="checkbox">
				    <label>
				      <input type="checkbox" name="frontpage[]" value="1" onchange="savePageTimer(<?=$page['id'];?>);" id="menu_<?=$page['id'];?>_default" <?=($page['isDefault'] == 1 ? 'checked' : '');?>> This is the frontpage
				    </label>
					<span class="ripple"></span>
					<span class="check"></span>
				</div>
				
				<div class="checkbox">
				    <label>
				      <input type="checkbox" name="inMenu[]" onchange="savePageTimer(<?=$page['id'];?>);" value="1" id="menu_<?=$page['id'];?>_inmenu" <?=($page['isHidden'] == 1 ? 'checked' : '');?>> Hide from menu
				    </label>
				</div>
				
				<div class="menu-item-actions description-wide submitbox">
					<a id="delete-<?=$page['id'];?>" href="" onclick="removeMenu(<?=$page['id'];?>);">Remove page</a>
				</div>

				
				</div>
			</div><!-- .menu-item-settings-->
			
		<?php } ?>
		
	<div class="col-sm-8" style="padding-top: 100px; display: none" id="newPage">
		<div style="padding: 20px;">
			<form action="<?=base_path_rewrite;?>/pages/new/0/commit" class="form-horizontal" method="POST">
			<input type="hidden" value="" name="template_id" id="templateIDnewPage" />
			<div style="font-size: 18pt; margin-bottom: 20px;">Enter the title of the page</div>
			<input type="text" value="" name="title" class="form-control" required/><br />
			
			<input type="submit" value="Start editing" class="btn btn-primary"/>
			</form>
		</div>
	</div>

 </div>
 
 <script type="text/javascript">
 
 	<?php foreach($allPages as $page) { ?>
 	$('#edit-settings-<?=$page['id'];?>').sidr({
      name: 'sidr-existing-content-<?=$page['id'];?>',
      source: '#menu-item-settings-<?=$page['id'];?>',
      side: 'right',
      renaming: false,
      displace: false,
      onOpen: function() {
	      
	   countCharactersTitle(<?=$page['id'];?>);
	   countCharactersMeta(<?=$page['id'];?>);   
	   NProgress.settings.parent = 'div#sidr-existing-content-<?=$page['id'];?> div.sidr-inner';
	   
      }
      
      
    });
    
    
    
    
    <?php } ?>
    
    <?php foreach($pageTypes as $pageType) {?>
    
    $('#new-page-<?=$pageType['id'];?>').sidr({
      name: 'sidr-newPage-<?=$pageType['id'];?>',
      source: '#newPage',
      side: 'right',
      renaming: false,
      displace: false,
      onOpen: function() {
	  	$('#sidr-newPage-<?=$pageType['id'];?> #templateIDnewPage').val(<?=$pageType['id'];?>);
	  	
	  }
    });
    
    
    <?php } ?>
    
    function editMenuTitle(id, content) {
        
        $('#edit-settings-'+id).html(content);
        
    }
    
    function launchNewPage(id) {
	    
	    
	    alert("page "+id);
	    
    }
    
    function countCharactersMeta(id) {
	    // 200
	 	mLength = $('#sidr-existing-content-'+id+' #menu_'+id+'_metadescription').val().length;
	 	$('#sidr-existing-content-'+id+' #charCounterp_'+id).html(mLength+"/170");
	 	if (mLength > 170) {
		 	
		 	$('#sidr-existing-content-'+id+' #menu_'+id+'_metadescription').css('color', '#FF0000');
		 	
	 	} else { 
		 	
		 	$('#sidr-existing-content-'+id+' #menu_'+id+'_metadescription').css('color', '#000000');
		 	
	 	}
	    
    }
    
     function countCharactersTitle(id) {
	 	// 90
	 	tLength = $('#sidr-existing-content-'+id+' #menu_'+id+'_pagetitle').val().length;
	 	$('#sidr-existing-content-'+id+' #charCountert_'+id).html(tLength+"/74");
	 	if (tLength > 74) {
		 	
		 	$('#sidr-existing-content-'+id+' #menu_'+id+'_pagetitle').css('color', '#FF0000');
		 	
	 	} else { 
		 	
		 	$('#sidr-existing-content-'+id+' #menu_'+id+'_pagetitle').css('color', '#000000');
		 	
	 	}
	    
    }
    
    keyTimerSave = "";
    
    function savePageTimer(id) {
        
        clearTimeout(keyTimerSave);
        keyTimerSave = setTimeout("saveMenuChanges("+id+")", 2000);
        if (NProgress.isStarted() == false) {
            NProgress.start();
        }
        
    }
 
 	function createDefaultMenu(id, text) {
	 	
	 	text = text.replace(/ /g, "-");
	 	text = text.toLowerCase();
	 	
	 	$('#sidr-existing-content-'+id+' #menu_'+id+'_location').val(text);
	 	
 	}
 
 	function saveMenuChanges(menuId) {
	 	
	 	isDefaultJS = $('#sidr-existing-content-'+menuId+' #menu_'+menuId+'_default:checked').val();
	 	isHiddenJS = $('#sidr-existing-content-'+menuId+' #menu_'+menuId+'_inmenu:checked').val();
	 	menuTitleJS = $('#sidr-existing-content-'+menuId+' #menu_'+menuId+'_title').val();
	 	menuLocationJS = $('#sidr-existing-content-'+menuId+' #menu_'+menuId+'_location').val();
	 	menuLinkJS = $('#sidr-existing-content-'+menuId+' #menu_'+menuId+'_link').val();
	 	menuAltJS = $('#sidr-existing-content-'+menuId+' #menu_'+menuId+'_alt').val();
	 	menuMetaJS = $('#sidr-existing-content-'+menuId+' #menu_'+menuId+'_metadescription').val();
	 	menuPageJS = $('#sidr-existing-content-'+menuId+' #menu_'+menuId+'_pagetitle').val();
	 	 	
	 	 	
	 	 	
	 	$.post(basePath+'/ajax/saveMenuContent', { menuID: menuId, isDefault: isDefaultJS, isHidden: isHiddenJS, menuTitle: menuTitleJS, menuLocation: menuLocationJS, menuLink: menuLinkJS, menuAlt: menuAltJS, menuMeta: menuMetaJS, menuPage: menuPageJS}, function(data) { 
		 	
		 	NProgress.done();
		 	 
	 	});
 	
 	}
 	
 	function removeMenu(id) {
	 	
	 	checkRemove = confirm("Are you certain you want to delete this page?");
	 	if (checkRemove == true) {
		 	
		 	$.post(basePath+'/ajax/deletePage', { menuID: id });
		 	$('#menu-item-'+id).fadeOut();
	
	 	}
	 	
	}
	
	function DropDown(el) {
		this.dd = el;
		this.placeholder = this.dd.children('span');
		this.opts = this.dd.find('ul.dropdown > li');
		this.val = '';
		this.index = -1;
		this.initEvents();
	}
	
	DropDown.prototype = {
		initEvents : function() {
			var obj = this;

			obj.dd.on('click', function(event){
				$(this).toggleClass('active');
			});

		},
		getValue : function() {
			return this.val;
		},
		getIndex : function() {
			return this.index;
		}
	}
	
	var dd = new DropDown( $('#dd') );
	
 </script>
