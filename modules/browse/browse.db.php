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
	public static function getNumLinksForTag($tags, $includePrivate = false)
	{
		
		$db = PlonkWebsite::getDB();
		
		try
		{
			
			// build query
				$query[0] = 'SELECT COUNT(*) FROM links INNER JOIN links_tags AS lt0 ON links.id = lt0.link_id';
				
				foreach ((array) $tags as $i => $tag)
				{
					
					// first tag (where clause)
					if ($i == 0)
					{
						$query[1] = sprintf(' WHERE lt0.tag = "%s"', $db->escape($tag));
					}
					
					// other tag (and it)
					else {
					
						$query[0] .= ' INNER JOIN links_tags AS lt' . $i . ' ON lt0.link_id = lt' . $i . '.link_id';
						$query[1] .= sprintf(' AND lt'.$i.'.tag = "%s"', $db->escape($tag));
						
					}
					
				}
				
				if (!$includePrivate)
					$query[1] .= ' AND links.private = 0';
			
			// execute query and return result
				$numLinks = (int) $db->getVar(implode($query));
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
						'SELECT links.*, GROUP_CONCAT(links_tags.tag) AS tags FROM links INNER JOIN links_tags ON links.id = links_tags.link_id %s GROUP BY links.id ORDER BY links.id DESC, links_tags.id ASC LIMIT %d, %d',
						((!(bool) $includePrivate) ? 'WHERE links.private = 0' : ''),
						((int) $page * (int) $limitPerPage),
						(int) $limitPerPage
					)
				);
				
		return (array) $links;
	}
	
	
	/**
	 * Gets all bookmarks tagged with the given tag(s)
	 * @param mixed $tag
	 * @param int $page
	 * @param int $limitPerPage
	 * @param bool $includePrivate
	 */
	public static function getLinksForTag($tags, $page, $limitPerPage, $includePrivate = false)
	{
		
		$db = PlonkWebsite::getDB();
		
		// build query
			$query[0] 	= 'SELECT links.*, GROUP_CONCAT(links_tags.tag) AS tags FROM links '
						. 'INNER JOIN links_tags ON links.id = links_tags.link_id';
			
			foreach ((array) $tags as $i => $tag)
			{
				$query[0] .= ' INNER JOIN links_tags AS lt'.$i.' ON links_tags.link_id = lt'.$i.'.link_id';
				if ($i == 0)
					$query[1] = sprintf(' WHERE lt'.$i.'.tag = "%s"', $db->escape($tag));
				else
					$query[1] .= sprintf(' AND lt'.$i.'.tag = "%s"', $db->escape($tag));
			}
			
			if (!$includePrivate)
				$query[1] .= ' AND links.private = 0';
		
		// add pagination
			$query[2] 	= sprintf(
							' GROUP BY links.id' .
							' ORDER BY links.id DESC, links_tags.id ASC LIMIT %d, %d',
							((int) $page * (int) $limitPerPage),
							(int) $limitPerPage
						);
				
		// execute query and return result
			$links 	= $db->retrieve(implode($query));
			return (array) $links;
	}
	
	
	/**
	 * Gets all related tags (including the count) for a tag
	 * @param string $tag
	 */
	public static function getRelatedTagsForTag($tags, $includePrivate = false)
	{
		
		$db = PlonkWebsite::getDB();
		
		// build query
			$query[0] 	= 'SELECT distinct(lt0.tag) AS tag, COUNT(lt0.tag) AS qty FROM links_tags AS lt '
						. 'INNER JOIN links ON links.id = lt.link_id';
			
			foreach ((array) $tags as $i => $tag)
			{
				$query[0] .= ' LEFT OUTER JOIN links_tags AS lt'.$i.' ON lt.link_id = lt'.$i.'.link_id ';
				if ($i == 0)
					$query[1] = sprintf(' WHERE lt.tag = "%s" AND lt'.$i.'.tag != "%s"', $db->escape($tag), $db->escape($tag));
				else
				{
					for ($j = 0; $j <= $i; $j++)
					{
						if ($j != $i)
							$query[1] .= sprintf(' AND lt'.$j.'.tag != "%s"', $db->escape($tag));
						else
							$query[1] .= sprintf(' AND lt'.$j.'.tag = "%s"', $db->escape($tag));
					}
				}
			}
			
			if (!$includePrivate)
				$query[1] .= ' AND links.private = 0';
		
		// add pagination
			$query[2] 	= ' GROUP BY lt0.tag'
						. ' ORDER BY lt0.tag ASC';
					
		// execute query and return result
			$tags 	= $db->retrieve(implode($query));
			return (array) $tags;
		
	}
	
	
	/**
	 * Gets a specific tag
	 * @param string $tag
	 * @todo Make this work to show only public tags too
	 */
	public static function getTag($tag, $includePrivate = false)
	{
		
		$db = PlonkWebsite::getDB();
		
		$tag 	= $db->getVar(
					sprintf(
						'SELECT tags.tag FROM tags WHERE tag = "%s"',
						$db->escape((string) $tag)
					)
				);
		
		return $tag;
		
	}
	
	
	/**
	 * Gets a tag list (top tags)
	 * @param int $limit
	 */
	public static function getTagslist($includePrivate = false, $limit = 10)
	{
		
		$db = PlonkWebsite::getDB();
		
		$tags	= $db->retrieve(
					sprintf(
						'SELECT tag, %s AS qty FROM tags ' .
						'WHERE SUBSTR(tag, 1, 4) != "for:" ' .		// exclude for:[username] links
						'AND %s > 0 ' .								// exclude tags that have 0 links
						'ORDER BY %s DESC ' .
						'LIMIT 0, %d',
						(($includePrivate) ? 'qty' : 'public_qty'),
						(($includePrivate) ? 'qty' : 'public_qty'),
						(($includePrivate) ? 'qty' : 'public_qty'),
						(int) $limit
					)
				);
				
		return (array) $tags;
		
	}
	
	
	/**
	 * Checks whether an array of tags exist
	 * @param mixed $tag
	 */
	public static function tagExists($tags, $includePrivate = false)
	{
		
		$tags = (array) $tags;
		
		foreach ($tags as $tag)
		{
			if (self::getTag((string) $tag, $includePrivate) === null) return false;
		}
		
		return true;
		
	}
	
}

// EOF