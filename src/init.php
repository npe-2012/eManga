<?php

// It's just a shortcut
define('DS', DIRECTORY_SEPARATOR);

// The path where the application is in
define('BASEPATH', dirname(__DIR__).DS);

// The path where the config folder and the lib folder are in
define('APPPATH', BASEPATH.'src'.DS);

// The path where the configuration files are in
define('CFGPATH', APPPATH.'config'.DS);

// The path where the libraries are in
define('LIBPATH', APPPATH.'lib'.DS);

// Import the config class
require_once LIBPATH.'config.php';

// Load the configuration
Config::load('config');

/**
 *
 * Let's generate the url of the web if it doesn't exists in the config file
 *
 */
Config::set('web.folder', trim(Config::get('web.folder'), '/'));

if ( null === Config::get('web.url', null) )
{
	Config::set('web.url', 'http://'.$_SERVER['SERVER_NAME'] . '/' . Config::get('web.folder') . '/' );
}
else
{
	Config::set('web.url', rtrim(Config::get('web.url'), '/') . '/');
}
/**
 * // End generating the url
 */

// Import the router class 
require_once LIBPATH.'router'.DS.'router.php';

// Create a new instance of the router with the request uri to clean it
$router = new Router($_SERVER['REQUEST_URI']);

$router->assert('manga', '([^/]*)');

$router->assert('chapter', '([[:digit:]]+)');

$router->assert('page', '([[:digit:]]+)');


/**
 *
 * Loading the routes
 *
 */
Config::load('routes', 'routes');

$routes = Config::get('routes');

Router::add_route($routes);

require_once LIBPATH.'view.php';

$view = new ViewSystem(BASEPATH.'media'.DS.'views'.DS);

/**
 *
 * Create a new instance to the mangas reader
 *
 */
require_once LIBPATH.'reader.php';

Config::set('mangas.path', trim(Config::get('mangas.path'), '/'));

$reader = new MangaReader(Config::get('mangas.path'));

/* End of file init.php */
/* Location: ./src/init.php */