  <div class="modal-dialog">
  	<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Add a link</h4>
      </div>
      <div class="modal-body">
        <p>Please enter the link you want to add to this picture</p>
       <input type="text" value="<?=$_POST['currentLink'];?>" id="piclink" class="form-control" placeholder="https://www.example.com/" />
        <p>Add a title</p>
       <input type="text" value="<?=$_POST['currentTitle'];?>" id="piclink_title" class="form-control" placeholder="" />
        <p>Specify a target</p>
       <input type="text" value="<?=$_POST['currentTarget'];?>" id="piclink_target" class="form-control" placeholder="" />
      
	   <br />
	   <br />
	   Or choose a internal page<br/ >
	   <select id="internalLink" class="form-control">
		   <option value="">No</option>
	   <?php $links = fetchInternalLinks();
		foreach($links as $link) { ?>
		<option value="<?=$link['id'];?>"><?=$link['menu_title'];?></option>
		<?php } ?></select>
      </div>
      
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="addPicLinkExecute('<?=$_POST['id'];?>');">Add link</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
 