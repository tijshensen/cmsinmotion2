<body class="cmsinmotion">

	<div id="modalAdd" class="modal fade">
	</div>
	
	<div id="modalPicLink" class="modal fade">
	</div>
	
	<div id="modalPicAlt" class="modal fade">
	</div>
	
	<input type="hidden" value="<?=$page->id;?>" id="page_id" />
	
	<section class="container-fluid section-content">
		<div class="row">
    		
    		<div class="col-sm-3 col-md-3 col-lg-4 sidebar" id="sidebar_SEO">
						
				<div class="sidebar-inner">
				    <br />
				    <div>
					    <h4 style="padding-left: 15px;">Search Engine Optimalization</h4>
				    </div>
				    <div>
    				    <div class="input-group">
    				        <input type="text" value="" placeholder="Keyword Analyzer" name="keyword" id="keywordSEO" class="form-control" />
        				    <span class="input-group-btn">
        				        <input type="button" id="analyseSEObtn"  class="btn btn-flat" value="Analyse" onclick="fireKeywordAnalyser();">
        				    </span>
    				    </div>
    				    <div id="loadingSEO" style="text-align: center; margin-top: 25px; display: none;">
        				    Please wait while we analyse your page...
    				    </div>
    				    <div id="resultsSEO" style="margin-top: 25px; display: none;">
        				    Please wait while we analyse your page...
    				    </div>
    				    <br />
    				    <br />
    				    
				    </div>	
				</div>
								
			</div>
    		
			<div class="col-sm-3 col-md-3 col-lg-4 sidebar" id="sidebar" style="position: absolute; opacity: 0; display: none; z-index: 9999999">
						
				<div class="sidebar-inner">
				
					<ul id="myTab" class="nav nav-tabs" role="tablist">
						<li class="active" style="margin-left: 20px;"><a href="#editContent" role="tab" data-toggle="tab">Content</a></li>
						<li><a href="#editStyle" role="tab" data-toggle="tab">Style</a></li>
						<li class="fullsize"><a href="#" data-toggle="togle"><i class="fa fa-desktop"></i></a></li>
						<li id="savedChanges">
							
							<i class="fa fa-2x fa-check-square" ></i>
							
						</li>
					</ul>
					
					<div id="myTabContent" class="tab-content">
						
						<div class="tab-pane fade in active" id="editContent">
							<h4>Please select a block to edit its content</h4>
							
						</div>
			
						<div class="tab-pane fade" id="editStyle">
                            <h4>Please select a block to edit its style</h4>
						</div>
												
					</div><!-- /tabcontent -->
				</div><!-- /tabcontent -->
				
			</div><!-- /sidebar -->
	
		    <div class="col-sm-9 col-md-9 col-lg-8 main" id="main">
				<div class="row">
					<div class="col-md-12">
						<div class="main-viewport" style="height: 100%">
							<iframe id="contentFrame" class="viewport device-100" frameborder="0" style="seamless: seamless; height: 100vh;" src="<?=base_path_rewrite;?>/pages/view/<?=$page->id;?>"></iframe>
						</div>
					</div>
				</div>
	        </div>
		</div>
	</section>


<script type="text/javascript">
	$('body').addClass('editor');
	
	var issetInternal = 0;
	var internalPages = [<?php
		
		$links = fetchInternalLinks();
	
		$pages = new ActiveRecord("page");
		$pages->fetchAll("prio ASC");
		$allPagesWithID = $pages->returnComplete(true);	
		
		function checkParent($parentID, $depth = 0, $allPagesWithID) {
	          
	          $depth = $depth + 1;
	          
	          if ($allPagesWithID[$parentID]['parent_id'] != 0) {
		          
		          return checkParent($allPagesWithID[$parentID]['parent_id'], $depth, $allPagesWithID);
		          			          
	          } else {
		          
		          return $depth;
		          
	          }		          
	          
          }
          
          foreach($links as $link) {
          
          if ($link['parent_id'] != 0) {
	         
	          $link['depth'] = checkParent($link['parent_id'], 0, $allPagesWithID);
	          
          } else {
	          
	          $link['depth'] = 0;
	          
          }
          
          	$numDots = "";
          	for ($i = 0; $i <= $link['depth']; $i++) {
          	$numDots .= "-";
          	}
       
			$linkBuild .= '["'.$numDots.$link['title'].'", "internalURI'.$link['id'].'"],';
			
		}
		$linkBuild = substr($linkBuild, 0, -1);
		echo $linkBuild;
		
		?>];
	
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
				return false;
			});

			obj.opts.on('click',function(){
				var opt = $(this);
				obj.val = opt.text();
				obj.index = opt.index();
				obj.placeholder.text('Device: ' + obj.val);
				$('#contentFrame').removeClass(function() { return this.className.split(' ').filter(function(className) {return className.match(/device-.*/)}).join(' '); }).addClass(opt.attr('id'));
				
			});
		},
		getValue : function() {
			return this.val;
		},
		getIndex : function() {
			return this.index;
		}
	}
	
	function addPicLink(id, currentLink, currentTitle, currentTarget) {
		
		$.post(basePath+"/ajax/addPicLink", {id : id, currentLink: currentLink, currentTitle: currentTitle, currentTarget: currentTarget}, function(data) {
		$('#modalPicLink').html(data);
		$('#modalPicLink').modal();
		});
		
		
	}
	
	function addPicAlt(id, currentAlt) {
		
		$.post(basePath+"/ajax/addPicAlt", {id : id, currentAlt: currentAlt}, function(data) {
		$('#modalPicAlt').html(data);
		$('#modalPicAlt').modal();
		});
		
		
	}
	
	function addTextLink(id, currentLink, currentTitle, currentTarget) {
		
		$.post(basePath+"/ajax/addTextLink", {id : id, currentLink: currentLink, currentTitle: currentTitle, currentTarget: currentTarget}, function(data) {
		$('#modalPicLink').html(data);
		$('#modalPicLink').modal();
		});
		
		
	}
	
	function fireKeywordAnalyser() {
    	
    	NProgress.settings.parent = 'div#sidebar_SEO .sidebar-inner';
    	NProgress.start();
    	$('#loadingSEO').show();
        $('#resultsSEO').hide();
    	$.post(basePath+"/ajax/analysePage", {id: <?=$page->id;?>, keyword: $('#keywordSEO').val() }, function(data) {
        	
        	$('#loadingSEO').hide();
        	NProgress.done();
        	$('#resultsSEO').show();
        	$('#resultsSEO').html(data);
        	
        });;
    	
	}
	
	var cropTool = "";
	
	function replaceImage(filename, id, blockid, ratio) {
		
		ratioSplit = ratio.split("/");
		ratioW = parseInt(ratioSplit[0]) + 80;
		ratioH = parseInt(ratioSplit[1]) + 80;
	
		originalHTML = $('#'+id+"_editor")[0].outerHTML;
				
		$('#'+id+"_editor").replaceWith('<div class="replaceImageClass" id="'+id+'_editor"><div class="cropMain" style="width: '+ratioW+'px; height: '+ratioH+'px;"></div><div class="cropSlider"></div><button class="cropButton">Crop</button></div>');
		var cropTool = new CROP();
		cropTool.init('#'+id+"_editor");
		cropTool.loadImg(basePath+"/content/images/"+filename);
		$('#'+id+'_editor button').on('click', function() {
			$.ajax({
				type: "post",
				dataType: "json",
				url: basePath+"/ajax/cropImage/"+blockid+"/"+id+"/"+ratioW+"/"+ratioH,
				data: $.param(coordinates(cropTool))
			})
			.done(function(data) {
				
				$('#'+id+"_editor").replaceWith(originalHTML);
				$('#contentFrame').contents().find('#'+id).attr("src", data.url);
				
				
			});
		});
				
	}
	
	function addPicAltExecute(id) {
		
		
		$.post(basePath+"/ajax/addPicAltExecute", { id : id, block_id: $('#pageBlockID').val(), link: $('#picalt').val() }, function(data) {
			$('#modalPicAlt').modal('hide');
		});

		
	}
	
	function addPicLinkExecute(id) {
		
		
		$.post(basePath+"/ajax/addPicLinkExecute", { id : id, block_id: $('#pageBlockID').val(), link: $('#piclink').val(), link_target: $('#piclink_target').val(), link_title: $('#piclink_title').val(), internalLink: $('#internalLink').val() }, function(data) {
			$('#modalPicLink').modal('hide');
		});

		
	}
	
	function addTextLinkExecute(id) {
		
		
		$.post(basePath+"/ajax/addTextLinkExecute", { id : id, block_id: $('#pageBlockID').val(), link: $('#textlink').val(), link_target: $('#textlink_target').val(), link_title: $('#textlink_title').val(), internalLink: $('#internalLink').val() }, function(data) {
			$('#modalPicLink').modal('hide');
		});

		
	}

	var dd = new DropDown( $('#dd') );
			
	  sidebarStatus = true;
	  $("[data-toggle='togle']").click(function() {

	   closeSidebar(); 
	   });
	   
	   function closeSidebar() {
		   
		    $('#sidebar_SEO').css({opacity: 1});
			$('#sidebar').animate({
		        width: "0",
		        opacity: "0"
		      }, 200).hide();
		      
		      sidebarStatus = true;
	    }
	    
	    function openSidebar() {
    	    
		  $('#sidebar_SEO').css({opacity: 0});
	      $('#sidebar').show().animate({
	        width: "33%",
	        opacity: "1"
	      }, 200);
	      sidebarStatus = false;
	    

		   
	   }
	
	
	
	/*CharacterCounter

	$(function(){
		//Example #1
		$("#pagetitle").characterCounter({
		  limit: '90' ,
		counterFormat: '%1 characters left'
		});
		$("#metadescription").characterCounter({
		  limit: '200' ,
		counterFormat: '%1 characters left'
		}); 
	});*/

	
			
</script>

<style>
.device-iphone{
	width: 320px;
	height: 640px;
}
.device-ipad
{
	width: 1024px;
	height:768px;
}
.device-100
{
	width: 100%;
	height: 100%;
}
</style>
