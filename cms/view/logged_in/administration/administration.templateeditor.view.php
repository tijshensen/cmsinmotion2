
<h2>Templates</h2>


<ol class="breadcrumb">
  <li><a href="<?=base_path_rewrite;?>/administration/load">Templates</a></li>
  <li class="active"><?=$templateSetLookup->name;?></li>
</ol>

	<div class="row">
		<div class="col-md-8">
			<a href="<?=base_path_rewrite;?>/administration/templates/pageadd/<?=$templateSetLookup->id;?>" class="btn btn-primary">Add page</a>
		
			
			<a href="#" class="btn btn-warning btn-flat"><i class="fa fa-trash-o"></i> Delete</a>
		</div>
	</div>
		
	<div class="row">
		<div class="col-md-8">							
			<ul class="list-group">
				<?php foreach($allTemplates as $templateFile) { ?>
				<li class="list-group-item theme-page">
					<div style="display: inline-block;">
						<a href="<?=base_path_rewrite;?>/administration/templates/pageedit/<?=$templateFile['id'];?>" ><?=$templateFile['name'];?></a>
					</div>
					<div style="display: inline-block; float: right;">
					
						<a href="<?=base_path_rewrite;?>/administration/templates/blockadd/<?=$templateFile['id'];?>"><i class="fa fa-plus-circle"></i> Add block</a>
					</div>	
				</li>
					<ul  style="margin-bottom: 10px;">
					<?php 
						$fetchBlocks = new ActiveRecord("template_block");
						$fetchBlocks->waar("template_id = '".$templateFile['id']."'");
						
						foreach($fetchBlocks->returnComplete() as $templateBlock) { ?>	

						<li class="list-group-item">
							<div style="display: inline-block;">
								<a href="<?=base_path_rewrite;?>/administration/templates/blockedit/<?=$templateBlock['id'];?>">
									<?=$templateBlock['name'];?>
								</a>
							</div>
							<div style="display: inline-block; float: right;">
								<a href="<?=base_path_rewrite;?>/administration/templates/blockdelete/<?=$templateBlock['id'];?>" ><i class="fa fa-times"></i></a>
							</div>
							
						</li>
					<?php } ?>
					</ul>
				<?php } ?>
			</ul>
		</div>
	</div>

