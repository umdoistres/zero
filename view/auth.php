<?php
use B7KP\Library\Route;
use B7KP\Library\Lang;
use B7KP\Core\App;
?>
<!doctype html>
<html>
<?php
	$head = array("title" => "Register");
	$this->render("ext/head.php", $head);
?>
	<body class="inner-min">
		<?php $this->render("ext/menu.php");?>
		<?php $this->render("ext/header.php");?>
		<div id="fh5co-main">
			<section>
				<div class="container">
					<div class="row">

						<div class="fh5co-spacer fh5co-spacer-sm"></div>	
						<div class="col-md-4 col-md-offset-4 text-center">
							<h2><?php echo Lang::get('conn');?> <br><small><?php echo Lang::get('alr')?> <a href="<?php echo Route::url('login');?>">Login</a></small></h2>

							<a class="btn btn-danger" href="<?php echo "http://www.last.fm/api/auth/?api_key=".App::get('lastfmapikey');?>">
							<i class="fa fa-lastfm"></i> <?php echo Lang::get('click_h');?>
							</a>
							<?php 
							if(isset($error) && $error)
							{
							?>
							<div class="alert alert-danger"><?php echo Lang::get('error_token');?> <i class="fa-frown-o fa-fw fa"></i></div>
							<?php
							}
							?>
						</div>

						<div class="fh5co-spacer fh5co-spacer-md"></div>	

					</div>
					
				</div>
			</section>
			<?php $this->render("ext/footer.php");?>
		</div>
	</body>
</html>