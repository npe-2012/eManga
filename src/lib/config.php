<?php
/**
 * This file is part of eManga.
 *
 * @author Alvaro Carneiro <d3sign.night@gmail.com>
 * @license MIT License
 * @copyright 2011 - 2012 Alvaro Carneiro
 */

/**
 * Class to manage settings
 *
 * @abstract
 */
abstract class Config {

	/**
	 * @var array Set of config data
	 * @access protected
	 */
	private static $config;
	
	/**
	 * Load a config file
	 *
	 * @param string $file The location of the config file to load
	 * @param string $prefix The prefix of the config data
	 * @access public
	 */
	public static function load($file, $prefix = null)
	{
		$data = (array)require_once CFGPATH.$file.'.php';
		
		array_set(static::$config, $prefix, $data);
	}

	/**
	 * Set a config value
	 *
	 * @param string $name Name of the config (can use "." to make an array example: "Config::set('web.url', 'test')" will result "$config['web']['url'] = 'test'")
	 * @param mixed $value Value of the config
	 * @access public
	 */
	public static function set($name, $value)
	{
		array_set(static::$config, $name, $value);
	}

	/**
	 * Get a config value
	 *
	 * @param string $name Name of the config (can use "." to search into an array example: "Config::get('web.url')" will get "$config['web]['url']")
	 * @aram string $default Default value to return if the setting doesn't exists
	 * @return mixed The config value
	 * @access public
	 */
	public static function get($name = '', $default = null)
	{
		return array_get(static::$config, $name, $default);
	}

}

/**
 * Get an item from an array using "dot" notation.
 *
 * <code>
 *		// Get the $array['user']['name'] value from the array
 *		$name = array_get($array, 'user.name');
 *
 *		// Return a default from if the specified item doesn't exist
 *		$name = array_get($array, 'user.name', 'Taylor');
 * </code>
 *
 * @param  array   $array
 * @param  string  $key
 * @param  mixed   $default
 * @return mixed
 */
function array_get($array, $key, $default = null)
{
	if (is_null($key)) return $array;

	// To retrieve the array item using dot syntax, we'll iterate through
	// each segment in the key and look for that value. If it exists, we
	// will return it, otherwise we will set the depth of the array and
	// look for the next segment.
	foreach (explode('.', $key) as $segment)
	{
		if ( ! is_array($array) or ! array_key_exists($segment, $array))
		{
			return $default;
		}

		$array = $array[$segment];
	}

	return $array;
}

/**
 * Set an array item to a given value using "dot" notation.
 *
 * If no key is given to the method, the entire array will be replaced.
 *
 * <code>
 *		// Set the $array['user']['name'] value on the array
 *		array_set($array, 'user.name', 'Taylor');
 *
 *		// Set the $array['user']['name']['first'] value on the array
 *		array_set($array, 'user.name.first', 'Michael');
 * </code>
 *
 * @param  array   $array
 * @param  string  $key
 * @param  mixed   $value
 * @return void
 */
function array_set(&$array, $key, $value)
{
	if (is_null($key)) return $array = $value;

	$keys = explode('.', $key);

	// This loop allows us to dig down into the array to a dynamic depth by
	// setting the array value for each level that we dig into. Once there
	// is one key left, we can fall out of the loop and set the value as
	// we should be at the proper depth.
	while (count($keys) > 1)
	{
		$key = array_shift($keys);

		// If the key doesn't exist at this depth, we will just create an
		// empty array to hold the next value, allowing us to create the
		// arrays to hold the final value.
		if ( ! isset($array[$key]) or ! is_array($array[$key]))
		{
			$array[$key] = array();
		}

		$array =& $array[$key];
	}

	$array[array_shift($keys)] = $value;
}

/* End of file config.php */
/* Location: ./src/lib/config.php */