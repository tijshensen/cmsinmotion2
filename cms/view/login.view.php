<body class="body-login">


<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			<h1 class="text-center">CMSinMotion</h1>
			<div class="login-body">
			
			<div><?=flash();?></div>
	            <form class="form center-block" action="<?=base_path_rewrite;?>/authenticate" method="POST">
	              <div class="form-group">
					  <label>E-mail</label>
	                <input type="text" name="emailaddress" class="form-control" />
	              </div>
	              <div class="form-group">
					  <label>Password</label>
	                <input type="password" name="password" class="form-control " />
	              </div>
	              <div class="form-group">
	  	          <input type="checkbox" name="cookieSetter" /> Ingelogd blijven<br />
	              </div>
	              <div class="form-group">
	                <input type="submit" value="Login" class="btn btn-primary" />
	              </div>
	            </form>	
			</div>
		</div>
	</div>
</div>