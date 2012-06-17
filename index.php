<?php

require_once 'src/init.php';

/**
 *
 * The home page
 *
 */
$router->get($routes['home'], function() use($view, $reader){
	
	$mangas = $reader->listMangas();
	
	$view->set('mangas', $mangas);
	
	$view->display('index');
	
});

/**
 *
 * To see all the chapters of a manga
 *
 * The parameter $name is the name of the manga
 */
$router->get($routes['manga.preview'], function($name) use($view, $reader){
	
	$name = $reader->decode($name);
	
	try {
		$manga = $reader->getManga($name);
	}
	catch(MangaNotFoundException $e)
	{
		notFound('Manga not found');
	}
	
	$view->set('manga', $manga);
	
	$view->display('manga');
	
});

$router->get($routes['manga.view'], function($name, $chapter, $pageno) use($view, $reader){
	
	try {
		$manga = $reader->getManga($name);
		$manga->preparePages($chapter);
		$page = $manga->getPage($pageno);
	}
	catch(MangaNotFoundException $e)
	{
		notFound('Manga not found');
	}
	catch(ChapterNotFoundException $e)
	{
		notFound('Chapter not found');
	}
	catch(ChapterPageNotFoundException $e)
	{
		notFound('The page of that chapter has not be found');
	}
	
	$view->set('page', $page);
	
	$view->set('pageno', $pageno);
	
	$view->set('manga', $manga);
	
	$view->display('reading');
	
});

try {
	$router->run();
}
catch(HttpNotFoundException $e)
{
	$view->set('message', $e->getMessage());
	$view->display('404');
}

/* End of file index.php */
/* Location: ./index.php */