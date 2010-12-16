<?php

	require('./config.php');

	if (!@file_exists(BACKUP_FILE))
		exit('Please define the exported delicious file in your config and make sure it exists. You can create a backup at <a href="https://secure.delicious.com/settings/bookmarks/export">https://secure.delicious.com/settings/bookmarks/export</a>');
	
	set_time_limit(0);
	
	$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die('Could not connect to database server');
	
	if (CLEARDB)
	{
		mysqli_query($connection, 'TRUNCATE TABLE links');
		mysqli_query($connection, 'TRUNCATE TABLE links_tags');
		mysqli_query($connection, 'TRUNCATE TABLE tags');
	}
	
	// little trick: we're reversing the array to first fetch the description (if any)
	// that way we can save ourselves of doing an update afterwards. 
	// Other side effect is that by doing so the first bookmarked link actually gets number 1 as id.
	$lines = array_reverse((array) @file(BACKUP_FILE));		
	
	$description = '';
	$lastInsertId = 0;
	
	foreach($lines as $line)
	{
		
		// description
		if (substr($line,0,4) == '<DD>')
		{
			
			// extract description
			$description = substr($line, 4);
			
		}
		
		// link
		if (substr($line,0,4) == '<DT>')
		{			
			
			// extract link, tags and date
			preg_match('/\<A HREF="(.*?)" ADD_DATE="(.*?)" PRIVATE="(.*?)" TAGS="(.*?)"\>(.*?)\<\/A\>/', substr($line,4), $matches);				
			list($orig, $link, $date, $private, $tags, $title) = $matches;
			
			// insert link
			mysqli_query($connection, sprintf('INSERT INTO links (link, title, description, added, private) VALUES ("%s", "%s", "%s", "%s", %d)',
				mysqli_real_escape_string($connection, $link),
				mysqli_real_escape_string($connection, $title),
				mysqli_real_escape_string($connection, $description),
				mysqli_real_escape_string($connection, date('Y-m-d H:i:s', $date)),
				mysqli_real_escape_string($connection, $private)
			));
			
			$linkId = mysqli_insert_id($connection);
			
			foreach (explode(',', $tags) as $tag)
			{
				
				// insert tag
				mysqli_query($connection, sprintf('INSERT INTO tags (tag, qty) VALUES ("%s", 1) ON DUPLICATE KEY UPDATE qty = qty + 1',
					mysqli_real_escape_string($connection, $tag)
				));
				
				// link the tag to the link
				mysqli_query($connection, sprintf('INSERT INTO links_tags (link_id, tag) VALUES (%d, "%s")',
					mysqli_real_escape_string($connection, $linkId),
					mysqli_real_escape_string($connection, $tag)
				));
				
			}
			
			// reset description for the next row
			$description = '';
			
		}
		
	}
	
	mysqli_close($connection);
	
	exit('All.done!');