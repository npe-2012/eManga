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
            <h1><?php echo $manga->getTitle(); ?></h1>
	    <?php
	    
		if ( $manga->hasDescription() )
		{
			echo $manga->getDescription();
		}
	    
	    ?>
            <p>Chapters of <?php echo $manga->getTitle(); ?></p>
            <?php
	    
	    $chapters = $manga->listChapters();
	    
	    if ( count($chapters) )
	    {
		foreach ($chapters as $chapter)
		{
			echo '<p><a href="' . $chapter['url'] . '">' . $chapter['name'] . '</a></p>';
		}
	    }
	    else
	    {
		echo '<p>This manga doesn\'t have chapters</p>';
	    }
	    
	    ?>
      </div><!--/row-->

      <hr>

      <?php $view->display('includes/footer'); ?>

    </div>
	</body>
</html>