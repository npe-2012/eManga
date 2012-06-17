<?php

return array(
	
	// Web configuration
	'web'		=>	array(
		
		// The title of your web to put it into <title> tag
		'title'		=>	'eManga - Online manga reader',
		
		// The name of your web
		'name'		=>	'eManga',
		
		// The url of the web, if it is null it will be auto-generated
		'url'			=>	null,
		
		// The folder where the application is in
		'folder'		=>	'emanga',
		
		// The charset
		'charset'	=>	'UTF-8'
	),
	
	// The configuration to the mangas
	'mangas'		=>	array(
		
		// The path where the mangas are in
		'path'						=>	'mangas',
		
		// Array with the allowed extensions that must have the manga image
		'allowed_extensions'			=>	array('jpg', 'jpeg', 'png', 'bmp', 'gif'),
		
		// The maximum width that could have the manga. In case that the manga was higher then resize it
		'max_width'					=>	null
		
	)
	
);