<!DOCTYPE html>
<html>
	<head>
		<title><?php echo Config::get('web.name'); ?> | Reading <?php echo $manga->getTitle(); ?></title>
		<?php echo css('bootstrap.css'); ?>
		<script type="text/javascript">
			document.onkeydown = function(e){
				
				var key = e.which || e.keyCode;
				
				if ( key == 39 ) // next
				{
					window.location = "<?php echo $manga->getNextPage($pageno); ?>";
				}
				else if( key == 37 ) // prev
				{
					window.location = "<?php echo $manga->getPrevPage($pageno); ?>";
				}
			}
		</script>
	</head>
	<body>
		<?php $view->display('includes/header'); ?>
		<!-- Body -->
		<div class="container">
          <div class="hero-unit" style="text-align: center;">
            <h1><?php echo $manga->getTitle(); ?></h1>
	    <p><a href="<?php echo $manga->getPrevPage($pageno); ?>">Prev</a> - <?php echo $pageno . ' of ' . $manga->countPages() ?> - <a href="<?php echo $manga->getNextPage($pageno); ?>">Next</a></p>
	    <p<?php if ( Config::get('mangas.max_width', null) ){ echo ' style="max-width: ' . Config::get('mangas.max_width') . 'px;"'; } ?>><img src="<?php echo $page ?>" /></p>
      </div><!--/row-->

      <hr>
      
      <?php $view->display('includes/footer'); ?>

    </div>
	</body>
</html>