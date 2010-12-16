<?php

	require('./config.php');
	
	$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die('Could not connect to database server');
	
	// tag set: get links with that tag
	if (isset($_GET['tag']) && (trim($_GET['tag']) !== ''))
	{
		
		$tag = mysqli_query($connection, sprintf('SELECT * FROM tags WHERE tag = "%s"',
			mysqli_real_escape_string($connection, $_GET['tag'])
		));
		
		if (mysqli_num_rows($tag) === 0) exit('This tag does not exist');
		
		$title = 'Links with the tag ' . htmlentities($_GET['tag']);
		
		$links = mysqli_query($connection, sprintf('SELECT links.*, GROUP_CONCAT(links_tags.tag) AS tags FROM links INNER JOIN links_tags ON links.id = links_tags.link_id  WHERE links_tags.tag = "%s" GROUP BY links.id ORDER BY links.id DESC',
			mysqli_real_escape_string($connection, $_GET['tag'])
		));
		
	} 
	
	// no tag set: get all links
	else {
		
		$title = 'All links';
	
		$links = mysqli_query($connection, sprintf('SELECT links.*, GROUP_CONCAT(links_tags.tag) AS tags FROM links INNER JOIN links_tags ON links.id = links_tags.link_id GROUP BY links.id ORDER BY links.id DESC'));
		
	}
	
	echo '<h1>' . $title . '</h1>' . PHP_EOL;
		
	if (mysqli_num_rows($links) === 0) exit('No links found. Make sure you run import.php first');
	
	if (isset($_GET['tag']) && (trim($_GET['tag']) !== ''))
	{
		echo '<a href="index.php" class="back">&larr; Back to overview</a>';
	}
	
	echo '<dl>' . PHP_EOL;
	
	while ($link = mysqli_fetch_assoc($links))
	{
		
		$tags 		= explode(',', $link['tags']);
		$tagsLinked = array();
		
		foreach ($tags as $tag)
		{
			$tagsLinked[] = '<a href="index.php?tag='.urlencode($tag).'" class="tag">'.htmlentities($tag).'</a>';
		}
		
		echo '<dt><em>'.$link['added'].'</em> <a href="'.$link['link'].'" title="'.$link['title'].'" class="bookmark">'.$link['title'].'</a> <small>('.implode($tagsLinked, ', ').')</small></dt>' . PHP_EOL;
	
		if (trim($link['description']) !== '')
		{
			echo '<dd>' . $link['description'] . '</dd>' . PHP_EOL;	
		}
		
	}
	
	echo '</dl>';
	