<h2><span class="username">{$username}'s</span> bookmarks</h2>

{option:oNoLinks}
	<p>No links have been bookmarked so far. Make sure you've run <a href="index.php?module=install">the import/install script</a> first</p>
{/option:oNoLinks}

{option:oHasLinks}
<ul class="links">
	{iteration:iLinks}
	<li class="link clearfix">
		{option:newDate}<span class="date">{$addedNice}</span>{/option:newDate}
		<h3><a href="{$link|htmlentities}" title="{$title|htmlentities}" data-added="{$added}" data-private="{$private}" class="link">{$title|htmlentities}</a></h3>
		{option:oDescription}<p class="description">{$description|htmlentities}</p>{/option:oDescription}
		{option:oHasTags}
		<ul class="tags">
			{iteration:iTags}
			<li><a href="index.php?module={$MODULE}&amp;view=tag&amp;tag={$tag|htmlentities|urlencode}">{$tag}</a></li>
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
				<li class="previousPage"><a href="index.php?module={$MODULE}&amp;p={$firstPage}">&laquo; first</a></li>
				<li class="previousPage"><a href="index.php?module={$MODULE}&amp;p={$prevPage}">&lt; prev</a></li>
				{/option:oPrevLink}
				{option:oNoPrevLink}
				<li class="previousPage ellipsis"><span>&laquo; first</span></li>
				<li class="previousPage ellipsis"><span>&lt; prev</span></li>
				{/option:oNoPrevLink}
				
				{iteration:iPagination}
				<li{option:oActive} class="currentpage"{/option:oActive}{option:oMore} class="ellipsis"{/option:oMore}>
					{option:oActive}<span>{$page}</span>{/option:oActive}
					{option:oNormal}<a href="index.php?module={$MODULE}&amp;p={$page}">{$page}</a>{/option:oNormal}
					{option:oMore}<span>&hellip;</span>{/option:oMore}
				</li>
				{/iteration:iPagination}
				
				{option:oNextLink}
				<li class="nextPage"><a href="index.php?module={$MODULE}&amp;p={$nextPage}">next &gt;</a></li>
				<li class="nextPage"><a href="index.php?module={$MODULE}&amp;p={$lastPage}">last &raquo;</a></li>
				{/option:oNextLink}
				{option:oNoNextLink}
				<li class="nextPage ellipsis"><span>next &gt;</span></li>
				<li class="nextPage ellipsis"><span>last &raquo;</span></li>
				{/option:oNoNextLink}
			
			</ul>
		</div>
	</div>
	<p class="numlinks">{$numLinks} bookmarks</p>