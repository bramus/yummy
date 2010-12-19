<?php

/**
 * Browse - Browse DB Class / Model
 * 
 * @author Bramus! <bramus@bram.us>
 * 
 */

class BrowseDB
{
	
	
	/**
	 * Gets the number of links
	 * @param bool $includePrivate
	 */
	public static function getNumLinks($includePrivate = false)
	{
		
		$db = PlonkWebsite::getDB();
		
		try
		{
			if ($includePrivate)
				$numLinks = (int) $db->getVar('SELECT COUNT(*) FROM links');
			else
				$numLinks = (int) $db->getVar('SELECT COUNT(*) FROM links WHERE private = 0');
				
			return $numLinks;
		} catch (Exception $e) {
			return false;
		}
		
	}
	
	
	/**
	 * Gets the number of links tagged with the tag $tag
	 * @param string $tag
	 * @param bool $includePrivate
	 */
	public static function getNumLinksForTag($tag, $includePrivate = false)
	{
		
		$db = PlonkWebsite::getDB();
		
		try
		{
			if ((bool) $includePrivate)
				$numLinks = (int) $db->getVar(sprintf('SELECT COUNT(*) FROM links INNER JOIN links_tags ON links.id = links_tags.link_id WHERE links_tags.tag = "%s"', $db->escape((string) $tag)));
			else
				$numLinks = (int) $db->getVar(sprintf('SELECT COUNT(*) FROM links INNER JOIN links_tags ON links.id = links_tags.link_id WHERE links_tags.tag = "%s" AND links.private = 0', $db->escape((string) $tag)));
				
			return $numLinks;
		} catch (Exception $e) {
			return false;
		}
		
	}
	
	/**
	 * Gets all links for the given page
	 * @param int $page
	 * @param int $limitPerPage
	 * @param bool $includePrivate
	 */
	public static function getLinks($page, $limitPerPage, $includePrivate = false)
	{
		
		$db = PlonkWebsite::getDB();
		
		$links 	= $db->retrieve(
					sprintf(
						'SELECT links.*, GROUP_CONCAT(links_tags.tag) AS tags FROM links INNER JOIN links_tags ON links.id = links_tags.link_id %s GROUP BY links.id ORDER BY links.id DESC LIMIT %d, %d',
						((!(bool) $includePrivate) ? 'WHERE links.private = 0' : ''),
						((int) $page * (int) $limitPerPage),
						(int) $limitPerPage
					)
				);
				
		return (array) $links;
	}
	
	
	public static function getLinksForTag($tag, $page, $limitPerPage, $includePrivate = false)
	{
		
		$db = PlonkWebsite::getDB();
		
		$links 	= $db->retrieve(
					sprintf(
						'SELECT links.*, GROUP_CONCAT(links_tags.tag) AS tags FROM links INNER JOIN links_tags ON links.id = links_tags.link_id WHERE links.id IN (SELECT link_id FROM links_tags WHERE links_tags.tag = "%s") %s GROUP BY links.id ORDER BY links.id DESC LIMIT %d, %d',
						$db->escape((string) $tag),
						((!(bool) $includePrivate) ? 'AND links.private = 0' : ''),
						((int) $page * (int) $limitPerPage),
						(int) $limitPerPage
					)
				);		
				
		return (array) $links;
	}
	
	
	public static function getTag($tag)
	{
		
		$db = PlonkWebsite::getDB();
		
		$tag 	= $db->getVar(
					sprintf('SELECT * FROM tags WHERE tag = "%s"',
						$db->escape((string) $tag)
					)
				);
		
		return $tag;
		
	}
	
	public static function tagExists($tag)
	{
		$tag = self::getTag((string) $tag);
		return ($tag !== null);
	}
	
}

// EOF