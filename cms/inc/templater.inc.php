<?php
	
	class Templater {
		
		var $templatePage_source = "";
		var $templateBlog_source = "";
		
		var $templatePage = "";
		var $strippedTemplate = "";
		var $templateBlog = "";
		
		var $templatePage_blocks = "";
		var $templateBlog_blocks = "";
		
		var $templatePage_blocks_metadata_name = "";
		var $templatePage_blocks_metadata_repeatable = "";
		var $templatePage_blocks_metadata_default = "";
		
		var $cssContent = "";
		
		function __construct($templateFile, $blockUniqueID = "") {
			global $count;


			// Open from templates directory
			if (file_exists($templateFile)) {
				
				try {
				
					$this->templatePage_source = file_get_contents($templateFile);
					
					preg_match_all("/<repeater>(.*?)<\/repeater>/ism", $this->templatePage_source, $matchRepeater);
					
					foreach($matchRepeater[1] as $repeatKey => $repeatBlocks) {
						
						preg_match_all("/(<block.*?>)(.*?)<\/block>/ism", $repeatBlocks, $matches);
						$this->templatePage_blocks[$repeatKey] = $matches;
						
					}
					
					$count = 0;
					
					$this->strippedTemplate = preg_replace_callback('/<repeater>(.*?)<\/repeater>/ism', 'rep_count', $this->templatePage_source);
															
				} catch(Exception $e) {
					
					die("The template '".$templateFile."' contains an error.");
					
				}
								
				foreach($this->templatePage_blocks as $repeatKey => $repeater) {
			
					foreach($repeater[0] as $blockKey => $blockEntry) {
																
						try {
						
							$XMLparser = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$blockEntry);
							$this->templatePage_blocks_metadata_name[$repeatKey][$blockKey] = $XMLparser->Attributes()->label;
							if ($XMLparser->Attributes()->repeatable == "true") {
								$this->templatePage_blocks_metadata_repeatable[$repeatKey][$blockKey] = 1;
							} else {
								$this->templatePage_blocks_metadata_repeatable[$repeatKey][$blockKey] = 0;
							}
							
							if ($XMLparser->Attributes()->default == "true") {
								
								if (!is_array($this->templatePage_blocks_metadata_default)) {
									
									$this->templatePage_blocks_metadata_default[$repeatKey][$blockKey] = 1;
									
								} else {
									
									die("The template '".$templateFile."' has two or more default blocks. Not allowed.");
									
								}
								
							} else {
								
								$this->templatePage_blocks_metadata_default[$repeatKey][$blockKey] = 0;
								
							}
							
						} catch(Exception $e) {
							
							echo("The template '".$templateFile."' contains an error.");
							
							libxml_use_internal_errors(true);
							$sxe = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>'.$blockEntry);
							if ($sxe === false) {
								echo "<Br/><pre>".htmlspecialchars($blockEntry)."</pre><Br />";
							    echo " Failure while parsing template: <br />";
							    foreach(libxml_get_errors() as $error) {
							        echo "<br />", $error->message;
							    }
							}
							die();
							
						}
						
						
					} 
				}
			
			} else {
				
				// Load in the passed in TemplateBlock
				$this->templatePage_blocks[1][0] = $templateFile;
				$this->blockID = $blockUniqueID;
				
				
			}
								
		}
		
		function transformImages() {
						
			foreach($this->templatePage_blocks as $repeatKey => $repeater) {
			
				foreach($repeater[0] as $blockKey => $blockEntry) {
			
					preg_match_all('/<img.*?src="(.*?)"/ism', $blockEntry, $imageLookup);
							
					foreach($imageLookup[1] as $imgFile){
				
						echo "--".$imgFile."<br />";
					
						$this->templatePage_blocks[$repeatKey][0][$blockKey] = str_replace($imgFile, base_path_rewrite."/content/".$imgFile, $blockEntry);
						
					}
				
				}
			
			}
			
		}
		
		function setContent($contentJSON) {
			
			$contentItem = json_decode($contentJSON, true);
			
			foreach($contentItem as $content) {
				
				$this->newContentArray[$content['name']] = $content['value'];
				$checkLink = explode("_", $content['name']);
				if ($checkLink[1] == "link") {
					
					$this->newContentArray[$content['name']."_title"] = $content['value_title'];
					$this->newContentArray[$content['name']."_target"] = $content['value_target'];
					
				}
				
			}
			
		}
		
		function setCSSContent($contentJSON) {
			
			$contentItem = json_decode($contentJSON, true);
			
			foreach($contentItem as $content) {
				
				$this->cssContent[$content['name']] = $content['value'];
				
			}
			
		}
		
				
		function edit() {
			
			foreach($this->templatePage_blocks[1] as $pageBlockKey => &$pageBlockValue) {
				
				// Replace all singleline elements with inputs
				preg_match_all('/(<singleline.*?>.*?<\/singleline>)/ism', $pageBlockValue, $singleLineElements);
				
				foreach($singleLineElements[1] as $singleLineElement) {
				
					$XMLparser = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$singleLineElement);
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)] != "") {
						
						$XMLparser->{0} = $this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)];
						
					}
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link"] != "") {
					
						$linkValue = $this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link"];
						
					} else {
						
						$linkValue = "";
						
					}
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link_target"] != "") {
					
						$targetValue = $this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link_target"];
						
					} else {
						
						$targetValue = "";
						
					}
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link_title"] != "") {
					
						$titleValue = $this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link_title"];
						
					} else {
						
						$titleValue = "";
						
					}
					
					$blockName = sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID);
					
					// New input
					$returnEditor .= "<strong>".ucfirst($XMLparser->Attributes()->name)."</strong><br />";
					$returnEditor .= "<input onkeyup='startEditTimer(); changeRealtime(\"".sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_".$pageBlockKey."\", $(this).val());' type='text' value='".$XMLparser->{0}."' name='".sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."' class='form-control' /><input type='button' id='textlink_".$blockName."' class='btn btn-primary' value='Add link' onclick='addTextLink(\"".$blockName."\", \"".$linkValue."\", \"".$titleValue."\", \"".$targetValue."\");'><br /><br />";
					
				}	
				
				// Replace all multiline elements with textareas
				preg_match_all('/(<multiline.*?>.*?<\/multiline>)/ism', $pageBlockValue, $multiLineElements);
				
				foreach($multiLineElements[1] as $multiLineElement) {
					
					$XMLparser = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$multiLineElement);
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$multiLineElement.$this->blockID)] != "") {
						$contentShow = $this->newContentArray[sha1($XMLparser->Attributes()->name.$multiLineElement.$this->blockID)];
					} else {
						$contentShow = $XMLparser->{0}->asXML();
					}
					// New textarea
					$returnEditor .= "<strong>".ucfirst($XMLparser->Attributes()->name)."</strong><br />";
					$returnEditor .= "<textarea id=\"".sha1($XMLparser->Attributes()->name.$multiLineElement.$this->blockID)."_original\" name='".sha1($XMLparser->Attributes()->name.$multiLineElement.$this->blockID)."_".$pageBlockKey."' ".($XMLparser->Attributes()->editable == 'false' ? ' disabled' :'')." class='ckeditor'>".$contentShow."</textarea><br />";
					
				}		
				
				// Replace all multiline elements with textareas
				preg_match_all('/(<img editable="true".*?\/>)/ism', $pageBlockValue, $pictureElements);
				
				foreach($pictureElements[1] as $pictureElement) {
					
					$XMLparser = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$pictureElement);
					// New textarea
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)] != "") {
						
						$XMLparser->{0} = $this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)];
						
					}
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_link"] != "") {
					
						$linkValue = $this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_link"];
						
					} else {
						
						$linkValue = "";
						
					}
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_link_target"] != "") {
					
						$targetValue = $this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_link_target"];
						
					} else {
						
						$targetValue = "";
						
					}
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_link_title"] != "") {
					
						$titleValue = $this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_link_title"];
						
					} else {
						
						$titleValue = "";
						
					}
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_alt"] != "") {
					
						$altValue = $this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_alt"];
						
					} else {
						
						$altValue = "";
						
					}

					
					$blockName = sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID);
					
					$returnEditor .= "<strong>".ucfirst($XMLparser->Attributes()->name)."</strong><br />";
					$returnEditor .= "<input type='button' id='pic_".$blockName."' class='btn btn-primary' value='Upload image for ".$XMLparser->Attributes()->name."'> <input type='button' id='piclink_".$blockName."' class='btn btn-primary' value='Add link' onclick='addPicLink(\"".$blockName."\", \"".$linkValue."\", \"".$titleValue."\", \"".$targetValue."\");'> <input type='button' id='picalt_".$blockName."' class='btn btn-primary' value='Add alt text' onclick='addPicAlt(\"".$blockName."\", \"".$altValue."\");'><br /><div id='".$blockName."_".$pageBlockKey."_editor'></div><br />";
					
					$returnJS .= "
					
					var uploader".$blockName." = new ss.SimpleUpload({
					      button: 'pic_".$blockName."', // HTML element used as upload button
					      url: basePath+'/ajax/uploadImage', // URL of server-side upload handler
					      name: 'uploadfile_".$blockName."', // Parameter name of the uploaded file
					      data: { postID: $('#pageBlockID').val() },
					      onComplete:   function(filename, response) {
					          if (!response) {
					            alert(filename + 'upload failed');
					            return false;
					          }
					          handleImageUpload(filename, '".$blockName."_".$pageBlockKey."', $('#pageBlockID').val(), '".$XMLparser->Attributes()->size."');
					        }
					});

					";
					
				}
				
				// Replace all file elements with inputs
				preg_match_all('/(<file.*?>.*?<\/file>)/ism', $pageBlockValue, $fileElements);
				
				foreach($fileElements[1] as $fileElement) {
				
					$XMLparser = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$fileElement);
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)] != "") {
						
						$XMLparser->{0} = $this->newContentArray[sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)];
						
					}
					
					$blockName = sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID);
					
					// New input
					$returnEditor .= "<strong>File description ".ucfirst($XMLparser->Attributes()->name)."</strong><br />";
					$returnEditor .= "<input onkeyup='startEditTimer(); changeRealtime(\"".sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)."_".$pageBlockKey."\", $(this).val());' type='text' value='".$XMLparser->{0}."' name='".sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)."' class='form-control' /><br />";
					$returnEditor .= "<input type='button' id='file_".$blockName."' class='btn btn-primary' value='Upload file ".$XMLparser->Attributes()->name."'><br />";
					
					$returnJS .= "
					
					var uploader".$blockName." = new ss.SimpleUpload({
					      button: 'file_".$blockName."', // HTML element used as upload button
					      url: basePath+'/ajax/uploadFile', // URL of server-side upload handler
					      name: 'uploadfile_".$blockName."', // Parameter name of the uploaded file
					      data: { postID: $('#pageBlockID').val() },
					      onComplete:   function(filename, response) {
					          if (!response) {
					            alert(filename + 'upload failed');
					            return false;
					          }
					          handleFileUpload(filename, '".$blockName."_".$pageBlockKey."', $('#pageBlockID').val());
					        }
					});

					";
					
				}
				
			}
			
			$returnJS .= "
			
	$('#sidebar textarea').ckeditor(function(){
     this.on('change', function(){ startEditTimer(); changeRealtime($('#'+this.name).attr('name'), this.getData()); });
    });

	
	
";
			
			$returnEditor .= '<script type="text/javascript">'.$returnJS.'</script>';
			
			return $returnEditor;
			
		}
		
		function viewCMS() {
			
			foreach($this->templatePage_blocks[1] as $pageBlockKey => &$pageBlockValue) {
								
				// Replace all singleline elements with inputs
				preg_match_all('/(<singleline.*?>.*?<\/singleline>)/ism', $pageBlockValue, $singleLineElements);
				
				foreach($singleLineElements[1] as $singleLineElement) {
					
					$XMLparser = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$singleLineElement);
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)] != "") {
						
						$XMLparser->{0} = $this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)];
						
					}
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link"] != "") {
						
						$pageBlockValue = str_replace($singleLineElement, "<a href='".$this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link"]."'><span id='".sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_".$pageBlockKey."'>".$XMLparser->{0}."</span></a>", $pageBlockValue);	
					} else {
						// New input
						$pageBlockValue = str_replace($singleLineElement, "<span id='".sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_".$pageBlockKey."'>".$XMLparser->{0}."</span>", $pageBlockValue);
					
					}
					$pageBlockValue = str_replace('<?xml version="1.0" encoding="UTF-8"?>', "", $pageBlockValue);
					
				}	
				
				// Replace all multiline elements with textareas
				preg_match_all('/(<multiline.*?>.*?<\/multiline>)/ism', $pageBlockValue, $multiLineElements);
				
				foreach($multiLineElements[1] as $multiLineElement) {
					
					$XMLparser = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$multiLineElement);
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$multiLineElement.$this->blockID)] != "") {
						$contentShow = $this->newContentArray[sha1($XMLparser->Attributes()->name.$multiLineElement.$this->blockID)];
					} else {
						$contentShow = $XMLparser->{0}->asXML();
					}
					// New textarea
					$pageBlockValue = str_replace($multiLineElement, "<div id='".sha1($XMLparser->Attributes()->name.$multiLineElement.$this->blockID)."_".$pageBlockKey."'>".$contentShow."</div>", $pageBlockValue);
					$pageBlockValue = str_replace('<?xml version="1.0" encoding="UTF-8"?>', "", $pageBlockValue);
					
				}		
				
				// Replace all multiline elements with textareas
				preg_match_all('/(<img editable="true".*?\/>)/ism', $pageBlockValue, $pictureElements);
				
				foreach($pictureElements[1] as $pictureElement) {
					
					$XMLparser = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$pictureElement);
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)] != "") {
						
						$XMLparser->Attributes()->src = $this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)];
						
					}
					
					// New textarea
					$pageBlockValue = str_replace($pictureElement, "<img class='".$XMLparser->Attributes()->class."' src='".$XMLparser->Attributes()->src."' id='".sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_".$pageBlockKey."' />", $pageBlockValue);
					$pageBlockValue = str_replace('<?xml version="1.0" encoding="UTF-8"?>', "", $pageBlockValue);
					
				}
				
				// Replace all file elements with inputs
				preg_match_all('/(<file.*?>.*?<\/file>)/ism', $pageBlockValue, $fileElements);
				
				foreach($fileElements[1] as $fileElement) {
				
					$XMLparser = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$fileElement);
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)] != "") {
						
						$XMLparser->{0} = $this->newContentArray[sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)];
						
					}
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)."_file"] != "") {
					
						$href = $this->newContentArray[sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)."_file"];
					
					} else {
						
						$href = $XMLparser->Attributes()->href;
						
					}
					
					// New textarea
					$pageBlockValue = str_replace($fileElement, "<a href='".$href."' id='".sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)."_".$pageBlockKey."'>".$XMLparser->{0}."</a>", $pageBlockValue);
					$pageBlockValue = str_replace('<?xml version="1.0" encoding="UTF-8"?>', "", $pageBlockValue);

				}
				
			}
			
		}
		
		function editCSS($pageBlock, $templateBlockID) {
			
			foreach($this->templatePage_blocks[1] as $pageBlockKey => &$pageBlockValue) {
				
				
				preg_match_all("/<(.*?)>/ism", $pageBlockValue, $allElements);
								
				foreach ($allElements[1] as $element) {
				
					if (substr($element, 0, 1) == "/") { continue; }
					if (substr($element, 0, 2) == "br") { continue; }
					
					if (count(explode(" ", $element) > 1)) { 
					
						$curElement = explode(" ", $element)[0];
					
					} else {
						
						$curElement = $element;
						
					}
					
					if (strpos($element, "class") !== false) {
						
						preg_match('/class="(.*?)"/', $element, $className);
						
						$allClasses = explode(" ", $className[1]);
						
						if (count($allClasses > 0)) {
						
							foreach ($allClasses as $newClassEditor) {
						
								$returnConstruct[] = $curElement.".".$newClassEditor;
						
							}
						
						} else {
							
							$returnConstruct[] = $curElement.".".$className[1];
						
						}
						
					} else {
						
						if ($element != "singleline" && $element != "multiline") {
							$returnConstruct[] = $curElement;
						}
						
					}
										
				}
				
				$returnConstruct = remove_dup($returnConstruct);
				asort($returnConstruct);
				
				foreach($returnConstruct as $constructor) {
				
					if ($constructor == "") { continue; }
				
					$returnEditor .= "<strong>".($constructor)."</strong><br />";
					$returnEditor .= "<textarea onkeyup='startEditCSSTimer(); changeCSSRealtime(\"$constructor\", $(this).val(), \"editBlock".$pageBlock."\");' name='$constructor' class='form-control'>".$this->cssContent[$constructor]."</textarea><br />";
					
				}	
				
			}
			
			return $returnEditor;
			
		}
		
		function view() {
			
			foreach($this->templatePage_blocks[1] as $pageBlockKey => &$pageBlockValue) {
								
				// Fetch first element
				preg_match_all("/<.*?>/m", $pageBlockValue, $firstElementMatch);
				
				$firstElementMatch = $firstElementMatch[0][0];
			
				preg_match_all("/class=\"(.*?)\"/m", $firstElementMatch, $classMatch);
				
				if ($classMatch[0][0] != "") {
					
					$replaceElement = str_replace("class=\"", "class=\"editBlock".$this->blockID." ", $firstElementMatch);
					
				} else {
					
					$replaceElement = str_replace(">", ' class="editBlock'.$this->blockID.'">', $firstElementMatch);
					
				}
				
				$pageBlockValue = implode($replaceElement, explode($firstElementMatch, $pageBlockValue, 2));
				
				// Replace all singleline elements with inputs
				preg_match_all('/(<singleline.*?>.*?<\/singleline>)/ism', $pageBlockValue, $singleLineElements);
				
				foreach($singleLineElements[1] as $singleLineElement) {
					
					$XMLparser = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$singleLineElement);
					// New input
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)] != "") {
						
						$XMLparser->{0} = $this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)];
						
					}
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link"] != "") {
						
						$newLink = "<a href='".$this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link"]."'"; 
						
						if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link_target"] != "") {
							
							$newLink .= " target='".$this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link_target"]."'";
						
						}
						
						if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link_title"] == "") {
							
							$this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link_title"] = $XMLparser->{0};
							
						}
						
						$newLink .= " title='".$this->newContentArray[sha1($XMLparser->Attributes()->name.$singleLineElement.$this->blockID)."_link_title"]."'>".$XMLparser->{0}."</a>";
						
						$XMLparser->{0} = $newLink;
											
					}
					
					$pageBlockValue = str_replace($singleLineElement, $XMLparser->{0}, $pageBlockValue);
					$pageBlockValue = str_replace('<?xml version="1.0" encoding="UTF-8"?>', "", $pageBlockValue);
					
				}	
				
				// Replace all multiline elements with textareas
				preg_match_all('/(<multiline.*?>.*?<\/multiline>)/ism', $pageBlockValue, $multiLineElements);
				
				foreach($multiLineElements[1] as $multiLineElement) {
					
					$XMLparser = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$multiLineElement);
					// New textarea
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$multiLineElement.$this->blockID)] != "") {
						$contentShow = $this->newContentArray[sha1($XMLparser->Attributes()->name.$multiLineElement.$this->blockID)];
					} else {
						$contentShow = $XMLparser->{0}->asXML();
					}
					$pageBlockValue = str_replace($multiLineElement, "<div>".$contentShow."</div>", $pageBlockValue);
					$pageBlockValue = str_replace('<?xml version="1.0" encoding="UTF-8"?>', "", $pageBlockValue);
					
				}		
				
				// Replace all multiline elements with textareas
				preg_match_all('/(<img editable="true".*?\/>)/ism', $pageBlockValue, $pictureElements);
				
				foreach($pictureElements[1] as $pictureElement) {
					
					$XMLparser = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$pictureElement);
					// New textarea
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)] != "") {
						
						$XMLparser->Attributes()->src = $this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)];
						
					}
					
					$altTekst = $this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_alt"];
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_link"] != "") {
						
						$newPageBlockValue = "<a href='".$this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_link"]."' ";
						
						if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_link_target"] != "") {
						
							$newPageBlockValue .= "target='".$this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_link_target"]."' ";
						
						}
						
						$newPageBlockValue .= "title='".$this->newContentArray[sha1($XMLparser->Attributes()->name.$pictureElement.$this->blockID)."_link_title"]."'><img alt='".$altTekst."' class='".$XMLparser->Attributes()->class."' src='".$XMLparser->Attributes()->src."' /></a>";
										
						$pageBlockValue = str_replace($pictureElement, $newPageBlockValue, $pageBlockValue);
											
					} else {
						
						$pageBlockValue = str_replace($pictureElement, "<img alt='".$altTekst."' class='".$XMLparser->Attributes()->class."' src='".$XMLparser->Attributes()->src."' />", $pageBlockValue);
					
					}
					
					$pageBlockValue = str_replace('<?xml version="1.0" encoding="UTF-8"?>', "", $pageBlockValue);
					
				}
				
				// Replace all file elements with inputs
				preg_match_all('/(<file.*?>.*?<\/file>)/ism', $pageBlockValue, $fileElements);
				
				foreach($fileElements[1] as $fileElement) {
				
					$XMLparser = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$fileElement);
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)] != "") {
						
						$XMLparser->{0} = $this->newContentArray[sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)];
						
					}
					
					if ($this->newContentArray[sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)."_file"] != "") {
					
						$href = $this->newContentArray[sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)."_file"];
					
					} else {
						
						$href = $XMLparser->Attributes()->href;
						
					}
					
					// New textarea
					$pageBlockValue = str_replace($fileElement, "<a href='".$href."' id='".sha1($XMLparser->Attributes()->name.$fileElement.$this->blockID)."_".$pageBlockKey."'>".$XMLparser->{0}."</a>", $pageBlockValue);
					$pageBlockValue = str_replace('<?xml version="1.0" encoding="UTF-8"?>', "", $pageBlockValue);

				}			
				
			}
			
		}
		
		function generate() {
			
				$returnValue .= $this->templatePage_blocks[1][0];
			
			return $returnValue;
			
		}
		
		function outputAsHTML() {
			
			if ($this->HTML != "") {
			
				return $this->HTML;
			
			} else {
				
				return "Template not generated.";
				
			}
			
		}
			
		
	}
	
?>