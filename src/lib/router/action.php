<?php
/**
 * This file is part of Krafter.
 *
 * @author Alvaro Carneiro <d3sign.night@gmail.com>
 * @license MIT License
 * @copyright 2011 - 2012 Alvaro Carneiro
 */

/**
 * Action of a route
 *
 * 
 */
class Router_Action {
	
	/**
	 * @var string The route of the action
	 * @access public
	 */
	public $route;
	
	/**
	 * @var Array Array with the arguments to pass to the action
	 * @access protected
	 */
	protected $args;
	
	/**
	 * Constructor of a new action
	 *
	 * @param string $route The route to load the callback
	 * @param callable $callback Callback to load when the uri matches the specified route
	 * @access public
	 */
	public function __construct($route, $callback)
	{
		$this->route = $route;
		$this->callback = $callback;
	}
	
	/**
	 * Run the action
	 * 
	 * @access public
	 */
	public function run(Router $router)
	{
		return call_user_func_array($this->callback, $router->get_args());
	}
	
	/**
	 * Prepare the route so now we can compare it with the uri
	 *
	 * @access public
	 */
	public function prepare_route()
	{
		$route = str_replace(array_keys(Router::$patterns), array_values(Router::$patterns), $this->route);
		
		$this->route = $route;
	}
	
	/**
	 * Add extensions to access
	 *
	 * @return Router_Action
	 * @access public
	 */
	public function ext()
	{
		// If no arguments
		if ( ($count = func_num_args()) === 0 )
		{
			return $this;
		}
		
		// Initialize exts
		$exts = '';
		
		// Loop each one argument
		foreach(func_get_args() as $key => $ext)
		{
			// If the ext isn't null then add 
			if ( $ext !== '' )
			{
				$ext = '\.'.$ext;
			}
			
			$exts .= $ext . ( ($key === $count-1) ? '' : '|' );
		}
		
		// use ?: to prevent them to by passed as an argument
		$exts = '(?:'.$exts.')';
		
		$this->route .= $exts;
		
		return $this;
	}
	
}

/* End of file action.php */
/* Location: ./src/libraries/router/action.php */