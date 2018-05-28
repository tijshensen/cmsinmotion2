<?php

    /*function mysql_real_escape_string($text) {

 	   $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
           $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
 
           return str_replace($search, $replace, $text);
    }*/

    function writeRenderLog($text) {
        
        if (cmsinmotion_debug == 1) {
            
            file_put_contents("../render.log", date("Y-m-d H:i:s"). " (".$_GET['url'].") : ".$text."\n", FILE_APPEND);
            
        }
        
    }
	
	function buildMenu($parent_id = 0) {
		
		$fetchMenu = new ActiveRecord("page");
		$fetchMenu->waar("parent_id = ".$parent_id." ORDER by `prio`");
		$menuStruct = $fetchMenu->returnComplete();
		
		foreach($menuStruct as $menu) {
			
			$checkDaughters = new ActiveRecord("page");
			$checkDaughters->waar("parent_id = '".$menu['id']."'");
			
			if ($menu['menu_title'] != "") { $menu['title'] = $menu['menu_title']; }
			
			if ($checkDaughters->returnRows() > 0) {
				
				$builtMenu[$menu['id']]['children'] = buildMenu($menu['id']);
				$builtMenu[$menu['id']]['title'] = $menu['title'];
				$builtMenu[$menu['id']]['isDefault'] = $menu['isDefault'];
				$builtMenu[$menu['id']]['isHidden'] = $menu['isHidden'];
				$builtMenu[$menu['id']]['page_seo_name'] = $menu['page_seo_name'];
				$builtMenu[$menu['id']]['id'] = $menu['id'];
				
			} else {
				
				$builtMenu[$menu['id']]['title'] = $menu['title'];
				$builtMenu[$menu['id']]['isDefault'] = $menu['isDefault'];
				$builtMenu[$menu['id']]['isHidden'] = $menu['isHidden'];
				$builtMenu[$menu['id']]['page_seo_name'] = $menu['page_seo_name'];
				$builtMenu[$menu['id']]['id'] = $menu['id'];
				
			}
			
		}
		
		return $builtMenu;
				
	}
	
	function fetchInternalLinks($returnType = "array") {
		
		$internalLinks = new ActiveRecord("page");
		$internalLinks->fetchAll("prio ASC");
		$allInternalLinks = $internalLinks->returnComplete();
		
		return $allInternalLinks;
		
	}

	function writeDiskLog($text) {
		
		if (cmsinmotion_debug == 1) {
			file_put_contents("../app.log", date("Y-m-d H:i:s")." (".$_SERVER['PHP_SELF'].") : ".$text."\n".print_r($_REQUEST, true)."\n\n", FILE_APPEND);
		}
		
	}
	
	function rep_count($matches) {
	  global $count;
	  return '[repeatBlock_' . $count++ .']';
	}


	function recursiveDelete($dir) {
	  $files = array_diff(scandir($dir), array('.','..'));
	  foreach ($files as $file) {
	    if (is_dir("$dir/$file")) { recursiveDelete("$dir/$file"); } else { unlink("$dir/$file"); };
	  }
	  return rmdir($dir);
	}

	function strip_tag($html, $tag) {

		$html = str_replace("<?xml version=\"1.0\" encoding=\"UTF-8\"?>", "", $html);
		return preg_replace('/<\/?' . $tag . '(.|\s)*?>/', '', $html);

	}

	function writeToLog($severity, $module, $action, $fromCLI = 0, $json = "") {
		
		if ($fromCLI == 1) {
			
			$user_id = 1;
			
		} else { 
		
			if ($_SESSION['id'] == "") {
				
				$user_id = 0; // Unauthenticated
			
			} else {
				
				$user_id = $_SESSION['id'];
				
			}
		
		}
		
		$newLogEntry = new ActiveRecord("logging");
		$newLogEntry->log_timestamp = date("Y-m-d H:i:s");
		$newLogEntry->log_timestamp_microtime = substr((string)microtime(), 1, 6);
		$newLogEntry->severity = $severity;
		$newLogEntry->module = strtoupper($module);
		$newLogEntry->action = $action;
		$newLogEntry->user_id = $user_id;
		$newLogEntry->session_id = $_SESSION['session-db-id'];
		$newLogEntry->json = $json;
		$newLogEntry->insert();		
		
	}
	
	function enable_implicit_flush()
	{
		@apache_setenv('no-gzip', 1);
		@ini_set('zlib.output_compression', 0);
		@ini_set('implicit_flush', 1);
		for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
		ob_implicit_flush(1);
		echo "<!-- ".str_repeat(' ', 2000)." -->";
	}
	function json_safe_encode($var)
	{
	   return json_encode(json_fix_cyr($var));
	}
	 
	function json_fix_cyr($var)
	{
	   if (is_array($var)) {
		   $new = array();
		   foreach ($var as $k => $v) {
			   $new[json_fix_cyr($k)] = json_fix_cyr($v);
		   }
		   $var = $new;
	   } elseif (is_object($var)) {
		   $vars = get_object_vars($var);
		   foreach ($vars as $m => $v) {
			   $var->$m = json_fix_cyr($v);
		   }
	   } elseif (is_string($var)) {
		   $var = iconv('cp1252', 'utf-8', $var);
	   }
	   return $var;
	}

	function __($tekst) {
                
			global $languageArray;
							
			// Translate Multi
			if ($languageArray[$_SESSION['language']][$tekst] != "") {
			
					return $languageArray[$_SESSION['language']][$tekst];
			
			} else {
			
					return $tekst;
			
			}               
			
	}
        		
	function thumbnailPhoto($photo) {
		
		$extensionSearch = explode(".", $photo);
		$extension = $extensionSearch[count($extensionSearch) - 1];
		if ($photo == "") { 
			$returnValue = "no_picture-thumb.png";
		} else {
			$returnValue = $extensionSearch[count($extensionSearch) - 2]."-thumb.".$extension;
		}
		
		return $returnValue;
		
	}

	// Begle misc and common functions
	function dprint_r($arr, $die = false) {
		
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
		if ($die == true) {
			
			die();
			
		}
		
	}
	
	function humanDate($date) {
		
		$deDatum = strtotime($date);
		$gisteren = strtotime("now -1 day");	
		$huidigeDatum = time();
		
		$dag = date("w", $deDatum);
		
		switch($dag) {
			
			case 0:
				$dag = "zondag";
				break;
			case 1:
				$dag = "maandag";
				break;
			case 2:
				$dag = "dinsdag";
				break;
			case 3:
				$dag = "woensdag";
				break;
			case 4:
				$dag = "donderdag";
				break;
			case 5:
				$dag = "vrijdag";
				break;
			case 6:
				$dag = "zaterdag";
				break;
			
		}
		
		$maandNr = date("m", $deDatum);
		
		switch($maandNr) {
			
			case 1:
				$maand = "januari";
				break;
			case 2:
				$maand = "februari";
				break;
			case 3:
				$maand = "maart";
				break;
			case 4:
				$maand = "april";
				break;
			case 5:
				$maand = "mei";
				break;
			case 6:
				$maand = "juni";
				break;
			case 7:
				$maand = "juli";
				break;
			case 8:
				$maand = "augustus";
				break;
			case 9:
				$maand = "september";
				break;
			case 10:
				$maand = "oktober";
				break;
			case 11:
				$maand = "november";
				break;
			case 12:
				$maand = "december";
				break;
			
		}
		
		if (date("d-m-Y", $deDatum) == date("d-m-Y", $huidigeDatum)) {
			
			$returnString = "Vandaag om ".date("H:i", $deDatum);
			
		} else {
			
			if (date("d-m-Y", $deDatum) == date("d-m-Y", $gisteren)) {
				
				$returnString = "Gisteren om ".date("H:i", $deDatum);
				
			} else {
				
				if (date("Y", $deDatum) == date("Y", $huidigeDatum)) {
				
					$returnString = $dag." ".date("d", $deDatum)." ".$maand." om ".date("H:i", $deDatum);
				
				} else { 
				
					$returnString = $dag." ".date("d", $deDatum)." ".$maand." ".date("Y", $deDatum)." om ".date("H:i", $deDatum);
				
				}
				
			}
			
		}
		
		return $returnString;
		
		
	}
	
	function humanDateNoTime($date, $useColour = false) {
		
		$deDatum = strtotime($date);
		$gisteren = strtotime("now -1 day");
		$morgen = strtotime("now +1 day");	
		$huidigeDatum = time();
		
		$dag = date("w", $deDatum);
		
		switch($dag) {
			
			case 0:
				$dag = "zondag";
				break;
			case 1:
				$dag = "maandag";
				break;
			case 2:
				$dag = "dinsdag";
				break;
			case 3:
				$dag = "woensdag";
				break;
			case 4:
				$dag = "donderdag";
				break;
			case 5:
				$dag = "vrijdag";
				break;
			case 6:
				$dag = "zaterdag";
				break;
			
		}
		
		$maandNr = date("m", $deDatum);
		
		switch($maandNr) {
			
			case 1:
				$maand = "januari";
				break;
			case 2:
				$maand = "februari";
				break;
			case 3:
				$maand = "maart";
				break;
			case 4:
				$maand = "april";
				break;
			case 5:
				$maand = "mei";
				break;
			case 6:
				$maand = "juni";
				break;
			case 7:
				$maand = "juli";
				break;
			case 8:
				$maand = "augustus";
				break;
			case 9:
				$maand = "september";
				break;
			case 10:
				$maand = "oktober";
				break;
			case 11:
				$maand = "november";
				break;
			case 12:
				$maand = "december";
				break;
			
		}
		
		if (date("d-m-Y", $deDatum) == date("d-m-Y", $huidigeDatum)) {
			
			if ($useColour == true) { 
			
				$returnString = "<span class='color-orange'>Vandaag</span>";
			
			} else { 
			
				$returnString = "Vandaag";
			
			}
			
		} else {
			
			if (date("d-m-Y", $deDatum) == date("d-m-Y", $gisteren)) {
				
				if ($useColour == true) { 
			
					$returnString = "<span class='color-red'>Gisteren</span>";
			
				} else { 
				
					$returnString = "Gisteren";
				
				}
				
			} else {
			
				if (date("d-m-Y", $deDatum) == date("d-m-Y", $morgen)) {
				
					if ($useColour == true) { 
			
						$returnString = "<span class='color-green'>Morgen</span>";
					
					} else { 
				
						$returnString = "Morgen";

					}
				
				} else { 
				
					if ($useColour == true) { 
				
						if ($huidigeDatum > $deDatum) { 

							if (date("Y", $deDatum) == date("Y", $huidigeDatum)) {
					
								$returnString = "<span class='color-red'>".$dag." ".date("d", $deDatum)." ".$maand."</span>";
							
							} else { 
							
								$returnString = "<span class='color-red'>".$dag." ".date("d", $deDatum)." ".$maand." ".date("Y", $deDatum)."</span>";
							
							}
						
						} else { 
						
							if (date("Y", $deDatum) == date("Y", $huidigeDatum)) {
					
								$returnString = "<span class='color-green'>".$dag." ".date("d", $deDatum)." ".$maand."</span>";
							
							} else { 
							
								$returnString = "<span class='color-green'>". $dag." ".date("d", $deDatum)." ".$maand." ".date("Y", $deDatum)."</span>";
							
							}
							
						}
				
					} else {
				
						if (date("Y", $deDatum) == date("Y", $huidigeDatum)) {
						
							$returnString = $dag." ".date("d", $deDatum)." ".$maand;
						
						} else { 
						
							$returnString = $dag." ".date("d", $deDatum)." ".$maand." ".date("Y", $deDatum);
						
						}
					
					}
				
				}
				
			}
			
		}
		
		return $returnString;
		
		
	}
	
	function createRandomPassword() { 
	
		$chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
		srand((double)microtime()*1000000); 
		$i = 0; 
		$pass = '' ; 
	
		while ($i <= 8) { 
			$num = rand() % 33; 
			$tmp = substr($chars, $num, 1); 
			$pass = $pass . $tmp; 
			$i++; 
		} 
	
		return $pass; 
	
	} 
	
	function aesEncrypt($sValue, $sSecretKey)
	{
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $sSecretKey, $sValue, MCRYPT_MODE_CBC, $iv))).":_:IV:_:".base64_encode($iv);
	}

	function aesDecrypt($sValue, $sSecretKey, $iv)
	{
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $sSecretKey, base64_decode($sValue), MCRYPT_MODE_CBC, base64_decode($iv)));
	}

	function setFlash($message, $colour = "info") {
		
		$_SESSION['flash_colour'] = $colour;
		$_SESSION['flash'] = $message;
	
	}
	function br2nl($string){ 
  		$return=eregi_replace('<br[[:space:]]*/?[[:space:]]*>',chr(13).chr(10),$string); 
  		return $return; 
	} 

	function flash() {
	
		if ($_SESSION['flash'] != "") {
			
			$flashReturn = '<div class="alert alert-'.$_SESSION['flash_colour'].'"> '.$_SESSION['flash'].'</div>';
			$_SESSION['flash'] = "";
			$_SESSION['flash_colour'] = "";
			
		} else {
		
			$flashReturn = "";
		
		}
	
		return $flashReturn;
		
	}
	
	function mysqlDate($datum, $splitTeken = "-") {
	
		$dateExplode = explode($splitTeken, $datum);
		$returnDate = $dateExplode[2].$splitTeken.$dateExplode[1].$splitTeken.$dateExplode[0];
		
		return $returnDate;
	
	}
	
	function curl_get($url, array $get = NULL, array $options = array()) 
	{    
		$defaults = array( 
			CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get), 
			CURLOPT_HEADER => 0, 
			CURLOPT_RETURNTRANSFER => TRUE, 
			CURLOPT_TIMEOUT => 4 
		); 
		
		$ch = curl_init(); 
		curl_setopt_array($ch, ($options + $defaults)); 
		if( ! $result = curl_exec($ch)) 
		{ 
			trigger_error(curl_error($ch)); 
		} 
		curl_close($ch); 
		return $result; 
	} 
		
	// Onderstaande schaamteloos overgenomen van WordPress :)
	
	function _make_url_clickable_cb($matches) {
		$ret = '';
		$url = $matches[2];
	 
		if ( empty($url) )
			return $matches[0];
		// removed trailing [.,;:] from URL
		if ( in_array(substr($url, -1), array('.', ',', ';', ':')) === true ) {
			$ret = substr($url, -1);
			$url = substr($url, 0, strlen($url)-1);
		}
		return $matches[1] . "<a href=\"$url\" rel=\"nofollow\" target=\"_blank\">$url</a>" . $ret;
	}
	 
	function _make_web_ftp_clickable_cb($matches) {
		$ret = '';
		$dest = $matches[2];
		$dest = 'http://' . $dest;
	 
		if ( empty($dest) )
			return $matches[0];
		// removed trailing [,;:] from URL
		if ( in_array(substr($dest, -1), array('.', ',', ';', ':')) === true ) {
			$ret = substr($dest, -1);
			$dest = substr($dest, 0, strlen($dest)-1);
		}
		return $matches[1] . "<a href=\"$dest\" rel=\"nofollow\" target=\"_blank\">$dest</a>" . $ret;
	}
	 
	function _make_email_clickable_cb($matches) {
		$email = $matches[2] . '@' . $matches[3];
		return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
	}
	 
	function make_clickable($ret) {
		$ret = ' ' . $ret;
		// in testing, using arrays here was found to be faster
		$ret = preg_replace_callback('#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_url_clickable_cb', $ret);
		$ret = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_web_ftp_clickable_cb', $ret);
		$ret = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', '_make_email_clickable_cb', $ret);
	 
		// this one is not in an array because we need it to run last, for cleanup of accidental links within links
		$ret = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret);
		$ret = trim($ret);
		return $ret;
	}
	
	function calculate_days_between_dates($startDate, $endDate = null) {
		if(empty($endDate)) $endDate = date('Y-m-d');
		return abs(strtotime($endDate) - strtotime($startDate)) / (60*60*24);
	}
	
	function is_ipad() {
		
		$user_agent = $_SERVER['HTTP_USER_AGENT']; // get the user agent value - this should be cleaned to ensure no nefarious input gets executed
        $accept     = $_SERVER['HTTP_ACCEPT']; // get the content accept value - this should be cleaned to ensure no nefarious input gets executed
        
        if (preg_match('/ipad/i', $user_agent)) {
	        return true;
	        
        } else {
        	return false;
        }
		
	}
	
	function is_mobile(){
        $user_agent = $_SERVER['HTTP_USER_AGENT']; // get the user agent value - this should be cleaned to ensure no nefarious input gets executed
        $accept     = $_SERVER['HTTP_ACCEPT']; // get the content accept value - this should be cleaned to ensure no nefarious input gets executed
        
        if (preg_match('/ipad/i', $user_agent)) {
	        return false;
	        
        } else {
        return false
            || (preg_match('/ipod/i',$user_agent)||preg_match('/iphone/i',$user_agent))
            || (preg_match('/android/i',$user_agent))
            || (preg_match('/opera mini/i',$user_agent))
            || (preg_match('/blackberry/i',$user_agent))
            || (preg_match('/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i',$user_agent))
            || (preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce;)/i',$user_agent))
            || (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|psp|treo)/i',$user_agent))
            || ((strpos($accept,'text/vnd.wap.wml')>0)||(strpos($accept,'application/vnd.wap.xhtml+xml')>0))
            || (isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE']))
            || (in_array(strtolower(substr($user_agent,0,4)),array('1207'=>'1207','3gso'=>'3gso','4thp'=>'4thp','501i'=>'501i','502i'=>'502i','503i'=>'503i','504i'=>'504i','505i'=>'505i','506i'=>'506i','6310'=>'6310','6590'=>'6590','770s'=>'770s','802s'=>'802s','a wa'=>'a wa','acer'=>'acer','acs-'=>'acs-','airn'=>'airn','alav'=>'alav','asus'=>'asus','attw'=>'attw','au-m'=>'au-m','aur '=>'aur ','aus '=>'aus ','abac'=>'abac','acoo'=>'acoo','aiko'=>'aiko','alco'=>'alco','alca'=>'alca','amoi'=>'amoi','anex'=>'anex','anny'=>'anny','anyw'=>'anyw','aptu'=>'aptu','arch'=>'arch','argo'=>'argo','bell'=>'bell','bird'=>'bird','bw-n'=>'bw-n','bw-u'=>'bw-u','beck'=>'beck','benq'=>'benq','bilb'=>'bilb','blac'=>'blac','c55/'=>'c55/','cdm-'=>'cdm-','chtm'=>'chtm','capi'=>'capi','cond'=>'cond','craw'=>'craw','dall'=>'dall','dbte'=>'dbte','dc-s'=>'dc-s','dica'=>'dica','ds-d'=>'ds-d','ds12'=>'ds12','dait'=>'dait','devi'=>'devi','dmob'=>'dmob','doco'=>'doco','dopo'=>'dopo','el49'=>'el49','erk0'=>'erk0','esl8'=>'esl8','ez40'=>'ez40','ez60'=>'ez60','ez70'=>'ez70','ezos'=>'ezos','ezze'=>'ezze','elai'=>'elai','emul'=>'emul','eric'=>'eric','ezwa'=>'ezwa','fake'=>'fake','fly-'=>'fly-','fly_'=>'fly_','g-mo'=>'g-mo','g1 u'=>'g1 u','g560'=>'g560','gf-5'=>'gf-5','grun'=>'grun','gene'=>'gene','go.w'=>'go.w','good'=>'good','grad'=>'grad','hcit'=>'hcit','hd-m'=>'hd-m','hd-p'=>'hd-p','hd-t'=>'hd-t','hei-'=>'hei-','hp i'=>'hp i','hpip'=>'hpip','hs-c'=>'hs-c','htc '=>'htc ','htc-'=>'htc-','htca'=>'htca','htcg'=>'htcg','htcp'=>'htcp','htcs'=>'htcs','htct'=>'htct','htc_'=>'htc_','haie'=>'haie','hita'=>'hita','huaw'=>'huaw','hutc'=>'hutc','i-20'=>'i-20','i-go'=>'i-go','i-ma'=>'i-ma','i230'=>'i230','iac'=>'iac','iac-'=>'iac-','iac/'=>'iac/','ig01'=>'ig01','im1k'=>'im1k','inno'=>'inno','iris'=>'iris','jata'=>'jata','java'=>'java','kddi'=>'kddi','kgt'=>'kgt','kgt/'=>'kgt/','kpt '=>'kpt ','kwc-'=>'kwc-','klon'=>'klon','lexi'=>'lexi','lg g'=>'lg g','lg-a'=>'lg-a','lg-b'=>'lg-b','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-f'=>'lg-f','lg-g'=>'lg-g','lg-k'=>'lg-k','lg-l'=>'lg-l','lg-m'=>'lg-m','lg-o'=>'lg-o','lg-p'=>'lg-p','lg-s'=>'lg-s','lg-t'=>'lg-t','lg-u'=>'lg-u','lg-w'=>'lg-w','lg/k'=>'lg/k','lg/l'=>'lg/l','lg/u'=>'lg/u','lg50'=>'lg50','lg54'=>'lg54','lge-'=>'lge-','lge/'=>'lge/','lynx'=>'lynx','leno'=>'leno','m1-w'=>'m1-w','m3ga'=>'m3ga','m50/'=>'m50/','maui'=>'maui','mc01'=>'mc01','mc21'=>'mc21','mcca'=>'mcca','medi'=>'medi','meri'=>'meri','mio8'=>'mio8','mioa'=>'mioa','mo01'=>'mo01','mo02'=>'mo02','mode'=>'mode','modo'=>'modo','mot '=>'mot ','mot-'=>'mot-','mt50'=>'mt50','mtp1'=>'mtp1','mtv '=>'mtv ','mate'=>'mate','maxo'=>'maxo','merc'=>'merc','mits'=>'mits','mobi'=>'mobi','motv'=>'motv','mozz'=>'mozz','n100'=>'n100','n101'=>'n101','n102'=>'n102','n202'=>'n202','n203'=>'n203','n300'=>'n300','n302'=>'n302','n500'=>'n500','n502'=>'n502','n505'=>'n505','n700'=>'n700','n701'=>'n701','n710'=>'n710','nec-'=>'nec-','nem-'=>'nem-','newg'=>'newg','neon'=>'neon','netf'=>'netf','noki'=>'noki','nzph'=>'nzph','o2 x'=>'o2 x','o2-x'=>'o2-x','opwv'=>'opwv','owg1'=>'owg1','opti'=>'opti','oran'=>'oran','p800'=>'p800','pand'=>'pand','pg-1'=>'pg-1','pg-2'=>'pg-2','pg-3'=>'pg-3','pg-6'=>'pg-6','pg-8'=>'pg-8','pg-c'=>'pg-c','pg13'=>'pg13','phil'=>'phil','pn-2'=>'pn-2','pt-g'=>'pt-g','palm'=>'palm','pana'=>'pana','pire'=>'pire','pock'=>'pock','pose'=>'pose','psio'=>'psio','qa-a'=>'qa-a','qc-2'=>'qc-2','qc-3'=>'qc-3','qc-5'=>'qc-5','qc-7'=>'qc-7','qc07'=>'qc07','qc12'=>'qc12','qc21'=>'qc21','qc32'=>'qc32','qc60'=>'qc60','qci-'=>'qci-','qwap'=>'qwap','qtek'=>'qtek','r380'=>'r380','r600'=>'r600','raks'=>'raks','rim9'=>'rim9','rove'=>'rove','s55/'=>'s55/','sage'=>'sage','sams'=>'sams','sc01'=>'sc01','sch-'=>'sch-','scp-'=>'scp-','sdk/'=>'sdk/','se47'=>'se47','sec-'=>'sec-','sec0'=>'sec0','sec1'=>'sec1','semc'=>'semc','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','sk-0'=>'sk-0','sl45'=>'sl45','slid'=>'slid','smb3'=>'smb3','smt5'=>'smt5','sp01'=>'sp01','sph-'=>'sph-','spv '=>'spv ','spv-'=>'spv-','sy01'=>'sy01','samm'=>'samm','sany'=>'sany','sava'=>'sava','scoo'=>'scoo','send'=>'send','siem'=>'siem','smar'=>'smar','smit'=>'smit','soft'=>'soft','sony'=>'sony','t-mo'=>'t-mo','t218'=>'t218','t250'=>'t250','t600'=>'t600','t610'=>'t610','t618'=>'t618','tcl-'=>'tcl-','tdg-'=>'tdg-','telm'=>'telm','tim-'=>'tim-','ts70'=>'ts70','tsm-'=>'tsm-','tsm3'=>'tsm3','tsm5'=>'tsm5','tx-9'=>'tx-9','tagt'=>'tagt','talk'=>'talk','teli'=>'teli','topl'=>'topl','hiba'=>'hiba','up.b'=>'up.b','upg1'=>'upg1','utst'=>'utst','v400'=>'v400','v750'=>'v750','veri'=>'veri','vk-v'=>'vk-v','vk40'=>'vk40','vk50'=>'vk50','vk52'=>'vk52','vk53'=>'vk53','vm40'=>'vm40','vx98'=>'vx98','virg'=>'virg','vite'=>'vite','voda'=>'voda','vulc'=>'vulc','w3c '=>'w3c ','w3c-'=>'w3c-','wapj'=>'wapj','wapp'=>'wapp','wapu'=>'wapu','wapm'=>'wapm','wig '=>'wig ','wapi'=>'wapi','wapr'=>'wapr','wapv'=>'wapv','wapy'=>'wapy','wapa'=>'wapa','waps'=>'waps','wapt'=>'wapt','winc'=>'winc','winw'=>'winw','wonu'=>'wonu','x700'=>'x700','xda2'=>'xda2','xdag'=>'xdag','yas-'=>'yas-','your'=>'your','zte-'=>'zte-','zeto'=>'zeto','acs-'=>'acs-','alav'=>'alav','alca'=>'alca','amoi'=>'amoi','aste'=>'aste','audi'=>'audi','avan'=>'avan','benq'=>'benq','bird'=>'bird','blac'=>'blac','blaz'=>'blaz','brew'=>'brew','brvw'=>'brvw','bumb'=>'bumb','ccwa'=>'ccwa','cell'=>'cell','cldc'=>'cldc','cmd-'=>'cmd-','dang'=>'dang','doco'=>'doco','eml2'=>'eml2','eric'=>'eric','fetc'=>'fetc','hipt'=>'hipt','http'=>'http','ibro'=>'ibro','idea'=>'idea','ikom'=>'ikom','inno'=>'inno','ipaq'=>'ipaq','jbro'=>'jbro','jemu'=>'jemu','java'=>'java','jigs'=>'jigs','kddi'=>'kddi','keji'=>'keji','kyoc'=>'kyoc','kyok'=>'kyok','leno'=>'leno','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-g'=>'lg-g','lge-'=>'lge-','libw'=>'libw','m-cr'=>'m-cr','maui'=>'maui','maxo'=>'maxo','midp'=>'midp','mits'=>'mits','mmef'=>'mmef','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','mwbp'=>'mwbp','mywa'=>'mywa','nec-'=>'nec-','newt'=>'newt','nok6'=>'nok6','noki'=>'noki','o2im'=>'o2im','opwv'=>'opwv','palm'=>'palm','pana'=>'pana','pant'=>'pant','pdxg'=>'pdxg','phil'=>'phil','play'=>'play','pluc'=>'pluc','port'=>'port','prox'=>'prox','qtek'=>'qtek','qwap'=>'qwap','rozo'=>'rozo','sage'=>'sage','sama'=>'sama','sams'=>'sams','sany'=>'sany','sch-'=>'sch-','sec-'=>'sec-','send'=>'send','seri'=>'seri','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','siem'=>'siem','smal'=>'smal','smar'=>'smar','sony'=>'sony','sph-'=>'sph-','symb'=>'symb','t-mo'=>'t-mo','teli'=>'teli','tim-'=>'tim-','tosh'=>'tosh','treo'=>'treo','tsm-'=>'tsm-','upg1'=>'upg1','upsi'=>'upsi','vk-v'=>'vk-v','voda'=>'voda','vx52'=>'vx52','vx53'=>'vx53','vx60'=>'vx60','vx61'=>'vx61','vx70'=>'vx70','vx80'=>'vx80','vx81'=>'vx81','vx83'=>'vx83','vx85'=>'vx85','wap-'=>'wap-','wapa'=>'wapa','wapi'=>'wapi','wapp'=>'wapp','wapr'=>'wapr','webc'=>'webc','whit'=>'whit','winw'=>'winw','wmlb'=>'wmlb','xda-'=>'xda-',)))
        ;
        }
    }
    
    function addLastJavascript($javascript) {
	    
	    global $lastJavascriptAdd;
	    
	    $lastJavascriptAdd .= $javascript;
	    
    }
    
    function remove_dup($matriz) { 
	    $aux_ini=array(); 
	    $entrega=array(); 
	    for($n=0;$n<count($matriz);$n++) 
	    { 
	        $aux_ini[]=serialize($matriz[$n]); 
	    } 
	    $mat=array_unique($aux_ini); 
	    for($n=0;$n<count($matriz);$n++) 
	    { 
	        
	            $entrega[]=unserialize($mat[$n]); 
	        
	    } 
	    return $entrega; 
	}
	
	function is_date( $str ){ 
	
		if (preg_match('/[0-9]{2}-[0-9]{2}-[0-9]{4}/', $str)) {
			return true;
		} else {
			return false;
		}
		
	}

	function is_date_reverse( $str ){ 
	
		if (preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $str)) {
			return true;
		} else {
			return false;
		}
		
	}
	
	function sort_multi_array ($array, $key)
	{
	  $keys = array();
	  for ($i=1;$i<func_num_args();$i++) {
	    $keys[$i-1] = func_get_arg($i);
	  }
	
	  // create a custom search function to pass to usort
	  $func = function ($a, $b) use ($keys) {
	    for ($i=0;$i<count($keys);$i++) {
	      if ($a[$keys[$i]] != $b[$keys[$i]]) {
	        return ($a[$keys[$i]] < $b[$keys[$i]]) ? -1 : 1;
	      }
	    }
	    return 0;
	  };
	
	  usort($array, $func);
	
	  return $array;
	
	} 
      
    function register_lastPage() {
	    
	    $_SESSION['lastPage'] = $_GET;
	    
    }
    
    function return_lastPage() {
	    
	  	return $_SESSION['lastPage'];
	    
    }
    
    function debugDatabase() {
	    $data = "Query count: ".$_SESSION['debugActiveRecordCount']."<hr />";
	    $data .= $_SESSION['debugActiveRecord'];
	    $_SESSION['debugActiveRecordCount'] = 0;
	    $_SESSION['debugActiveRecord'] = "<h3>Sessie instance '".date("d-m H:i:s")."'</h3>";
	    return $data;
	    
    }
	
	function fetchCURL($url) {
	
		// create a new cURL resource
		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// grab URL and pass it to the browser
		$fetchData = curl_exec($ch);

		// close cURL resource, and free up system resources
		curl_close($ch);
		
		return $fetchData;
		
	}
		
	function normalize ($string) {
		$table = array(
			'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj', 'Ž'=>'Z', 'ž'=>'z', 'C'=>'C', 'c'=>'c', 'C'=>'C', 'c'=>'c',
			'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
			'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
			'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
			'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
			'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
			'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
			'ÿ'=>'y', 'R'=>'R', 'r'=>'r',
		);

		return strtr($string, $table);
	}
	
?>
