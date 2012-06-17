<?php
/**
 * This file is part of eManga.
 *
 * @author Alvaro Carneiro <d3sign.night@gmail.com>
 * @license MIT License
 * @copyright 2011 - 2012 Alvaro Carneiro
 */

/**
 * Class to manage the views
 */
class ViewSystem {
	
	/**
	 * @var string The path where the views are in
	 * @access private
	 */
	private $path;
	
	/**
	 * @var array The variables to pass to the views
	 * @access private
	 */
	private $context = array();
	

	/**
	 * Returns a new template object
	 *
	 * @param string $path The path where the views are in
	 */
	public function __construct($path)
	{
		$this->path = $path;
	}
	
	/**
	 * Add a new context value to the views
	 *
	 * @param string $key The key
	 * @param mixed $value The value
	 * @access public
	 */
	public function set($key, $value)
	{
		array_set($this->context, $key, $value);
	}
	
	/**
	 * Return the content of a view
	 *
	 * @param string $name The name of the view
	 * @param array $context The context values to the view
	 * @access public
	 */
	public function render($name, array $context = array())
	{
		$path = $this->path.str_replace('.', DS, $name).'.php';
		
		if ( ! file_exists($path) )
		{
			trigger_error('The view file "'.$name.'" [in: "'.dirname($path).DS.'" ] doesn\'t exists', E_USER_ERROR);
		}
		
		$context = array_merge($this->context, $context);
		
		extract(array_merge($context, array('view' => $this)), EXTR_SKIP);
		
		ob_start();
		
		include $path;
		
		$content = ob_get_clean();
		
		return $content;
	}
	
	/**
	 * Display a view
	 *
	 * @param string $name The name of the view
	 * @param array $context The context values to the view
	 * @access public
	 */
	public function display($name, array $context = array())
	{
		echo $this->render($name, $context);
	}
	
}

/**
 *
 * @param string $file The name of the css file to include
 */
function css($file)
{
	if ( substr($file, 0, 4) == 'http' )
	{
		$href = $file;
	}
	else
	{
		$href = Config::get('web.url').'media/css/'.$file;
	}
	
	return '<link rel="stylesheet" href="'.$href.'" />';
}

/* End of file view.php */
/* Location: ./src/lib/view.php */