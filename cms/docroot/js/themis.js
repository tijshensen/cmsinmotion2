function klapMenu() {
	
	if (parseInt($('#menuBar').css("margin-top")) > 0) {
	
		$('#menuBar').animate({marginTop: "0px"});
		$('.topmenu').animate({marginBottom: "0px"});
	
	} else {
		
		$('#menuBar').animate({marginTop: "50px"});
		$('.topmenu').animate({marginBottom: "50px"});
	
	}	
	
}

function handleFileUpload(filename, block, pageBlockID) {
	
	$('#contentFrame').contents().find('#'+block).attr('href', basePath+"/content/uploads/"+filename);
	
}

function changeRealtime(el, valueNew) {
	
	var matches = "";
	matches = valueNew.match(/\[.*?\]/g);
	
	if($.isArray(matches)) {
		
		$.post(basePath+"/ajax/fetchInsert", { id: matches }, function(data) { 
			
			tagsContent = data.split(':_SPLITFETCH_:');
			for (tagIndex = 0; tagIndex < tagsContent.length; ++tagIndex) {
				splitCurrent = tagsContent[tagIndex].split(':_SPLITTAG_:');
				console.log("Replacing "+splitCurrent[0]+" with " + splitCurrent[1]);
				
				if (splitCurrent[0] != "") {
					
					valueNew = valueNew.replace(splitCurrent[0], splitCurrent[1]);
				}
				
				
			}
			
			$('#contentFrame').contents().find('#'+el).html(valueNew);
	
			
		});
		
	} else {
		
		$('#contentFrame').contents().find('#'+el).html(valueNew);
		
		
	}
	
	
	
}

function changeCSSRealtime(el, valueNew, editBlock) {
	
	valueNew = valueNew.replace(/(\r\n|\n|\r)/gm," ");
	
	valueNew = valueNew.replace(/{/,"");
	valueNew = valueNew.replace(/}/,"");
	
	currentContent = $('#contentFrame').contents().find('#cssStyles').html();
	
	var filter = new RegExp(' #' + editBlock + ' ' + el + ' {.*?}')
	
	if (currentContent.match(filter)) {
		
		currentContent = currentContent.replace(filter, ' .' + editBlock + ' '+ el+ ' { ' + valueNew + ' }');
		$('#contentFrame').contents().find('#cssStyles').html(currentContent);
		
	} else {
	
		$('#contentFrame').contents().find('#cssStyles').html($('#contentFrame').contents().find('#cssStyles').html() + ' .' + editBlock + ' '+ el+ ' { ' + valueNew + ' }');
	
	}
	
}

function rightMenu() {
	
	$('#rightMenuTemplate').html();
	
}

function startEdit(elementID) {
	
	$('#editStyle').html();
	$('#editContent').html();
	
	
	$.post(basePath+"/ajax/getElementTemplateEditBoxes/", { id: elementID }, function(data) {
		
		$('#editContent').html(data);
		
	});
	
	$.post(basePath+"/ajax/getElementTemplateEditBoxesCSS/", { id: elementID }, function(data) {
		
		$('#editStyle').html(data);
		
	});
	
	
}

function addBlock(template_id) {
		
	// Fetch Modal
	$.post(basePath+"/ajax/fetchModalAddBlock/"+template_id, function(data) { 
		
		$('#modalAdd').html(data);
		$('#modalAdd').modal();
		
	});
	
}

function addNewBlockExecute() {

	page_id = $('#page_id').val();
	block_id = $('#new_block_id').val();

	$.post(basePath+"/ajax/addBlockExecute/", {page_id : page_id, block_id : block_id}, function(data) { 
	
		$('#contentFrame').attr( 'src', function ( i, val ) { return val; });
	
	});
	
}

editTimer = "";
editCSSTimer = "";

function startEditCSSTimer() { 
	
	clearTimeout(editCSSTimer);
	editCSSTimer = setTimeout("saveCSSChanges();", 800);
	
}

function handleImageUpload(filename, id, blockid, ratio) {
	
	//$('#contentFrame').contents().find('#'+id).attr('src', basePath+'/content/images/'+filename);
	replaceImage(filename, id, blockid, ratio);
	
}


function startEditTimer() {
	
	clearTimeout(editTimer);
	editTimer = setTimeout("saveChanges();", 800);
	
}

function saveCSSChanges() {
	
	pageID = $('#pageBlockID').val();
	
	$.post(basePath+"/ajax/saveChangesCSSInPageBlock/", {pageID: pageID, data: $('#currentCSSEditForm').serializeArray()}, function(data) { console.log(data); });
	$('#savedChanges').animate({opacity: 1});
	
	setTimeout("hideSaveChanges()", 3500);
	
}

function saveChanges() {

	pageID = $('#pageBlockID').val();
	
	console.log($('#currentEditForm').serializeArray());
	
	$.post(basePath+"/ajax/saveChangesInPageBlock/", {pageID: pageID, data: $('#currentEditForm').serializeArray()}, function(data) { console.log(data); });
	$('#savedChanges').animate({opacity: 1});
	
	setTimeout("hideSaveChanges()", 3500);
	
}

function hideSaveChanges() {
	
	$('#savedChanges').animate({opacity: 0}, 75);
	
	
}