<?php
/**
 * This file is part of eManga.
 *
 * @author Alvaro Carneiro <d3sign.night@gmail.com>
 * @license MIT License
 * @copyright 2011 - 2012 Alvaro Carneiro
 */
// When no manga found
class MangaNotFoundException extends Exception { }

// When no chapter found
class ChapterNotFoundException extends Exception { }

// When a page of a chapter doesn't exists
class ChapterPageNotFoundException extends Exception { }
/**
 * This represents a manga
 */
class Manga {
	
	/**
	 * @var string The name of the manga
	 * @access private
	 */
	private $name;
	
	/**
	 * @var int The number of the chapter
	 * @access private
	 */
	public $chapter;
	
	/**
	 * @var array An array with the pages of the chapter of the manga
	 * @access private
	 */
	private $pages = array();
	
	/**
	 * Create a new instance of a manga
	 *
	 * @param string $name The name of the manga
	 * @throws MangaNotFoundException If the directory of the manga doesn't exists
	 * @access public
	 */
	public function __construct($name)
	{
		$this->name = $name;
		
		if ( ! is_dir(Config::get('mangas.path').DS.$name) )
		{
			throw new MangaNotFoundException();
		}
	}
	
	/**
	 * Get the name of the manga
	 *
	 * @return string The name of the manga
	 * @access public
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Get the title name of the manga
	 *
	 * @return string The name of the manga using ucwords()
	 * @access public
	 */
	public function getTitle()
	{
		return ucwords($this->name);
	}
	
	/**
	 * Get the list of chapters of the manga
	 *
	 * @param string $order The order to return the chapters SORT_DESC || SORT_ASC
	 * @access public
	 */
	public function listChapters($order = SORT_DESC)
	{
		$iterator = new DirectoryIterator(Config::get('mangas.path').DS.$this->name);
		
		$chapters = array();
		
		foreach ($iterator as $file)
		{
			if ( $file->isDir() && ! $file->isDot() )
			{
				$name = $file->getFilename();
				$chapters[] = array(
					'name'		=>	ucfirst($name),
					'url'			=>	Router::generate('manga.view', array('manga' => $this->name, 'chapter' => (int)$name, 'page' => 1))
				);
			}
		}
		
		array_multisort($chapters, $order);
		
		return $chapters;
	}
	
	/**
	 * Prepare the pages of the current chapter
	 *
	 * @param int $chapter The number of the chapter to load the pages
	 * @throws ChapterNotFoundException If the chapter doesn't exists
	 * @return void
	 * @access public
	 */
	public function preparePages($chapterid)
	{
		$this->chapter = $chapterid;
		$iterator = new DirectoryIterator(Config::get('mangas.path').DS.$this->name);
		$found = false;
		foreach ( $iterator as $file)
		{
			if ( $file->isDir() && ! $file->isDot() )
			{
				$name = $file->getFilename();
				
				if ( ((int)$name) == ((int)$chapterid) && ((int)$name))
				{
					$found = true;
					
					$path = Config::get('mangas.path').DS.$this->name.DS.$name;
					
					break;
				}
				elseif ( $name < $chapterid ) // Linux servers sorts the folders automatically (I don't know if other type of servers do it too)
				{
					break;
				}
			}
		}
		
		if ( $found === false )
		{
			throw new ChapterNotFoundException();
		}
		
		foreach( new DirectoryIterator($path) as $file )
		{
			if ( in_array($file->getExtension(), Config::get('mangas.allowed_extensions')) )
			{
				$this->pages[] = Config::get('web.url') . Config::get('mangas.path').'/' . $this->name . '/' . $name . '/' . $file->getFilename();
			}
		}
		
		sort($this->pages);
	}
	
	/**
	 * Get the url of the current page of the manga
	 *
	 * @param int $page The number of the current page
	 * @return string The url of the current page
	 * @throws ChapterPageNotFoundException If the page doesn't exists
	 * @access public
	 */
	public function getPage($page)
	{
		if ( isset($this->pages[--$page]) )
		{
			return $this->pages[$page];
		}
		
		throw new ChapterPageNotFoundException();
	}
	
	/**
	 * Get the number of pages that have the current chapter
	 *
	 * @return int the number of pages
	 * @access public
	 */
	public function countPages()
	{
		return count($this->pages);
	}
	
	/**
	 * Get the url of the next page
	 *
	 * @param int $page The number of the current page
	 * @return string The url of the next page
	 * @access public
	 */
	public function getNextPage($page)
	{
		return isset($this->pages[++$page])
					?	Router::generate('manga.view', array('manga' => $this->name, 'chapter' => $this->chapter, 'page' => $page))
					:	Router::generate('manga.preview', array('manga' => $this->name));
	}
	
	/**
	 *  Get the url of the previous page
	 *
	 * @param int $page The number of the current page
	 * @return string The url fo the previous page
	 * @access public
	 */
	public function getPrevPage($page)
	{
		return isset($this->pages[--$page])
					?	Router::generate('manga.view', array('manga' => $this->name, 'chapter' => $this->chapter, 'page' => $page))
					:	Router::generate('manga.preview', array('manga' => $this->name));
	}
	
	/**
	 * Checks if the description file of this manga exists
	 *
	 * @return bool The returned value of "file_exists"
	 * @access public
	 */
	public function hasDescription()
	{
		return file_exists(Config::get('mangas.path').DS.$this->name.DS.'description.txt');
	}
	
	/**
	 * Get the description of the manga
	 *
	 * @return mixed The content of the description file
	 * @access public
	 */
	public function getDescription()
	{
		return file_get_contents(Config::get('mangas.path').DS.$this->name.DS.'description.txt');
	}
	
}

/* End of file manga.php */
/* Location: ./src/lib/manga.php */