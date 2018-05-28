  <div class="modal-dialog">
  	<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Add alt-text</h4>
      </div>
      <div class="modal-body">
        <p>Please enter the alt-text you want to add to this picture</p>
       <input type="text" value="<?=$_POST['currentAlt'];?>" id="picalt" class="form-control" />
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="addPicAltExecute('<?=$_POST['id'];?>');">Add alt-text</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->