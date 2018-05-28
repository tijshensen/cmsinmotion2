<?php
    
    // Fetch all text
    $allTextItemsSearch = new ActiveRecord("page_block");
    $allTextItemsSearch->waar("page_id = '".$_POST['id']."'");
    $allTextItems = $allTextItemsSearch->returnComplete();

    foreach ($allTextItems as $textItem) {
      
       $contentDecode = json_decode($textItem['content'], true);
        foreach($contentDecode as $value) {
         
            if ((strpos($value['name'], "link") === false && strpos($value['name'], "alt") !== false) || strpos($value['name'], "link") === false) {
            
                if (strpos(strip_tags($value['value']), "http://") === false && (strpos(strip_tags($value['value']), ".jpg") === false || strpos(strip_tags($value['value']), ".png") === false)) {
                
                    $allText .= strip_tags($value['value']);
                    
                }
                
            }
           
        }
    }
    $allText = preg_replace("~\\s{2,}~", " ", $allText);
    
    $allTextSplitted = explode(" ", $allText);
    $found = 0;
    
    foreach($allTextSplitted as &$changeCase) {
        
        $changeCase = strtolower($changeCase);
        
    }
    
    foreach($allTextSplitted as $word) {
        
        if (strtolower($word) == strtolower($_POST['keyword'])) {
            
            $found++;
            
        }
        
    }
    
    $inText = $found;
    $found = 0;
    
    foreach($allTextSplitted as $word) {
        
        if (strpos(strtolower($word), strtolower($_POST['keyword'])) !== false) {
            
            $found++;
            
        }
        
    }
    
    $partialText = $found;
    $totalText = count($allTextSplitted);
    
    $allWords = remove_dup($allTextSplitted);
    
    foreach($allWords as $word) {
    
        $word = strtolower($word);
        
        foreach($allTextSplitted as $analysis) {
    
            if (strtolower($analysis) == $word) {
            
                $perWordAnalysis[$word] = $perWordAnalysis[$word] + 1;
            
            }
        
        }
        
    }
    
    arsort($perWordAnalysis);
    
    
?>
<div style="padding-left: 25px;">
<h1>Results for '<?=strtolower($_POST['keyword']);?>':</h1>
<p>
    
    Pagetitle: <br />
    Description: <br />
    In text: <?=$inText;?>/<?=$totalText;?><br />
    Partial matches: <?=$partialText;?>/<?=$totalText;?><br />
    <br />
</p>

<h1>Per-word analysis</h1>
<p>
    <?php foreach($perWordAnalysis as $analysedWord => $number) { ?>
    <?=$analysedWord;?> - <?=$number;?> - <?=round($number/$totalText*100, 2);?>%<br />
    <?php } ?>
    
</p>
</div>
