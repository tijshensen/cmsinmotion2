<div class="container" style="height: 100% !important; width: 100%;">
	<div id="modalAdd" class="modal fade">
	</div>
	<input type="hidden" value="<?=$page->id;?>" id="page_id" />

<div class="col-sm-3 col-md-3 col-lg-4 sidebar" style="background-color: #fff; margin-top: 70px; padding: 0;">
         


			<div class="bs-example bs-example-tabs" style="padding-top: 8px;">
			    <ul id="myTab" class="nav nav-tabs" role="tablist">
			      	<li><a href="#hide" role="tab" data-toggle="tab" style="padding-left: 14px; padding-right: 14px;"><i class="fa fa-chevron-left" style="margin-top: 4px;"></i></a></li>
			      	<li class="active" ><a href="#editContent" role="tab" data-toggle="tab">Content</a></li>
			  		<li><a href="#editStyle" role="tab" data-toggle="tab">Style</a></li>
			  		<li class="pull-right" id="savedChanges" style="opacity: 0; margin-top: 8px; margin-right: 20px;"><span class="glyphicon glyphicon-ok"></span> Your changes have been saved.</li>
			    </ul>
			    <div id="myTabContent" class="tab-content">
				  <div class="tab-pane fade" id="hide">
			        <p>Javascript die ervoor zorgt dat dit scherm inklapt</p>
			      </div>
			      <div class="tab-pane fade in active" id="editContent">
					 	<p>Please select a block to edit.</p>
			      </div>

			      <div class="tab-pane fade" id="editStyle">
				
			        <p>Please select a block to edit.</p>
				 
			      </div>

			    </div>
			  </div><!-- /tabcontent -->


	
			
          </ul>


        </div>

	 <div class="col-sm-9 col-md-9 col-lg-8 main"  style="height: 100%; text-align:center">
	 <div class="row" style="height: 100%; text-align:center;">
	<iframe id="contentFrame" class="viewport device-iphone col-sm-9" frameborder="0" style="seamless: seamless;" src="<?=base_path_rewrite;?>/pages/view/<?=$page->id;?>"></iframe>
	 </div>
	 </div>
	
</div>

<div class="modal fade pagesettings" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Page / SEO settings</h4>
			</div>
			<div class="modal-body" style="padding-top: 0; padding-bottom: 0;">
				
				 <div class="row">
					<!-- /submenu -->
					<div class="col-md-3" style="padding-right: 0; padding-left: 0;">
						<ul id="myTab2" class="nav nav-tabs nav-stacked" role="tablist">
							<li class="active" ><a href="#basic" role="tab" data-toggle="tab">Basic</a></li>
							<li><a href="#advanced" role="tab" data-toggle="tab">Advanced</a></li>
							<li><a href="#seo" role="tab" data-toggle="tab">SEO</a></li>
						</ul>
					</div>
					<!-- /tabcontent -->
					
					<div class="col-md-9" style="border-left:1px solid #e1e1e1;">
						<div id="myTabContent" class="tab-content">
							<div class="tab-pane fade in active" id="basic">
								<form class="form-horizontal" role="form">
									<div class="form-group">
										<label for="pagetitle" class="col-sm-3 control-label">Page Title</label>
										<div class="col-sm-9">
											<input name="pagetitle" type="text" class="form-control" value="<?=$page->title;?>" id="pagetitle"></input>	
										</div>
									</div>
									<div class="form-group">
										<label for="metadescription" class="col-sm-3 control-label">Meta description</label>
										<div class="col-sm-9">
											<textarea id="metadescription" name="metadescription" value="<?=$page->meta_description;?>" class="form-control" rows="4"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label for="Author" class="col-sm-3 control-label">Author</label>
										<div class="col-sm-9">
											<input type="text" name="author" class="form-control" value="<?=$page->author;?>" id="author"></input>	
										</div>
									</div>
									<div class="form-group">
										<label for="publishdate" class="col-sm-3 control-label">Publish Date</label>
										<div class="col-sm-3">
											<input type="text" name="publishdate" class="form-control" id="publishdate" placeholder="31-12-2014"></input>	
										</div>
									</div>
									
								</form>
							</div>
							<!--Advanced-->
							<div class="tab-pane fade in" id="advanced">
								<form class="form-horizontal" role="form">
									<p>Hold your Ctrl or Command key while selecting the CSS and JS libraries needed for this page.</p>
									<div class="form-group">
										<label for="css" class="col-sm-3 control-label">CSS</label>
										<div class="col-sm-9">
											<select multiple name="css" class="form-control">
											  <option>1</option>
											  <option>2</option>
											  <option>3</option>
											  <option>4</option>
											  <option>5</option>
											</select>	
										</div>
									</div>
									
									<div class="form-group">
										<label for="js" class="col-sm-3 control-label">JS</label>
										<div class="col-sm-9">
											<select multiple name="js" class="form-control">
											  <option>1</option>
											  <option>2</option>
											  <option>3</option>
											  <option>4</option>
											  <option>5</option>
											</select>
										</div>
									</div>	
								</form>
							</div>		
							<!--SEO-->		      
							<div class="tab-pane fade in" id="seo">
								<form class="form-inline" role="form">
								  <div class="form-group">
								    <label class="sr-only" for="keyword">Email address</label>
								    <input type="text" name="keyword" class="form-control" id="keyword" placeholder="Keyword">
								  </div>
								 
								  <button type="submit" class="btn btn-default">Analyze</button>
								</form>
							</div>
						</div>
					</div>
				</div>
				
			</div><!--end footer-->
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	$('body').addClass('editor');
			
	function DropDown(el) {
		this.dd = el;
		this.placeholder = this.dd.children('span');
		this.opts = this.dd.find('ul.dropdown > li');
		this.val = '';
		this.index = -1;
		this.initEvents();
	}
	
	DropDown.prototype = {
		initEvents : function() {
			var obj = this;

			obj.dd.on('click', function(event){
				$(this).toggleClass('active');
				return false;
			});

			obj.opts.on('click',function(){
				var opt = $(this);
				obj.val = opt.text();
				obj.index = opt.index();
				obj.placeholder.text('Device: ' + obj.val);
				$('#contentFrame').removeClass(function() { return this.className.split(' ').filter(function(className) {return className.match(/device-.*/)}).join(' '); }).addClass(opt.attr('id'));
				
			});
		},
		getValue : function() {
			return this.val;
		},
		getIndex : function() {
			return this.index;
		}
	}

	var dd = new DropDown( $('#dd') );
			
</script>

<style>
.device-iphone{
	width: 320px;
	height: 640px;
}
.device-ipad
{
	width: 1024px;
	height:768px;
}
.device-100
{
	width: 100%;
	height: 100%;
}
</style>
