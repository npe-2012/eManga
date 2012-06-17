<!DOCTYPE html>
<html>
	<head>
		<title><?php echo Config::get('web.title'); ?></title>
		<?php echo css('bootstrap.css'); ?>
	</head>
	<body>
		<?php $view->display('includes/header'); ?>
		<!-- Body -->
		<div class="container">
          <div class="hero-unit">
            <h1>Error 404</h1>
	    <p><?php echo $message; ?></p>
      </div><!--/row-->

      <hr>

      <?php $view->display('includes/footer'); ?>

    </div>
	</body>
</html>