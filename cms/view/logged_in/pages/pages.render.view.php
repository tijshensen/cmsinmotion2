<body class="cmsinmotion">

	<div class="container">
		<p style="font-size: 20px; margin-top: 10px;">Please wait while your website is being rendered...</p>
	</div>
	
	<div class="container renderport">
		<p style="font-size: 14px; margin-top: 10px;">
	<?php
	
	$fetchAllPages = new ActiveRecord("page");
	$fetchAllPages->fetchAll();
	
	foreach($fetchAllPages->returnComplete() as $currentRenderPage) {
		
	
	$page = new ActiveRecord("page");
	$page->waar("id = '".$currentRenderPage['id']."'");
	
	echo "Clearing caches... ";
	$compiledCSS = "";
	$compiledJS = "";
	$outPut = "";
	
	echo "-Starting to render ".$currentRenderPage['title']."...<br />";
	writeRenderLog("Start render on ".$currentRenderPage['id']." (".$currentRenderPage['title'].")");
	
	$untilNow = ob_get_contents();
	ob_clean();
	
	ob_start();
	
	$fetchTemplate = new ActiveRecord("template");
	$fetchTemplate->waar("id = '".$page->template_id."'");
		
	// Get current templateSet
	$fetchTemplateSet = new ActiveRecord("template_sets");
	$fetchTemplateSet->waar("id = '".$fetchTemplate->template_set_id."'");
	
	// alle JS en CSS matches
	preg_match_all('/\[css_(.*?)]/i', $fetchTemplate->core, $cssMatches);
	
	preg_match_all('/\[js_(.*?)]/i', $fetchTemplate->core, $jsMatches);
	
	foreach($cssMatches[1] as $css) {
		
		$fetchCSS = new ActiveRecord("template_css");
		$fetchCSS->waar("id = '".$css."'");
		
		$compiledCSS = $compiledCSS."\n".$fetchCSS->content;
		
	}
		
	foreach($jsMatches[1] as $js) {
		
		$fetchJS = new ActiveRecord("template_js");
		$fetchJS->waar("id = '".$js."'");
		
		$compiledJS = $compiledJS."\n".$fetchJS->content;
		
	}
	
	writeRenderLog("Included Javascript and CSS");
	
	$fetchPageBlocks = new ActiveRecord("page_block");
	$fetchPageBlocks->query("SELECT page_block.*, template_block.content as template, template_block.repeat_id FROM page_block LEFT JOIN template_block ON page_block.template_block_id = template_block.id WHERE page_block.page_id = '".$page->id."' ORDER BY page_block.`priority` ASC");
	
	foreach($fetchPageBlocks->returnComplete() as $pageBlock) {
	
		if ($pageBlock['hidden'] == 1) { continue(1); }
		
		$currentBlock = '<div class="blockCSS'.$pageBlock['id'].'">'.$pageBlock['template']."</div>";
		
		$templater = new Templater($currentBlock, $pageBlock['id']);
		$templater->setContent($pageBlock['content']);
		
		$templater->view();
		$outPut[$pageBlock['repeat_id']] .= $templater->generate();
		
		if ($pageBlock['repeat_id'] > $maxRepeatID) { $maxRepeatID = $pageBlock['repeat_id']; }
		
		$css = json_decode($pageBlock['css'], true);
		
		foreach ($css as $cssValue) {
			
			if ($cssValue['value'] != "") {
			
				$generatedCSS .= ' .blockCSS'.$pageBlock['id'].' '.$cssValue['name'].' { '.$cssValue['value']." }\n";
			
			}
			
		}
					
	}
	
	for ($repeats = 0; $repeats <= $maxRepeatID; $repeats++) {
		
		$fetchTemplate->core = str_replace("[repeatBlock_".$repeats."]", $outPut[$repeats], $fetchTemplate->core);
		
        writeRenderLog("Replaced repeatblock ".$repeats);
		
	}
	
	// Zoeken van Inserts
	
	preg_match_all("/(\[.*?\])/", $fetchTemplate->core, $insertMatches);
	
	foreach($insertMatches[1] as $insertTag) {
				
		$insertLookup = new ActiveRecord("inserts");
		$insertLookup->waar("tag = '".$insertTag."'");
		if ($insertLookup->returnRows() != 0) {
    		writeRenderLog("Insert ".$insertTag." replaced");
			$fetchTemplate->core = str_replace($insertTag, $insertLookup->content, $fetchTemplate->core);
		}
		
	}
	
	// Fetch menu
	if ($fetchTemplateSet->menu != "") {
	
		$pagesForMenu = new ActiveRecord("page");
		$pagesForMenu->waar("parent_id = 0 AND isHidden = 0 ORDER BY prio ASC");
		$allPagesTop = $pagesForMenu->returnComplete();
		
		$menu = $fetchTemplateSet->menu;
		$submenu = $fetchTemplateSet->submenu;
		
		preg_match_all('/<menu type="head">(.*?)<\/menu>/ims', $menu, $headMatch);
		
		$menuLoopHead = $headMatch[1][0];
		
		$menuWrap = explode($headMatch[0][0], $menu);
		$menuWrap = $menuWrap[0].$menuWrap[1];
			
		preg_match_all('/<menu type="head-with-dropdown">(.*?)<\/menu>/ims', $menu, $dropDownMatch);
		$menuLoopDropdown = $dropDownMatch[1][0];
		
		$menuWrap = explode($dropDownMatch[0][0], $menuWrap);
		$menuWrap = $menuWrap[0]."[[menuContent]]".$menuWrap[1];
		
		// Get dropdown items
		preg_match_all('/<menuitem type="dropdown">(.*?)<\/menuitem>/ims', $menuLoopDropdown, $dropDownItem);
		$menuLoopDropdownHead = $dropDownItem[1][0];
		
		// Toplayer
		$menuItems = "";
		$menuItemsDropdown = "";
		foreach ($allPagesTop as $topLevelPage) {
					
			// Check for lower pagers
			$getLowerLevelPages = new ActiveRecord("page");
			$getLowerLevelPages->waar("parent_id = '".$topLevelPage['id']."' AND isHidden = 0 ORDER BY prio ASC");
			
			if ($getLowerLevelPages->returnRows() == 0) {
				
				if ($topLevelPage['menu_title'] != "") {
					 
				 	$topLevelPage['title'] =  $topLevelPage['menu_title'];
				 
				}
				
				if ($page->id == $topLevelPage['id']) {
					
					$menuItems .= str_replace("[[currentindicator]]", "active", str_replace("[[href]]", base_path_site_url."/".$topLevelPage['page_seo_name'], str_replace("[[title]]", $topLevelPage['title'], $menuLoopHead)));
				
				} else {
				
					$menuItems .= str_replace("[[currentindicator]]", "", str_replace("[[href]]", base_path_site_url."/".$topLevelPage['page_seo_name'], str_replace("[[title]]", $topLevelPage['title'], $menuLoopHead)));
				
				}
				
			} else {
			
				$menuItemsDropdown = "";
			
				foreach ($getLowerLevelPages->returnComplete() as $lowLevel) {
					
					if ($lowLevel['menu_title'] != "") {
					 
					 	$lowLevel['title'] =  $lowLevel['menu_title'];
					 
					}
					
					if ($page->id == $lowLevel['id']) {
						
						$menuItemsDropdown .= str_replace("[[currentindicator]]", "active", str_replace("[[href]]", base_path_site_url."/".$lowLevel['page_seo_name'], str_replace("[[title]]", $lowLevel['title'], $menuLoopDropdownHead)));
				
					} else {
						
						$menuItemsDropdown .= str_replace("[[currentindicator]]", "", str_replace("[[href]]", base_path_site_url."/".$lowLevel['page_seo_name'], str_replace("[[title]]", $lowLevel['title'], $menuLoopDropdownHead)));
				
					}
				
					
				}
				
				if ($topLevelPage['menu_title'] != "") {
					 
				 	$topLevelPage['title'] =  $topLevelPage['menu_title'];
				 
				}				
				
				$emptyDrop = explode($dropDownItem[0][0], $menuLoopDropdown);
				
				if ($page->id == $topLevelPage['id']) {
					$menuItems .= str_replace("[[currentindicator]]", "active", str_replace('[[title]]', $topLevelPage['title'], $emptyDrop[0])).$menuItemsDropdown.$emptyDrop[1];
				} else {
					$menuItems .= str_replace("[[currentindicator]]", "", str_replace('[[title]]', $topLevelPage['title'], $emptyDrop[0])).$menuItemsDropdown.$emptyDrop[1];
				}
				
			}
			
		}
		
		
		$menuItems = str_replace("[[menuContent]]", $menuItems, $menuWrap);
		
		$fetchTemplate->core = preg_replace('/<insert menu.*?\/>/ims', $menuItems, $fetchTemplate->core);
		
	}
	
	// Submenu
	if ($fetchTemplateSet->submenu != "") {
	
		$pagesForMenu = new ActiveRecord("page");
		$pagesForMenu->waar("parent_id = '".$page->parent_id."' AND isHidden = 0 ORDER BY prio ASC");
		
		if ($pagesForMenu->returnRows() != 0) {
		
			$allSiblings = $pagesForMenu->returnComplete();
		
			// Get dropdown items
			preg_match_all('/<menuitem type="submenu">(.*?)<\/menuitem>/ims', $submenu, $submenuMatch);
			$menuLoopSubmenu = $submenuMatch[1][0];
			$submenuWrap = explode($submenuMatch[0][0], $submenu);
			$submenuWrap = $submenuWrap[0]."[[menuContent]]".$submenuWrap[1];
			
			// Toplayer
			$submenuItems = "";;
			foreach ($allSiblings as $siblingPage) {
			
				 if ($siblingPage['menu_title'] != "") {
					 
					 $siblingPage['title'] =  $siblingPage['menu_title'];
					 
				 }
				
				if ($page->id == $siblingPage['id']) {
					
					$submenuItems .= str_replace("[[currentindicator]]", "active", str_replace("[[href]]", base_path_site_url."/".$siblingPage['page_seo_name'], str_replace("[[title]]", $siblingPage['title'], $menuLoopSubmenu)));
					
				} else {
				
					$submenuItems .= str_replace("[[currentindicator]]", "", str_replace("[[href]]", base_path_site_url."/".$siblingPage['page_seo_name'], str_replace("[[title]]", $siblingPage['title'], $menuLoopSubmenu)));
				
					
				}
				
				
								
			}
			
			$submenuItems = str_replace("[[menuContent]]", $submenuItems, $submenuWrap);
			
			$fetchTemplate->core = preg_replace('/<insert submenu.*?\/>/ims', $submenuItems, $fetchTemplate->core);
		
		}

	
	}
	
	$fetchTemplate->core = str_replace('<meta name="description" content="" />', '<meta name="description" content="'.$page->meta_description.'" />', $fetchTemplate->core);
    $fetchTemplate->core = str_replace('<meta name="description" content="">', '<meta name="description" content="'.$page->meta_description.'" />', $fetchTemplate->core);

    $fetchTemplate->core = preg_replace("/<title>.*?<\/title>/ims", "<title>".$page->title."</title>", $fetchTemplate->core);
	
	$fetchTemplate->core = str_replace("<multiline>", "", $fetchTemplate->core);
	$fetchTemplate->core = str_replace("</multiline>", "", $fetchTemplate->core);	
	
	// InternalURI resolving
	
	preg_match_all("/#internalURI(.*?)[\"|']/", $fetchTemplate->core, $matchesURI);
	foreach ($matchesURI[1] as $match) {
		
		$fetchPageInternalLink = new ActiveRecord("page");
		$fetchPageInternalLink->waar("id = ".$match);
		
		$fetchTemplate->core = str_replace("#internalURI".$match, base_path_site_url."/".$fetchPageInternalLink->page_seo_name, $fetchTemplate->core);
				
	}
	
	// Snel nog even de HEAD veranderen voor de rendered CSS
	$fetchTemplate->core = str_replace("</head>", '<link rel="stylesheet" type="text/css" href="'.base_path_site_url.'/'.$currentRenderPage['id'].'.css" /></head>', $fetchTemplate->core);
	
	echo $fetchTemplate->core;
	
	$webPageRendered = ob_get_contents();
	ob_clean();
	ob_start();
	echo $untilNow;
	
	echo "-Render complete: ".$currentRenderPage['id']."...<br />";
	
	if (file_exists("content/logic.php")) {
    	// execute final PHP in template
    	$pageMetadata['pageID'] = $currentRenderPage['id'];
    	$pageMetadata['pageTitle'] = $currentRenderPage['title'];
    	$pageMetadata['pageMenuTitle'] = $currentRenderPage['menu_title'];
    	$pageMetadata['pageDefault'] = $currentRenderPage['isDefault'];
    	$pageMetadata['pageParent'] = $currentRenderPage['parent_id'];
    	$pageMetadata['pageSeo'] = $currentRenderPage['page_seo_name'];
    	
        include("content/logic.php");
	}
	
	file_put_contents("../renders/".$page->id.".html", $webPageRendered);
	
	echo "-Render saved: ".$currentRenderPage['id'].".html<br /><br />";
	
	echo "-Creating CSS file: ".$currentRenderPage['id']."...<br />";
	$untilNow = ob_get_contents();
	ob_clean();
	ob_start();
	?>
	<?=$generatedCSS;?>
	<?php
		
	$cssRendered = ob_get_contents();
	ob_clean();
	ob_start();
	echo $untilNow;
	
	file_put_contents("../renders/".$page->id.".css", $cssRendered);
	
	echo "-CSS File Created: ".$currentRenderPage['id'].".css<br />";
	
	$generateMenu[$currentRenderPage['id'].".css"] = $currentRenderPage['id'];
		
	if ($globalSettings->isMultiLanguage == 1) {
		
		$currentLanguage = new ActiveRecord("languages");
		$currentLanguage->waar("id = '".$currentRenderPage['language_id']."'");
		
		$generateMenu[$currentLanguage->url][$currentRenderPage['page_seo_name']] = $currentRenderPage['id'];
	
		if ($currentRenderPage['isDefault'] == 1) {
			
			$generateMenu[$currentLanguage->url]['defaultPage'] = $currentRenderPage['id'];
			
		}
		
	} else {
	
		$generateMenu[$currentRenderPage['page_seo_name']] = $currentRenderPage['id'];
	
		if ($currentRenderPage['isDefault'] == 1) {
			
			$generateMenu['defaultPage'] = $currentRenderPage['id'];
			
		}
	
	}
	
	}
	
	if ($globalSettings->isMultiLanguage == 1) {
	
		$generateMenu['CMSinMotionSettings']['multiLanguage'] = 1;
		
	}
		
	// Generate menu
	$generateMenu = json_encode($generateMenu);
	
	file_put_contents("../renders/infrastructure_old.json", file_get_contents("../renders/infrastructure.json"));
	file_put_contents("../renders/infrastructure.json", $generateMenu);
	
	// Generate sitemap
	echo "Building XML sitemap...";
	$xmlSitemap = '<?xml version="1.0" encoding="UTF-8"?>
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
';

    $fetchAllPages->fetchAll();
	
	foreach($fetchAllPages->returnComplete() as $currentRenderPage) {
    
        $xmlSitemap .= "<url>
    <loc>".base_path_site_url."/".$currentRenderPage['page_seo_name']."</loc>
</url>
";
    	
    }

$xmlSitemap .= '</urlset>
';
	
    file_put_contents("../renders/sitemap.data", $xmlSitemap);
    
    echo "Built sitemap and saved.";
    
?>
		</p></div>
<div class="container">
	<p style="font-size: 30px; margin-top: 10px;">Your webpage has been rendered and saved to the rendered pages directory. You can view your site at <a href="<?=base_path_site_url;?>" target="_new"><?=base_path_site_url;?></a></p>
	<p style="font-size: 30px; margin-top: 30px;">Or go back <a href="javascript:history.go(-1);">to the editor</a>.
</div>
