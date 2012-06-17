<?php

// (:manga)	=> The name of the manga
// (:chapter)	=> The number of the current page
// (:page)	=> The number of the current page

return array(
	
	// The url of the home page
	'home'			=>		'/',
	
	// The url to view the chapters of a manga
	'manga.preview'	=>		'/view/(:manga)',
	
	// The url to read the pages of the manga
	'manga.view'	=>		'/view/(:manga)/(:chapter)/(:page)'
	
);