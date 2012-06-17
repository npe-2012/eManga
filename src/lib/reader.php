<?php
/**
 * This file is part of eManga.
 *
 * @author Alvaro Carneiro <d3sign.night@gmail.com>
 * @license MIT License
 * @copyright 2011 - 2012 Alvaro Carneiro
 */

/**
 * Class to read the manga's directory in search of mangas, chapters, etc.
 */
class MangaReader {
	
	/**
	 * @var string The path where the mangas are in
	 * @access private
	 */
	private $path;
	
	/**
	 * Create a new reader instance
	 *
	 * @param string $path The  path where the mangas are in
	 * @access public
	 */
	public function __construct($path)
	{
		$this->path = $path;
		
		require_once LIBPATH.'manga.php';
	}
	
	/**
	 * List all the available mangas
	 *
	 * @return array Array that contains all the mangas inside the mangas directory
	 * @access public
	 */
	public function listMangas()
	{
		$iterator = new DirectoryIterator($this->path);
		
		$mangas = array();
		
		foreach ($iterator as $file)
		{
			if ( $file->isDir() && ! $file->isDot() )
			{
				$name = $file->getFilename();
				$mangas[] = array(
					'name'		=>	ucfirst($name),
					'url'			=>	Router::generate('manga.preview', array('manga' => $this->encode($name)))
				);
			}
		}
		
		return $mangas;
	}
	
	/**
	 * Return an instance of a manga
	 *
	 * @param string $name The name of the manga
	 * @return Manga The manga
	 * @throws MangaNotFoundException If the directory of the manga doesn't exists
	 * @access public
	 */
	public function getManga($name)
	{
		return new Manga($name);
	}
	
	/**
	 * Encode the name of a manga (just replace spaces to underscores)
	 *
	 * @param string $manga The name of the manga to encode
	 * @return string The encoded name of the manga
	 * @access public
	 */
	public function encode($manga)
	{
		return str_replace(' ', '_', $manga);
	}
	
	/**
	 * Decode the name of a manga (just replace underscores to spaces)
	 *
	 * @param string $manga The name of the encoded manga to decode
	 * @return string The decoded result
	 * @access public
	 */
	public function decode($manga)
	{
		return str_replace('_', ' ', $manga);
	}
	
}

/* End of file reader.php */
/* Location: ./src/lib/reader.php */