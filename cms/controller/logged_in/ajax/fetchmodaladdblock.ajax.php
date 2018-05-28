<?php
	$allTemplateBlocks = new ActiveRecord("template_block");
	$allTemplateBlocks->waar("template_id = '".$requestParameter."'");
	$allTemplateBlocks = $allTemplateBlocks->returnComplete();
?>
  <div class="modal-dialog">
  	<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Add a new block</h4>
      </div>
      <div class="modal-body">
        <p>Please select the block you want to add</p>
        <select name="block_id" id="new_block_id" class="form-control">
        	<?php foreach($allTemplateBlocks as $block) { ?>
        		<option value="<?=$block['id'];?>"><?=$block['name'];?></option>
        	<?php } ?>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="addNewBlockExecute();">Insert block</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->