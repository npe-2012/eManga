<!DOCTYPE html>
<html>
	<head>
		<title><?php echo Config::get('web.title'); ?></title>
		<?php echo css('bootstrap.css'); ?>
	</head>
	<body>
		<?php $view->display('includes/header'); ?>
		<!-- Body -->
		<div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Available mangas</li>
	      <?php foreach($mangas as $manga){ ?>
	      <li><a href="<?php echo $manga['url']; ?>"><?php echo $manga['name'] ?></a></li>
	      <?php } ?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <div class="hero-unit" style="text-align: center;">
            <h1><?php echo Config::get('web.name'); ?></h1><br />
            <p>
		<select name="manga" onchange="if ( this.value ) { window.location.href = this.value; }">
		<option value="" selected>Select manga to read</option>
		<?php foreach($mangas as $manga){ ?>
			<option value="<?php echo $manga['url']; ?>"><?php echo $manga['name'] ?></option>
		<?php } ?>
		</select>
	    </p>
          </div>
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <?php $view->display('includes/footer'); ?>

    </div>
	</body>
</html>