<?php
/**
 * This file is part of eManga.
 *
 * @author Alvaro Carneiro <d3sign.night@gmail.com>
 * @license MIT License
 * @copyright 2011 - 2012 Alvaro Carneiro
 */

class HttpNotFoundException extends Exception { }

/**
 * Class to manage the routes
 */
class Router {
	
	/**
	 * @var array An array that contains the routes that can be generated later
	 * @access protected
	 */
	protected static $routes = array();
	
	/**
	 * @var array The wildcard patterns we replace in the routes
	 * @access public
	 */
	public static $patterns = array();
	
	/**
	 * @var array The arguments to the callback
	 * @access private
	 */
	private $args;
	
	/**
	 * @var string The current uri
	 * @access public
	 */
	private $uri;
	
	/**
	 * @var Response The response
	 * @access private
	 */
	private $response;

	/**
	 * @var array Set of actions
	 * @access protected
	 */
	protected static $actions = array(
		'ANY'  => array(),
		'GET'  => array(),
		'POST' => array()
	);
	
	public function __construct($uri)
	{
		require_once LIBPATH.'router'.DS.'action.php';
		
		$uri = preg_replace('#' . Config::get('web.folder') . '/?([^/]+\.php/?)?#', '', $uri, 1);
		
		$uri = trim(parse_url($uri, PHP_URL_PATH), '/');
		
		if ( $uri == '' )
		{
			$uri = '/';
		}
		$this->uri = $uri;
	}
	
	/**
	 * Add a route that can be generated later
	 *
	 * @param string $name The name of the route
	 * @param string $route The route
	 * @access public
	 */
	public static function add_route($name, $route = null)
	{
		// also you can use the $name var as array to do something like this:
		/*
		 Router::add_route(array(
			'home'	=>	'/',
			'profile'	=>	'profile/(:username)'
		 ))
		*/
		if ( is_array($name) )
		{
			foreach($name as $_name => $_route)
			{
				static::$routes[$_name] = $_route;
			}
			return true;
		}
		
		static::$routes[$name] = $route;
		
		return true;
	}
	
	/**
	 * Generate a route
	 *
	 * @param string $name The name of the route to generate
	 * @param array $structure The structure of the route.
	 * @return string The generated route | The web url if the route doesn't exists
	 * @access public
	 */
	public static function generate($name, array $structure = array())
	{
		// If the route doesn't exists
		if ( ! isset(static::$routes[$name]) )
		{
			// Then return the url of the web
			return Config::get('web.url');
		}
		
		// then fetch only the first part of it, we don't want to return all of them 
		$route = static::$routes[$name];
		
		// let's ...
		foreach ( $structure as $key => $value)
		{
			// structurize it
			$route = str_replace('(:'.$key.')', $value, $route);
		}
		
		return Config::get('web.url').ltrim($route, '/');
	}
	
	/**
	 * Add pattern to replace into the routes
	 *
	 * @param string $name The name of the pattern
	 * @param string $pattern The regex
	 * @access public
	 */
	public function assert($name, $pattern)
	{
		static::$patterns['(:'.$name.')'] = $pattern;
	}
	
	/**
	 * Register a callback to a route
	 *
	 * @throws InvalidArgumentException when the parameter $callback is not callable
	 *
	 * @param string $route The route we need
	 * @param callable $callback Callback to load when the uri matches the specified route
	 * @return Router_Action
	 * @access public
	 */
	protected function register($route, $callback, $method)
	{
		// the slash sometimes throws errors
		$route = trim($route, '/');
		
		if ( $route == '' )
		{
			$route = '/';
		}
		
		// Create the new action and return it to do our stuff then
		return static::$actions[$method][] = new Router_Action($route, $callback);
	}
	
	/**
	 *  Register a route that handles any request method.
	 *
	 * @param string $route The route to load the callback
	 * @param Closure $callback Callback to load when the uri matches the specified route
	 * @return Router_Action
	 * @access public
	 */
	public function any($route, Closure $callback)
	{
		return $this->register($route, $callback, 'ANY');
	}
	
	/**
	 * Register a POST route
	 *
	 * @param string $route The route to load the callback
	 * @param Closure $callback Callback to load when the uri matches the specified route
	 * @return Router_Action
	 * @access public
	 */
	public function post($route, Closure $callback)
	{
		return $this->register($route, $callback, 'POST');
	}
	
	/**
	 * Register a GET route
	 *
	 * @param string $route The route to load the callback
	 * @param Closure $callback Callback to load when the uri matches the specified route
	 * @return Router_Action
	 * @access public
	 */
	public function get($route, Closure $callback)
	{
		return $this->register($route, $callback, 'GET');
	}
	
	/**
	 * Manage request
	 * 
	 * @throws HttpNotFoundException If the request action not found
	 *
	 * @access public
	 */
	public function run()
	{
		foreach(array_merge(static::$actions['ANY'], static::$actions[$_SERVER['REQUEST_METHOD']]) as $action)
		{
			// prepare the rout
			$action->prepare_route();
			
			// if the current route has response then call it
			if ($this->matches($action->route))
			{
				ob_start();
				
				$action->run($this);
				
				$output = ob_get_clean();
				
				if ( ! headers_sent() )
				{
					header('Content-type: text/html;charset='.Config::get('web.charset'));
				}
				
				echo $output;
				return;
			}
		}
		// Throw 
		throw new HttpNotFoundException('Page not found');
	}
	
	/**
	 * Check if the uri matches
	 *
	 * @param string $route The route that must match the uri
	 * @return bool Return true if the uri and the route matches, false otherwise
	 * @access public
	 */
	public function matches($route)
	{
		if ( ! preg_match('#^'. $route . '$#', $this->uri, $subs ) )
		{
			return false;
		}
		
		if ( count($subs) )
		{
			unset($subs[0]);
		}
		
		$this->args = array_values($subs);
		
		return true;
	}
	
	/**
	 * Get uri arguments to call the action
	 *
	 * @return array An array with the uri arguments to the callback
	 * @access public
	 */
	public function get_args()
	{
		return $this->args;
	}
	
}

/**
 *
 * Shortcut to "throw new HttpNotFoundException"
 * this is to trigger the 404 not found page
 *
 */
function notFound($message = 'Page not found')
{
	throw new HttpNotFoundException($message);
}

/**
 * Shortcut to Router::generate()
 *
 * Generate a route
 *
 * @param string $name The name of the route to generate
 * @param array $structure The structure of the route.
 * @return string The generated route | The web url if the route doesn't exists
 */
function route($name, array $structure = array())
{
	return Router::generate($name, $structure);
}

/* End of file router.php */
/* Location: ./src/lib/router/router.php */