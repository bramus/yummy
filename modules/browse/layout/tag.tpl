<div id="main">
	<h2><span class="username">{$username}'s</span> <span class="tag">{$tag}</span> bookmarks</h2>
	{option:oNoLinks}
		<p>No links with this tag have been bookmarked so far. Make sure you've run <a href="/install">the import/install script</a> first</p>
	{/option:oNoLinks}
	
	{option:oHasLinks}
	<ul class="links">
		{iteration:iLinks}
		<li class="link clearfix private-{$private}">
			{option:newDate}<span class="date">{$addedNice}</span>{/option:newDate}
			<h3><a href="{$link|htmlentities}" title="{$title|htmlentities}" data-added="{$added}" data-private="{$private}" class="link" style="background-image: url(//www.google.com/s2/favicons?domain={$linkdomain|urlencode})">{$title|htmlentities}</a></h3>
			{option:oDescription}<p class="description">{$description|htmlentities|nl2br}</p>{/option:oDescription}
			{option:oHasTags}
			<ul class="tags">
				{iteration:iTags}
				<li{option:oActive} class="active"{/option:oActive}><a href="/{$MODULE}/tag/{$tag|htmlentities|urlencode}">{$tag|htmlentities}</a></li>
				{/iteration:iTags}
			</ul>
			{/option:oHasTags}
		</li>
		{/iteration:iLinks}
	</ul>
	{/option:oHasLinks}
	
	<div class="pagination-wrap clearfix">
		<div class="pagination">
			<ul class="clearfix">
	
				{option:oPrevLink}
				<li class="previousPage"><a href="/{$MODULE}/tag/{$tagForUri}/{$firstPage}">&laquo; first</a></li>
				<li class="previousPage"><a href="/{$MODULE}/tag/{$tagForUri}/{$prevPage}">&lt; prev</a></li>
				{/option:oPrevLink}
				{option:oNoPrevLink}
				<li class="previousPage ellipsis"><span>&laquo; first</span></li>
				<li class="previousPage ellipsis"><span>&lt; prev</span></li>
				{/option:oNoPrevLink}
				
				{iteration:iPagination}
				<li{option:oActive} class="currentpage"{/option:oActive}{option:oMore} class="ellipsis"{/option:oMore}>
					{option:oActive}<span>{$page}</span>{/option:oActive}
					{option:oNormal}<a href="/{$MODULE}/tag/{$tagForUri}/{$page}">{$page}</a>{/option:oNormal}
					{option:oMore}<span>&hellip;</span>{/option:oMore}
				</li>
				{/iteration:iPagination}
				
				{option:oNextLink}
				<li class="nextPage"><a href="/{$MODULE}/tag/{$tagForUri}/{$nextPage}">next &gt;</a></li>
				<li class="nextPage"><a href="/{$MODULE}/tag/{$tagForUri}/{$lastPage}">last &raquo;</a></li>
				{/option:oNextLink}
				{option:oNoNextLink}
				<li class="nextPage ellipsis"><span>next &gt;</span></li>
				<li class="nextPage ellipsis"><span>last &raquo;</span></li>
				{/option:oNoNextLink}
			
			</ul>
		</div>
	</div>
	<p class="numlinks">{$numLinks} bookmarks</p>
</div>

<div id="sidebar">
	<div id="relatedTags">
		<h4>Related Tags</h4>
		{$relatedTags}
	</div>
	<div id="topTags">
		<h4>Top Tags</h4>
		{$topTags}
	</div>
</div>