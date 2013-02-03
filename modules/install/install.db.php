<?php

/**
 * Install - Install DB Class / Model
 * 
 * @author Bramus! <bramus@bram.us>
 * 
 */

class InstallDB
{
	
	
	/**
	 * Checks if the databaseconfiguration is valid
	 */
	public static function checkDb()
	{
		$db = PlonkWebsite::getDB();
		
		try {
			$db->connect();
			return true;
		} catch (Exception $e)
		{
			return false;
		}
		
	}
	
	
	/**
	 * Checks if the databaseconfiguration is valid
	 */
	public static function checkSql()
	{
		$db = PlonkWebsite::getDB();
		
		try {
			if($db->getVar('DESCRIBE links'))
				return true;
		} catch (Exception $e)
		{
			return false;
		}
		
	}
	
	
	/**
	 * Checks if Yummy! has possibly been installed before
	 */
	public static function possiblyInstalled()
	{
		
		$db = PlonkWebsite::getDB();
		
		try {
			return $db->getVar('SELECT COUNT(*) FROM links');
		} catch (Exception $e)
		{
			return false;
		}
		
	}
	
	
	/**
	 * Imports (the contents of) a backupped file
	 * @param array $lines
	 */
	public static function importBackup(array $lines)
	{
		
		// try cranking the time limit up a notch
		@set_time_limit(180);
		
		// get DB
		$db = PlonkWebsite::getDB();
		
		// Clean up
		$db->execute('TRUNCATE TABLE links');
		$db->execute('TRUNCATE TABLE links_tags');
		$db->execute('TRUNCATE TABLE tags');
		
		// little trick: we're reversing the array to first fetch the description (if any)
		// that way we can save ourselves of doing an update afterwards. 
		// Other side effect is that by doing so the first bookmarked link actually gets number 1 as id.
		$lines = array_reverse((array) $lines);		
		
		$description = '';
		$lastInsertId = 0;
		$numLinks = 0;
		$prevDate = 0;
		
		foreach($lines as $line)
		{

			// link
			if ((substr($line,0,4) == '<DT>') || (substr($line,0,11) == '<DL><p><DT>'))
			{			
				
				// extract link, private, tags and date
				preg_match('/\<A HREF="(.*?)" ADD_DATE="(.*?)" PRIVATE="(.*?)" TAGS=("?)(.*?)("?)\>(.*?)\<\/A\>/', substr($line,4), $matches);
				
				// data extracted!
				if (sizeof($matches) == 8) {
					list($orig, $link, $date, $private, $quot1, $tags, $quot2, $title) = $matches;
				}
				
				// Data not extracted, try again
				// @note: In the new Delicious export, when no tags are entered, the tags attribute is omitted (great, huh?)
				else { 
					
					// extract link, private and date
					preg_match('/\<A HREF="(.*?)" ADD_DATE="(.*?)" PRIVATE="(.*?)"\>(.*?)\<\/A\>/', substr($line,4), $matches);
					
					// data extracted!
					if (sizeof($matches) == 5) {
						list($orig, $link, $date, $private, $title) = $matches;
					}
					
					// data not extracted, skip link.
					else continue;
					
				}
				
				// Apparently, the new Delicious doesn't know for sure when you've added a link
				if ($date == 'None') $date = $prevDate;
				
				// insert link
				$linkId = $db->execute(sprintf('INSERT INTO links (link, title, description, added, private) VALUES ("%s", "%s", "%s", "%s", %d)',
					$db->escape(($link)),
					$db->escape(htmlspecialchars_decode(($title), ENT_QUOTES)),
					$db->escape(htmlspecialchars_decode(($description), ENT_QUOTES)),
					$db->escape(date('Y-m-d H:i:s', $date)),
					$db->escape((int) $private)
				));
				
				foreach (explode(',', $tags) as $tag)
				{
					
					// insert tag
					$db->execute(sprintf('INSERT INTO tags (tag, qty, public_qty) VALUES ("%s", 1, %d) ON DUPLICATE KEY UPDATE qty = qty + 1, public_qty = public_qty + %d',
						$db->escape(utf8_decode($tag)),
						(int) $private,
						(int) $private
					));
					
					// link the tag to the link
					$db->execute(sprintf('INSERT INTO links_tags (link_id, tag) VALUES (%d, "%s")',
						$db->escape($linkId),
						$db->escape(utf8_decode($tag))
					));
					
				}
				
				// reset description for the next row
				$description = '';
				
				// increment the number of links imported
				$numLinks++;
				
				// keep track of the previous date
				$prevDate = $date;
				
			}

			// description
			else if (substr($line,0,4) == '<DD>')
			{

				// extract description (and modify it a bit first to get the same results for the old and the new delicious)
				$description = html_entity_decode(strip_tags(substr($line, 4))) . $description;

			}

			// description (multiline comment)
			else if (substr($line,0,1) != '<') {

				// extract description (and modify it a bit first to get the same results for the old and the new delicious)
				$description = html_entity_decode(strip_tags($line)) . $description;

			}
			
		}
		
		return $numLinks;
		
	}
	
}

// EOF