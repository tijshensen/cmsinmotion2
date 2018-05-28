<?php

	switch($requestAction) {
        case "analysePage":
            include("../controller/logged_in/ajax/analysepage.ajax.php");
			break;
            
		case "uploadFile":
			include("../controller/logged_in/ajax/uploadfile.ajax.php");
			break;
			
		case "addPicAltExecute":
			include("../controller/logged_in/ajax/addpicaltexecute.ajax.php");
			break;
			
		case "addPicAlt":
			include("../controller/logged_in/ajax/addpicalt.ajax.php");
			break;
			
		case "savePageEditor":
			include("../controller/logged_in/ajax/savepageeditor.ajax.php");
			break;
			
		case "addTextLinkExecute":
			include("../controller/logged_in/ajax/addsinglelinkexecute.ajax.php");
			break;
			
		case "addTextLink":
			include("../controller/logged_in/ajax/addsinglelink.ajax.php");
			break;
			
		case "savePageNewBlock":
			include("../controller/logged_in/ajax/savepagenewblock.ajax.php");
			break;
	
		case "addPicLinkExecute":
			include("../controller/logged_in/ajax/addpiclinkexecute.ajax.php");
			break;
			
		case "addPicLink":
			include("../controller/logged_in/ajax/addpiclink.ajax.php");
			break;
			
		case "cropImage":
			include("../controller/logged_in/ajax/cropimage.ajax.php");
			break;
			
		case "deleteInsert":
			include("../controller/logged_in/ajax/deleteinsert.ajax.php");
			break;
			
		case "deletePage":
			include("../controller/logged_in/ajax/deletepage.ajax.php");
			break;
			
		case "saveMenuContent":
			include("../controller/logged_in/ajax/savemenucontent.ajax.php");
			break;
	
		case "savePageBlock":
			include("../controller/logged_in/ajax/savepageblock.ajax.php");
			break;
		
		case "uploadImage":
			include("../controller/logged_in/ajax/uploadimage.ajax.php");
			break;
		
		case "doSaveSortOrderBlocks":
			include("../controller/logged_in/ajax/dosavesortorderblocks.ajax.php");
			break;
	
		case "fetchInsert":
			include("../controller/logged_in/ajax/fetchinsert.ajax.php");
			break;
	
		case "saveInsert":
			include("../controller/logged_in/ajax/saveinsert.ajax.php");
			break;
	
		case "loadInsert":
			include("../controller/logged_in/ajax/loadinsert.ajax.php");
			break;
	
		case "saveMenus":
			include("../controller/logged_in/ajax/savemenus.ajax.php");
			break;
	
		case "doDeleteBlock":
			include("../controller/logged_in/ajax/dodeleteblock.ajax.php");
			break;
		
		case "hideBlock":
			include("../controller/logged_in/ajax/hideblock.ajax.php");
			break;
		
		case "unhideBlock":
			include("../controller/logged_in/ajax/unhideblock.ajax.php");
			break;
			
		case "saveChangesCSSInPageBlock":
			include("../controller/logged_in/ajax/savechangescssinpageblock.ajax.php");
			break;
			
		case "saveChangesInPageBlock":
			include("../controller/logged_in/ajax/savechangesinpageblock.ajax.php");
			break;
	
		case "addBlockExecute":
			include("../controller/logged_in/ajax/addblockexecute.ajax.php");
			break;
			
		case "fetchModalAddBlock":
			include("../controller/logged_in/ajax/fetchmodaladdblock.ajax.php");
			break;
		
		case "getElementTemplateEditBoxes":
			include("../controller/logged_in/ajax/getelementtemplateditboxes.ajax.php");
			break;		
			
		case "getElementTemplateEditBoxesCSS":
			include("../controller/logged_in/ajax/getelementtemplateeditboxes_style.ajax.php");
			break;	
			
	}

?>